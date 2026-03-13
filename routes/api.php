<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

Route::post('/login', [AuthController::class, 'login'])->name('login');

// PUBLIC ROUTES
Route::get('/dashboard-stats', [DashboardController::class, 'index']);
Route::get('/weather/{city}', [WeatherController::class, 'getWeather']);


// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/courses', [CourseController::class, 'index']);
});