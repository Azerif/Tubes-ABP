<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="UserProfile",
 *     description="API untuk mengelola profil pengguna"
 * )
 */
class UserProfileController extends Controller
{
    /**
     * @OA\Put(
     *     path="/api/user/profile/{id}",
     *     summary="Memperbarui profil pengguna",
     *     tags={"UserProfile"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID profil pengguna",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"height", "weight", "activity_level"},
     *             @OA\Property(property="height", type="integer", example=170),
     *             @OA\Property(property="weight", type="integer", example=65),
     *             @OA\Property(property="activity_level", type="string", example="Active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profil berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="height", type="integer", example=170),
     *             @OA\Property(property="weight", type="integer", example=65),
     *             @OA\Property(property="activity_level", type="string", example="Active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Tidak diizinkan memperbarui profil pengguna lain"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Profil tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $request->validate([
            'height' => 'required|integer',
            'weight' => 'required|integer',
            'activity_level' => 'required|string'
        ]);

        // Find the profile based on the ID
        $profile = UserProfile::findOrFail($id);

        // Ensure the authenticated user can only update their own profile
        if ($profile->user_id !== Auth::id()) {
            return response()->json(['message' => 'Tidak diizinkan memperbarui profil pengguna lain'], 403);
        }

        // Update the profile
        $profile->update([
            'height' => $request->height,
            'weight' => $request->weight,
            'activity_level' => $request->activity_level
        ]);

        return response()->json($profile, 200); // Return the updated profile
    }

    /**
     * @OA\Get(
     *     path="/api/user/profile/bmi",
     *     summary="Menghitung kategori BMI berdasarkan profil pengguna",
     *     tags={"UserProfile"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="BMI berhasil dihitung",
     *         @OA\JsonContent(
     *             @OA\Property(property="bmi", type="number", format="float", example=22.5),
     *             @OA\Property(property="category", type="string", example="Normal")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Profil tidak ditemukan"
     *     )
     * )
     */
    public function calculateCategory()
    {
        $profile = Auth::user()->profile;

        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $bmi = $profile->weight / pow($profile->height / 100, 2);
        $category = $bmi < 18.5 ? 'Underweight' :
                    ($bmi < 24.9 ? 'Normal' :
                    ($bmi < 29.9 ? 'Overweight' : 'Obese'));

        return response()->json(['bmi' => $bmi, 'category' => $category]);
    }
}
