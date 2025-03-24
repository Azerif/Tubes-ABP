<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastingSchedule extends Model
{
    use HasFactory;

    protected $table = 'fasting_schedules';

    protected $fillable = [
        'user_id',
        'schedule_type',
        'schedule_date',
        'start_time',
        'end_time',
        'duration_hours',
        'is_completed'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'schedule_date' => 'date',
    ];

    /**
     * Relasi ke model User (Seorang user memiliki banyak jadwal puasa)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
