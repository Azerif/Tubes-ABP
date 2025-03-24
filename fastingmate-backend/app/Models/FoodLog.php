<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodLog extends Model
{
    protected $fillable = [
        'user_id', 'food_id', 'quantity', 'meal_type',
        'total_calories', 'total_protein', 'total_carbs', 'total_fat', 'consumed_at'
    ];
}