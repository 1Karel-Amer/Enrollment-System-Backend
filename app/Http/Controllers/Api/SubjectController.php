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

        if ($request->filled('program')) {
            $query->where('program', $request->program);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        return response()->json(Subject::findOrFail($id));
    }
}