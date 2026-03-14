<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {
        // 1. Create your Login User (So you can still enter the Dashboard)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'tester@example.com',
            'password' => bcrypt('password'), // Explicitly set a password
            'role' => 'admin',
        ]);

        // 2. Run the specific enrollment system seeders in order
        // Note: CourseSeeder MUST come before StudentSeeder
        $this->call([
            CourseSeeder::class,
            StudentSeeder::class,
            SchoolDaySeeder::class,
            ProgramSeeder::class, // Added here
            SubjectSeeder::class,
        ]);
    }
}