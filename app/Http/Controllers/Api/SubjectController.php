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

   if ($request->filled('program') && $request->program !== 'All') {
            $program = $request->program;
            $query->where(function($q) use ($program) {
                $q->where('program', 'LIKE', "%$program%")
                  ->orWhere('program', 'LIKE', substr($program, 0, 4) . "%"); 
            }); // <-- Added the closing parenthesis and semicolon here
        }

       
        if ($request->filled('year') && $request->year !== 'All') {
            $query->where('year', $request->year);
        }
        
       
        if ($request->filled('term') && $request->term !== 'All') {
            $query->where('term', $request->term);
        }
        
        return response()->json($query->orderBy('year')->orderBy('term')->get());
    }

    public function show($id)
    {
        return response()->json(Subject::findOrFail($id));
    }

    public function store(Request $request)
    {
       
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