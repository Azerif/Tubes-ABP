<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="User",
 *     description="API untuk mengelola pengguna"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/user/{id}",
     *      operationId="getUserProfile",
     *      tags={"User"},
     *      summary="Menampilkan profil pengguna berdasarkan ID",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID pengguna yang ingin ditampilkan",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Berhasil mengambil data user",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="john@example.com"),
     *              @OA\Property(property="height", type="number", format="float", example=175),
     *              @OA\Property(property="weight", type="number", format="float", example=70),
     *              @OA\Property(property="activity_level", type="string", example="moderate"),
     *          )
     *      ),
     *      @OA\Response(response=404, description="User not found")
     * )
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    /**
     * @OA\Put(
     *      path="/api/user/{id}",
     *      operationId="updateUserProfile",
     *      tags={"User"},
     *      summary="Mengupdate profil pengguna berdasarkan ID",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID pengguna yang ingin diperbarui",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "height", "weight", "activity_level"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="height", type="number", format="float", example=175),
     *              @OA\Property(property="weight", type="number", format="float", example=70),
     *              @OA\Property(property="activity_level", type="string", enum={"low", "moderate", "high"}, example="moderate"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Profil berhasil diperbarui",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Profile updated successfully"),
     *              @OA\Property(property="user", type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="John Doe"),
     *                  @OA\Property(property="height", type="number", format="float", example=175),
     *                  @OA\Property(property="weight", type="number", format="float", example=70),
     *                  @OA\Property(property="activity_level", type="string", example="moderate"),
     *              ),
     *          )
     *      ),
     *      @OA\Response(response=404, description="User not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'name' => 'string|max:255',
            'height' => 'numeric|min:50|max:250',
            'weight' => 'numeric|min:10|max:300',
            'activity_level' => 'string|in:low,moderate,high',
        ]);

        $user->update($request->only(['name', 'height', 'weight', 'activity_level']));

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }

    /**
     * @OA\Get(
     *      path="/api/user/{id}/weight-category",
     *      operationId="getUserWeightCategory",
     *      tags={"User"},
     *      summary="Menghitung kategori berat badan berdasarkan tinggi dan berat pengguna berdasarkan ID",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID pengguna yang ingin dihitung kategori beratnya",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Kategori berat badan berhasil dihitung",
     *          @OA\JsonContent(
     *              @OA\Property(property="weight_category", type="string", example="normal"),
     *              @OA\Property(property="bmi", type="number", format="float", example=22.86),
     *          )
     *      ),
     *      @OA\Response(response=404, description="User not found")
     * )
     */
    public function calculateCategory($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!$user->height || !$user->weight) {
            return response()->json(['message' => 'Height and weight must be set to calculate BMI'], 400);
        }

        $bmi = $user->weight / (($user->height / 100) ** 2);
        $weight_category = $bmi < 18.5 ? 'underweight' : ($bmi < 25 ? 'normal' : 'overweight');

        return response()->json(['weight_category' => $weight_category, 'bmi' => $bmi]);
    }
}
