import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:mobile_app/core/network/api_client.dart';
import 'package:mobile_app/core/storage/secure_storage.dart';

import 'dart:async';

final authProvider = AsyncNotifierProvider<AuthNotifier, void>(() {
  return AuthNotifier();
});

class AuthNotifier extends AsyncNotifier<void> {
  @override
  FutureOr<void> build() {
    return null;
  }

  Future<bool> login(String email, String password) async {
    state = const AsyncLoading();
    try {
      final response = await ApiClient.instance.post('/login', data: {
        'email': email,
        'password': password,
      });

      if (response.data['status'] == 'success') {
        final token = response.data['data']['token'];
        await SecureStorage.saveToken(token);
        state = const AsyncData(null);
        return true;
      } else {
        state = AsyncError(response.data['message'] ?? 'Login failed', StackTrace.current);
        return false;
      }
    } on DioException catch (e) {
      String errorMessage = 'Terjadi kesalahan jaringan';
      if (e.response != null && e.response?.data != null) {
        if (e.response?.data['errors'] != null) {
           errorMessage = e.response?.data['errors'].values.first[0];
        } else if (e.response?.data['message'] != null) {
           errorMessage = e.response?.data['message'];
        }
      }
      state = AsyncError(errorMessage, StackTrace.current);
      return false;
    } catch (e) {
      state = AsyncError(e.toString(), StackTrace.current);
      return false;
    }
  }
}
