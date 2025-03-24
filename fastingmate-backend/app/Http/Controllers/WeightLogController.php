<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WeightLog;

/**
 * @OA\Tag(
 *     name="Weight Logs",
 *     description="API untuk mengelola catatan berat badan pengguna"
 * )
 */
class WeightLogController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/weight-logs",
     *     summary="Menampilkan daftar catatan berat badan pengguna",
     *     tags={"Weight Logs"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar catatan berat badan berhasil diambil",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="weight", type="float", example=70.5),
     *                 @OA\Property(property="recorded_date", type="string", format="date", example="2025-03-21"),
     *                 @OA\Property(property="created_at", type="string", format="datetime", example="2025-03-21 10:00:00")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $weightLogs = WeightLog::where('user_id', Auth::id())->orderBy('recorded_date', 'desc')->get();
        return response()->json($weightLogs);
    }

    /**
     * @OA\Post(
     *     path="/api/weight-logs",
     *     summary="Menambahkan catatan berat badan baru",
     *     tags={"Weight Logs"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"weight", "recorded_date"},
     *             @OA\Property(property="weight", type="float", example=70.5),
     *             @OA\Property(property="recorded_date", type="string", format="date", example="2025-03-21")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Catatan berat badan berhasil dibuat"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|min:20|max:300',
            'recorded_date' => 'required|date',
        ]);

        $weightLog = WeightLog::create([
            'user_id' => Auth::id(),
            'weight' => $request->weight,
            'recorded_date' => $request->recorded_date,
        ]);

        return response()->json($weightLog, 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/weight-logs/{id}",
     *     summary="Menghapus catatan berat badan berdasarkan ID",
     *     tags={"Weight Logs"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Catatan berat badan berhasil dihapus"),
     *     @OA\Response(response=404, description="Catatan tidak ditemukan")
     * )
     */
    public function destroy($id)
    {
        $weightLog = WeightLog::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$weightLog) {
            return response()->json(['message' => 'Weight log not found'], 404);
        }

        $weightLog->delete();
        return response()->json(['message' => 'Weight log deleted successfully']);
    }
}
