<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // with('course') ensures we get the course name, not just the ID
        $query = Student::with('course');

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where(function($q) use ($search) {
                $q->where('student_id', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }

        // Returns paginated data including the course relationship
        return response()->json($query->paginate(15));
    }

    public function show($id)
    {
        $student = Student::with('course')->findOrFail($id);
        return response()->json($student);
    }
}