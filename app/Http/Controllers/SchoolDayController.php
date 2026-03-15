<?php

namespace App\Http\Controllers;

use App\Models\SchoolDay;
use Illuminate\Http\Request;

class SchoolDayController extends Controller
{
    public function index()
    {
        
        return response()->json(SchoolDay::orderBy('date', 'asc')->get());
    }
}