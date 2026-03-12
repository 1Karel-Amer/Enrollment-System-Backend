<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// 1. PUBLIC ROUTES (No login required)
Route::post('/login', [AuthController::class, 'login'])->name('login');

// MOVE THIS HERE (Outside the middleware)
Route::get('/dashboard-stats', [DashboardController::class, 'index']);

// 2. PROTECTED ROUTES (Requires token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/courses', [CourseController::class, 'index']);
});