<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FastingSchedule;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="FastingSchedule",
 *     description="API untuk mengelola jadwal puasa pengguna"
 * )
 */
class FastingScheduleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/fasting-schedules",
     *     summary="Menampilkan daftar jadwal puasa pengguna",
     *     tags={"FastingSchedule"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar jadwal puasa berhasil diambil",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-03-21"),
     *                 @OA\Property(property="start_time", type="string", format="time", example="04:30"),
     *                 @OA\Property(property="end_time", type="string", format="time", example="18:30"),
     *                 @OA\Property(property="completed", type="boolean", example=false)
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $fastingSchedules = Auth::user()->fastingSchedules;
        return response()->json($fastingSchedules);
    }

    /**
     * @OA\Post(
     *     path="/api/fasting-schedules",
     *     summary="Menambahkan jadwal puasa baru",
     *     tags={"FastingSchedule"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"date", "start_time", "end_time"},
     *             @OA\Property(property="date", type="string", format="date", example="2025-03-21"),
     *             @OA\Property(property="start_time", type="string", format="time", example="04:30"),
     *             @OA\Property(property="end_time", type="string", format="time", example="18:30")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Jadwal puasa berhasil dibuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=5),
     *             @OA\Property(property="date", type="string", format="date", example="2025-03-21"),
     *             @OA\Property(property="start_time", type="string", format="time", example="04:30"),
     *             @OA\Property(property="end_time", type="string", format="time", example="18:30"),
     *             @OA\Property(property="completed", type="boolean", example=false)
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
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        $schedule = FastingSchedule::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time
        ]);

        return response()->json($schedule, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/fasting-schedules/{id}/complete",
     *     summary="Menandai jadwal puasa sebagai selesai",
     *     tags={"FastingSchedule"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID jadwal puasa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Jadwal puasa berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Fasting marked as completed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Jadwal puasa tidak ditemukan"
     *     )
     * )
     */
    public function updateStatus($id)
    {
        $schedule = FastingSchedule::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        $schedule->update(['completed' => true]);
        return response()->json(['message' => 'Fasting marked as completed']);
    }
}
