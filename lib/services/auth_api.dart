// lib/services/auth_api.dart
import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../config/api_config.dart';

class AuthResult {
  final Map<String, dynamic> user;
  final String token;
  AuthResult({required this.user, required this.token});
}

abstract class TokenStore {
  Future<void> save(String token);
  Future<String?> read();
  Future<void> clear();
}

class SecureTokenStore implements TokenStore {
  final _storage = const FlutterSecureStorage();
  final _key = 'auth_token';

  @override
  Future<void> save(String token) => _storage.write(key: _key, value: token);

  @override
  Future<String?> read() => _storage.read(key: _key);

  @override
  Future<void> clear() => _storage.delete(key: _key);
}

class PrefsTokenStore implements TokenStore {
  final _key = 'auth_token';

  @override
  Future<void> save(String token) async {
    final p = await SharedPreferences.getInstance();
    await p.setString(_key, token);
  }

  @override
  Future<String?> read() async {
    final p = await SharedPreferences.getInstance();
    return p.getString(_key);
  }

  @override
  Future<void> clear() async {
    final p = await SharedPreferences.getInstance();
    await p.remove(_key);
  }
}

TokenStore _makeStore() => kIsWeb ? PrefsTokenStore() : SecureTokenStore();

class AuthApi {
  late final Dio _dio;
  final TokenStore _store = _makeStore();

  AuthApi() {
    _dio = Dio(
      BaseOptions(
        baseUrl: '${ApiConfig.baseUrl}/api',
        connectTimeout: const Duration(seconds: 10),
        receiveTimeout: const Duration(seconds: 10),
        headers: {
          'Accept': 'application/json',
        },
      ),
    );
  }

  // =========================
  // LOGIN /api/login
  // =========================
  Future<AuthResult> login({
    required String email,
    required String password,
  }) async {
    try {
      final res = await _dio.post('/login', data: {
        'email': email,
        'password': password,
      });

      final data = res.data['data'] as Map<String, dynamic>;
      final user = data['user'] as Map<String, dynamic>;
      final token = data['token'] as String;

      await _store.save(token);

      return AuthResult(user: user, token: token);
    } on DioException catch (e) {
      if (e.response?.statusCode == 401) {
        throw Exception('Email atau password salah');
      }
      if (e.response?.statusCode == 422) {
        throw Exception('Form belum lengkap / format salah');
      }
      throw Exception('Gagal login: ${e.message}');
    }
  }

  // =========================
  // REGISTER /api/register
  // =========================
  Future<AuthResult> register({
    required String nama,
    required String email,
    required String password,
  }) async {
    try {
      final res = await _dio.post('/register', data: {
        'nama': nama,
        'email': email,
        'password': password,
        'password_confirmation': password,
      });

      final data = res.data['data'] as Map<String, dynamic>;
      final user = data['user'] as Map<String, dynamic>;
      final token = data['token'] as String;

      await _store.save(token);

      return AuthResult(user: user, token: token);
    } on DioException catch (e) {
      throw Exception('Gagal register: ${e.response?.data ?? e.message}');
    }
  }

  // =========================
  // LOGOUT /api/logout
  // (Laravel pakai auth:sanctum)
  // =========================
  Future<void> logout() async {
    final token = await _store.read();
    if (token == null) return;

    try {
      await _dio.post(
        '/logout',
        options: Options(
          headers: {
            'Authorization': 'Bearer $token',
          },
        ),
      );
    } catch (_) {
      // kalau token expired ya sudah
    } finally {
      await _store.clear();
    }
  }

  Future<String?> currentToken() => _store.read();
}
