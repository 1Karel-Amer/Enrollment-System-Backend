<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create your Login User
        // This ensures you always have an admin account after a migrate:fresh
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Run the specific enrollment system seeders in order
        // Note: CourseSeeder MUST come before StudentSeeder
        $this->call([
            CourseSeeder::class,
            StudentSeeder::class,
            SchoolDaySeeder::class,
            ProgramSeeder::class,
            SubjectSeeder::class,
        ]);
    }
}