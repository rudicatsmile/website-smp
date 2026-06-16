import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'dart:async';
import 'package:mobile_app/core/network/api_client.dart';

final userProvider = AsyncNotifierProvider<UserNotifier, Map<String, dynamic>?>(() {
  return UserNotifier();
});

class UserNotifier extends AsyncNotifier<Map<String, dynamic>?> {
  @override
  FutureOr<Map<String, dynamic>?> build() async {
    return await fetchUser();
  }

  Future<Map<String, dynamic>?> fetchUser() async {
    try {
      final response = await ApiClient.instance.get('/user');
      return response.data as Map<String, dynamic>;
    } on DioException catch (_) {
      // Return null or handle error silently if user is not authenticated
      return null;
    } catch (_) {
      return null;
    }
  }
}
