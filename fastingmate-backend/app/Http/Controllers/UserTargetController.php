<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserTarget;

/**
 * @OA\Tag(
 *     name="User Targets",
 *     description="API untuk mengelola target pengguna terkait berat badan dan nutrisi"
 * )
 */
class UserTargetController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user-targets",
     *     summary="Menampilkan semua target pengguna",
     *     tags={"User Targets"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar target pengguna berhasil diambil",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="target_weight", type="float", example=65),
     *                 @OA\Property(property="target_daily_calories", type="float", example=2000),
     *                 @OA\Property(property="target_protein", type="float", example=100),
     *                 @OA\Property(property="target_carbs", type="float", example=250),
     *                 @OA\Property(property="target_fat", type="float", example=50),
     *                 @OA\Property(property="target_date", type="string", format="date", example="2025-06-30"),
     *                 @OA\Property(property="is_achieved", type="boolean", example=false)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $targets = UserTarget::where('user_id', Auth::id())->orderBy('target_date', 'asc')->get();
        return response()->json($targets);
    }

    /**
     * @OA\Post(
     *     path="/api/user-targets",
     *     summary="Menambahkan target baru untuk pengguna",
     *     tags={"User Targets"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"target_weight", "target_daily_calories", "target_protein", "target_carbs", "target_fat", "target_date"},
     *             @OA\Property(property="target_weight", type="float", example=65),
     *             @OA\Property(property="target_daily_calories", type="float", example=2000),
     *             @OA\Property(property="target_protein", type="float", example=100),
     *             @OA\Property(property="target_carbs", type="float", example=250),
     *             @OA\Property(property="target_fat", type="float", example=50),
     *             @OA\Property(property="target_date", type="string", format="date", example="2025-06-30")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Target berhasil dibuat"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'target_weight' => 'required|numeric|min:30|max:300',
            'target_daily_calories' => 'required|numeric|min:1000|max:5000',
            'target_protein' => 'required|numeric|min:10|max:300',
            'target_carbs' => 'required|numeric|min:10|max:500',
            'target_fat' => 'required|numeric|min:5|max:200',
            'target_date' => 'required|date',
        ]);

        $target = UserTarget::create([
            'user_id' => Auth::id(),
            'target_weight' => $request->target_weight,
            'target_daily_calories' => $request->target_daily_calories,
            'target_protein' => $request->target_protein,
            'target_carbs' => $request->target_carbs,
            'target_fat' => $request->target_fat,
            'target_date' => $request->target_date,
            'is_achieved' => false,
        ]);

        return response()->json($target, 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/user-targets/{id}",
     *     summary="Menghapus target berdasarkan ID",
     *     tags={"User Targets"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Target berhasil dihapus"),
     *     @OA\Response(response=404, description="Target tidak ditemukan")
     * )
     */
    public function destroy($id)
    {
        $target = UserTarget::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$target) {
            return response()->json(['message' => 'Target not found'], 404);
        }

        $target->delete();
        return response()->json(['message' => 'Target deleted successfully']);
    }
}
