import 'package:flutter/material.dart';
import '../../core/api/api_service.dart';

class CalorieLogScreen extends StatefulWidget {
  const CalorieLogScreen({super.key});

  @override
  _CalorieLogScreenState createState() => _CalorieLogScreenState();
}

class _CalorieLogScreenState extends State<CalorieLogScreen> {
  final ApiService apiService = ApiService();
  List calorieLogs = [];
  bool isLoading = true;
  final TextEditingController typeController = TextEditingController();
  final TextEditingController descController = TextEditingController();
  final TextEditingController calorieController = TextEditingController();

  @override
  void initState() {
    super.initState();
    fetchCalories();
  }

  Future<void> fetchCalories() async {
    try {
      final data = await apiService.getCalories();
      setState(() {
        calorieLogs = data;
        isLoading = false;
      });
    } catch (e) {
      print("Error fetching calorie logs: $e");
    }
  }

  void addCalorie() async {
    if (typeController.text.isEmpty || descController.text.isEmpty || calorieController.text.isEmpty) {
      return;
    }

    int calories = int.tryParse(calorieController.text) ?? 0;
    await apiService.addCalorieLog(typeController.text, descController.text, calories);
    fetchCalories(); // Refresh data setelah menambah catatan
    typeController.clear();
    descController.clear();
    calorieController.clear();
    Navigator.pop(context); // Tutup modal input
  }

  void showAddCalorieDialog() {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text("Tambah Catatan Kalori"),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(controller: typeController, decoration: const InputDecoration(labelText: "Jenis (food/activity)")),
              TextField(controller: descController, decoration: const InputDecoration(labelText: "Deskripsi")),
              TextField(controller: calorieController, decoration: const InputDecoration(labelText: "Kalori"), keyboardType: TextInputType.number),
            ],
          ),
          actions: [
            TextButton(onPressed: () => Navigator.pop(context), child: const Text("Batal")),
            ElevatedButton(onPressed: addCalorie, child: const Text("Simpan")),
          ],
        );
      },
    );

  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Catatan Kalori")),
      floatingActionButton: FloatingActionButton(
        onPressed: showAddCalorieDialog,
        child: const Icon(Icons.add),
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: calorieLogs.length,
              itemBuilder: (context, index) {
                final log = calorieLogs[index];
                return Card(
                  child: ListTile(
                    title: Text("${log["description"]} (${log["type"]})"),
                    subtitle: Text("${log["calories"]} Kalori"),
                  ),
                );
              },
            ),
    );
  }
}
