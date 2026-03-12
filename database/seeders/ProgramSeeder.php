<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            ["code" => "BSIT", "name" => "BS in Information Technology", "type" => "Bachelor's", "duration" => "4 Years", "units" => 150, "status" => "Active", "description" => "Focuses on computer systems and information management."],
            ["code" => "BSCS", "name" => "BS in Computer Science", "type" => "Bachelor's", "duration" => "4 Years", "units" => 155, "status" => "Active", "description" => "Focuses on algorithmic processes and software theory."],
            ["code" => "BSIS", "name" => "BS in Information Systems", "type" => "Bachelor's", "duration" => "4 Years", "units" => 148, "status" => "Inactive", "description" => "Integration of IT solutions and business processes."],
            ["code" => "BSCE", "name" => "BS in Civil Engineering", "type" => "Bachelor's", "duration" => "5 Years", "units" => 180, "status" => "Active", "description" => "Deals with the design, construction, and maintenance of the physical environment."],
            ["code" => "BSCrim", "name" => "BS in Criminology", "type" => "Bachelor's", "duration" => "4 Years", "units" => 160, "status" => "Active", "description" => "The scientific study of crime, including its causes and responses by law enforcement."],
            ["code" => "BSA", "name" => "BS in Accountancy", "type" => "Bachelor's", "duration" => "4 Years", "units" => 175, "status" => "Active", "description" => "Provides a foundation for a career in the accounting profession."],
            ["code" => "BSPsych", "name" => "BS in Psychology", "type" => "Bachelor's", "duration" => "4 Years", "units" => 145, "status" => "Under Review", "description" => "The scientific study of the human mind and its functions."]
        ];

        foreach ($programs as $p) {
            Program::create($p);
        }
    }
}