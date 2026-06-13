<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [

        'student_id',
        'first_name',
        'last_name',
        'email',
        'gender',
        'date_of_birth',
        'year_level',
        'contact_no',
        'address',
        'emergency_contact_name',
        'emergency_contact_no',
        'course_id',
        'enrollment_date',
        'gpa',        
        'attendance'

    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date'
    ];

    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

   
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(
            Subject::class,
            'enrollments',
            'student_id',
            'subject_id'
        )->withTimestamps();
    }
}