import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'dart:async';
import 'package:mobile_app/core/network/api_client.dart';
import 'package:mobile_app/features/lesson_session/models/lesson_session_model.dart';

final lessonSessionsProvider = AsyncNotifierProvider<LessonSessionsNotifier, List<LessonSessionModel>>(() {
  return LessonSessionsNotifier();
});

class LessonSessionsNotifier extends AsyncNotifier<List<LessonSessionModel>> {
  @override
  FutureOr<List<LessonSessionModel>> build() async {
    return await fetchSessions();
  }

  Future<List<LessonSessionModel>> fetchSessions() async {
    try {
      final response = await ApiClient.instance.get('/lesson-sessions');
      final data = response.data['data'] as List;
      return data.map((json) => LessonSessionModel.fromJson(json)).toList();
    } on DioException catch (e) {
      throw Exception('Gagal memuat sesi pelajaran: ${e.message}');
    } catch (e) {
      throw Exception('Terjadi kesalahan: $e');
    }
  }

  Future<void> updateSession(int id, Map<String, dynamic> updateData) async {
    try {
      await ApiClient.instance.put('/lesson-sessions/$id', data: updateData);
      // Refresh state to fetch updated data
      ref.invalidateSelf();
    } on DioException catch (e) {
      throw Exception('Gagal menyimpan sesi: ${e.response?.data['message'] ?? e.message}');
    } catch (e) {
      throw Exception('Terjadi kesalahan saat menyimpan: $e');
    }
  }
}
