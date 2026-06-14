<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
        'gpa' => 'float',
        'attendance' => 'integer'
    ];

    /**
     * Get the course/program associated with the student.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the direct enrollment logs for the student.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the subjects enrolled by the student.
     * Pivot columns removed to prevent SQL "Column not found" errors.
     */
    public function subjects()
    {
        return $this->belongsToMany(
            Subject::class,
            'enrollments',
            'student_id',
            'subject_id'
        );
    }
}