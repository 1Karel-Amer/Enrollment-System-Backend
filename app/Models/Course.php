<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = ['name', 'code', 'description'];

    /**
     * Relationship: A course has many students.
     * This allows withCount('students') to function in the controller.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'course_id');
    }
}