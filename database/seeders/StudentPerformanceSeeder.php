<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentPerformanceSeeder extends Seeder
{
    public function run(): void
    {
        
        Student::all()->each(function ($student) {
            $student->update([
                'gpa' => rand(100, 400) / 100, 
                'attendance' => rand(50, 100), 
            ]);
        });
    }
}