<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolDay extends Model
{
    protected $fillable = [
        'date',
        'event_type',
        'attendance_count',
        'event_name', // ADD THIS LINE
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
