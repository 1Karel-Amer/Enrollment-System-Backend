<?php

namespace App\Http\Controllers;

use App\Models\SchoolDay;
use Illuminate\Http\Request;

class SchoolDayController extends Controller
{
    public function index()
    {
        // Fetch all calendar data to send to the React frontend
        return response()->json(SchoolDay::orderBy('date', 'asc')->get());
    }
}