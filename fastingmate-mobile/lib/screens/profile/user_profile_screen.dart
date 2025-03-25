import 'package:flutter/material.dart';
import '../../core/api/api_service.dart';
import '../auth/login_screen.dart';

class UserProfileScreen extends StatefulWidget {
  const UserProfileScreen({super.key});

  @override
  _UserProfileScreenState createState() => _UserProfileScreenState();
}

class _UserProfileScreenState extends State<UserProfileScreen> {
  final ApiService apiService = ApiService();
  final TextEditingController heightController = TextEditingController();
  final TextEditingController weightController = TextEditingController();
  String activityLevel = "Sedentary";
  double? bmi;
  String? category;
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    fetchBMI();
  }

  Future<void> fetchBMI() async {
    try {
      final data = await apiService.getUserBMI();
      setState(() {
        bmi = data["bmi"];
        category = data["category"];
        isLoading = false;
      });
    } catch (e) {
      print("Error fetching BMI: $e");
      setState(() => isLoading = false);
    }
  }

  void saveProfile() async {
    if (heightController.text.isEmpty || weightController.text.isEmpty) return;

    int height = int.tryParse(heightController.text) ?? 0;
    int weight = int.tryParse(weightController.text) ?? 0;
    await apiService.updateUserProfile(height, weight, activityLevel);

    fetchBMI(); // Refresh BMI setelah menyimpan profil
    Navigator.pop(context); // Tutup modal input
  }

  void showEditProfileDialog() {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text("Edit Profil"),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                controller: heightController,
                decoration: const InputDecoration(labelText: "Tinggi (cm)"),
                keyboardType: TextInputType.number,
              ),
              TextField(
                controller: weightController,
                decoration: const InputDecoration(labelText: "Berat (kg)"),
                keyboardType: TextInputType.number,
              ),
              DropdownButtonFormField<String>(
                value: activityLevel,
                onChanged: (value) => setState(() => activityLevel = value!),
                items: ["Sedentary", "Lightly Active", "Active", "Very Active"]
                    .map((level) => DropdownMenuItem(value: level, child: Text(level)))
                    .toList(),
                decoration: const InputDecoration(labelText: "Tingkat Aktivitas"),
              ),
            ],
          ),
          actions: [
            TextButton(onPressed: () => Navigator.pop(context), child: const Text("Batal")),
            ElevatedButton(onPressed: saveProfile, child: const Text("Simpan")),
          ],
        );
      },
    );
  }

  void logout() {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text("Konfirmasi Logout"),
          content: const Text("Apakah Anda yakin ingin keluar?"),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context), // Tutup dialog
              child: const Text("Batal"),
            ),
            ElevatedButton(
              onPressed: () {
                Navigator.pop(context); // Tutup dialog
                Navigator.pushReplacement(
                  context,
                  MaterialPageRoute(builder: (context) => const LoginScreen()),
                ); // Navigasi ke layar login
              },
              child: const Text("Logout"),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Profil Pengguna")),
      floatingActionButton: FloatingActionButton(
        onPressed: showEditProfileDialog,
        child: const Icon(Icons.edit),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            isLoading
                ? const Center(child: CircularProgressIndicator())
                : Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text("BMI: ${bmi ?? 'N/A'}", style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                      Text("Kategori: ${category ?? 'N/A'}", style: const TextStyle(fontSize: 16)),
                    ],
                  ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: showEditProfileDialog,
              child: const Text("Edit Profil"),
            ),
            const Spacer(), // Mendorong tombol logout ke bawah
            ElevatedButton.icon(
              onPressed: logout,
              icon: const Icon(Icons.logout),
              label: const Text("Logout"),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.red,
                foregroundColor: Colors.white,
                minimumSize: const Size(double.infinity, 50), // Tombol penuh lebar
              ),
            ),
          ],
        ),
      ),
    );
  }
}
