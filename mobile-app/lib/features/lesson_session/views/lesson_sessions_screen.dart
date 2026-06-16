import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:mobile_app/features/lesson_session/providers/lesson_session_provider.dart';
import 'package:mobile_app/features/lesson_session/models/lesson_session_model.dart';

class LessonSessionsScreen extends ConsumerWidget {
  const LessonSessionsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final sessionsState = ref.watch(lessonSessionsProvider);

    return Scaffold(
      backgroundColor: const Color(0xFFF8F9FA),
      appBar: AppBar(
        title: const Text('Lesson Sessions', style: TextStyle(color: Colors.black87, fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: false,
        iconTheme: const IconThemeData(color: Colors.black87),
      ),
      body: sessionsState.when(
        data: (sessions) {
          if (sessions.isEmpty) {
            return RefreshIndicator(
              onRefresh: () async {
                return ref.refresh(lessonSessionsProvider.future);
              },
              child: ListView(
                physics: const AlwaysScrollableScrollPhysics(),
                children: const [
                  SizedBox(height: 100),
                  Center(child: Text('Tidak ada jadwal pelajaran.')),
                ],
              ),
            );
          }
          return RefreshIndicator(
            onRefresh: () async {
              return ref.refresh(lessonSessionsProvider.future);
            },
            child: ListView.builder(
              physics: const AlwaysScrollableScrollPhysics(),
              padding: const EdgeInsets.all(20),
              itemCount: sessions.length,
              itemBuilder: (context, index) {
                return _buildSessionCard(context, sessions[index]);
              },
            ),
          );
        },
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (err, stack) => Center(child: Text(err.toString(), style: const TextStyle(color: Colors.red))),
      ),
    );
  }

  Widget _buildSessionCard(BuildContext context, LessonSessionModel session) {
    String statusLabel = session.status.toUpperCase();
    Color statusColor = Colors.grey;
    if (session.status == 'completed') {
      statusColor = Colors.green;
    } else if (session.status == 'ongoing') {
      statusColor = Colors.orange;
    } else if (session.status == 'published') {
      statusColor = Colors.blue;
    }

    // Ekstrak jam dan menit dari format H:i:s
    String startTimeStr = session.startTime ?? '00:00';
    if (startTimeStr.length >= 5) {
      startTimeStr = startTimeStr.substring(0, 5);
    }
    
    String endTimeStr = session.endTime ?? '00:00';
    if (endTimeStr.length >= 5) {
      endTimeStr = endTimeStr.substring(0, 5);
    }
    
    // Format Tanggal
    String formattedDate = '-';
    if (session.sessionDate != null) {
      try {
        DateTime parsedDate = DateTime.parse(session.sessionDate!);
        List<String> days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        List<String> months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        String dayName = days[parsedDate.weekday % 7];
        String monthName = months[parsedDate.month - 1];
        formattedDate = '$dayName, ${parsedDate.day} $monthName ${parsedDate.year}';
      } catch (e) {
        formattedDate = session.sessionDate!;
      }
    }

    return GestureDetector(
      onTap: () {
        context.push('/sessions/detail', extra: session);
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 16),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.03),
              blurRadius: 10,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Time Indicator
            Column(
              children: [
                Text(
                  startTimeStr,
                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                ),
                const SizedBox(height: 4),
                const Text('-', style: TextStyle(color: Colors.grey)),
                const SizedBox(height: 4),
                Text(
                  endTimeStr,
                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                ),
              ],
            ),
            const SizedBox(width: 16),
            // Divider
            Container(width: 1, height: 80, color: Colors.grey[300]),
            const SizedBox(width: 16),
            // Content
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    session.topic ?? session.subjectName ?? 'Tanpa Topik',
                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                  ),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      const Icon(Icons.class_outlined, size: 14, color: Colors.grey),
                      const SizedBox(width: 4),
                      Text(session.schoolClassName ?? '-', style: const TextStyle(color: Colors.grey, fontSize: 12)),
                      const SizedBox(width: 12),
                      const Icon(Icons.calendar_today_outlined, size: 14, color: Colors.grey),
                      const SizedBox(width: 4),
                      Expanded(
                        child: Text(
                          formattedDate, 
                          style: const TextStyle(color: Colors.grey, fontSize: 12),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: statusColor.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Text(
                      statusLabel,
                      style: TextStyle(fontSize: 10, color: statusColor, fontWeight: FontWeight.bold),
                    ),
                  ),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }
}
