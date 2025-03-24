<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FastingSchedule;

/**
 * @OA\Tag(
 *     name="Streak",
 *     description="API untuk menghitung streak puasa pengguna"
 * )
 */
class StreakController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/streak",
     *     summary="Menghitung streak puasa pengguna",
     *     tags={"Streak"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Streak puasa pengguna berhasil dihitung",
     *         @OA\JsonContent(
     *             @OA\Property(property="current_streak", type="integer", example=5)
     *         )
     *     )
     * )
     */
    public function index()
    {
        $userId = Auth::id();
        $streak = 0;
        $previousDate = null;

        $fastingLogs = FastingSchedule::where('user_id', $userId)
            ->where('is_completed', true)
            ->orderBy('schedule_date', 'desc')
            ->get();

        foreach ($fastingLogs as $log) {
            if ($previousDate === null) {
                $streak = 1;
            } elseif (strtotime($previousDate) - strtotime($log->schedule_date) == 86400) {
                $streak++;
            } else {
                break;
            }
            $previousDate = $log->schedule_date;
        }

        return response()->json(['current_streak' => $streak]);
    }
}
