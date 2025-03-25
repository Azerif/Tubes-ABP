import 'package:flutter/material.dart';
import '../../core/api/api_service.dart';

class NotificationScreen extends StatefulWidget {
  const NotificationScreen({super.key});

  @override
  _NotificationScreenState createState() => _NotificationScreenState();
}

class _NotificationScreenState extends State<NotificationScreen> {
  final ApiService apiService = ApiService();
  List notifications = [];
  bool isLoading = true;
  final TextEditingController messageController = TextEditingController();

  @override
  void initState() {
    super.initState();
    fetchNotifications();
  }

  Future<void> fetchNotifications() async {
    try {
      final data = await apiService.getNotifications();
      setState(() {
        notifications = data;
        isLoading = false;
      });
    } catch (e) {
      print("Error fetching notifications: $e");
    }
  }

  void addNotification() async {
    if (messageController.text.isEmpty) return;

    await apiService.createNotification(messageController.text);
    fetchNotifications(); // Refresh data setelah menambah notifikasi
    messageController.clear();
    Navigator.pop(context); // Tutup modal input
  }

  void deleteNotification(int id) async {
    await apiService.deleteNotification(id);
    fetchNotifications(); // Refresh data setelah menghapus notifikasi
  }

  void showAddNotificationDialog() {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text("Tambah Notifikasi"),
          content: TextField(controller: messageController, decoration: const InputDecoration(labelText: "Pesan Notifikasi")),
          actions: [
            TextButton(onPressed: () => Navigator.pop(context), child: const Text("Batal")),
            ElevatedButton(onPressed: addNotification, child: const Text("Simpan")),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Notifikasi")),
      floatingActionButton: FloatingActionButton(
        onPressed: showAddNotificationDialog,
        child: const Icon(Icons.add),
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: notifications.length,
              itemBuilder: (context, index) {
                final notif = notifications[index];
                return Card(
                  child: ListTile(
                    title: Text(notif["message"]),
                    subtitle: Text("Dibuat: ${notif["created_at"]}"),
                    trailing: IconButton(
                      icon: const Icon(Icons.delete, color: Colors.red),
                      onPressed: () => deleteNotification(notif["id"]),
                    ),
                  ),
                );
              },
            ),
    );
  }
}
