<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectAPIController;
use App\Http\Controllers\Api\UserAPIController;
use App\Http\Controllers\Api\TaskAPIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Route API untuk aplikasi BagiTugas
| Digunakan untuk akses data berbasis JSON (REST API)
|
*/

// ==================== PROJECT API ====================
// Endpoint untuk manajemen project (CRUD)
Route::apiResource('project', ProjectAPIController::class);

// ==================== USER API ====================
// Endpoint untuk mendapatkan data user
Route::get('users', [UserAPIController::class, 'index']);
Route::get('users/{id}', [UserAPIController::class, 'show']);

// ==================== TASK API ====================
// Endpoint untuk mendapatkan data tugas
Route::get('tasks', [TaskAPIController::class, 'index']);
Route::get('tasks/{id}', [TaskAPIController::class, 'show']);
