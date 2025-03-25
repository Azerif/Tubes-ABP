import 'package:flutter/material.dart';
import '../../core/api/api_service.dart';

class UserProfileScreen extends StatefulWidget {
  final int userId;

  const UserProfileScreen({super.key, required this.userId});

  @override
  _UserProfileScreenState createState() => _UserProfileScreenState();
}

class _UserProfileScreenState extends State<UserProfileScreen> {
  final ApiService apiService = ApiService();

  bool isLoading = true;
  bool isEditing = false;
  Map<String, dynamic>? userData;
  String weightCategory = "";

  final TextEditingController nameController = TextEditingController();
  final TextEditingController heightController = TextEditingController();
  final TextEditingController weightController = TextEditingController();
  final TextEditingController activityLevelController = TextEditingController();

  @override
  void initState() {
    super.initState();
    fetchUserData();
  }

  Future<void> fetchUserData() async {
    try {
      final data = await apiService.getUserById(widget.userId);
      final category = await apiService.getUserWeightCategory(widget.userId);
      setState(() {
        userData = data;
        weightCategory = category['weight_category'];
        nameController.text = data['name'];
        heightController.text = data['height'].toString();
        weightController.text = data['weight'].toString();
        activityLevelController.text = data['activity_level'];
        isLoading = false;
      });
    } catch (e) {
      print(e);
      setState(() => isLoading = false);
    }
  }

Future<void> updateUserProfile() async {
  setState(() => isLoading = true);
  try {
    final data = {
      'name': nameController.text,
      'height': double.parse(heightController.text),
      'weight': double.parse(weightController.text),
      'activity_level': activityLevelController.text,
    };
    print("Data yang dikirim ke server: $data");

    await apiService.updateUserById(widget.userId, data);
    fetchUserData();
    setState(() => isEditing = false);
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text("Profil berhasil diperbarui!")),
    );
  } catch (e) {
    print("Error updating profile: $e");
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text("Gagal memperbarui profil")),
    );
  } finally {
    setState(() => isLoading = false);
  }
}

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Profil Pengguna")),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildProfileField("Nama", nameController, isEditing),
                  _buildProfileField("Tinggi (cm)", heightController, isEditing, isNumeric: true),
                  _buildProfileField("Berat (kg)", weightController, isEditing, isNumeric: true),
                  _buildProfileField("Level Aktivitas", activityLevelController, isEditing),
                  const SizedBox(height: 10),
                  Text(
                    "Kategori Berat Badan: $weightCategory",
                    style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 20),
                  isEditing
                      ? ElevatedButton(
                          onPressed: updateUserProfile,
                          child: const Text("Simpan Perubahan"),
                        )
                      : ElevatedButton(
                          onPressed: () => setState(() => isEditing = true),
                          child: const Text("Edit Profil"),
                        ),
                ],
              ),
            ),
    );
  }

  Widget _buildProfileField(String label, TextEditingController controller, bool editable, {bool isNumeric = false}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: TextField(
        controller: controller,
        enabled: editable,
        keyboardType: isNumeric ? TextInputType.number : TextInputType.text,
        decoration: InputDecoration(
          labelText: label,
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(8)),
        ),
      ),
    );
  }
}