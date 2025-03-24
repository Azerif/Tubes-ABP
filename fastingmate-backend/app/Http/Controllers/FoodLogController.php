<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FoodLog;

/**
 * @OA\Tag(
 *     name="Food Logs",
 *     description="API untuk mengelola catatan konsumsi makanan pengguna"
 * )
 */
class FoodLogController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/food-logs",
     *     summary="Menampilkan daftar konsumsi makanan pengguna",
     *     tags={"Food Logs"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar konsumsi makanan berhasil diambil",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="food_id", type="integer", example=3),
     *                 @OA\Property(property="quantity", type="float", example=1.5),
     *                 @OA\Property(property="meal_type", type="string", example="lunch"),
     *                 @OA\Property(property="total_calories", type="float", example=500),
     *                 @OA\Property(property="total_protein", type="float", example=30),
     *                 @OA\Property(property="total_carbs", type="float", example=60),
     *                 @OA\Property(property="total_fat", type="float", example=10),
     *                 @OA\Property(property="consumed_at", type="string", format="datetime", example="2025-03-21 12:00:00"),
     *                 @OA\Property(property="created_at", type="string", format="datetime", example="2025-03-21 12:05:00")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $foodLogs = FoodLog::where('user_id', Auth::id())->orderBy('consumed_at', 'desc')->get();
        return response()->json($foodLogs);
    }

    /**
     * @OA\Post(
     *     path="/api/food-logs",
     *     summary="Menambahkan catatan konsumsi makanan baru",
     *     tags={"Food Logs"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"food_id", "quantity", "meal_type", "total_calories", "total_protein", "total_carbs", "total_fat", "consumed_at"},
     *             @OA\Property(property="food_id", type="integer", example=3),
     *             @OA\Property(property="quantity", type="float", example=1.5),
     *             @OA\Property(property="meal_type", type="string", example="lunch"),
     *             @OA\Property(property="total_calories", type="float", example=500),
     *             @OA\Property(property="total_protein", type="float", example=30),
     *             @OA\Property(property="total_carbs", type="float", example=60),
     *             @OA\Property(property="total_fat", type="float", example=10),
     *             @OA\Property(property="consumed_at", type="string", format="datetime", example="2025-03-21 12:00:00")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Catatan konsumsi makanan berhasil dibuat"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:food,id',
            'quantity' => 'required|numeric|min:0.1',
            'meal_type' => 'required|string',
            'total_calories' => 'required|numeric|min:0',
            'total_protein' => 'required|numeric|min:0',
            'total_carbs' => 'required|numeric|min:0',
            'total_fat' => 'required|numeric|min:0',
            'consumed_at' => 'required|date',
        ]);

        $foodLog = FoodLog::create([
            'user_id' => Auth::id(),
            'food_id' => $request->food_id,
            'quantity' => $request->quantity,
            'meal_type' => $request->meal_type,
            'total_calories' => $request->total_calories,
            'total_protein' => $request->total_protein,
            'total_carbs' => $request->total_carbs,
            'total_fat' => $request->total_fat,
            'consumed_at' => $request->consumed_at,
        ]);

        return response()->json($foodLog, 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/food-logs/{id}",
     *     summary="Menghapus catatan konsumsi makanan berdasarkan ID",
     *     tags={"Food Logs"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Catatan konsumsi makanan berhasil dihapus"),
     *     @OA\Response(response=404, description="Catatan tidak ditemukan")
     * )
     */
    public function destroy($id)
    {
        $foodLog = FoodLog::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$foodLog) {
            return response()->json(['message' => 'Food log not found'], 404);
        }

        $foodLog->delete();
        return response()->json(['message' => 'Food log deleted successfully']);
    }
}
