<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            ['course_name' => 'BS in Information Technology', 'department' => 'CCE'],
            ['course_name' => 'BS in Computer Science', 'department' => 'CCE'],
            ['course_name' => 'BS in Data Science', 'department' => 'CCE'],
            ['course_name' => 'BS in Civil Engineering', 'department' => 'CEA'],
            ['course_name' => 'BS in Mechanical Engineering', 'department' => 'CEA'],
            ['course_name' => 'BS in Electrical Engineering', 'department' => 'CEA'],
            ['course_name' => 'BS in Architecture', 'department' => 'CEA'],
            ['course_name' => 'BS in Accountancy', 'department' => 'CBA'],
            ['course_name' => 'BS in Business Administration', 'department' => 'CBA'],
            ['course_name' => 'BS in Entrepreneurship', 'department' => 'CBA'],
            ['course_name' => 'BS in Tourism Management', 'department' => 'CHTM'],
            ['course_name' => 'BS in Hospitality Management', 'department' => 'CHTM'],
            ['course_name' => 'BA in Communication', 'department' => 'CAS'],
            ['course_name' => 'BS in Psychology', 'department' => 'CAS'],
            ['course_name' => 'BS in Criminology', 'department' => 'CCJE'],
            ['course_name' => 'Bachelor of Elementary Education', 'department' => 'CTE'],
            ['course_name' => 'Bachelor of Secondary Education', 'department' => 'CTE'],
            ['course_name' => 'BS in Nursing', 'department' => 'CON'],
            ['course_name' => 'BS in Pharmacy', 'department' => 'CON'],
            ['course_name' => 'BS in Medical Technology', 'department' => 'CON'],
        ];

        DB::table('courses')->insert($courses);
    }
}