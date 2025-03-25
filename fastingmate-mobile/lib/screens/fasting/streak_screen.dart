import 'package:flutter/material.dart';

class StreakScreen extends StatelessWidget {
  const StreakScreen({super.key});

  @override
  Widget build(BuildContext context) {
    int streakCount = 5; // Dummy data

    return Scaffold(
      appBar: AppBar(title: const Text("Streak Puasa")),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Text("🔥 Streak Puasa Saat Ini 🔥", style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),
            Text(
              "$streakCount Hari Berturut-turut!",
              style: const TextStyle(fontSize: 28, fontWeight: FontWeight.bold, color: Colors.blue),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: () {},
              child: const Text("Lihat Detail"),
            ),
          ],
        ),
      ),
    );
  }
}
