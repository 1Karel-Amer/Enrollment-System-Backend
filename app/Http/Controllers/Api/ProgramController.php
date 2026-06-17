<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Subject; // 🌟 Added this line so we can query Subjects directly
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        return response()->json(Program::all());
    }

    public function show($id)
    {
        // 1. Find the program by its ID
        $program = Program::findOrFail($id);

        // 2. Query the subjects table matching the program's code column
        // This mirrors exactly how your updated Subject Seeder maps data now!
        $subjects = Subject::where('program', $program->code)->get();

        // 3. Attach the subjects collection dynamically to your program object response
        $program->subjects = $subjects;

        return response()->json($program);
    }
}