<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Subject;


class StudentSubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Get all your seeded students and subjects
        $students = Student::all();
        $subjects = Subject::all();

        if ($students->isEmpty() || $subjects->isEmpty()) {
            return;
        }

        $records = [];
        $terms = ['1st Semester', '2nd Semester'];
        $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

        foreach ($students as $student) {
            // Determine the maximum year level to generate history for based on current standing
            $currentYearIndex = array_search($student->year_level, $years);
            if ($currentYearIndex === false) $currentYearIndex = 0;

            // Get the program code for the student to filter subjects
            $programCode = DB::table('programs')->where('id', $student->program_id)->value('code');
            
            // Filter global subjects collection down to ONLY match this student's program
            $programSubjects = $subjects->where('program', $programCode);

            // Fallback safety check: If no program-specific subjects exist, default back to all subjects
            if ($programSubjects->isEmpty()) {
                $programSubjects = $subjects;
            }

            // Loop through their past academic years up to their current standing
            for ($y = 0; $y <= $currentYearIndex; $y++) {
                foreach ($terms as $term) {
                    
                    // Don't generate future semesters if they are currently in progress
                    if ($y === $currentYearIndex && $term === '2nd Semester') {
                        continue;
                    }

                    
                    $semesterSubjects = $programSubjects->random(min(rand(4, 5), $programSubjects->count()));

                    foreach ($semesterSubjects as $subject) {
                        
                        // Default Status and Grading Generation
                        $status = 'Passed';
                        
                        // UM Scale (1.00 is excellent, 3.00 is passing, 5.00 is failing)
                        $midterm = rand(100, 350) / 100; 
                        $final = rand(100, 350) / 100;

                        // CRITICAL FOR ML: If the student was flagged with a poor overall GPA in your last seeder,
                        // simulate high dropouts and failures so the model can learn the pattern!
                        if ($student->gpa > 3.0) { // On UM scale, higher numbers mean struggling
                            if (rand(1, 10) > 6) {
                                $status = rand(1, 2) === 1 ? 'Dropped' : 'Failed';
                                $midterm = rand(300, 500) / 100;
                                $final = $status === 'Dropped' ? null : 5.00;
                            }
                        }

                        $records[] = [
                            'student_id'    => $student->id,
                            'subject_id'    => $subject->id,
                            'year_level'    => $years[$y],
                            'term'          => $term,
                            'school_year'   => (2022 + $y) . '-' . (2023 + $y),
                            'midterm_grade' => $midterm,
                            'final_grade'   => $final,
                            'status'        => $status,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ];
                    }
                }
            }
        }

        foreach (array_chunk($records, 1000) as $chunk) {
            DB::table('student_subjects')->insert($chunk);
        }
    }
}