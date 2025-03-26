// import 'package:flutter/material.dart';
// import '../../core/api/api_service.dart';

// class FastingScheduleScreen extends StatefulWidget {
//   const FastingScheduleScreen({super.key});

//   @override
//   _FastingScheduleScreenState createState() => _FastingScheduleScreenState();
// }

// class _FastingScheduleScreenState extends State<FastingScheduleScreen> {
//   final ApiService apiService = ApiService();
//   List fastingSchedules = [];
//   bool isLoading = true;
//   final TextEditingController dateController = TextEditingController();
//   final TextEditingController startTimeController = TextEditingController();
//   final TextEditingController endTimeController = TextEditingController();

//   @override
//   void initState() {
//     super.initState();
//     fetchFastingSchedules();
//   }

//   Future<void> fetchFastingSchedules() async {
//     try {
//       final data = await apiService.getFastingSchedules();
//       setState(() {
//         fastingSchedules = data;
//         isLoading = false;
//       });
//     } catch (e) {
//       print("Error fetching fasting schedules: $e");
//     }
//   }

//   void addFastingSchedule() async {
//     if (dateController.text.isEmpty || startTimeController.text.isEmpty || endTimeController.text.isEmpty) {
//       return;
//     }

//     await apiService.addFastingSchedule(
//       dateController.text,
//       startTimeController.text,
//       endTimeController.text,
//     );

//     fetchFastingSchedules(); // Refresh data
//     dateController.clear();
//     startTimeController.clear();
//     endTimeController.clear();
//     Navigator.pop(context); // Tutup modal input
//   }

//   void completeFasting(int id) async {
//     await apiService.completeFasting(id);
//     fetchFastingSchedules(); // Refresh setelah menandai selesai
//   }

//   void showAddFastingDialog() {
//     showDialog(
//       context: context,
//       builder: (context) {
//         return AlertDialog(
//           title: const Text("Tambah Jadwal Puasa"),
//           content: Column(
//             mainAxisSize: MainAxisSize.min,
//             children: [
//               TextField(controller: dateController, decoration: const InputDecoration(labelText: "Tanggal (YYYY-MM-DD)")),
//               TextField(controller: startTimeController, decoration: const InputDecoration(labelText: "Jam Mulai (HH:MM)")),
//               TextField(controller: endTimeController, decoration: const InputDecoration(labelText: "Jam Selesai (HH:MM)")),
//             ],
//           ),
//           actions: [
//             TextButton(onPressed: () => Navigator.pop(context), child: const Text("Batal")),
//             ElevatedButton(onPressed: addFastingSchedule, child: const Text("Simpan")),
//           ],
//         );
//       },
//     );
//   }

//   @override
//   Widget build(BuildContext context) {
//     return Scaffold(
//       appBar: AppBar(title: const Text("Jadwal Puasa")),
//       floatingActionButton: FloatingActionButton(
//         onPressed: showAddFastingDialog,
//         child: const Icon(Icons.add),
//       ),
//       body: isLoading
//           ? const Center(child: CircularProgressIndicator())
//           : ListView.builder(
//               padding: const EdgeInsets.all(16),
//               itemCount: fastingSchedules.length,
//               itemBuilder: (context, index) {
//                 final fasting = fastingSchedules[index];
//                 return Card(
//                   shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
//                   child: ListTile(
//                     title: Text("Tanggal: ${fasting['date']}"),
//                     subtitle: Text("Pukul ${fasting['start_time']} - ${fasting['end_time']}"),
//                     trailing: fasting['completed']
//                         ? const Icon(Icons.check_circle, color: Colors.green)
//                         : IconButton(
//                             icon: const Icon(Icons.check, color: Colors.orange),
//                             onPressed: () => completeFasting(fasting['id']),
//                           ),
//                   ),
//                 );
//               },
//             ),
//     );
//   }
// }
