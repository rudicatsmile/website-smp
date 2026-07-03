<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LessonSessionController;
use App\Http\Controllers\Api\SessionRelationController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Sesi Pelajaran (Utama)
    Route::get('/lesson-sessions', [LessonSessionController::class, 'index']);
    Route::put('/lesson-sessions/{id}', [LessonSessionController::class, 'update']);

    // Sesi Pelajaran (Relasi & Lookup)
    Route::get('/lookup/materials', [SessionRelationController::class, 'availableMaterials']);
    Route::get('/lookup/assignments', [SessionRelationController::class, 'availableAssignments']);
    Route::get('/lookup/students', [SessionRelationController::class, 'availableStudents']);

    Route::post('/lesson-sessions/{id}/materials', [SessionRelationController::class, 'attachMaterial']);
    Route::delete('/lesson-sessions/{id}/materials/{materialId}', [SessionRelationController::class, 'detachMaterial']);

    Route::post('/lesson-sessions/{id}/assignments', [SessionRelationController::class, 'attachAssignment']);
    Route::delete('/lesson-sessions/{id}/assignments/{assignmentId}', [SessionRelationController::class, 'detachAssignment']);

    Route::post('/lesson-sessions/{id}/assessments', [SessionRelationController::class, 'storeAssessment']);
    Route::delete('/lesson-sessions/{id}/assessments/{assessmentId}', [SessionRelationController::class, 'deleteAssessment']);

    Route::post('/lesson-sessions/{id}/cases', [SessionRelationController::class, 'storeCase']);
    Route::delete('/lesson-sessions/{id}/cases/{caseId}', [SessionRelationController::class, 'deleteCase']);
});
