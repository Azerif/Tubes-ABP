import 'package:dio/dio.dart';
import 'dio_client.dart';
import '../utils/shared_prefs.dart';

class AuthApi {
  final DioClient _dioClient = DioClient();

  // **✅ Register User**
  Future<bool> register(String name, String email, String password) async {
    try {
      Response response = await _dioClient.dio.post(
        '/register',
        data: {
          "name": name,
          "email": email,
          "password": password,
        },
        options: Options(headers: {
          "Accept": "application/json", // ✅ Tambahkan ini agar Laravel tidak mengembalikan redirect (302)
        }),
      );

      // **Periksa apakah API mengembalikan token**
      if (response.statusCode == 201 && response.data is Map && response.data.containsKey('token')) {
        await SharedPrefs.saveToken(response.data['token']);
        return true; // **Berhasil daftar**
      }

      print("Register failed: ${response.data}");
      return false; // **Gagal daftar**
    } on DioException catch (e) {
      print("❌ Register error: ${e.response?.statusCode} - ${e.response?.data}");
      return false;
    }
  }

   Future<Map<String, dynamic>?> login(String email, String password) async {
    try {
      Response response = await _dioClient.dio.post('/login', data: {
        "email": email,
        "password": password,
      });

      if (response.statusCode == 200 && response.data.containsKey('token')) {
        await SharedPrefs.saveToken(response.data['token']); // Simpan token
        return response.data; // ✅ Kembalikan data user
      }

      return null; // Jika tidak ada token, return null
    } catch (e) {
      print("Login error: $e");
      return null;
    }
  }

  // **✅ Logout User**
  Future<bool> logout() async {
    try {
      await _dioClient.dio.post(
        '/logout',
        options: Options(headers: {
          "Accept": "application/json",
        }),
      );

      await SharedPrefs.clearToken();
      return true; // **Berhasil logout**
    } on DioException catch (e) {
      print("❌ Logout error: ${e.response?.statusCode} - ${e.response?.data}");
      return false;
    }
  }
}
