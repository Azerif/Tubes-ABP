<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTarget extends Model
{
    use HasFactory;

    protected $table = 'user_targets';

    protected $fillable = [
        'user_id',
        'target_weight',
        'target_daily_calories',
        'target_protein',
        'target_carbs',
        'target_fat',
        'target_date',
        'is_achieved',
    ];

    protected $casts = [
        'target_date' => 'date',
        'is_achieved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
