import 'package:flutter/material.dart';
import '../../core/api/api_service.dart';
import '../fasting/fasting_schedule_screen.dart';
import '../calories/calorie_log_screen.dart';
import '../profile/user_profile_screen.dart';
import '../notifications/notification_screen.dart';
import '../leaderboard/leaderboard_screen.dart';
// import '../reports/report_screen.dart';
// import '../streaks/streak_screen.dart';
import '../auth/login_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final ApiService apiService = ApiService();
  String username = "Pengguna";
  double bmi = 0;
  String category = "";
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    fetchUserProfile();
  }

  Future<void> fetchUserProfile() async {
    try {
      final data = await apiService.getUserBMI();
      setState(() {
        bmi = data["bmi"];
        category = data["category"];
        username = "User"; // Ubah ini jika ada endpoint untuk nama pengguna
        isLoading = false;
      });
    } catch (e) {
      print("Error fetching user profile: $e");
      setState(() => isLoading = false);
    }
  }

  void logout() {
    Navigator.pushReplacement(context, MaterialPageRoute(builder: (context) => const LoginScreen()));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[200],
      appBar: AppBar(
        title: const Text("Dashboard"),
        backgroundColor: const Color.fromARGB(255, 255, 153, 0),
        elevation: 0,
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Greeting
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

                  // BMI & Status Kesehatan
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
                          Text("BMI: ${bmi.toStringAsFixed(1)}"),
                          Text("Kategori: $category"),
                        ],
                      ),
                    ),
                  ),

                  const SizedBox(height: 20),

                  // Tombol Navigasi
                  Expanded(
                    child: GridView.count(
                      crossAxisCount: 2,
                      crossAxisSpacing: 16,
                      mainAxisSpacing: 16,
                      children: [
                        _buildDashboardButton(
                          icon: Icons.schedule,
                          label: "Jadwal Puasa",
                          onTap: () => Navigator.push(
                              context, MaterialPageRoute(builder: (context) => const FastingScheduleScreen())),
                        ),
                        _buildDashboardButton(
                          icon: Icons.fastfood,
                          label: "Catatan Kalori",
                          onTap: () => Navigator.push(
                              context, MaterialPageRoute(builder: (context) => const CalorieLogScreen())),
                        ),
                        _buildDashboardButton(
                          icon: Icons.leaderboard,
                          label: "Leaderboard",
                          onTap: () => Navigator.push(
                              context, MaterialPageRoute(builder: (context) => const LeaderboardScreen())),
                        ),
                        _buildDashboardButton(
                          icon: Icons.notifications,
                          label: "Notifikasi",
                          onTap: () => Navigator.push(
                              context, MaterialPageRoute(builder: (context) => const NotificationScreen())),
                        ),
                        // _buildDashboardButton(
                        //   icon: Icons.bar_chart,
                        //   label: "Laporan",
                        //   onTap: () => Navigator.push(
                        //       context, MaterialPageRoute(builder: (context) => const ReportScreen())),
                        // ),
                        // _buildDashboardButton(
                        //   icon: Icons.fireplace,
                        //   label: "Streak Puasa",
                        //   onTap: () => Navigator.push(
                        //       context, MaterialPageRoute(builder: (context) => const StreakScreen())),
                        // ),
                        _buildDashboardButton(
                          icon: Icons.person,
                          label: "Profil",
                          onTap: () => Navigator.push(
                              context, MaterialPageRoute(builder: (context) => const UserProfileScreen())),
                        ),
                        _buildDashboardButton(
                          icon: Icons.logout,
                          label: "Logout",
                          onTap: logout,
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
    );
  }

  Widget _buildDashboardButton({required IconData icon, required String label, required VoidCallback onTap}) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          boxShadow: [
            BoxShadow(
              color: Colors.black12,
              blurRadius: 6,
              offset: Offset(0, 3),
            ),
          ],
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 40, color: Colors.blue),
            const SizedBox(height: 8),
            Text(label, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w500)),
          ],
        ),
      ),
    );
  }
}
