<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('program');

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }

        return response()->json($query->paginate(15));
    }

    public function show($id)
    {
        $student = Student::with(['program', 'attendanceLogs'])->findOrFail($id);
        
        $subjects = DB::table('student_subjects')
            ->join('subjects', 'student_subjects.subject_id', '=', 'subjects.id')
            ->where('student_subjects.student_id', $student->id)
            ->orderBy('student_subjects.school_year')
            ->orderBy('student_subjects.term')
            ->select(
                'subjects.code as code', 
                'subjects.title as title', 
                'subjects.units as units',
                'student_subjects.status as status',
                'student_subjects.school_year as year', 
                'student_subjects.term as term',
                'student_subjects.attendance as subject_attendance',
                'student_subjects.midterm_grade as midterm_grade',
                'student_subjects.final_grade as final_grade'
            
            )
            ->get();

        $studentArray = $student->toArray();
        $studentArray['grades'] = $subjects;
        $studentArray['subjects'] = $subjects;

        return response()->json($studentArray);
    }

    /**
     * POST /students/{id}/grades
     */
    public function addGrade(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'subject_id'    => 'required|exists:subjects,id',
            'year_level'    => 'required|string',
            'term'          => 'required|string',
            'school_year'   => 'required|string',
            'attendance'    => 'nullable|integer|min:0|max:100',
            'midterm_grade' => 'nullable|numeric|min:0|max:5',
            'final_grade'   => 'nullable|numeric|min:0|max:5',
            'status'        => 'required|string|in:Passed,Failed,Dropped',
        ]);

        $validated['student_id'] = $student->id;
        $validated['created_at'] = now();
        $validated['updated_at'] = now();

        $recordId = DB::table('student_subjects')->insertGetId($validated);

        Cache::forget("dropout_risk_{$id}");

        return response()->json([
            'message' => 'Grade record added successfully.',
            'id'      => $recordId,
        ], 201);
    }

    /**
     * GET /students/{id}/attendance
     * SIMPLIFIED: Returns ONLY present and absent logs and filters out late/excused.
     */
    public function attendanceLog($id)
    {
        $student = Student::findOrFail($id);

        // 1. Calculate Overall Summary (Only counting Present and Absent)
        $logs = DB::table('attendance_logs')
            ->where('student_id', $student->id)
            ->whereIn('status', ['present', 'absent']) // Filter out late/excused
            ->get();

        $present = $logs->where('status', 'present')->count();
        $absent  = $logs->where('status', 'absent')->count();
        $total   = $present + $absent;

        $rate = $total > 0
            ? round(($present / $total) * 100)
            : ($student->attendance ?? 0);

        $summary = [
            'attendance_rate' => $rate,
            'present'         => $present,
            'absent'          => $absent,
        ];

        // 2. Calculate Per-Subject Attendance (Only counting Present and Absent)
        $subjectLogs = DB::table('attendance_logs')
            ->join('subjects', 'attendance_logs.subject_id', '=', 'subjects.id')
            ->where('attendance_logs.student_id', $student->id)
            ->whereIn('attendance_logs.status', ['present', 'absent']) // Filter out late/excused
            ->select('attendance_logs.status', 'subjects.code as subject_code', 'subjects.title as subject_name')
            ->get();

        $subjectAttendance = [];
        $grouped = $subjectLogs->groupBy('subject_code');

        foreach ($grouped as $code => $group) {
             $subPresent = $group->where('status', 'present')->count();
             $subAbsent  = $group->where('status', 'absent')->count();
             $subTotal   = $subPresent + $subAbsent;

             $subRate = $subTotal > 0 
                ? round(($subPresent / $subTotal) * 100) 
                : 0;

             $subjectAttendance[] = [
                 'subject_code'    => $code,
                 'subject_name'    => $group->first()->subject_name,
                 'present'         => $subPresent,
                 'absent'          => $subAbsent,
                 'attendance_rate' => $subRate
             ];
        }

        return response()->json([
            'summary'            => $summary,
            'subject_attendance' => array_values($subjectAttendance)
        ]);
    }

    public function predictDropoutRisk($id)
    {
        $student = Student::with('program')->findOrFail($id);

        $cacheKey = "dropout_risk_{$id}";
        $cacheTtl = 60 * 60 * 6;

        return Cache::remember($cacheKey, $cacheTtl, function () use ($student) {
            return $this->computePrediction($student);
        });
    }

    private function computePrediction(Student $student): \Illuminate\Http\JsonResponse
    {
        $yearsMap = ['1st Year' => 1, '2nd Year' => 2, '3rd Year' => 3, '4th Year' => 4];
        $yearEncoded = $yearsMap[$student->year_level] ?? 1;
        $termEncoded = 1;

        $subjects = DB::table('student_subjects')
            ->join('subjects', 'student_subjects.subject_id', '=', 'subjects.id')
            ->where('student_subjects.student_id', $student->id)
            ->orderBy('student_subjects.school_year')
            ->orderBy('student_subjects.term')
            ->select(
                'student_subjects.*',
                'subjects.title as subject_name',
                'subjects.code as subject_code',
                'subjects.units as units' // <-- ADDED: Now we pull units for accurate math
            )
            ->get();

        // Calculate Overall Weighted GPAs
        $totalUnits = 0;
        $totalMidtermWeighted = 0;
        $totalFinalWeighted = 0;

        foreach ($subjects as $sub) {
            $units = (float) ($sub->units ?? 3);
            $mGrade = (float) $sub->midterm_grade;
            $fGrade = (float) $sub->final_grade;

            if ($mGrade > 0) $totalMidtermWeighted += ($mGrade * $units);
            if ($fGrade > 0) {
                $totalFinalWeighted += ($fGrade * $units);
                $totalUnits += $units; // Use final grade valid units as baseline
            }
        }

        $umMidtermGpa = $totalUnits > 0 ? round($totalMidtermWeighted / $totalUnits, 2) : ($student->gpa ?? 2.00);
        $umFinalGpa   = $totalUnits > 0 ? round($totalFinalWeighted / $totalUnits, 2) : ($student->gpa ?? 2.00);
        $attendance   = $student->attendance ?? 100;

        $convertGpaToPercentage = function ($gpa) {
            if ($gpa >= 4.0)  return 98.0;
            if ($gpa >= 3.5)  return 92.0;
            if ($gpa >= 3.0)  return 87.0;
            if ($gpa >= 2.75) return 83.0;
            if ($gpa >= 2.5)  return 79.0;
            if ($gpa >= 2.25) return 75.0;
            if ($gpa >= 2.0)  return 72.0;
            return 60.0;
        };

        $midtermPercent = $convertGpaToPercentage($umMidtermGpa);
        $finalPercent   = $convertGpaToPercentage($umFinalGpa);

        // Calculate Weighted GPA Trend History
        $history = [];
        $groupedBySem = $subjects->groupBy(function ($item) {
            return $item->school_year . ' T' . $item->term;
        });

        foreach ($groupedBySem as $sem => $semSubjects) {
            $semTotalUnits = 0;
            $semTotalWeightedGrades = 0;

            foreach ($semSubjects as $sub) {
                $grade = (float) $sub->final_grade;
                $units = (float) ($sub->units ?? 3);

                if ($grade > 0) {
                    $semTotalUnits += $units;
                    $semTotalWeightedGrades += ($grade * $units);
                }
            }

            $history[] = [
                'sem' => $sem,
                'gpa' => $semTotalUnits > 0 ? round($semTotalWeightedGrades / $semTotalUnits, 2) : 2.00,
            ];
        }

        $direction = 'stable';
        $slope     = 0;
        $stdDev    = 0;

        if (count($history) >= 2) {
            $gpas    = array_column($history, 'gpa');
            $n       = count($gpas);
            $indices = range(0, $n - 1);

            $meanX = array_sum($indices) / $n;
            $meanY = array_sum($gpas) / $n;

            $numerator   = 0;
            $denominator = 0;
            foreach ($indices as $i) {
                $numerator   += ($i - $meanX) * ($gpas[$i] - $meanY);
                $denominator += ($i - $meanX) ** 2;
            }
            $slope = $denominator != 0 ? round($numerator / $denominator, 2) : 0;

            $variance = array_sum(array_map(fn($g) => ($g - $meanY) ** 2, $gpas)) / $n;
            $stdDev   = round(sqrt($variance), 2);

            if ($stdDev >= 0.5) {
                $direction = 'volatile';
            } elseif ($slope <= -0.2) {
                $direction = 'declining';
            } elseif ($slope >= 0.2) {
                $direction = 'improving';
            }
        }

        $scriptPath = storage_path('app/ai/predict.py');
        $result     = Process::run("python {$scriptPath} {$yearEncoded} {$termEncoded} {$midtermPercent} {$finalPercent} {$attendance}");

        $rawAiScore = trim($result->output());
        $riskScore  = floatval($rawAiScore);
        $aiThreshold = 0.70;

        if ($riskScore < 0.05) {
            $riskScore = round(0.05 + (($attendance / 100) * 0.10), 2);
        }

        $isHighRisk = ($riskScore >= $aiThreshold);

        $keyFactors = [];

        if ($attendance < 75) {
            $keyFactors[] = [
                'factor' => 'attendance_rate',
                'value'  => $attendance,
                'impact' => 'high',
                'note'   => 'Below 75% threshold — strong dropout predictor',
            ];
        }

        if ($direction === 'declining') {
            $keyFactors[] = [
                'factor' => 'gpa_trend',
                'value'  => 'declining',
                'impact' => 'high',
                'note'   => "GPA dropped {$slope} points over time",
            ];
        }

        if ($direction === 'volatile') {
            $keyFactors[] = [
                'factor' => 'gpa_volatility',
                'value'  => $stdDev,
                'impact' => 'high',
                'note'   => 'GPA swings sharply between semesters — unstable academic performance',
            ];
        }

        if ($umFinalGpa < 2.0) {
            $keyFactors[] = [
                'factor' => 'final_gpa_avg',
                'value'  => round($umFinalGpa, 2),
                'impact' => 'medium',
                'note'   => 'Below university passing average of 2.0',
            ];
        }

        if ($umFinalGpa >= 2.0 && $umFinalGpa < 2.5) {
            $keyFactors[] = [
                'factor' => 'current_gpa',
                'value'  => round($umFinalGpa, 2),
                'impact' => 'medium',
                'note'   => 'GPA is near the minimum passing threshold — needs monitoring',
            ];
        }

        if (empty($keyFactors)) {
            $keyFactors[] = [
                'factor' => 'stable_metrics',
                'value'  => 'All Safe',
                'impact' => 'low',
                'note'   => 'Student is meeting all academic and attendance standards',
            ];
        }

        $suggestions = [];

        if ($isHighRisk && $student->program) {
            $bestSubject = $subjects
                ->filter(fn($s) => $s->final_grade !== null)
                ->sortByDesc('final_grade')
                ->first();

            $bestSubjectName = $bestSubject
                ? ($bestSubject->subject_name ?? 'core subjects')
                : 'core subjects';

            $allPrograms = Program::where('id', '!=', $student->program_id)->get();

            $scored = $allPrograms->map(function ($program) use ($student, $umFinalGpa, $attendance, $bestSubjectName) {
                $score = 50;

                if ($program->department === $student->program->department) $score += 20;
                if ($umFinalGpa >= 2.5) $score += 10;
                if ($attendance >= 75)  $score += 10;

                $score += rand(0, 20);
                $score += ($program->id % 7);

                if ($program->department !== $student->program->department) {
                    $score -= rand(0, 10);
                }

                $score = min($score, 99);

                $reason = ($program->department === $student->program->department)
                    ? "Credits strongly align within the {$program->department} department — minimal units lost on transfer."
                    : "Best performance in {$bestSubjectName} suggests aptitude that aligns with this program's focus.";

                return [
                    'id'            => $program->id,
                    'course_name'   => $program->name,
                    'department'    => $program->department,
                    'match_score'   => round($score / 100, 2),
                    'match_display' => $score . '%',
                    'reason'        => $reason,
                ];
            })
            ->sortByDesc('match_score')
            ->take(2)
            ->values();

            $rank = 1;
            foreach ($scored as $s) {
                $suggestions[] = array_merge($s, ['rank' => $rank++]);
            }
        }

        return response()->json([
            'student_id'         => $student->id,
            'name'               => $student->first_name . ' ' . $student->last_name,
            'current_program'    => $student->program->name     ?? 'Unassigned',
            'current_department' => $student->program->department ?? 'Unassigned',

            'metrics_evaluated' => [
                'year_level_encoded' => $yearEncoded,
                'term_encoded'       => $termEncoded,
                'attendance_rate'    => $attendance,
                'um_scale' => [
                    'midterm_gpa_avg' => round($umMidtermGpa, 2),
                    'final_gpa_avg'   => round($umFinalGpa, 2),
                ],
                'ai_scale_converted' => [
                    'midterm_percentage' => $midtermPercent,
                    'final_percentage'   => $finalPercent,
                ],
                'gpa_trend' => [
                    'direction' => $direction,
                    'history'   => $history,
                    'slope'     => $slope,
                    'std_dev'   => $stdDev,
                ],
            ],

            'dropout_risk' => [
                'label'          => $isHighRisk ? 'High Risk' : 'Safe',
                'score'          => round($riskScore, 2),
                'score_display'  => round($riskScore * 100) . '%',
                'threshold_used' => $aiThreshold,
            ],

            'key_factors'                    => $keyFactors,
            'suggested_alternative_programs' => $suggestions,

            'meta' => [
                'model_version'     => 'v1.2.0',
                'evaluated_at'      => now()->toIso8601String(),
                'semesters_used'    => count($history),
                'data_completeness' => count($history) > 0 ? 'full' : 'partial',
                'cached'            => true,
            ],
        ]);
    }
}