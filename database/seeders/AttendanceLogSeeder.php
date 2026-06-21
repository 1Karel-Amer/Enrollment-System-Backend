<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Subject;
use Carbon\Carbon;

class AttendanceLogSeeder extends Seeder
{
    public function run(): void
    {
        // Clear any existing residual entries safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('attendance_logs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $students = Student::all();
        $subjects = Subject::all();

        if ($students->isEmpty() || $subjects->isEmpty()) {
            return;
        }

        // Weighted pool to favor realistic 'present' weights over absences
        $statuses = ['present', 'present', 'present', 'present', 'late', 'absent', 'excused'];
        $startDate = Carbon::now()->subDays(14);

        foreach ($students as $student) {
            // Assign 3 to 5 random subjects to the current student
            $assignedSubjects = $subjects->random(rand(3, 5));

            foreach ($assignedSubjects as $subject) {
                // Generate up to 10 consecutive weekdays of logs per subject
                for ($i = 0; $i < 10; $i++) {
                    $date = (clone $startDate)->addDays($i);

                    // Skip weekend records
                    if ($date->isWeekend()) {
                        continue;
                    }

                    $status = $statuses[array_rand($statuses)];
                    $remarks = null;

                    if ($status === 'late') {
                        $remarks = 'Arrived late to class session.';
                    } elseif ($status === 'excused') {
                        $remarks = 'Excused official documentation submitted.';
                    }

                    DB::table('attendance_logs')->insert([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'date'       => $date->format('Y-m-d'),
                        'status'     => $status,
                        'remarks'    => $remarks,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}