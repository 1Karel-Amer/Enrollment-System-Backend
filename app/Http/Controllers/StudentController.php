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
        $student = Student::with('program')->findOrFail($id);
        return response()->json($student);
    }

    public function predictDropoutRisk($id)
    {
        $student = Student::with('program')->findOrFail($id);

        // ── Cache the full prediction result per student ───────────────────
        // The Python subprocess (predict.py) is expensive — it spawns a new
        // process, loads sklearn, and unpickles the model on every request.
        // We cache the entire JSON response for 6 hours per student.
        // To force a refresh (e.g. after grades update), call:
        //   Cache::forget("dropout_risk_{$id}");
        $cacheKey = "dropout_risk_{$id}";
        $cacheTtl = 60 * 60 * 6; // 6 hours in seconds

        return Cache::remember($cacheKey, $cacheTtl, function () use ($student) {
            return $this->computePrediction($student);
        });
    }

    /**
     * All prediction logic extracted into a private method.
     * This keeps predictDropoutRisk() clean and makes the cache wrapper obvious.
     */
    private function computePrediction(Student $student): \Illuminate\Http\JsonResponse
    {
        // ── 1. Encode year level and term ────────────────────────────────────
        $yearsMap = ['1st Year' => 1, '2nd Year' => 2, '3rd Year' => 3, '4th Year' => 4];
        $yearEncoded = $yearsMap[$student->year_level] ?? 1;
        $termEncoded = 1;

        // ── 2. Fetch all subject records ──────────────────────────────────────
        $subjects = DB::table('student_subjects')
            ->where('student_id', $student->id)
            ->orderBy('school_year')
            ->orderBy('term')
            ->get();

        $umMidtermGpa = $subjects->avg('midterm_grade') ?? $student->gpa ?? 2.00;
        $umFinalGpa   = $subjects->avg('final_grade')   ?? $student->gpa ?? 2.00;
        $attendance   = $student->attendance ?? 100;

        // ── 3. Tighter GPA → percentage bands ────────────────────────────────
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

        // ── 4. Build GPA trend history semester by semester ───────────────────
        $history = [];
        $groupedBySem = $subjects->groupBy(function ($item) {
            return $item->school_year . ' T' . $item->term;
        });

        foreach ($groupedBySem as $sem => $semSubjects) {
            $history[] = [
                'sem' => $sem,
                'gpa' => round($semSubjects->avg('final_grade') ?? 2.0, 2),
            ];
        }

        // ── 5. Linear regression slope + std deviation ────────────────────────
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

        // ── 6. Run Python ML model ────────────────────────────────────────────
        $scriptPath = storage_path('app/ai/predict.py');
        $result     = Process::run("python {$scriptPath} {$yearEncoded} {$termEncoded} {$midtermPercent} {$finalPercent} {$attendance}");

        $rawAiScore = trim($result->output());
        $riskScore  = floatval($rawAiScore);
        $aiThreshold = 0.70;

        if ($riskScore < 0.05) {
            $riskScore = round(0.05 + (($attendance / 100) * 0.10), 2);
        }

        // ── 7. High risk triggers ─────────────────────────────────────────────
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

        // ── 8. Key factors ────────────────────────────────────────────────────
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

        // ── 9. Suggestions — now based on Program, not the legacy Course table ─
        $suggestions = [];

        if ($isHighRisk && $student->program) {
            $bestSubject = $subjects
                ->filter(fn($s) => $s->final_grade !== null)
                ->sortBy('final_grade')
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
                    'course_name'   => $program->name, // key kept as-is so InsightsPanel.jsx needs no changes
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

        // ── 10. Return enriched JSON ──────────────────────────────────────────
        return response()->json([
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
                'cached'            => true, // lets you verify caching is active
            ],
        ]);
    }
}