<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('food_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('food_id')->nullable()->constrained('food')->onDelete('set null');
            $table->float('quantity');
            $table->string('meal_type'); // breakfast, lunch, dinner
            $table->float('total_calories');
            $table->float('total_protein');
            $table->float('total_carbs');
            $table->float('total_fat');
            $table->dateTime('consumed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_logs');
    }
};
