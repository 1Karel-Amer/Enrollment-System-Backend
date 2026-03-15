<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // BS INFORMATION TECHNOLOGY
            ["code" => "IT101", "title" => "Intro to Computing", "units" => 3, "year" => "1st Year", "term" => "1st Semester", "program" => "BSIT"],
            
            // BS CIVIL ENGINEERING
            ["code" => "MATH111", "title" => "Differential Calculus", "units" => 3, "year" => "1st Year", "term" => "1st Semester", "program" => "BSCE"],
            ["code" => "MATH111-B", "title" => "Differential Calculus", "units" => 3, "year" => "1st Year", "term" => "1st Semester", "program" => "BS Civil Engineering"],

            // BS CRIMINOLOGY
            ["code" => "CRIM1", "title" => "Intro to Criminology", "units" => 3, "year" => "1st Year", "term" => "1st Semester", "program" => "BSCrim"],
            ["code" => "CRIM1-B", "title" => "Intro to Criminology", "units" => 3, "year" => "1st Year", "term" => "1st Semester", "program" => "BS Criminology"],

            // BS ACCOUNTANCY
            ["code" => "ACC101", "title" => "Financial Accounting 1", "units" => 6, "year" => "1st Year", "term" => "1st Semester", "program" => "BSA"],
            ["code" => "ACC101-B", "title" => "Financial Accounting 1", "units" => 6, "year" => "1st Year", "term" => "1st Semester", "program" => "BS Accountancy"],

            // BS PSYCHOLOGY
            ["code" => "PSY101", "title" => "General Psychology", "units" => 3, "year" => "1st Year", "term" => "1st Semester", "program" => "BSPsych"],
            ["code" => "PSY101-B", "title" => "General Psychology", "units" => 3, "year" => "1st Year", "term" => "1st Semester", "program" => "BS Psychology"],
        ];

        foreach ($subjects as $s) {
            Subject::updateOrCreate(['code' => $s['code']], $s);
        }
    }
}