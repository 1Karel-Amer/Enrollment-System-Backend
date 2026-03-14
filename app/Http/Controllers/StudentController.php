<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the students with their academic course.
     */
    public function index()
    {
        try {
            // "Eager Load" the course relationship
            $students = Student::with('course')->get();
            return response()->json($students, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Database error',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}