<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LessonSessionController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Sesi Pelajaran
    Route::get('/lesson-sessions', [LessonSessionController::class, 'index']);
    Route::put('/lesson-sessions/{id}', [LessonSessionController::class, 'update']);
});
