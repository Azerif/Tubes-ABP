<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FastingSchedule;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Streaks",
 *     description="API untuk mendapatkan streak puasa pengguna"
 * )
 */
class StreakController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/streaks",
     *     summary="Menampilkan streak puasa pengguna",
     *     tags={"Streaks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Jumlah streak puasa berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="streak", type="integer", example=5)
     *         )
     *     )
     * )
     */
    public function index()
    {
        $streak = Auth::MyUserModel()->fastingSchedules()->where('completed', true)->orderByDesc('date')->pluck('date');

        $currentStreak = 0;
        $yesterday = now()->subDay();

        foreach ($streak as $date) {
            if ($date == $yesterday->toDateString()) {
                $currentStreak++;
                $yesterday = $yesterday->subDay();
            } else {
                break;
            }
        }

        return response()->json(['streak' => $currentStreak]);
    }
}
