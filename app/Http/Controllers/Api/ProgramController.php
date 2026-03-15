<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        return response()->json(Program::all());
    }

    public function show($id)
    {
        $program = Program::with('subjects')->findOrFail($id);

        return response()->json($program);
    }
}