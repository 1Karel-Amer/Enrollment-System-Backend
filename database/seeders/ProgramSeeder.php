<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;

// OVERHAUL: ProgramSeeder now has all 20 programs with departments.
// This replaces BOTH the old ProgramSeeder and CourseSeeder.
// The Student model will now reference programs directly via program_id.

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            // CCE — College of Computing Education
            [
                'code'        => 'BSIT',
                'department'  => 'CCE',
                'name'        => 'BS in Information Technology',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 150,
                'status'      => 'Active',
                'description' => 'Focuses on computer systems, networks, and information management.',
            ],
            [
                'code'        => 'BSCS',
                'department'  => 'CCE',
                'name'        => 'BS in Computer Science',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 155,
                'status'      => 'Active',
                'description' => 'Focuses on algorithmic processes, computation theory, and software development.',
            ],
            [
                'code'        => 'BSDS',
                'department'  => 'CCE',
                'name'        => 'BS in Data Science',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 148,
                'status'      => 'Active',
                'description' => 'Combines statistics, machine learning, and data engineering.',
            ],

            // CEA — College of Engineering and Architecture
            [
                'code'        => 'BSCE',
                'department'  => 'CEA',
                'name'        => 'BS in Civil Engineering',
                'type'        => "Bachelor's",
                'duration'    => '5 Years',
                'units'       => 180,
                'status'      => 'Active',
                'description' => 'Design, construction, and maintenance of infrastructure.',
            ],
            [
                'code'        => 'BSME',
                'department'  => 'CEA',
                'name'        => 'BS in Mechanical Engineering',
                'type'        => "Bachelor's",
                'duration'    => '5 Years',
                'units'       => 180,
                'status'      => 'Active',
                'description' => 'Application of physics and materials science for mechanical systems.',
            ],
            [
                'code'        => 'BSEE',
                'department'  => 'CEA',
                'name'        => 'BS in Electrical Engineering',
                'type'        => "Bachelor's",
                'duration'    => '5 Years',
                'units'       => 178,
                'status'      => 'Active',
                'description' => 'Study of electricity, electronics, and electromagnetism.',
            ],
            [
                'code'        => 'BSArch',
                'department'  => 'CEA',
                'name'        => 'BS in Architecture',
                'type'        => "Bachelor's",
                'duration'    => '5 Years',
                'units'       => 182,
                'status'      => 'Active',
                'description' => 'Design and planning of buildings and structures.',
            ],

            // CBA — College of Business Administration
            [
                'code'        => 'BSA',
                'department'  => 'CBA',
                'name'        => 'BS in Accountancy',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 175,
                'status'      => 'Active',
                'description' => 'Provides a foundation for a career in the accounting profession.',
            ],
            [
                'code'        => 'BSBA',
                'department'  => 'CBA',
                'name'        => 'BS in Business Administration',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 148,
                'status'      => 'Active',
                'description' => 'Core business principles including management, marketing, and finance.',
            ],
            [
                'code'        => 'BSEntrep',
                'department'  => 'CBA',
                'name'        => 'BS in Entrepreneurship',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 145,
                'status'      => 'Active',
                'description' => 'Developing skills to start and manage a business venture.',
            ],

            // CHTM — College of Hospitality and Tourism Management
            [
                'code'        => 'BSTM',
                'department'  => 'CHTM',
                'name'        => 'BS in Tourism Management',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 142,
                'status'      => 'Active',
                'description' => 'Management of travel and tourism operations.',
            ],
            [
                'code'        => 'BSHM',
                'department'  => 'CHTM',
                'name'        => 'BS in Hospitality Management',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 144,
                'status'      => 'Active',
                'description' => 'Operations and management in the hospitality industry.',
            ],

            // CAS — College of Arts and Sciences
            [
                'code'        => 'BAComm',
                'department'  => 'CAS',
                'name'        => 'BA in Communication',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 140,
                'status'      => 'Active',
                'description' => 'Study of media, journalism, and communication theory.',
            ],
            [
                'code'        => 'BSPsych',
                'department'  => 'CAS',
                'name'        => 'BS in Psychology',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 145,
                'status'      => 'Active',
                'description' => 'Scientific study of human mind, behavior, and mental processes.',
            ],

            // CCJE — College of Criminal Justice Education
            [
                'code'        => 'BSCrim',
                'department'  => 'CCJE',
                'name'        => 'BS in Criminology',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 160,
                'status'      => 'Active',
                'description' => 'Scientific study of crime, its causes, and law enforcement responses.',
            ],

            // CTE — College of Teacher Education
            [
                'code'        => 'BEEd',
                'department'  => 'CTE',
                'name'        => 'Bachelor of Elementary Education',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 150,
                'status'      => 'Active',
                'description' => 'Preparing teachers for elementary school instruction.',
            ],
            [
                'code'        => 'BSEd',
                'department'  => 'CTE',
                'name'        => 'Bachelor of Secondary Education',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 152,
                'status'      => 'Active',
                'description' => 'Preparing teachers for secondary school instruction.',
            ],

            // CON — College of Nursing and Health Sciences
            [
                'code'        => 'BSN',
                'department'  => 'CON',
                'name'        => 'BS in Nursing',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 165,
                'status'      => 'Active',
                'description' => 'Professional nursing education and clinical practice.',
            ],
            [
                'code'        => 'BSPharm',
                'department'  => 'CON',
                'name'        => 'BS in Pharmacy',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 168,
                'status'      => 'Active',
                'description' => 'Study of drugs, their composition, and clinical use.',
            ],
            [
                'code'        => 'BSMT',
                'department'  => 'CON',
                'name'        => 'BS in Medical Technology',
                'type'        => "Bachelor's",
                'duration'    => '4 Years',
                'units'       => 162,
                'status'      => 'Active',
                'description' => 'Laboratory diagnostics and medical testing sciences.',
            ],
        ];

        foreach ($programs as $p) {
            Program::updateOrCreate(['code' => $p['code']], $p);
        }
    }
}