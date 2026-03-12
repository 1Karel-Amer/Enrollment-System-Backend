<?php

namespace App\Http\Controllers;

use App\Models\Student; 
use App\Models\Course;
use App\Models\SchoolDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Fetch real data
        $enrollmentData = Student::select(
            DB::raw('MONTH(created_at) as month'), 
            DB::raw('count(*) as count')
        )->groupBy('month')->get();

        $courseData = Course::withCount('students as students_count')->get();

        $attendanceData = SchoolDay::select('date', 'attendance_count')
            ->orderBy('date', 'asc')
            ->get();

        // 2. Fallback Logic: If database is empty, send dummy data so charts aren't blank
        return response()->json([
            'enrollment_trends' => $enrollmentData->isEmpty() ? [
                ['month' => 1, 'count' => 10],
                ['month' => 2, 'count' => 25],
                ['month' => 3, 'count' => 15]
            ] : $enrollmentData,

            'course_distribution' => $courseData->isEmpty() ? [
                ['name' => 'BSIT', 'students_count' => 45],
                ['name' => 'BSCS', 'students_count' => 30],
                ['name' => 'BSIS', 'students_count' => 20]
            ] : $courseData,

            'attendance_patterns' => $attendanceData->isEmpty() ? [
                ['date' => '2026-03-01', 'attendance_count' => 85],
                ['date' => '2026-03-02', 'attendance_count' => 92],
                ['date' => '2026-03-03', 'attendance_count' => 78]
            ] : $attendanceData,
        ]);
    }
}