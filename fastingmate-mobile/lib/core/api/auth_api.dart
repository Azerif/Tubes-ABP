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

  // **✅ Login User**
  Future<bool> login(String email, String password) async {
    try {
      Response response = await _dioClient.dio.post(
        '/login',
        data: {
          "email": email,
          "password": password,
        },
        options: Options(headers: {
          "Accept": "application/json", // ✅ Mencegah redirect ke HTML jika terjadi kesalahan
        }),
      );

      // **Periksa apakah API mengembalikan token**
      if (response.statusCode == 200 && response.data is Map && response.data.containsKey('token')) {
        await SharedPrefs.saveToken(response.data['token']);
        return true; // **Berhasil login**
      }

      print("Login failed: ${response.data}");
      return false; // **Gagal login**
    } on DioException catch (e) {
      print("❌ Login error: ${e.response?.statusCode} - ${e.response?.data}");
      return false;
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
