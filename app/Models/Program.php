<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    // This tells Laravel which columns you are allowed to save
    protected $fillable = ['code', 'name', 'type', 'duration', 'units', 'status', 'description'];
}