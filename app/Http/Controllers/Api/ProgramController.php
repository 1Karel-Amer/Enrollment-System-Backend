<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;

class ProgramController extends Controller
{
    public function index()
    {
        // select() ensures we aren't downloading heavy, unused columns
        return response()->json(Program::select('id', 'name')->get());
    }
}