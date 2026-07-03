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
      ref.invalidateSelf();
    } catch (e) {
      throw Exception('Terjadi kesalahan saat menyimpan: $e');
    }
  }

  Future<void> attachMaterial(int sessionId, int materialId) async {
    await ApiClient.instance.post('/lesson-sessions/$sessionId/materials', data: {'material_id': materialId});
    ref.invalidateSelf();
  }

  Future<void> detachMaterial(int sessionId, int materialId) async {
    await ApiClient.instance.delete('/lesson-sessions/$sessionId/materials/$materialId');
    ref.invalidateSelf();
  }

  Future<void> attachAssignment(int sessionId, int assignmentId) async {
    await ApiClient.instance.post('/lesson-sessions/$sessionId/assignments', data: {'assignment_id': assignmentId});
    ref.invalidateSelf();
  }

  Future<void> detachAssignment(int sessionId, int assignmentId) async {
    await ApiClient.instance.delete('/lesson-sessions/$sessionId/assignments/$assignmentId');
    ref.invalidateSelf();
  }

  Future<void> storeAssessment(int sessionId, Map<String, dynamic> data) async {
    await ApiClient.instance.post('/lesson-sessions/$sessionId/assessments', data: data);
    ref.invalidateSelf();
  }

  Future<void> deleteAssessment(int sessionId, int assessmentId) async {
    await ApiClient.instance.delete('/lesson-sessions/$sessionId/assessments/$assessmentId');
    ref.invalidateSelf();
  }

  Future<void> storeCase(int sessionId, Map<String, dynamic> data) async {
    await ApiClient.instance.post('/lesson-sessions/$sessionId/cases', data: data);
    ref.invalidateSelf();
  }

  Future<void> deleteCase(int sessionId, int caseId) async {
    await ApiClient.instance.delete('/lesson-sessions/$sessionId/cases/$caseId');
    ref.invalidateSelf();
  }
}
