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
        Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('student_id')->unique();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('gender');
                $table->date('date_of_birth');
                $table->string('year_level'); // <--- ADD THIS LINE
                $table->string('contact_no');
                $table->text('address');
        
                // Emergency Contact Fields
                $table->string('emergency_contact_name'); 
                $table->string('emergency_contact_no');

                $table->foreignId('course_id')->constrained('courses');
                $table->date('enrollment_date');
                $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
