<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

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
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar notifikasi berhasil diambil",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="title", type="string", example="Pengingat Puasa"),
     *                 @OA\Property(property="message", type="string", example="Jangan lupa sahur pukul 04:30"),
     *                 @OA\Property(property="type", type="string", example="reminder"),
     *                 @OA\Property(property="is_read", type="boolean", example=false),
     *                 @OA\Property(property="sent_at", type="string", format="datetime", example="2025-03-21 08:00:00")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())->orderBy('sent_at', 'desc')->get();
        return response()->json($notifications);
    }

    /**
     * @OA\Post(
     *     path="/api/notifications",
     *     summary="Membuat notifikasi baru",
     *     tags={"Notifications"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "message", "type"},
     *             @OA\Property(property="title", type="string", example="Pengingat Puasa"),
     *             @OA\Property(property="message", type="string", example="Jangan lupa sahur pukul 04:30"),
     *             @OA\Property(property="type", type="string", example="reminder")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Notifikasi berhasil dibuat"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string', // alert, reminder, etc.
        ]);

        $notification = Notification::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'is_read' => false,
            'sent_at' => now(),
        ]);

        return response()->json($notification, 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/notifications/{id}",
     *     summary="Menghapus notifikasi berdasarkan ID",
     *     tags={"Notifications"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Notifikasi berhasil dihapus"),
     *     @OA\Response(response=404, description="Notifikasi tidak ditemukan")
     * )
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->delete();
        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
