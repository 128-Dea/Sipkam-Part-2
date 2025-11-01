import 'package:dio/dio.dart';
import '../config/api_config.dart';

class ApiClient {
  late final Dio dio;

  ApiClient() {
    dio = Dio(
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

  // contoh: GET /api/pengguna
  Future<Map<String, dynamic>> getPenggunaRaw() async {
    final res = await dio.get('/pengguna');
    return res.data as Map<String, dynamic>;
  }

  // contoh: POST /api/login
  Future<Map<String, dynamic>> login(String email, String password) async {
    final res = await dio.post('/login', data: {
      'email': email,
      'password': password,
    });
    return res.data as Map<String, dynamic>;
  }

  // kalau nanti butuh token:
  void setToken(String token) {
    dio.options.headers['Authorization'] = 'Bearer $token';
  }
}
