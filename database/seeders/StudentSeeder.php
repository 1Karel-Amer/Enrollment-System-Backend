<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        // Get all course IDs so we can assign students to them randomly
        $courseIds = Course::pluck('id')->toArray();

        for ($i = 0; $i < 500; $i++) {
            // Inside the StudentSeeder loop:
    Student::create([
        'first_name'      => $faker->firstName,
        'last_name'       => $faker->lastName,
        'email'           => $faker->unique()->safeEmail,
        'gender'          => $faker->randomElement(['Male', 'Female']),
        'date_of_birth'   => $faker->date('Y-m-d', '2005-12-31'), // Updated name
        'course_id'       => $faker->randomElement($courseIds),
        'enrollment_date' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);

        }
    }
}