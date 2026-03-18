<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domain\User\Controllers\UserController;
use App\Domain\Attendance\Controllers\AttendanceController;

// Example API route
Route::get('/hello', function () {
    return response()->json(['message' => 'Hello API!']);
});




Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/attendance/history', [AttendanceController::class, 'history']);
    Route::post('/attendance/check', [AttendanceController::class, 'checkInOut']);
    Route::post('/attendance/calendar', [AttendanceController::class, 'calendar']);
    Route::post('/logout', [UserController::class, 'logout']);
});
