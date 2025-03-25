import 'package:flutter/material.dart';

class CalorieLogScreen extends StatelessWidget {
  const CalorieLogScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final List<Map<String, dynamic>> calorieLogs = [
      {"food": "Nasi Goreng", "calories": 400},
      {"food": "Ayam Bakar", "calories": 350},
      {"food": "Jus Alpukat", "calories": 250},
    ];

    return Scaffold(
      appBar: AppBar(title: const Text("Catatan Kalori")),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: calorieLogs.length,
        itemBuilder: (context, index) {
          final log = calorieLogs[index];
          return Card(
            child: ListTile(
              title: Text(log["food"]),
              subtitle: Text("${log["calories"]} Kalori"),
            ),
          );
        },
      ),
    );
  }
}
