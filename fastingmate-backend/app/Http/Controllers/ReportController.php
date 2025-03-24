<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FoodLog;
use App\Models\ActivityLog;
use App\Models\WeightLog;

class ReportController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/report",
     *      operationId="getUserHealthReport",
     *      tags={"Report"},
     *      summary="Menampilkan laporan kesehatan pengguna",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Laporan kesehatan berhasil diambil",
     *          @OA\JsonContent(
     *              @OA\Property(property="calories_consumed", type="number", format="float", example=2100),
     *              @OA\Property(property="calories_burned", type="number", format="float", example=500),
     *              @OA\Property(
     *                  property="weight_logs",
     *                  type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="user_id", type="integer", example=123),
     *                      @OA\Property(property="weight", type="number", format="float", example=70.5),
     *                      @OA\Property(property="recorded_date", type="string", format="date", example="2025-03-24"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T14:00:00Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T14:00:00Z"),
     *                  )
     *              ),
     *          )
     *      )
     * )
     */
    public function index()
    {
        $userId = Auth::id();

        // Mengambil total konsumsi kalori dari food_logs
        $totalCaloriesConsumed = FoodLog::where('user_id', $userId)->sum('total_calories');

        // Mengambil total kalori yang terbakar dari activity_logs
        $totalCaloriesBurned = ActivityLog::where('user_id', $userId)->sum('calories_burned');

        // Mengambil data perubahan berat badan dari weight_logs
        $weightLogs = WeightLog::where('user_id', $userId)->orderBy('recorded_date', 'desc')->get();

        return response()->json([
            'calories_consumed' => $totalCaloriesConsumed,
            'calories_burned' => $totalCaloriesBurned,
            'weight_logs' => $weightLogs,
        ]);
    }
}
