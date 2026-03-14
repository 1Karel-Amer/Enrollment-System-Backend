<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolDay;
use Carbon\Carbon;

class SchoolDaySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear old data so we start fresh
        SchoolDay::truncate();

        // 2. Map of specific dates to their names
        $events = [
            '2026-03-16' => 'Term Break',
            '2026-03-20' => 'Eid Al-Fitr',
            '2026-03-25' => 'Campus Event',
            '2026-06-12' => 'Independence Day',
            '2026-06-15' => 'Opening of Classes',
        ];

        // 3. Loop through the entire year of 2026
        $date = Carbon::create(2026, 1, 1);
        $endOfYear = Carbon::create(2026, 12, 31);

        while ($date <= $endOfYear) {
            $dateString = $date->format('Y-m-d');
            $isWeekend = $date->isWeekend();
            
            // Determine type
            $type = 'regular';
            if (isset($events[$dateString])) {
                // If it's in our list, call it a 'Holiday' or 'Event'
                $type = str_contains($events[$dateString], 'Day') ? 'Holiday' : 'Event';
            } elseif ($isWeekend) {
                $type = 'weekend';
            }

            // Random attendance for school days, 0 for others
            $attendance = ($type === 'regular' || $type === 'Event') ? rand(400, 500) : 0;

            SchoolDay::create([
                'date'             => $dateString,
                'event_type'       => $type,
                'event_name'       => $events[$dateString] ?? null,
                'attendance_count' => $attendance,
            ]);

            $date->addDay();
        }
    }
}