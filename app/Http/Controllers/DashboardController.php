<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Program;
use App\Models\SchoolDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // FIX 4: Cache the entire dashboard response for 60 seconds.
        //
        // The dashboard runs several aggregate queries (Student::count, Program::count,
        // enrollment trends, program distribution, attendance patterns) on every
        // page load. These numbers don't change second-to-second, so we cache
        // the full result and only recompute it once per minute.
        //
        // To bust the cache immediately (e.g. after a bulk import), run:
        //   Cache::forget('dashboard_stats');
        // Or call the helper below from wherever you write student/enrollment data.

        return Cache::remember('dashboard_stats', 60, function () {
            return $this->buildStats();
        });
    }

    /**
     * Call this whenever you write new students or enrollments so the
     * dashboard reflects the change on the next request.
     *
     * Usage example in another controller:
     *   app(DashboardController::class)->bustCache();
     */
    public function bustCache(): void
    {
        Cache::forget('dashboard_stats');
    }

    private function buildStats(): \Illuminate\Http\JsonResponse
    {
        $summary = [
            [
                'label' => 'Total Enrollment',
                'value' => Student::count(),
                'color' => 'bg-red-50',
                'trend' => '+12% from last sem',
            ],
            [
                'label' => 'Active Programs',
                'value' => Program::count(),
                'color' => 'bg-slate-50',
                'trend' => '8 Departments',
            ],
            [
                'label' => "Today's Attendance",
                'value' => SchoolDay::whereDate('date', Carbon::today())->value('attendance_count') ?? 0,
                'color' => 'bg-orange-50',
                'trend' => 'Real-time',
            ],
        ];

        // Enrollment trend: last 6 months
        $rawEnrollment = Student::select(
                DB::raw('MONTH(created_at) as month_num'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month_num')
            ->get();

        $enrollmentData = collect(range(0, 5))->map(function ($i) use ($rawEnrollment) {
            $monthDate = now()->subMonths(5 - $i);
            $match = $rawEnrollment->firstWhere('month_num', $monthDate->month);
            return [
                'month' => $monthDate->format('M'),
                'count' => $match ? $match->count : 0,
            ];
        });

        // Program distribution
        $programData = Program::withCount('students')->get()->map(function ($program) {
            return [
                'name'           => $program->name ?? 'Unknown Program',
                'students_count' => (int) $program->students_count,
            ];
        });

        // Attendance patterns
        $attendanceData = SchoolDay::select('date', 'attendance_count')
            ->orderBy('date', 'asc')
            ->take(10)
            ->get();

        return response()->json([
            'summary'             => $summary,
            'enrollment_trends'   => $enrollmentData,
            'course_distribution' => $programData,
            'attendance_patterns' => $attendanceData,
        ]);
    }
}