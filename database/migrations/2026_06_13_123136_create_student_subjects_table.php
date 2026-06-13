<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

   public function up(): void
{
    Schema::create('student_subjects', function (Blueprint $table) {
        $table->id();
        $table->foreignId('student_id')->constrained()->onDelete('cascade');
        $table->foreignId('subject_id')->constrained()->onDelete('cascade');
        
        $table->string('year_level');   
        $table->string('term');         
        $table->string('school_year');  
      
        $table->decimal('midterm_grade', 3, 2)->nullable(); 
        $table->decimal('final_grade', 3, 2)->nullable();   
        $table->string('status');                           
        
        $table->timestamps();
    });
}
};
