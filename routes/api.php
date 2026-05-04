<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;

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
Route::apiResource('projects', ProjectController::class);
