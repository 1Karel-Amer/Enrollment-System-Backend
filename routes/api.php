<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\SchoolDayController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// AUTHENTICATION
Route::post('/login', [AuthController::class, 'login'])->name('login');

// PUBLIC ROUTES
Route::get('/dashboard-stats', [DashboardController::class, 'index']);
Route::get('/weather/{city}', [WeatherController::class, 'getWeather']);

// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    // Logout Logic
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    });

    // Student Module
    Route::get('/students', [StudentController::class, 'index']);
    
    // Course Module
    Route::get('/courses', [CourseController::class, 'index']);
    
    // School Days & Academic Calendar Module
    Route::get('/school-days', [SchoolDayController::class, 'index']);
});