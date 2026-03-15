<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    
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
}