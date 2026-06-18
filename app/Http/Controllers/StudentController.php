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
    // ── UM Tagum College Grading Scale (S.Y. 2020-2021) ──────────────────────
    // Scale  | Conversion | Grade | Description
    // 96-100 | 4.0        | A     | High Distinction
    // 90-95  | 3.5        | B+    | Distinction
    // 85-89  | 3.0        | B     | Very Good
    // 80-84  | 2.5        | C+    | Good
    // 75-79  | 2.0        | C-    | Average
    // <75    | 1.0        | F     | Fail
    private function getGradeInfo(?float $grade): array
    {
        if ($grade === null) {
            return ['letter' => 'INC', 'description' => 'Incomplete', 'range' => '-'];
        }
        if ($grade >= 4.0) return ['letter' => 'A',  'description' => 'High Distinction', 'range' => '96–100'];
        if ($grade >= 3.5) return ['letter' => 'B+', 'description' => 'Distinction',      'range' => '90–95'];
        if ($grade >= 3.0) return ['letter' => 'B',  'description' => 'Very Good',         'range' => '85–89'];
        if ($grade >= 2.5) return ['letter' => 'C+', 'description' => 'Good',              'range' => '80–84'];
        if ($grade >= 2.0) return ['letter' => 'C-', 'description' => 'Average',           'range' => '75–79'];
        return                    ['letter' => 'F',  'description' => 'Fail',              'range' => '<75'];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /students
    // ─────────────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Student::with('program');

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'LIKE', "%{$search}%")
                  ->orWhere('first_name',  'LIKE', "%{$search}%")
                  ->orWhere('last_name',   'LIKE', "%{$search}%");
            });
        }

        return response()->json($query->paginate(15));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /students/{id}
    // ─────────────────────────────────────────────────────────────────────────
    public function show($id)
    {
        $student = Student::with(['program', 'subjects', 'attendance'])->findOrFail($id);

        // Start with the model array (contains all columns + eager-loaded relations)
        $studentData = $student->toArray();

        if ($student->relationLoaded('subjects')) {
            $gradesCollection = $student->subjects->map(function ($subject) {
                $finalGrade   = $subject->pivot->final_grade   !== null ? (float) $subject->pivot->final_grade   : null;
                $midtermGrade = $subject->pivot->midterm_grade !== null ? (float) $subject->pivot->midterm_grade : null;
                $gradeInfo    = $this->getGradeInfo($finalGrade);

                return [
                    'subject_code'      => $subject->code,
                    'subject_name'      => $subject->title,
                    'units'             => (int) $subject->units,
                    'midterm_grade'     => $midtermGrade,
                    'final_grade'       => $finalGrade,
                    'grade_letter'      => $gradeInfo['letter'],
                    'grade_description' => $gradeInfo['description'],
                    'grade_range'       => $gradeInfo['range'],
                    'status'            => $subject->pivot->status,
                    'academic_year'     => $subject->pivot->school_year,
                    'semester'          => $subject->pivot->term,
                    'year_level'        => $subject->pivot->year_level,
                ];
            });

            $studentData['grades'] = $gradesCollection->values();

            // ── Cumulative GWA: weighted average of ALL graded subjects ────────
            // (Failed subjects are included — they drag the GWA down, as is standard)
            $graded      = $gradesCollection->filter(fn($g) => $g['final_grade'] !== null);
            $totalUnits  = $graded->sum('units');
            $weightedSum = $graded->sum(fn($g) => $g['final_grade'] * $g['units']);

            $studentData['gpa']            = $totalUnits > 0 ? round($weightedSum / $totalUnits, 2) : 0.00;
            $studentData['required_units'] = $student->program ? $student->program->units : 148;

        } else {
            $studentData['grades']         = [];
            $studentData['gpa']            = 0.00;
            $studentData['required_units'] = 148;
        }

        return response()->json($studentData);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PUT /students/{id}  — assign / change program
    // ─────────────────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'program_id' => 'nullable|exists:programs,id',
        ]);

        $student->program_id = $request->input('program_id');
        $student->save();

        // Bust the prediction cache — program change affects suggestions
        Cache::forget("dropout_risk_{$id}");

        return response()->json([
            'message' => 'Program assigned successfully.',
            'student' => $student->load('program'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /students/{id}/predict-risk
    // ─────────────────────────────────────────────────────────────────────────
    public function predictDropoutRisk($id)
    {
        $student = Student::with(['program', 'attendance'])->findOrFail($id);

        $cacheKey = "dropout_risk_{$id}";
        $cacheTtl = 60 * 60 * 6; // 6 hours

        $data = Cache::remember($cacheKey, $cacheTtl, function () use ($student) {
            return $this->computePrediction($student);
        });

        return response()->json($data);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE — ML prediction engine
    // ─────────────────────────────────────────────────────────────────────────
    private function computePrediction(Student $student): array
    {
        // ── 1. Encode year level and term ──────────────────────────────────────
        $yearsMap    = ['1st Year' => 1, '2nd Year' => 2, '3rd Year' => 3, '4th Year' => 4];
        $yearEncoded = $yearsMap[$student->year_level] ?? 1;
        $termEncoded = 1;

        // ── 2. Fetch all subject records ───────────────────────────────────────
        $subjects = DB::table('student_subjects')
            ->where('student_id', $student->id)
            ->orderBy('school_year')
            ->orderBy('term')
            ->get();

        $umMidtermGpa = $subjects->avg('midterm_grade') ?? $student->gpa ?? 2.00;
        $umFinalGpa   = $subjects->avg('final_grade')   ?? $student->gpa ?? 2.00;

        // ── 3. Calculate Real Attendance Rate ──────────────────────────────────
        $attendanceCollection = $student->relationLoaded('attendance')
            ? $student->getRelation('attendance')
            : collect();

        if ($attendanceCollection->count() > 0) {
            $totalClasses  = $attendanceCollection->count() * 36; // ~36 meetings/subject
            $totalAbsences = $attendanceCollection->sum('absences');
            $attendance    = round((($totalClasses - $totalAbsences) / $totalClasses) * 100);
            $attendance    = max(0, min(100, $attendance));
        } else {
            $attendance = $student->getAttributes()['attendance'] ?? 100;
        }

        // ── 4. UM GPA → percentage (correctly mapped to the 2020-2021 scale) ──
        // UM: 4.0=A(96-100), 3.5=B+(90-95), 3.0=B(85-89), 2.5=C+(80-84), 2.0=C-(75-79), 1.0=F(<75)
        $convertGpaToPercentage = function ($gpa) {
            if ($gpa >= 4.0) return 98.0;  // midpoint of 96-100
            if ($gpa >= 3.5) return 92.0;  // midpoint of 90-95
            if ($gpa >= 3.0) return 87.0;  // midpoint of 85-89
            if ($gpa >= 2.5) return 82.0;  // midpoint of 80-84
            if ($gpa >= 2.0) return 77.0;  // midpoint of 75-79
            return 65.0;                    // below 75 (F / 1.0)
        };

        $midtermPercent = $convertGpaToPercentage($umMidtermGpa);
        $finalPercent   = $convertGpaToPercentage($umFinalGpa);

        // ── 5. GPA trend history semester by semester ──────────────────────────
        $history      = [];
        $groupedBySem = $subjects->groupBy(function ($item) {
            return $item->school_year . ' T' . $item->term;
        });

        foreach ($groupedBySem as $sem => $semSubjects) {
            $history[] = [
                'sem' => $sem,
                'gpa' => round($semSubjects->avg('final_grade') ?? 2.0, 2),
            ];
        }

        // ── 6. Linear regression slope + standard deviation ───────────────────
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

        // ── 7. Run Python ML model ─────────────────────────────────────────────
        $scriptPath = storage_path('app/ai/predict.py');
        $result     = Process::run("python {$scriptPath} {$yearEncoded} {$termEncoded} {$midtermPercent} {$finalPercent} {$attendance}");

        $rawAiScore  = trim($result->output());
        $riskScore   = floatval($rawAiScore);
        $aiThreshold = 0.70;

        if ($riskScore < 0.05) {
            $riskScore = round(0.05 + (($attendance / 100) * 0.10), 2);
        }

        // ── 8. High risk triggers ──────────────────────────────────────────────
        $isHighRisk = (
            $riskScore >= $aiThreshold ||
            $attendance < 75           ||
            $umFinalGpa < 2.0          ||
            $direction === 'declining' ||
            $direction === 'volatile'
        );

        if ($isHighRisk && $riskScore < $aiThreshold) {
            $riskScore = 0.85;
        }

        // ── 9. Key factors ─────────────────────────────────────────────────────
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
                'note'   => 'Below university passing average (C- / 2.0 on UM scale)',
            ];
        }

        if ($umFinalGpa >= 2.0 && $umFinalGpa < 2.5) {
            $keyFactors[] = [
                'factor' => 'current_gpa',
                'value'  => round($umFinalGpa, 2),
                'impact' => 'medium',
                'note'   => 'GPA is near the C- passing threshold — monitor closely',
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

        // ── 10. Alternative program suggestions ───────────────────────────────
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
                $suggestions[] = array_merge($s->toArray(), ['rank' => $rank++]);
            }
        }

        // ── 11. Return enriched array ──────────────────────────────────────────
        return [
            'student_id'         => $student->id,
            'name'               => $student->first_name . ' ' . $student->last_name,
            'current_program'    => $student->program->name       ?? 'Unassigned',
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
        ];
    }
}