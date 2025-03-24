<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'height',
        'weight',
        'activity_level',
        'daily_calorie_needs',
        'weight_category',
        'total_points',
        'current_streak',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke tabel fasting_schedules (jadwal puasa pengguna)
     */
    public function fastingSchedules()
    {
        return $this->hasMany(FastingSchedule::class);
    }

    /**
     * Relasi ke tabel food_logs (catatan makanan pengguna)
     */
    public function foodLogs()
    {
        return $this->hasMany(FoodLog::class);
    }

    /**
     * Relasi ke tabel activity_logs (catatan aktivitas pengguna)
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Relasi ke tabel user_targets (target pengguna)
     */
    // public function userTargets()
    // {
    //     return $this->hasMany(UserTarget::class);
    // }

    /**
     * Relasi ke tabel weight_logs (catatan berat badan pengguna)
     */
    // public function weightLogs()
    // {
    //     return $this->hasMany(WeightLog::class);
    // }

    /**
     * Relasi ke tabel leaderboard (peringkat pengguna)
     */
    public function leaderboard()
    {
        return $this->hasOne(Leaderboard::class);
    }

    /**
     * Relasi ke tabel notifications (notifikasi pengguna)
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relasi ke tabel chatbot_conversations (percakapan dengan chatbot)
     */
    // public function chatbotConversations()
    // {
    //     return $this->hasMany(ChatbotConversation::class);
    // }
}
