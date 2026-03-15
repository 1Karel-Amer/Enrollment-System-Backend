<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index()
    {
        // Limiting columns makes the "Size" in your Network tab much smaller
        return response()->json(Subject::select('id', 'subject_code', 'description')->get());
    }
}