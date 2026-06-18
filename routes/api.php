<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectAPIController;
use App\Http\Controllers\Api\UserAPIController;
use App\Http\Controllers\Api\TaskAPIController;

// Project API
Route::apiResource('project', ProjectAPIController::class);

// User API CRUD
Route::apiResource('users', UserAPIController::class);

// Task API CRUD
Route::apiResource('tasks', TaskApiController::class)->names('api.tasks');