<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $programs = Program::all()->keyBy('code');

        if ($programs->isEmpty()) {
            $this->command->error('No programs found. Run ProgramSeeder first.');
            return;
        }

        $programCodes = $programs->keys()->toArray();
        $yearLevels   = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        $genders      = ['Male', 'Female'];

        $firstNames = ['Maria', 'Juan', 'Jose', 'Ana', 'Carlo', 'Liza', 'Mark', 'Nina', 'Paolo', 'Rosa', 'Luis', 'Grace', 'Kevin', 'Ella', 'Ryan', 'Faith'];
        $lastNames  = ['Santos', 'Reyes', 'Cruz', 'Bautista', 'Garcia', 'Mendoza', 'Torres', 'Flores', 'Rivera', 'Ramos', 'Gomez', 'Diaz', 'Morales'];

        $students = [];
        $year     = now()->year;

        // UM Tagum Official Discrete Passing Grades for Overall GPA
        $goodGrades = [2.5, 3.0, 3.5, 4.0];
        $poorGrades = [1.0, 2.0];

        for ($i = 1; $i <= 1000; $i++) {
            $firstName    = $firstNames[array_rand($firstNames)];
            $lastName     = $lastNames[array_rand($lastNames)];
            $gender       = $genders[array_rand($genders)];
            $yearLevel    = $yearLevels[array_rand($yearLevels)];
            $programCode  = $programCodes[array_rand($programCodes)];
            $program      = $programs[$programCode];

            $scholarship = rand(1, 100) <= 15; 
            $unpaidFees  = rand(1, 100) <= 12;   

            if ($scholarship) {
                $unpaidFees = rand(1, 100) <= 1; 
            }

            // Consistent Higher-is-better discrete scale
            $isStruggling = rand(1, 10) > 7; 

            if ($isStruggling) {
                $gpa = $poorGrades[array_rand($poorGrades)];
                $attendance = rand(40, 74);            
                if (!$scholarship && rand(1,10) <= 3) $unpaidFees = true; 
            } else {
                $gpa = $goodGrades[array_rand($goodGrades)]; 
                $attendance = rand(80, 100);           
            }

            $students[] = [
                'student_id'              => $year . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'first_name'              => $firstName,
                'last_name'               => $lastName,
                'email'                   => strtolower($firstName . '.' . $lastName . $i . '@um.edu.ph'),
                'gender'                  => $gender,
                'date_of_birth'           => now()->subYears(rand(18, 25))->subDays(rand(0, 365))->toDateString(),
                'year_level'              => $yearLevel,
                'contact_no'              => '09' . rand(100000000, 999999999),
                'address'                 => 'Tagum City, Davao del Norte',
                'emergency_contact_name'  => $firstNames[array_rand($firstNames)] . ' ' . $lastName,
                'emergency_contact_no'    => '09' . rand(100000000, 999999999),
                'program_id'              => $program->id,
                'enrollment_date'         => now()->subMonths(rand(1, 36))->toDateString(),
                'gpa'                     => $gpa,
                'attendance'              => $attendance,
                'scholarship_status'      => $scholarship,
                'has_unpaid_fees'         => $unpaidFees,
                'created_at'              => now(),
                'updated_at'              => now(),
            ];
        }

        foreach (array_chunk($students, 50) as $chunk) {
            DB::table('students')->insert($chunk);
        }
        
        $this->command->info('Successfully seeded 1000 discrete, correct-scale student records.');
    }
}