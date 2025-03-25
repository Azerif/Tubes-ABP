import 'package:flutter/material.dart';

class HomeContentScreen extends StatelessWidget {
  final String username;
  final double bmi;
  final String category;

  const HomeContentScreen({
    super.key,
    required this.username,
    required this.bmi,
    required this.category,
  });

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // 🔹 Sapaan Pengguna
          Text(
            "Selamat Datang, $username! 👋",
            style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          const Text(
            "Pantau progres puasamu dan tetap semangat!",
            style: TextStyle(fontSize: 16, color: Colors.grey),
          ),
          const SizedBox(height: 20),

          // 🔹 Kartu Informasi BMI
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            elevation: 4,
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    "Status Kesehatan",
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 8),
                  Text("BMI: ${bmi.toStringAsFixed(2)}"),
                  Text("Kategori: $category"),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
