<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// OVERHAUL STEP 1:
// Adds `department` to programs so it can fully replace the courses table.
// Adds `program_id` to students so they reference programs directly.
// The old `course_id` column is kept temporarily so existing data isn't lost
// during migration — drop it manually after re-seeding if you want.

return new class extends Migration
{
    public function up(): void
    {
        // Add department to programs (was only on courses before)
        Schema::table('programs', function (Blueprint $table) {
            $table->string('department')->nullable()->after('code');
        });

        // Add program_id to students (replaces course_id)
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('program_id')
                  ->nullable()
                  ->after('course_id')
                  ->constrained('programs')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
            $table->dropColumn('program_id');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('department');
        });
    }
};