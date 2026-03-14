<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',            // Added to match seeder
        'first_name',
        'last_name',
        'email',
        'gender',
        'date_of_birth',
        'year_level',           // Added for demographics
        'contact_no',            // Added for demographics
        'address',               // Added for demographics
        'emergency_contact_name', // Added for demographics
        'emergency_contact_no',   // Added for demographics
        'course_id',
        'enrollment_date',
    ];

    /**
     * Relationship: A student belongs to a course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}