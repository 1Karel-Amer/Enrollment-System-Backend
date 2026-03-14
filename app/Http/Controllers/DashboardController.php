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
        // 1. Fetch Enrollment Trends (Last 6 Months)
        $rawEnrollment = Student::select(
                DB::raw('MONTH(created_at) as month_num'), 
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month_num')
            ->get();

        // Generate a continuous 6-month array so the chart isn't empty-looking
        $enrollmentData = collect(range(0, 5))->map(function ($i) use ($rawEnrollment) {
            $monthDate = now()->subMonths(5 - $i);
            $monthNumber = $monthDate->month;
            
            // Find if we have database records for this specific month
            $match = $rawEnrollment->firstWhere('month_num', $monthNumber);

            return [
                'month' => $monthDate->format('M'), // Returns "Jan", "Feb", etc.
                'count' => $match ? $match->count : 0
            ];
        });

        // 2. Fetch Course Distribution
        $courseData = Course::withCount('students as students_count')->get();

        // 3. Fetch Attendance Patterns
        $attendanceData = SchoolDay::select('date', 'attendance_count')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'enrollment_trends' => $enrollmentData,

            'course_distribution' => $courseData->isEmpty() ? [
                ['name' => 'BSIT', 'students_count' => 0],
                ['name' => 'BSCS', 'students_count' => 0],
            ] : $courseData,

            'attendance_patterns' => $attendanceData->isEmpty() ? [
                ['date' => now()->toDateString(), 'attendance_count' => 0],
            ] : $attendanceData,
        ]);
    }
}