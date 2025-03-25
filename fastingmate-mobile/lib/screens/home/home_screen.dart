import 'package:flutter/material.dart';
import '../../core/api/api_service.dart';
import '../fasting/fasting_schedule_screen.dart';
import '../calories/calorie_log_screen.dart';
import '../profile/user_profile_screen.dart';
import '../notifications/notification_screen.dart';
import '../leaderboard/leaderboard_screen.dart';
import '../auth/login_screen.dart';

class HomeScreen extends StatefulWidget {
  final bool showWelcomePopup; // Parameter untuk menampilkan popup

  const HomeScreen({super.key, this.showWelcomePopup = false});

  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final ApiService apiService = ApiService();
  String username = "@name";
  double bmi = 0;
  String category = "";
  bool isLoading = true;

  int _currentIndex = 0;

  final List<Widget> _screens = [
    HomeContentScreen(), // Konten utama dashboard
    FastingScheduleScreen(), // Jadwal Puasa
    LeaderboardScreen(), // Leaderboard
    UserProfileScreen(), // Profil Pengguna
  ];

  @override
  void initState() {
    super.initState();
    fetchUserProfile();

    // Tampilkan popup jika showWelcomePopup bernilai true
    if (widget.showWelcomePopup) {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        showDialog(
          context: context,
          builder: (context) {
            return AlertDialog(
              title: const Text(
                "Login Berhasil",
                style: TextStyle(fontWeight: FontWeight.bold),
              ),
              content: const Text(
                "Selamat datang di FastingMate! Semoga harimu menyenangkan 😊",
                style: TextStyle(fontSize: 16),
              ),
              actions: [
                TextButton(
                  onPressed: () {
                    Navigator.pop(context); // Tutup dialog
                  },
                  child: const Text(
                    "OK",
                    style: TextStyle(color: Colors.blue),
                  ),
                ),
              ],
            );
          },
        );
      });
    }
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
          : _screens[_currentIndex], // Menampilkan layar sesuai indeks
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,
        onTap: (index) {
          setState(() {
            _currentIndex = index;
          });
        },
        type: BottomNavigationBarType.fixed,
        selectedItemColor: Colors.orange,
        unselectedItemColor: Colors.grey,
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.dashboard),
            label: "Dashboard",
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.schedule),
            label: "Puasa",
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.leaderboard),
            label: "Leaderboard",
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.person),
            label: "Profil",
          ),
        ],
      ),
    );
  }
}

class HomeContentScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Greeting
          const Text(
            "Selamat Datang, User! 👋",
            style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
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
                children: const [
                  Text(
                    "Status Kesehatan",
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                  SizedBox(height: 8),
                  Text("BMI: 22.5"),
                  Text("Kategori: Normal"),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
