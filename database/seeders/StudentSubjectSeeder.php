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
        $students = Student::all();
        $subjects = Subject::all();

        if ($students->isEmpty() || $subjects->isEmpty()) {
            return;
        }

        $records = [];
        $terms = ['1st Semester', '2nd Semester'];
        $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

        // UM Tagum Official Discrete Grades
        $excellentGrades = [3.5, 4.0];
        $averageGrades   = [2.0, 2.5, 3.0, 3.5];
        $failGrades      = [1.0, 2.0];

        foreach ($students as $student) {
            $currentYearIndex = array_search($student->year_level, $years);
            if ($currentYearIndex === false) $currentYearIndex = 0;

            $programCode = DB::table('programs')->where('id', $student->program_id)->value('code');
            $programSubjects = $subjects->where('program', $programCode);

            if ($programSubjects->isEmpty()) {
                $programSubjects = $subjects;
            }

            $profileRoll = rand(1, 100);
            if ($profileRoll <= 15) {
                $profile = 'at_risk';    
            } elseif ($profileRoll <= 40) {
                $profile = 'excellent';   
            } else {
                $profile = 'average';     
            }

            for ($y = 0; $y <= $currentYearIndex; $y++) {
                foreach ($terms as $term) {
                    if ($y === $currentYearIndex && $term === '2nd Semester') {
                        continue;
                    }

                    $semesterSubjects = $programSubjects->random(min(rand(4, 5), $programSubjects->count()));

                    foreach ($semesterSubjects as $subject) {
                        $status = 'Passed';
                        
                        if ($profile === 'excellent') {
                            $attendance = rand(94, 100);
                            $midterm = $excellentGrades[array_rand($excellentGrades)];
                            $final   = $excellentGrades[array_rand($excellentGrades)];
                        } 
                        elseif ($profile === 'average') {
                            $attendance = rand(80, 95);
                            $midterm = $averageGrades[array_rand($averageGrades)];
                            $final   = $averageGrades[array_rand($averageGrades)];
                        } 
                        else { 
                            $attendance = rand(40, 74);      
                            $midterm = $failGrades[array_rand($failGrades)];
                            
                            $outcomeRoll = rand(1, 10);
                            if ($outcomeRoll <= 5) {
                                $status = 'Dropped';
                                $final = null;                
                                $attendance = rand(25, 55);   
                            } elseif ($outcomeRoll <= 8) {
                                $status = 'Failed';
                                $final = 1.0; // Explicit 1.0 Failure               
                            } else {
                                $status = 'Passed';            
                                $final = 2.0; // Scraped by with lowest passing grade
                            }
                        }

                        $records[] = [
                            'student_id'    => $student->id,
                            'subject_id'    => $subject->id,
                            'year_level'    => $years[$y],
                            'term'          => $term,
                            'school_year'   => (2022 + $y) . '-' . (2023 + $y),
                            'attendance'    => $attendance, 
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