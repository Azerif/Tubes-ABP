import 'package:flutter/material.dart';
import '../../core/api/auth_api.dart';
import '../home/home_screen.dart';
import 'register_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  final AuthApi authApi = AuthApi();
  bool isLoading = false;
  bool _isObscured = true; // Variabel untuk mengontrol visibilitas password

  void login() async {
    setState(() => isLoading = true);
    bool success = await authApi.login(emailController.text, passwordController.text);

    if (success) {
      print("Login Success!");

      // Tampilkan popup login berhasil
      showDialog(
        context: context,
        builder: (context) {
          return AlertDialog(
            title: const Text("Login Berhasil"),
            content: const Text("Selamat datang di FastingMate!"),
            actions: [
              TextButton(
                onPressed: () {
                  Navigator.pop(context); // Tutup dialog
                  Navigator.pushReplacement(
                    context,
                    MaterialPageRoute(builder: (context) => const HomeScreen()),
                  ); // Navigasi ke HomeScreen
                },
                child: const Text("OK"),
              ),
            ],
          );
        },
      );
    } else {
      print("Login Failed");
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Login gagal, periksa kembali email dan password")),
      );
    }
    setState(() => isLoading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        children: [
          // Background Gradient
          Container(
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                colors: [Color(0xFF2196F3), Color(0xFF1976D2)], // Warna biru gradasi
                begin: Alignment.topCenter,
                end: Alignment.bottomCenter,
              ),
            ),
          ),

          // Login Form
          Center(
            child: SingleChildScrollView(
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 24.0),
                child: Card(
                  elevation: 8,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                  child: Padding(
                    padding: const EdgeInsets.all(24.0),
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      children: [
                        // App Name
                        const Text(
                          "FastingMate",
                          textAlign: TextAlign.center,
                          style: TextStyle(
                            fontSize: 28,
                            fontWeight: FontWeight.bold,
                            color: Color(0xFF1976D2),
                          ),
                        ),
                        const SizedBox(height: 20),

                        // Email Input
                        TextField(
                          controller: emailController,
                          decoration: InputDecoration(
                            labelText: "Email",
                            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                            prefixIcon: const Icon(Icons.email, color: Color(0xFF1976D2)),
                          ),
                        ),
                        const SizedBox(height: 12),

                        // Password Input
                        TextField(
                          controller: passwordController,
                          obscureText: _isObscured, // Gunakan variabel untuk mengontrol visibilitas password
                          decoration: InputDecoration(
                            labelText: "Password",
                            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                            prefixIcon: const Icon(Icons.lock, color: Color(0xFF1976D2)),
                            suffixIcon: IconButton(
                              icon: Icon(
                                _isObscured ? Icons.visibility_off : Icons.visibility,
                                color: Color(0xFF1976D2),
                              ),
                              onPressed: () {
                                setState(() {
                                  _isObscured = !_isObscured;
                                });
                              },
                            ),
                          ),
                        ),

                        const SizedBox(height: 20),

                        // Login Button
                        ElevatedButton(
                          onPressed: isLoading ? null : login,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: const Color(0xFF1976D2),
                            padding: const EdgeInsets.symmetric(vertical: 14),
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                          ),
                          child: isLoading
                              ? const CircularProgressIndicator(color: Colors.white)
                              : const Text("Login", style: TextStyle(fontSize: 16, color: Colors.white)),
                        ),

                        // Register Navigation
                        TextButton(
                          onPressed: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(builder: (context) => const RegisterScreen()),
                            );
                          },
                          child: const Text(
                            "Belum punya akun? Daftar di sini",
                            style: TextStyle(color: Color(0xFF1976D2)),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
