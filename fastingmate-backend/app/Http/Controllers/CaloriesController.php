<?php

namespace App\Http\Controllers;

use App\Models\CalorieLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Calories",
 *     description="API untuk mengelola catatan kalori pengguna"
 * )
 */
class CaloriesController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/calories",
     *     summary="Menampilkan daftar catatan kalori pengguna",
     *     tags={"Calories"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar catatan kalori berhasil diambil",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="type", type="string", enum={"food", "activity"}, example="food"),
     *                 @OA\Property(property="description", type="string", example="Ayam bakar"),
     *                 @OA\Property(property="calories", type="integer", example=250),
     *                 @OA\Property(property="user_id", type="integer", example=5)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $user = Auth::user();
        $calories = $user->calorieLogs;

        return response()->json($calories);
    }

    /**
     * @OA\Post(
     *     path="/api/calories",
     *     summary="Menambahkan catatan kalori baru",
     *     tags={"Calories"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"type", "description", "calories"},
     *             @OA\Property(property="type", type="string", enum={"food", "activity"}, example="food"),
     *             @OA\Property(property="description", type="string", example="Ayam bakar"),
     *             @OA\Property(property="calories", type="integer", example=250)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Catatan kalori berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=5),
     *             @OA\Property(property="type", type="string", example="food"),
     *             @OA\Property(property="description", type="string", example="Ayam bakar"),
     *             @OA\Property(property="calories", type="integer", example=250)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:food,activity',
            'description' => 'required|string',
            'calories' => 'required|integer'
        ]);

        $log = CalorieLog::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'description' => $request->description,
            'calories' => $request->calories
        ]);

        return response()->json($log, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/calories/{id}",
     *     summary="Memperbarui catatan kalori berdasarkan ID",
     *     tags={"Calories"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="type", type="string", enum={"food", "activity"}, example="food"),
     *             @OA\Property(property="description", type="string", example="Ayam bakar"),
     *             @OA\Property(property="calories", type="integer", example=250)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catatan kalori berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=5),
     *             @OA\Property(property="type", type="string", example="food"),
     *             @OA\Property(property="description", type="string", example="Ayam bakar"),
     *             @OA\Property(property="calories", type="integer", example=250)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catatan kalori tidak ditemukan"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $log = CalorieLog::find($id);

        if (!$log) {
            return response()->json(['message' => 'Calorie log not found'], 404);
        }

        $request->validate([
            'type' => 'in:food,activity',
            'description' => 'string',
            'calories' => 'integer'
        ]);

        $log->update($request->only(['type', 'description', 'calories']));

        return response()->json($log);
    }

    /**
     * @OA\Delete(
     *     path="/api/calories/{id}",
     *     summary="Menghapus catatan kalori berdasarkan ID",
     *     tags={"Calories"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catatan kalori berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Calorie log deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catatan kalori tidak ditemukan"
     *     )
     * )
     */
    public function destroy($id)
    {
        $log = CalorieLog::find($id);

        if (!$log) {
            return response()->json(['message' => 'Calorie log not found'], 404);
        }

        $log->delete();

        return response()->json(['message' => 'Calorie log deleted successfully']);
    }
}
