<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ["code" => "IT101", "title" => "Introduction to Computing", "units" => 3, "year" => "1st Year", "term" => "1st Semester", "program" => "BSIT", "preReq" => "None", "description" => "Basics of computer hardware and software."],
            ["code" => "IT102", "title" => "Computer Programming 1", "units" => 3, "year" => "1st Year", "term" => "1st Semester", "program" => "BSIT", "preReq" => "None", "description" => "Basic logic and syntax."],
            ["code" => "IT103", "title" => "Computer Programming 2", "units" => 3, "year" => "1st Year", "term" => "2nd Semester", "program" => "BSIT", "preReq" => "IT102", "description" => "Intermediate programming and OOP."],
            ["code" => "IT104", "title" => "Data Structures & Algorithms", "units" => 3, "year" => "2nd Year", "term" => "1st Semester", "program" => "BSIT", "preReq" => "IT103", "description" => "Advanced data organization."],
            ["code" => "IT201", "title" => "Web Development", "units" => 3, "year" => "2nd Year", "term" => "2nd Semester", "program" => "BSIT", "preReq" => "IT103", "description" => "Full-stack web fundamentals."],
            ["code" => "MATH111", "title" => "Differential Calculus", "units" => 3, "year" => "1st Year", "term" => "1st Semester", "program" => "BSCE", "preReq" => "None", "description" => "Limits and derivatives."],
            ["code" => "MATH122", "title" => "Integral Calculus", "units" => 3, "year" => "1st Year", "term" => "2nd Semester", "program" => "BSCE", "preReq" => "MATH111", "description" => "Integration and engineering applications."],
            ["code" => "PHYS201", "title" => "Physics for Engineers", "units" => 4, "year" => "2nd Year", "term" => "1st Semester", "program" => "BSCE", "preReq" => "MATH111", "description" => "Mechanics and heat."],
            ["code" => "CE301", "title" => "Structural Theory", "units" => 4, "year" => "3rd Year", "term" => "1st Semester", "program" => "BSCE", "preReq" => "PHYS201", "description" => "Analysis of statically determinate structures."],
            ["code" => "ACC101", "title" => "Financial Accounting 1", "units" => 6, "year" => "1st Year", "term" => "1st Semester", "program" => "BSA", "preReq" => "None", "description" => "Accounting cycle."],
            ["code" => "ACC102", "title" => "Financial Accounting 2", "units" => 6, "year" => "1st Year", "term" => "2nd Semester", "program" => "BSA", "preReq" => "ACC101", "description" => "Intermediate accounting."],
            ["code" => "AUD401", "title" => "Auditing Theory", "units" => 3, "year" => "4th Year", "term" => "1st Semester", "program" => "BSA", "preReq" => "ACC102", "description" => "Standards and professional ethics."],
            ["code" => "CRIM1", "title" => "Intro to Criminology", "units" => 3, "year" => "1st Year", "term" => "1st Semester", "program" => "BSCrim", "preReq" => "None", "description" => "Crime and society."],
            ["code" => "CRIM2", "title" => "Criminal Justice System", "units" => 3, "year" => "1st Year", "term" => "2nd Semester", "program" => "BSCrim", "preReq" => "CRIM1", "description" => "Five pillars of CJS."],
            ["code" => "PSY101", "title" => "General Psychology", "units" => 3, "year" => "1st Year", "term" => "1st Semester", "program" => "BSPsych", "preReq" => "None", "description" => "Study of human behavior."],
            ["code" => "PSY102", "title" => "Developmental Psychology", "units" => 3, "year" => "1st Year", "term" => "2nd Semester", "program" => "BSPsych", "preReq" => "PSY101", "description" => "Human growth."]
        ];

        foreach ($subjects as $s) {
            Subject::create($s);
        }
    }
}