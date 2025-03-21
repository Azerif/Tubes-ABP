<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="Leaderboard",
 *     description="API untuk menampilkan papan peringkat berdasarkan jumlah puasa yang diselesaikan"
 * )
 */
class LeaderboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/leaderboard",
     *     summary="Menampilkan papan peringkat pengguna berdasarkan jumlah puasa yang telah diselesaikan",
     *     tags={"Leaderboard"},
     *     @OA\Response(
     *         response=200,
     *         description="Data leaderboard berhasil diambil",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="fasting_schedules_count", type="integer", example=10)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $leaderboard = User::withCount(['fastingSchedules' => function ($query) {
            $query->where('completed', true);
        }])->orderByDesc('fasting_schedules_count')->get();

        return response()->json($leaderboard);
    }
}
