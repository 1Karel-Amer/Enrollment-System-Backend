<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use Faker\Factory as Faker;
use Carbon\Carbon;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_PH'); // Using Philippines locale for more realistic local data
        
        // 1. Get all course IDs to assign students to valid courses
        $courseIds = Course::pluck('id')->toArray();

        if (empty($courseIds)) {
            $this->command->warn("No courses found. Please run ProgramSeeder or CourseSeeder first.");
            return;
        }

        $this->command->info("Seeding 500 students with full demographic data...");

        // Define the possible year levels
        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

        for ($i = 0; $i < 500; $i++) {
            // 2. Generate a random date within the last 6 months
            $randomDate = $faker->dateTimeBetween('-6 months', 'now');
            $carbonDate = Carbon::instance($randomDate);

            Student::create([
                // Generate a custom Student ID (e.g., 2026-0001)
                'student_id'      => '2026-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'first_name'      => $faker->firstName,
                'last_name'       => $faker->lastName,
                'email'           => $faker->unique()->safeEmail,
                'gender'          => $faker->randomElement(['Male', 'Female']),
                'date_of_birth'   => $faker->date('Y-m-d', '2005-12-31'),
                
                // --- NEW: Added Year Level randomization ---
                'year_level'      => $faker->randomElement($yearLevels),
                
                // --- Demographic Information ---
                'contact_no'      => $faker->phoneNumber,
                'address'         => $faker->address,
                
                // Emergency Contact Info
                'emergency_contact_name' => $faker->name,
                'emergency_contact_no'   => $faker->phoneNumber,
                // -------------------------------

                'course_id'       => $faker->randomElement($courseIds),
                
                // 3. Sync all date columns for your dashboard charts
                'enrollment_date' => $carbonDate->format('Y-m-d'),
                'created_at'      => $carbonDate, 
                'updated_at'      => $carbonDate,
            ]);
        }

        $this->command->info("Student Seeding Completed with 500 records!");
    }
}