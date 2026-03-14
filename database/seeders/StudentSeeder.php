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
        $faker = Faker::create();
        
        // 1. Get all course IDs to assign students to valid courses
        $courseIds = Course::pluck('id')->toArray();

        if (empty($courseIds)) {
            $this->command->warn("No courses found. Please run ProgramSeeder or CourseSeeder first.");
            return;
        }

        $this->command->info("Seeding 500 students across the last 6 months...");

        for ($i = 0; $i < 500; $i++) {
            // 2. Generate a random date within the last 6 months
            // This ensures your graph has data points for Jan, Feb, Mar, etc.
            $randomDate = $faker->dateTimeBetween('-6 months', 'now');
            $carbonDate = Carbon::instance($randomDate);

            Student::create([
                'first_name'      => $faker->firstName,
                'last_name'       => $faker->lastName,
                'email'           => $faker->unique()->safeEmail,
                'gender'          => $faker->randomElement(['Male', 'Female']),
                'date_of_birth'   => $faker->date('Y-m-d', '2005-12-31'),
                'course_id'       => $faker->randomElement($courseIds),
                
                // 3. Sync all date columns so the backend query finds them
                'enrollment_date' => $carbonDate->format('Y-m-d'),
                'created_at'      => $carbonDate, 
                'updated_at'      => $carbonDate,
            ]);
        }

        $this->command->info("Student Seeding Completed!");
    }
}