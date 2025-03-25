import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  final Dio _dio = Dio(BaseOptions(
    baseUrl: "http://10.0.2.2:8000/api",
    connectTimeout: const Duration(seconds: 10),
    receiveTimeout: const Duration(seconds: 10),
  ));

  // **🔥 Catatan Kalori**
  Future<List<dynamic>> getCalories() async {
    try {
      final response = await _dio.get('/calories');
      return response.data; // Mengembalikan list JSON
    } catch (e) {
      throw Exception("Gagal mengambil data kalori: $e");
    }
  }

  Future<void> addCalorieLog(String type, String description, int calories) async {
    try {
      await _dio.post('/calories', data: {
        "type": type, 
        "description": description, 
        "calories": calories
      });
    } catch (e) {
      throw Exception("Gagal menambahkan catatan kalori: $e");
    }
  }

  // **📅 Jadwal Puasa**
  Future<List<dynamic>> getFastingSchedules() async {
    try {
      final response = await _dio.get('/fasting-schedules');
      return response.data;
    } catch (e) {
      throw Exception("Gagal mengambil jadwal puasa: $e");
    }
  }

  Future<void> addFastingSchedule(String date, String startTime, String endTime) async {
    try {
      await _dio.post('/fasting-schedules', data: {
        "date": date,
        "start_time": startTime,
        "end_time": endTime
      });
    } catch (e) {
      throw Exception("Gagal menambahkan jadwal puasa: $e");
    }
  }

  Future<void> completeFasting(int id) async {
    try {
      await _dio.put('/fasting-schedules/$id/complete');
    } catch (e) {
      throw Exception("Gagal menandai puasa selesai: $e");
    }
  }

  // **🔔 Notifikasi**
  Future<List<dynamic>> getNotifications() async {
    try {
      final response = await _dio.get('/notifications');
      return response.data;
    } catch (e) {
      throw Exception("Gagal mengambil notifikasi: $e");
    }
  }

  Future<void> createNotification(String message) async {
    try {
      await _dio.post('/notifications', data: {"message": message});
    } catch (e) {
      throw Exception("Gagal membuat notifikasi: $e");
    }
  }

  Future<void> deleteNotification(int id) async {
    try {
      await _dio.delete('/notifications/$id');
    } catch (e) {
      throw Exception("Gagal menghapus notifikasi: $e");
    }
  }

  // **👤 Profil Pengguna**
  Future<void> updateUserProfile(int height, int weight, String activityLevel) async {
    try {
      await _dio.post('/user/profile', data: {
        "height": height,
        "weight": weight,
        "activity_level": activityLevel
      });
    } catch (e) {
      throw Exception("Gagal menyimpan profil: $e");
    }
  }

  Future<Map<String, dynamic>> getUserBMI() async {
    try {
      final response = await _dio.get('/user/profile/bmi');
      return response.data;
    } catch (e) {
      throw Exception("Gagal mengambil data BMI: $e");
    }
  }
}
