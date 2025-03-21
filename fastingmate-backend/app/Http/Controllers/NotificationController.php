<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Notifications",
 *     description="API untuk mengelola notifikasi pengguna"
 * )
 */
class NotificationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/notifications",
     *     summary="Menampilkan daftar notifikasi pengguna",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar notifikasi berhasil diambil",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="message", type="string", example="Your fasting session has ended"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-21T14:55:00Z")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Auth::user()->notifications);
    }

    /**
     * @OA\Post(
     *     path="/api/notifications",
     *     summary="Membuat notifikasi baru",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"message"},
     *             @OA\Property(property="message", type="string", example="Your fasting schedule is set!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Notifikasi berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=5),
     *             @OA\Property(property="message", type="string", example="Your fasting schedule is set!"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-03-21T14:55:00Z")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        $notification = Notification::create(['user_id' => Auth::id(), 'message' => $request->message]);
        return response()->json($notification, 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/notifications/{id}",
     *     summary="Menghapus notifikasi berdasarkan ID",
     *     tags={"Notifications"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID notifikasi yang akan dihapus",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notifikasi berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notifikasi tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $notif = Notification::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$notif) return response()->json(['message' => 'Not Found'], 404);
        $notif->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
