<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// FIX 3: Add indexes to all columns used in WHERE, ORDER BY, or JOIN clauses.
//
// Run with: php artisan migrate
//
// What each index fixes:
//   students.student_id       — StudentController search by student_id LIKE
//   students.first/last_name  — StudentController search by name LIKE
//   students.course_id        — Student::with('course') JOIN, withCount('students')
//   school_days.date          — SchoolDay queries filtered/ordered by date
//   student_subjects.student_id — predictDropoutRisk fetches all rows per student
//   student_subjects.school_year/term — orderBy in predictDropoutRisk query
//   enrollments.student_id    — Student->enrollments() relationship
//   enrollments.subject_id    — Subject->enrollments() relationship
//   subjects.program          — SubjectController filter by program
//   subjects.year / term      — SubjectController orderBy year, term

return new class extends Migration
{
    public function up(): void
    {
        // ── students ──────────────────────────────────────────────────────────
        Schema::table('students', function (Blueprint $table) {
            // Used in LIKE search — index helps prefix searches (e.g. "2024-")
            $table->index('student_id', 'idx_students_student_id');

            // Composite index covers ORDER BY and full-name searches
            $table->index(['first_name', 'last_name'], 'idx_students_name');

            // FK join to courses — critical for withCount('students')
            $table->index('course_id', 'idx_students_course_id');
        });

        // ── school_days ───────────────────────────────────────────────────────
        Schema::table('school_days', function (Blueprint $table) {
            // Already has unique() on date, but adding a plain index
            // speeds up range queries like orderBy('date', 'asc')->take(10)
            // Note: if the unique constraint already acts as an index in your
            // MySQL version, this line is a no-op — safe to keep either way.
            if (!$this->indexExists('school_days', 'school_days_date_index')) {
                $table->index('date', 'idx_school_days_date');
            }
        });

        // ── student_subjects ──────────────────────────────────────────────────
        Schema::table('student_subjects', function (Blueprint $table) {
            // Most critical — predictDropoutRisk does a full table scan per student
            $table->index('student_id', 'idx_student_subjects_student_id');

            // Covers the orderBy('school_year')->orderBy('term') in the query
            $table->index(['school_year', 'term'], 'idx_student_subjects_period');
        });

        // ── enrollments ───────────────────────────────────────────────────────
        Schema::table('enrollments', function (Blueprint $table) {
            // Both sides of the pivot — speeds up belongsToMany loads
            $table->index('student_id', 'idx_enrollments_student_id');
            $table->index('subject_id', 'idx_enrollments_subject_id');
        });

        // ── subjects ──────────────────────────────────────────────────────────
        Schema::table('subjects', function (Blueprint $table) {
            // SubjectController filters by program column
            $table->index('program', 'idx_subjects_program');

            // SubjectController orderBy year, term
            $table->index(['year', 'term'], 'idx_subjects_year_term');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('idx_students_student_id');
            $table->dropIndex('idx_students_name');
            $table->dropIndex('idx_students_course_id');
        });

        Schema::table('school_days', function (Blueprint $table) {
            if ($this->indexExists('school_days', 'idx_school_days_date')) {
                $table->dropIndex('idx_school_days_date');
            }
        });

        Schema::table('student_subjects', function (Blueprint $table) {
            $table->dropIndex('idx_student_subjects_student_id');
            $table->dropIndex('idx_student_subjects_period');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex('idx_enrollments_student_id');
            $table->dropIndex('idx_enrollments_subject_id');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropIndex('idx_subjects_program');
            $table->dropIndex('idx_subjects_year_term');
        });
    }

    /**
     * Helper: check if an index already exists before adding it.
     * Prevents duplicate index errors when re-running migrations.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = \Illuminate\Support\Facades\DB::select(
            "SHOW INDEX FROM `{$table}` WHERE Key_name = ?",
            [$indexName]
        );
        return count($indexes) > 0;
    }
};