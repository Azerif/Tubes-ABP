<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FastingScheduleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CaloriesController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\StreakController;

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Authenticated routes (Sanctum middleware)
Route::middleware('auth:sanctum')->group(function () {
    // Update profile route should use PUT, not POST
    Route::put('user/profile/{id}', [UserProfileController::class, 'update']); // Corrected from POST to PUT
    
    Route::get('/user/profile/category', [UserProfileController::class, 'calculateCategory']);
    
    Route::get('/fasting-schedules', [FastingScheduleController::class, 'index']);
    Route::post('/fasting-schedules', [FastingScheduleController::class, 'store']);
    Route::patch('/fasting-schedules/{id}', [FastingScheduleController::class, 'updateStatus']);
   
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
    // Menampilkan daftar catatan kalori
    Route::get('/calories', [CaloriesController::class, 'index']);
        
    // Menambahkan catatan kalori baru
    Route::post('/calories', [CaloriesController::class, 'store']);
    
    // Memperbarui catatan kalori
    Route::put('/calories/{id}', [CaloriesController::class, 'update']);
    
    // Menghapus catatan kalori
    Route::delete('/calories/{id}', [CaloriesController::class, 'destroy']);
    Route::get('/leaderboard', [LeaderboardController::class, 'index']);
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/streak', [StreakController::class, 'index']);
});