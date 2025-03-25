import 'package:flutter/material.dart';

class LeaderboardScreen extends StatelessWidget {
  const LeaderboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final List<Map<String, dynamic>> leaderboard = [
      {"name": "Azrian", "fastingDays": 30},
      {"name": "Budi", "fastingDays": 25},
      {"name": "Citra", "fastingDays": 20},
    ];

    return Scaffold(
      appBar: AppBar(title: const Text("Leaderboard")),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: leaderboard.length,
        itemBuilder: (context, index) {
          final user = leaderboard[index];
          return Card(
            child: ListTile(
              leading: CircleAvatar(child: Text("${index + 1}")),
              title: Text(user["name"]),
              subtitle: Text("Puasa selesai: ${user["fastingDays"]} hari"),
            ),
          );
        },
      ),
    );
  }
}
