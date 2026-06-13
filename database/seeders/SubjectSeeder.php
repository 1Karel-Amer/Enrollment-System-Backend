<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            'BSIT' => 'BS Information Technology',
            'BSCS' => 'BS Computer Science',
            'BSCE' => 'BS Civil Engineering',
            'BSCrim' => 'BS Criminology',
            'BSA' => 'BS Accountancy',
            'BSPsych' => 'BS Psychology'
        ];

        $years = ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'];
        $terms = ['1st Semester', '2nd Semester'];

        foreach ($programs as $code => $fullName) {
            foreach ($years as $year) {
                // Skip 5th year for non-engineering
                if ($year === '5th Year' && $code !== 'BSCE') continue;

                foreach ($terms as $term) {
                 
                    for ($i = 1; $i <= 2; $i++) {
                        $sCode = $code . "-" . substr($year, 0, 1) . ($term[0]) . $i;
                        
                        $data = [
                            "code" => $sCode,
                            "title" => "$fullName Major $i (" . substr($year, 0, 3) . " " . substr($term, 0, 3) . ")",
                            "units" => ($code === 'BSA') ? 6 : 3,
                            "year" => $year,
                            "term" => $term,
                            "program" => $code,
                            "description" => "Core subject for $fullName students."
                        ];

                        Subject::updateOrCreate(['code' => $data['code']], $data);

                        $data['code'] = $sCode . "-ALT";
                        $data['program'] = $fullName;
                        Subject::updateOrCreate(['code' => $data['code']], $data);
                    }
                }
            }
        }
    }
}