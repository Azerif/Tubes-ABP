<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel API Documentation",
 *      description="Dokumentasi API untuk autentikasi pengguna",
 *      @OA\Contact(
 *          email="support@example.com"
 *      )
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints terkait autentikasi pengguna"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register User",
     *     description="Mendaftarkan pengguna baru",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","height","weight","activity_level"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="height", type="number", format="float", example=175.5),
     *             @OA\Property(property="weight", type="number", format="float", example=70.2),
     *             @OA\Property(property="activity_level", type="string", example="moderate")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User registered successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'activity_level' => 'required|string'
        ]);

        // Menghitung kebutuhan kalori berdasarkan berat, tinggi, dan aktivitas (Contoh kasar)
        $daily_calorie_needs = ($request->weight * 10) + ($request->height * 6.25) - (5 * 25); 
        if ($request->activity_level === "high") {
            $daily_calorie_needs *= 1.55; 
        } elseif ($request->activity_level === "moderate") {
            $daily_calorie_needs *= 1.375;
        } else {
            $daily_calorie_needs *= 1.2;
        }

        // Menentukan kategori berat badan (Contoh sederhana)
        $bmi = $request->weight / (($request->height / 100) ** 2);
        $weight_category = $bmi < 18.5 ? 'underweight' : ($bmi < 25 ? 'normal' : 'overweight');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'height' => $request->height,
            'weight' => $request->weight,
            'activity_level' => $request->activity_level,
            'daily_calorie_needs' => $daily_calorie_needs,
            'weight_category' => $weight_category,
            'total_points' => 0,
            'current_streak' => 0
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login User",
     *     description="Melakukan autentikasi pengguna",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login successful"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $token = $request->user()->createToken('token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout User",
     *     description="Menghapus token autentikasi pengguna",
     *     tags={"Authentication"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(response=200, description="Logged out successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
