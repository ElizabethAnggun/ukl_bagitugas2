<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Berikut adalah definisi route untuk aplikasi BagiTugas.
| Route dibagi menjadi: Public (tanpa login) dan Protected (perlu login)
|
*/

// ==================== PUBLIC ROUTES ====================
// Route yang bisa diakses tanpa login

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// ==================== PROTECTED ROUTES ====================
// Route yang hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('projects', ProjectController::class);
   
    // ==================== TASK ROUTES ====================
    // CRUD Tugas
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/create', [TaskController::class, 'create'])->name('create');
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::get('/{task}', [TaskController::class, 'show'])->name('show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
        Route::put('/{task}', [TaskController::class, 'update'])->name('update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
        
        // Update status via AJAX
        Route::post('/{task}/status', [TaskController::class, 'updateStatus'])->name('updateStatus');
    });

    // Bukti Pengerjaan (Di luar grup tasks. agar name sesuai route('uploadProof'))
    Route::post('/tasks/{task}/proof', [TaskController::class, 'uploadProof'])->name('uploadProof');
    Route::get('/tasks/{task}/proof/download', [TaskController::class, 'downloadProof'])->name('downloadProof');
    Route::delete('/tasks/{task}/proof', [TaskController::class, 'deleteProof'])->name('deleteProof');

    // ==================== COMMENT ROUTES ====================
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // ==================== NOTIFICATION ROUTES ====================
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    
    // ==================== ACTIVITY LOG ROUTES ====================
    // Riwayat Aktivitas
    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
    });
    
});
