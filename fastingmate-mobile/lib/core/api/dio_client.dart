import 'package:dio/dio.dart';
import '../utils/shared_prefs.dart';

class DioClient {
  static const String baseUrl = "http://10.0.2.2:8000/api"; // Sesuaikan dengan API Laravel
  late Dio dio;

  DioClient() {
    dio = Dio(BaseOptions(
      baseUrl: baseUrl,
      connectTimeout: const Duration(seconds: 10),
      receiveTimeout: const Duration(seconds: 10),
      headers: {'Content-Type': 'application/json'},
    ));

    // Tambahkan interceptor untuk menyertakan token di setiap request
    dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        final token = await SharedPrefs.getToken();
        if (token != null) {
          options.headers["Authorization"] = "Bearer $token";
        }
        return handler.next(options);
      },
      onError: (DioException e, handler) {
        if (e.response?.statusCode == 401) {
          // Handle Unauthorized
          print("Unauthorized, please login again.");
        }
        return handler.next(e);
      },
    ));
  }
}
