<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        // 1. Create your Login User
        // This the  admin account after a migrate:fresh
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        
      $this->call([
        ProgramSeeder::class,        // 1st — programs must exist first
        CourseSeeder::class,         // 2nd — courses (if still used)
        SubjectSeeder::class,        // 3rd — subjects need programs to exist
        StudentSeeder::class,        // 4th — students reference program_id
        StudentSubjectSeeder::class, // 5th — needs both students + subjects
        SchoolDaySeeder::class,      // last — independent, can go anywhere
    ]);
    }
}