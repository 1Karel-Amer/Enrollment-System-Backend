<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'department',
        'name',
        'type',
        'duration',
        'units',
        'status',
        'description',
    ];

    /**
     * Students enrolled in this program.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'program_id');
    }
}