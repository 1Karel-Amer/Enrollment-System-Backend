<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('students', function (Blueprint $table) {
        // 1. Drop the foreign key constraint first
        $table->dropForeign('students_course_id_foreign');
        
        // 2. Now you can safely drop the column
        $table->dropColumn('course_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
