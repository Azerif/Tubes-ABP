<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leaderboard;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="Leaderboard",
 *     description="API untuk menampilkan papan peringkat pengguna"
 * )
 */
class LeaderboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/leaderboard",
     *     summary="Menampilkan papan peringkat pengguna berdasarkan poin dan jumlah puasa",
     *     tags={"Leaderboard"},
     *     @OA\Response(
     *         response=200,
     *         description="Data leaderboard berhasil diambil",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="rank", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="total_points", type="integer", example=1500),
     *                 @OA\Property(property="total_fasting_days", type="integer", example=30),
     *                 @OA\Property(property="total_weight_loss", type="float", example=2.5)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $leaderboard = Leaderboard::with('user')
            ->orderByDesc('total_points')
            ->orderByDesc('total_fasting_days')
            ->get()
            ->map(function ($item, $index) {
                return [
                    'rank' => $index + 1,
                    'user_id' => $item->user_id,
                    'name' => $item->user->name,
                    'total_points' => $item->total_points,
                    'total_fasting_days' => $item->total_fasting_days,
                    'total_weight_loss' => $item->total_weight_loss,
                ];
            });

        return response()->json($leaderboard);
    }
}
