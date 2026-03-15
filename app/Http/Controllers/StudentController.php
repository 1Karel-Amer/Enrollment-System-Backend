<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
   public function index(Request $request)
{
    $query = Student::with('course');

    // If there is a search term, filter by ID or Name
    if ($request->has('search')) {
        $search = $request->query('search');
        $query->where('student_id', 'LIKE', "%{$search}%")
              ->orWhere('first_name', 'LIKE', "%{$search}%")
              ->orWhere('last_name', 'LIKE', "%{$search}%");
    }

    // Return paginated results (15 per page)
    return response()->json($query->paginate(15));
}
}