import 'package:flutter/material.dart';
import '../../core/api/api_service.dart';
import '../leaderboard/leaderboard_screen.dart';
import '../profile/user_profile_screen.dart';
import '../auth/login_screen.dart';
import 'home_content_screen.dart';

class HomeScreen extends StatefulWidget {
  final int userId;
  final bool showWelcomePopup;

  const HomeScreen({super.key, required this.userId, this.showWelcomePopup = false});

  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  final ApiService apiService = ApiService();
  String username = "User";
  double bmi = 0;
  String category = "";
  bool isLoading = true;

  int _currentIndex = 0;

  @override
  void initState() {
    super.initState();
    fetchUserProfile();

    if (widget.showWelcomePopup) {
      WidgetsBinding.instance.addPostFrameCallback((_) => _showWelcomePopup());
    }
  }

  Future<void> fetchUserProfile() async {
    try {
      print("🔄 Fetching user profile for ID: ${widget.userId}");

      final userData = await apiService.getUserById(widget.userId);
      final categoryData = await apiService.getUserWeightCategory(widget.userId);

      setState(() {
        username = userData["name"] ?? "User";
        bmi = categoryData["bmi"] ?? 0.0;
        category = categoryData["weight_category"] ?? "Unknown";
        isLoading = false;
      });

      print("✅ User Data: $userData");
      print("✅ BMI Data: $categoryData");
    } catch (e) {
      print("❌ Error fetching user profile: $e");
      setState(() => isLoading = false);
    }
  }

  void _showWelcomePopup() {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text("Login Berhasil", style: TextStyle(fontWeight: FontWeight.bold)),
          content: const Text("Selamat datang di FastingMate! Semoga harimu menyenangkan 😊"),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text("OK", style: TextStyle(color: Colors.blue)),
            ),
          ],
        );
      },
    );
  }

  void logout() {
    Navigator.pushReplacement(
      context,
      MaterialPageRoute(builder: (context) => const LoginScreen()),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[200],
      appBar: AppBar(
        title: const Text("Dashboard"),
        backgroundColor: const Color(0xFFFF9900),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: logout,
          ),
        ],
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator())
          : IndexedStack(
              index: _currentIndex,
              children: [
                HomeContentScreen(username: username, bmi: bmi, category: category),
                LeaderboardScreen(),
                UserProfileScreen(userId: widget.userId),
              ],
            ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _currentIndex,
        onTap: (index) => setState(() => _currentIndex = index),
        type: BottomNavigationBarType.fixed,
        selectedItemColor: Colors.orange,
        unselectedItemColor: Colors.grey,
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.dashboard), label: "Dashboard"),
          BottomNavigationBarItem(icon: Icon(Icons.leaderboard), label: "Leaderboard"),
          BottomNavigationBarItem(icon: Icon(Icons.person), label: "Profil"),
        ],
      ),
    );
  }
}
