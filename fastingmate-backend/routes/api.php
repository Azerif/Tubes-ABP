<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FastingScheduleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FoodlogController;
use App\Http\Controllers\ActivitylogController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\StreakController;
use App\Http\Controllers\WeightLogController;
use App\Http\Controllers\UserTargetController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ChatbotConversationController;

// Routes untuk autentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Routes yang memerlukan autentikasi (Sanctum middleware)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user/{id}', [UserController::class, 'show']); // Get user profile by ID
    Route::put('/user/{id}', [UserController::class, 'update']); // Update user profile by ID
    Route::get('/user/{id}/weight-category', [UserController::class, 'calculateCategory']); // Get BMI category by ID
    
    // 🔹 **Weight Log (Catatan Berat Badan)**
    Route::apiResource('weight-logs', WeightLogController::class)->only(['index', 'store', 'destroy']);

    Route::apiResource('food-logs', FoodLogController::class);
    Route::apiResource('activity-logs', ActivityLogController::class);

    // 🔹 **Fasting Schedules (Jadwal Puasa)**
    Route::apiResource('fasting-schedules', FastingScheduleController::class);
    Route::put('/fasting-schedules/{id}/complete', [FastingScheduleController::class, 'updateStatus']);

    // 🔹 **Notifications (Notifikasi)**
    Route::apiResource('notifications', NotificationController::class)->only(['index', 'store', 'destroy']);

    // 🔹 **User Targets (Target Pengguna)**
    Route::apiResource('user-targets', UserTargetController::class);

    // 🔹 **Leaderboard (Peringkat)**
    Route::get('/leaderboard', [LeaderboardController::class, 'index']); // Menampilkan leaderboard
    Route::get('/leaderboard/{id}', [LeaderboardController::class, 'show']); // Menampilkan detail peringkat user tertentu

    // 🔹 **Reports (Laporan)**
    Route::get('/reports', [ReportController::class, 'index']);

    // 🔹 **Streak (Catatan Hari Berturut-turut Puasa)**
    Route::get('/streak', [StreakController::class, 'index']);

    // // 🔹 **Chatbot**
    // Route::post('/chatbot', [ChatbotController::class, 'chat']); // Mengirim pesan ke chatbot
    // Route::get('/chatbot/conversations', [ChatbotConversationController::class, 'index']); // Menampilkan riwayat percakapan
    // Route::delete('/chatbot/conversations/{id}', [ChatbotConversationController::class, 'destroy']); // Menghapus percakapan chatbot
});
