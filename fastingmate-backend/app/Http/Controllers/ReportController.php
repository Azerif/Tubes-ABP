<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Reports",
 *     description="API untuk mendapatkan laporan pengguna"
 * )
 */
class ReportController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reports",
     *     summary="Menampilkan laporan pengguna",
     *     tags={"Reports"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Laporan berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="weight", type="integer", example=70),
     *             @OA\Property(property="target_weight", type="integer", example=65),
     *             @OA\Property(property="daily_calories", type="integer", example=1800)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Profil pengguna tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Profile not found")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $profile = Auth::user()->profile;
        if (!$profile) return response()->json(['message' => 'Profile not found'], 404);

        return response()->json([
            'weight' => $profile->weight,
            'target_weight' => $profile->target_weight,
            'daily_calories' => $profile->daily_calories
        ]);
    }
}
