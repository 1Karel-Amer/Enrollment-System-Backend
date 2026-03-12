<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolDay;
use Carbon\Carbon;

class SchoolDaySeeder extends Seeder
{
    public function run()
    {
        // Generate attendance for the last 30 days
        for ($i = 30; $i >= 0; $i--) {
            SchoolDay::create([
                'date'             => Carbon::now()->subDays($i)->format('Y-m-d'),
                'event_type'       => 'regular',
                'attendance_count' => rand(400, 500), // Random daily attendance
            ]);
        }
    }
}