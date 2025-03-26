import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../utils/shared_prefs.dart';

class ApiService {
  final Dio _dio = Dio(BaseOptions(
    baseUrl: "http://10.0.2.2:8000/api",
    connectTimeout: const Duration(seconds: 10),
    receiveTimeout: const Duration(seconds: 10),
  ));

  // 🔐 **Autentikasi Token**  
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString("token");
  }

  void _setAuthHeaders() async {
    final token = await getToken();
    if (token != null) {
      _dio.options.headers["Authorization"] = "Bearer $token";
    }
  }

  // 📌 **Mendapatkan Profil Pengguna berdasarkan ID**  
Future<Map<String, dynamic>> getUserById(int id) async {
  try {
    final response = await _dio.get(
      '/user/$id',
      options: Options(
        headers: {
          "Authorization": "Bearer ${await SharedPrefs.getToken()}",
          "Accept": "application/json",
        },
      ),
    );
    return response.data;
  } on DioException catch (e) {
    print("Error getUserById: ${e.response?.statusCode} - ${e.response?.data}");
    throw Exception("Gagal mengambil data pengguna: ${e.message}");
  }
}

Future<void> updateUserById(int id, Map<String, dynamic> data) async {
  try {
    print("🔹 Data yang dikirim ke server: $data"); // Debugging sebelum request

    final response = await _dio.put(
      '/user/$id',
      data: data,
      options: Options(headers: {
        'Authorization': 'Bearer ${await SharedPrefs.getToken()}', // Pastikan token dikirim
        'Content-Type': 'application/json',
      }),
    );

    print("✅ Response dari server: ${response.data}");
  } catch (e) {
    print("❌ Error updating profile: $e");
  }
}

Future<Map<String, dynamic>> getUserWeightCategory(int id) async {
  try {
    final response = await _dio.get(
      '/user/$id/weight-category',
      options: Options(
        headers: {
          "Authorization": "Bearer ${await SharedPrefs.getToken()}"
        },
      ),
    );

    if (response.statusCode == 200) {
      return response.data;  // ✅ API Berhasil
    } else {
      throw Exception("Error: ${response.statusCode}");
    }
  } catch (e) {
    print("❌ Error API getUserWeightCategory: $e");
    throw Exception("Gagal menghitung kategori berat badan: $e");
  }
}
}
