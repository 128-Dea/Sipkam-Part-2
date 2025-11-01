// lib/data/auth_api.dart
import 'package:dio/dio.dart';
import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:shared_preferences/shared_preferences.dart';

/// ========== KONFIGURASI DASAR ==========
/// Ganti baseUrl sesuai lokasi Laravel kamu.
/// - Android emulator -> http://10.0.2.2:8000
/// - iOS simulator / Desktop -> http://127.0.0.1:8000
/// - Device fisik -> pakai IP LAN, mis. http://192.168.1.10:8000
const String kBaseUrl = 'http://10.0.2.2:8000';

/// ========== TOKEN STORE ==========
/// Simpan token di SecureStorage (mobile) atau SharedPreferences (web).
abstract class TokenStore {
  Future<void> save(String token);
  Future<String?> read();
  Future<void> clear();
}

class SecureTokenStore implements TokenStore {
  final _secure = const FlutterSecureStorage();
  final _key = 'auth_token';

  @override
  Future<void> save(String token) => _secure.write(key: _key, value: token);

  @override
  Future<String?> read() => _secure.read(key: _key);

  @override
  Future<void> clear() => _secure.delete(key: _key);
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

/// ========== MODEL HASIL AUTH ==========
class AuthResult {
  final Map<String, dynamic> user;
  final String token;
  AuthResult({required this.user, required this.token});
}

/// ========== ERROR API ==========
class ApiException implements Exception {
  final String message;
  final int? statusCode;
  final Map<String, List<String>>? fieldErrors;
  ApiException(this.message, {this.statusCode, this.fieldErrors});

  @override
  String toString() => 'ApiException($statusCode) $message';
}

/// ========== AUTH API (REGISTER / LOGIN / LOGOUT) ==========
class AuthApi {
  final Dio _dio;
  final TokenStore _store;

  AuthApi._internal(this._dio, this._store);

  factory AuthApi() {
    final store = _makeStore();
    final dio = Dio(BaseOptions(
      baseUrl: '$kBaseUrl/api',
      connectTimeout: const Duration(seconds: 15),
      receiveTimeout: const Duration(seconds: 20),
      headers: {
        'Accept': 'application/json',
        // Authorization akan diisi via interceptor jika token ada
      },
    ));

    // Interceptor untuk auto-attach Bearer token
    dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        final token = await store.read();
        if (token != null && token.isNotEmpty) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        handler.next(options);
      },
      onError: (e, handler) {
        // Mapping error Laravel: 422 (validation), 401 (unauthorized)
        handler.next(e);
      },
    ));

    return AuthApi._internal(dio, store);
  }

  /// Parse validation errors format Laravel:
  /// { errors: { email: ["The email has already been taken."] } }
  ApiException _mapDioError(DioException e) {
    try {
      final data = e.response?.data;
      if (data is Map && data['errors'] is Map) {
        final errs = <String, List<String>>{};
        (data['errors'] as Map).forEach((k, v) {
          if (v is List) {
            errs[k.toString()] = v.map((x) => x.toString()).toList();
          } else if (v is String) {
            errs[k.toString()] = [v];
          }
        });

        final firstMsg = errs.values.isNotEmpty && errs.values.first.isNotEmpty
            ? errs.values.first.first
            : (data['message']?.toString() ?? 'Terjadi kesalahan');
        return ApiException(firstMsg,
            statusCode: e.response?.statusCode, fieldErrors: errs);
      }

      // Fallback message
      final msg = (data is Map && data['message'] is String)
          ? data['message'] as String
          : e.message ?? 'Terjadi kesalahan jaringan';
      return ApiException(msg, statusCode: e.response?.statusCode);
    } catch (_) {
      return ApiException(
        e.message ?? 'Terjadi kesalahan jaringan',
        statusCode: e.response?.statusCode,
      );
    }
  }

  Future<AuthResult> register({
    required String nama,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    try {
      final res = await _dio.post('/register', data: {
        'nama': nama,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
      });

      final data = res.data?['data'] ?? {};
      final token = data['token']?.toString() ?? '';
      final user = (data['user'] as Map?)?.map((k, v) => MapEntry('$k', v)) ?? {};

      if (token.isEmpty) {
        throw ApiException('Token tidak ditemukan pada respons.', statusCode: res.statusCode);
      }

      await _store.save(token);
      return AuthResult(user: user, token: token);
    } on DioException catch (e) {
      throw _mapDioError(e);
    }
  }

  Future<AuthResult> login({
    required String email,
    required String password,
  }) async {
    try {
      final res = await _dio.post('/login', data: {
        'email': email,
        'password': password,
      });

      final data = res.data?['data'] ?? {};
      final token = data['token']?.toString() ?? '';
      final user = (data['user'] as Map?)?.map((k, v) => MapEntry('$k', v)) ?? {};

      if (token.isEmpty) {
        throw ApiException('Token tidak ditemukan pada respons.', statusCode: res.statusCode);
      }

      await _store.save(token);
      return AuthResult(user: user, token: token);
    } on DioException catch (e) {
      throw _mapDioError(e);
    }
  }

  Future<void> logout() async {
    try {
      // endpoint dilindungi sanctum (auth:sanctum)
      await _dio.post('/logout');
    } on DioException catch (e) {
      // kalau token sudah tidak valid, tetap lanjut hapus lokal
      final status = e.response?.statusCode ?? 0;
      if (status != 401) {
        throw _mapDioError(e);
      }
    } finally {
      await _store.clear();
    }
  }

  /// Baca token jika perlu (mis. untuk debug atau guard)
  Future<String?> currentToken() => _store.read();
}
