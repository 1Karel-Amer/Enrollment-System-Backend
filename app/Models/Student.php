<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relationship to Program
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subjects', 'student_id', 'subject_id')
                    ->withPivot(['school_year', 'term', 'final_grade', 'status']); 
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}