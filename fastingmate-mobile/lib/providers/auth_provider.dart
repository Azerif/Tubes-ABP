import 'package:flutter/material.dart';
import '../core/api/auth_api.dart';

class AuthProvider extends ChangeNotifier {
  final AuthApi _authApi = AuthApi();
  bool _isAuthenticated = false;
  bool get isAuthenticated => _isAuthenticated;

  Future<bool> login(String email, String password) async {
    bool success = await _authApi.login(email, password);
    if (success) {
      _isAuthenticated = true;
      notifyListeners();
    }
    return success;
  }

  Future<bool> register(String name, String email, String password) async {
    final userData = await _authApi.register(name, email, password);
    return userData != null;
  }

  Future<void> logout() async {
    if (await _authApi.logout()) {
      _isAuthenticated = false;
      notifyListeners();
    }
  }
}
