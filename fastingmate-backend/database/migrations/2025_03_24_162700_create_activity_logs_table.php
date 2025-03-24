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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke pengguna
            $table->foreignId('activity_id')->constrained('activity')->onDelete('cascade'); // Relasi ke aktivitas
            $table->integer('duration_minutes'); // Durasi aktivitas dalam menit
            $table->float('calories_burned'); // Kalori yang terbakar
            $table->timestamp('performed_at'); // Waktu aktivitas dilakukan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
