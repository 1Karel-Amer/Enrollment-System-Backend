<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProgramController; 
use App\Http\Controllers\Api\SubjectController; 
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\SchoolDayController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Student;

// --- AUTHENTICATION ---
Route::post('/login', [AuthController::class, 'login'])->name('login');

// --- PUBLIC ROUTES (No Token Required) ---
// 🚨 MOVED THESE HERE: This fixes the 401 Unauthorized error in React!
Route::get('/students', [StudentController::class, 'index']);
Route::get('/students/{id}', [StudentController::class, 'show']);
Route::get('/students/{id}/predict-risk', [StudentController::class, 'predictDropoutRisk']);

Route::get('/dashboard-stats', [DashboardController::class, 'index']);
Route::get('/weather/{city}', [WeatherController::class, 'getWeather']);


// --- PROTECTED ROUTES (Token Required) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // 1. Session Management
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    });

    // 2. University Modules
    Route::get('/programs', [ProgramController::class, 'index']);
    Route::get('/programs/{id}', [ProgramController::class, 'show']);
    
    // SUBJECT ROUTES - Added POST and DELETE
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::post('/subjects', [SubjectController::class, 'store']);    // Required to save new subjects
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy']); // Required to archive
    
    // 4. Course Module
    Route::get('/courses', [CourseController::class, 'index']);
    
    // 5. School Days & Academic Calendar
    Route::get('/school-days', [SchoolDayController::class, 'index']);
});