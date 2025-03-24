<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

/**
 * @OA\Tag(
 *     name="Activity Logs",
 *     description="API untuk mengelola catatan aktivitas pengguna"
 * )
 */
class ActivityLogController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/activity-logs",
     *     summary="Menampilkan daftar aktivitas pengguna",
     *     tags={"Activity Logs"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar aktivitas berhasil diambil",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="activity_id", type="integer", example=2),
     *                 @OA\Property(property="duration_minutes", type="integer", example=30),
     *                 @OA\Property(property="calories_burned", type="float", example=150),
     *                 @OA\Property(property="performed_at", type="string", format="datetime", example="2025-03-21 07:00:00"),
     *                 @OA\Property(property="created_at", type="string", format="datetime", example="2025-03-21 07:10:00")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $activityLogs = ActivityLog::where('user_id', Auth::id())->orderBy('performed_at', 'desc')->get();
        return response()->json($activityLogs);
    }

    /**
     * @OA\Post(
     *     path="/api/activity-logs",
     *     summary="Menambahkan catatan aktivitas baru",
     *     tags={"Activity Logs"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"activity_id", "duration_minutes", "calories_burned", "performed_at"},
     *             @OA\Property(property="activity_id", type="integer", example=2),
     *             @OA\Property(property="duration_minutes", type="integer", example=30),
     *             @OA\Property(property="calories_burned", type="float", example=150),
     *             @OA\Property(property="performed_at", type="string", format="datetime", example="2025-03-21 07:00:00")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Catatan aktivitas berhasil dibuat"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'duration_minutes' => 'required|integer|min:1',
            'calories_burned' => 'required|numeric|min:0',
            'performed_at' => 'required|date',
        ]);

        $activityLog = ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_id' => $request->activity_id,
            'duration_minutes' => $request->duration_minutes,
            'calories_burned' => $request->calories_burned,
            'performed_at' => $request->performed_at,
        ]);

        return response()->json($activityLog, 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/activity-logs/{id}",
     *     summary="Menghapus catatan aktivitas berdasarkan ID",
     *     tags={"Activity Logs"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Catatan aktivitas berhasil dihapus"),
     *     @OA\Response(response=404, description="Catatan aktivitas tidak ditemukan")
     * )
     */
    public function destroy($id)
    {
        $activityLog = ActivityLog::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$activityLog) {
            return response()->json(['message' => 'Activity log not found'], 404);
        }

        $activityLog->delete();
        return response()->json(['message' => 'Activity log deleted successfully']);
    }
}
