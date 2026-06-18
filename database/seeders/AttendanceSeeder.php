<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();

        if ($students->isEmpty()) {
            $this->command->info('No students found. Please seed students first.');
            return;
        }

        // Wipe the table clean so we don't get duplicates on multiple runs
        DB::table('attendance')->truncate();

        $attendanceRecords = [];

        foreach ($students as $student) {
            // Get the actual subjects the student is enrolled in from the pivot table
            $enrolledSubjects = DB::table('student_subjects')
                ->join('subjects', 'student_subjects.subject_id', '=', 'subjects.id')
                ->where('student_subjects.student_id', $student->id)
                ->select('subjects.code', 'student_subjects.school_year', 'student_subjects.term', 'student_subjects.final_grade', 'student_subjects.status')
                ->get();

            foreach ($enrolledSubjects as $subject) {
                $absences = 0;
                $tardies = rand(0, 2);
                $remarks = null;

                // ── AI TRAINING SYNERGY ──
                if ($subject->status === 'Dropped' || $subject->final_grade === '5.00') {
                    $absences = rand(8, 15);
                    $tardies = rand(3, 8);
                    $remarks = $subject->status === 'Dropped' 
                        ? 'Dropped due to excessive absences (FDA)' 
                        : 'Warning: High risk of failure due to attendance';
                } else {
                    // Normal students (mostly 0 absences, sometimes 1-4)
                    if (rand(1, 10) > 6) {
                        $absences = rand(1, 4);
                        $tardies = rand(1, 5);
                    }
                }

                // Add a remark if they are getting close to the limit (usually 7 absences)
                if ($absences >= 5 && $absences < 8 && !$remarks) {
                    $remarks = 'Notice: Approaching maximum allowed absences';
                }

                $attendanceRecords[] = [
                    'student_id'   => $student->id,
                    'subject_code' => $subject->code,
                    'school_year'  => $subject->school_year,
                    'term'         => $subject->term,
                    'absences'     => $absences,
                    'tardies'      => $tardies,
                    'remarks'      => $remarks,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
        }

        // Insert in chunks of 1000 for faster database performance
        foreach (array_chunk($attendanceRecords, 1000) as $chunk) {
            DB::table('attendance')->insert($chunk);
        }

        $this->command->info('Successfully seeded realistic attendance records! 📅');
    }
}