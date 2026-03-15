<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();

        // FIX: Use LIKE instead of exact match to handle "BSIT" vs "BS Information Technology"
        if ($request->filled('program') && $request->program !== 'All') {
            $program = $request->program;
            $query->where(function($q) use ($program) {
                $q->where('program', 'LIKE', "%$program%")
                  ->orWhere('program', 'LIKE', substr($program, 0, 4) . "%"); // Catches "BSIT" from "BS Information..."
            });
        }

        // FIX: Allow year filtering but ensure it doesn't break if not provided
        if ($request->filled('year') && $request->year !== 'All') {
            $query->where('year', $request->year);
        }
        
        // FIX: Add term filtering to support your Semester tabs
        if ($request->filled('term') && $request->term !== 'All') {
            $query->where('term', $request->term);
        }

        // Return everything else sorted by year and term
        return response()->json($query->orderBy('year')->orderBy('term')->get());
    }

    public function show($id)
    {
        return response()->json(Subject::findOrFail($id));
    }

    public function store(Request $request)
    {
        // Added 'sometimes' to description/preReq to prevent validation crashes
        $validated = $request->validate([
            'code' => 'required|unique:subjects,code',
            'title' => 'required|string',
            'units' => 'required|numeric',
            'year' => 'required|string',
            'term' => 'required|string',
            'program' => 'required|string',
            'description' => 'nullable|string',
            'preReq' => 'nullable|string'
        ]);

        $subject = Subject::create($validated);

        return response()->json($subject, 201);
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete(); 

        return response()->json([
            'message' => 'Subject has been successfully moved to archives.'
        ]);
    }
}