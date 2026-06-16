import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class SecureStorage {
  static const _storage = FlutterSecureStorage();
  static const _tokenKey = 'auth_token';

  /// Save token securely
  static Future<void> saveToken(String token) async {
    await _storage.write(key: _tokenKey, value: token);
  }

  /// Get token
  static Future<String?> getToken() async {
    return await _storage.read(key: _tokenKey);
  }

  /// Delete token
  static Future<void> deleteToken() async {
    await _storage.delete(key: _tokenKey);
  }

  /// Check if user is logged in
  static Future<bool> hasToken() async {
    final token = await getToken();
    return token != null;
  }
}
