<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProgramController; // Added this
use App\Http\Controllers\Api\SubjectController; // Added this
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\SchoolDayController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// --- AUTHENTICATION ---
Route::post('/login', [AuthController::class, 'login'])->name('login');

// --- PUBLIC ROUTES ---
Route::get('/dashboard-stats', [DashboardController::class, 'index']);
Route::get('/weather/{city}', [WeatherController::class, 'getWeather']);

// --- PROTECTED ROUTES ---
Route::middleware('auth:sanctum')->group(function () {
    
    // 1. Session Management
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    });

    // 2. University Modules (Programs & Subjects)
    Route::get('/programs', [ProgramController::class, 'index']); // Now accessible!
   Route::get('/programs/{id}', [ProgramController::class, 'show']); // ADD THIS LINE
    Route::get('/subjects', [SubjectController::class, 'index']); // Now accessible!

    
    // 3. Student Module
    Route::get('/students', [StudentController::class, 'index']);
    
    // 4. Course Module
    Route::get('/courses', [CourseController::class, 'index']);
    
    // 5. School Days & Academic Calendar
    Route::get('/school-days', [SchoolDayController::class, 'index']);
});