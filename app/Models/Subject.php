<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'units',
        'year',
        'term',
        'program',
        'preReq',
        'description'
    ];

    
    // Subject belongs to program
    public function programData(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program', 'code');
    }

    // Students enrolled in this subject
    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'enrollments',
            'subject_id',
            'student_id'
        )->withTimestamps();
    }

    // Enrollment records
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}