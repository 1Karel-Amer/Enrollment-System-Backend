<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use Faker\Factory as Faker;
use Carbon\Carbon;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_PH');

        $courseIds = Course::pluck('id')->toArray();

        if (!$courseIds) {
            $this->command->warn("Run CourseSeeder first.");
            return;
        }

        $yearLevels = ['1st Year','2nd Year','3rd Year','4th Year'];

        $this->command->info("Seeding 500 Students...");

        foreach(range(1,500) as $i){

            $date = Carbon::instance(
                $faker->dateTimeBetween('-6 months','now')
            );

            $year = $faker->randomElement($yearLevels);

            Student::create([

                'student_id' => '2026-' . str_pad($i,4,'0',STR_PAD_LEFT),

                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,

                'email' => $faker->unique()->safeEmail,

                'gender' => $faker->randomElement(['Male','Female']),

                'date_of_birth' => $faker->date('Y-m-d','2006-12-31'),

                'year_level' => $year,

                'contact_no' => '09'.$faker->numberBetween(100000000,999999999),

                'address' => $faker->streetAddress.', '.$faker->city,

                'emergency_contact_name' => $faker->name,
                'emergency_contact_no' => '09'.$faker->numberBetween(100000000,999999999),

                'course_id' => $faker->randomElement($courseIds),

                'enrollment_date' => $date,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        $this->command->info("500 Students Seeded Successfully");
    }
}