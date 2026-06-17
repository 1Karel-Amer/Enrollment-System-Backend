<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

// OVERHAUL: Students now reference program_id (programs table) directly.
// This eliminates the course_id / courses table disconnect.

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Load all programs so we can assign them by code
        $programs = Program::all()->keyBy('code');

        if ($programs->isEmpty()) {
            $this->command->error('No programs found. Run ProgramSeeder first.');
            return;
        }

        $programCodes = $programs->keys()->toArray();
        $yearLevels   = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        $genders      = ['Male', 'Female'];

        $firstNames = [
            'Maria', 'Juan', 'Jose', 'Ana', 'Carlo', 'Liza', 'Mark', 'Nina',
            'Paolo', 'Rosa', 'Luis', 'Grace', 'Kevin', 'Ella', 'Ryan', 'Faith',
            'James', 'Claire', 'Miguel', 'Sophia', 'Aaron', 'Jasmine', 'Noel',
            'Bianca', 'Renz', 'Katrina', 'Jerome', 'Angela', 'Vince', 'Danna',
        ];

        $lastNames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Garcia', 'Mendoza', 'Torres',
            'Flores', 'Rivera', 'Ramos', 'Gomez', 'Diaz', 'Morales', 'Castro',
            'Vargas', 'Aquino', 'Lim', 'Tan', 'Go', 'Sy', 'Dela Cruz', 'Villanueva',
            'Fernandez', 'Lopez', 'Pascual', 'Navarro', 'Aguilar', 'Salazar',
        ];

        $students = [];
        $year     = now()->year;

        for ($i = 1; $i <= 200; $i++) {
            $firstName    = $firstNames[array_rand($firstNames)];
            $lastName     = $lastNames[array_rand($lastNames)];
            $gender       = $genders[array_rand($genders)];
            $yearLevel    = $yearLevels[array_rand($yearLevels)];
            $programCode  = $programCodes[array_rand($programCodes)];
            $program      = $programs[$programCode];

            // GPA on UM scale: 1.00 = best, 5.00 = failing
            // 70% of students are passing (1.0–3.0), 30% are struggling
            $gpa = rand(1, 10) <= 7
                ? round(rand(100, 300) / 100, 2)   // 1.00–3.00 passing
                : round(rand(301, 500) / 100, 2);  // 3.01–5.00 at risk

            $attendance = rand(1, 10) <= 8
                ? rand(75, 100)   // 75%–100% good attendance
                : rand(40, 74);   // 40%–74% poor attendance

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
                'created_at'              => now(),
                'updated_at'              => now(),
            ];
        }

        foreach (array_chunk($students, 50) as $chunk) {
            DB::table('students')->insert($chunk);
        }
    }
}