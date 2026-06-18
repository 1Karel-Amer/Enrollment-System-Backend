<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // This forces Laravel to use the singular table name!
    protected $table = 'attendance'; 

    protected $guarded = [];
}