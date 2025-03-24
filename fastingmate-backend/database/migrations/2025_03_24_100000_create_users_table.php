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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->float('height')->nullable();
            $table->float('weight')->nullable();
            $table->string('activity_level')->nullable(); // low, moderate, high
            $table->float('daily_calorie_needs')->nullable();
            $table->string('weight_category')->nullable(); // underweight, normal, overweight
            $table->integer('total_points')->default(0);
            $table->integer('current_streak')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
