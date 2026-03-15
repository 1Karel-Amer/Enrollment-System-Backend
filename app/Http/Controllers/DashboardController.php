<?php

namespace App\Http\Controllers;

use App\Models\Student; 
use App\Models\Course;
use App\Models\SchoolDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Summary Stats (The "Big Numbers")
        $summary = [
            [
                'label' => 'Total Enrollment',
                'value' => Student::count(),
                'color' => 'bg-red-50',
                'trend' => '+12% from last sem'
            ],
            [
                'label' => 'Active Programs',
                'value' => Course::count(),
                'color' => 'bg-slate-50',
                'trend' => '8 Departments'
            ],
            [
                'label' => 'Today\'s Attendance',
                'value' => SchoolDay::whereDate('date', Carbon::today())->value('attendance_count') ?? 0,
                'color' => 'bg-orange-50',
                'trend' => 'Real-time'
            ],
        ];

        // 2. Enrollment Trends (Last 6 Months)
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
                'count' => $match ? $match->count : 0
            ];
        });

        // 3. Course Distribution
        // FIX: Mapping the collection to ensure React gets integers and guaranteed names
        $courseData = Course::withCount('students')->get()->map(function ($course) {
            return [
                'name' => $course->name ?? $course->course_name ?? 'Unknown Program',
                'students_count' => (int) $course->students_count
            ];
        });

        // 4. Attendance Patterns
        $attendanceData = SchoolDay::select('date', 'attendance_count')
            ->orderBy('date', 'asc')
            ->take(10)
            ->get();

        return response()->json([
            'summary' => $summary,
            'enrollment_trends' => $enrollmentData,
            'course_distribution' => $courseData,
            'attendance_patterns' => $attendanceData,
        ]);
    }
}