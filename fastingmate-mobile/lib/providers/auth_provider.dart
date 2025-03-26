import 'package:flutter/material.dart';
import '../core/api/auth_api.dart';

class AuthProvider extends ChangeNotifier {
  final AuthApi _authApi = AuthApi();
  bool _isAuthenticated = false;
  int? _userId; // Simpan userId setelah login
  bool get isAuthenticated => _isAuthenticated;
  int? get userId => _userId; // Getter untuk userId

  Future<bool> login(String email, String password) async {
    final userData = await _authApi.login(email, password);
    if (userData != null) {
      _isAuthenticated = true;
      _userId = userData['user']['id']; // ✅ Simpan userId
      notifyListeners();
      return true;
    }
    return false;
  }

  Future<bool> register(String name, String email, String password) async {
    final userData = await _authApi.register(name, email, password);
    return userData != null;
  }

  Future<void> logout() async {
    if (await _authApi.logout()) {
      _isAuthenticated = false;
      _userId = null;
      notifyListeners();
    }
  }
}
