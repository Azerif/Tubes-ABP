<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'activity_id', 'duration_minutes', 'calories_burned', 'performed_at'
    ];
}