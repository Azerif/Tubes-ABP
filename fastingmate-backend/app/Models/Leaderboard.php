<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $table = 'leaderboard';

    protected $fillable = [
        'user_id',
        'total_points',
        'total_fasting_days',
        'total_weight_loss',
        'current_rank'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}