import 'package:dio/dio.dart';
import 'package:mobile_app/core/storage/secure_storage.dart';

class ApiClient {
  static final Dio _dio = Dio(
    BaseOptions(
      // Menggunakan IP lokal komputer agar bisa diakses oleh HP fisik (192.168.1.8)
      baseUrl: 'http://192.168.1.8:8000/api', 
      connectTimeout: const Duration(seconds: 10),
      receiveTimeout: const Duration(seconds: 10),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    ),
  );

  static Dio get instance {
    _dio.interceptors.clear();
    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) async {
          final token = await SecureStorage.getToken();
          if (token != null) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          return handler.next(options);
        },
        onError: (DioException e, handler) async {
          if (e.response?.statusCode == 401) {
            // Token tidak valid atau kadaluarsa, lakukan aksi logout
            await SecureStorage.deleteToken();
            // TODO: Redirect ke halaman login menggunakan router
          }
          return handler.next(e);
        },
      ),
    );
    return _dio;
  }
}
