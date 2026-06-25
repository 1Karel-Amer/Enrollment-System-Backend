<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Check if Program data exists to map relationships
        $programs = Program::all()->keyBy('code');

        if ($programs->isEmpty()) {
            $this->command->error('No programs found. Run ProgramSeeder first.');
            return;
        }

        // 2. Define the path to your CSV file
        $csvPath = 'C:/Users/Acer/Desktop/School_FilesFolder/Business ANalythiscs/Enrollment-System-Backend/data.csv'; //Insert the path location to your data.csv

        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");
            return;
        }

        // 3. Open and read the CSV file
        $students = [];
        if (($handle = fopen($csvPath, 'r')) !== FALSE) {
            
            // Skip the header row (e.g., student_id, first_name, program_code...)
            $header = fgetcsv($handle, 1000, ','); 

            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                
                // Assuming your CSV has a column for the Program Code (e.g., 'BSIT', 'BSBA')
                $programCode = $data[11]; // <-- CHANGE THIS INDEX to match your CSV column
                $program = $programs->get($programCode);

                // Skip this row or handle it if the program code doesn't exist in the DB
                if (!$program) {
                    $this->command->warn("Program code '{$programCode}' not found. Skipping student.");
                    continue;
                }

                // Map your CSV columns directly to your database table columns
                // $data[0] is the first column, $data[1] is the second, etc.
                $students[] = [
                    'student_id'             => $data[0],
                    'first_name'             => $data[1],
                    'last_name'              => $data[2],
                    'email'                  => $data[3],
                    'gender'                 => $data[4],
                    'date_of_birth'          => $data[5],
                    'year_level'             => $data[6],
                    'contact_no'             => $data[7],
                    'address'                => $data[8],
                    'emergency_contact_name' => $data[9],
                    'emergency_contact_no'   => $data[10],
                    'program_id'             => $program->id, // Uses the ID looked up from the code
                    'enrollment_date'        => $data[12],
                    'gpa'                    => (float)$data[13],
                    'attendance'             => (int)$data[14],
                    'scholarship_status'     => filter_var($data[15], FILTER_VALIDATE_BOOLEAN),
                    'has_unpaid_fees'        => filter_var($data[16], FILTER_VALIDATE_BOOLEAN),
                    'created_at'             => now(),
                    'updated_at'             => now(),
                ];
            }
            fclose($handle);
        }

        // 4. Insert into the database in chunks for performance
        foreach (array_chunk($students, 50) as $chunk) {
            DB::table('students')->insert($chunk);
        }
        
        $this->command->info('Successfully seeded student records from CSV.');
    }
}