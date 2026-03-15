<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function programData(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program', 'code');
    }
}