import 'package:dio/dio.dart';
import '../config/api_config.dart';
import '../model/pengguna.dart';

class PenggunaApi {
  final Dio _dio = Dio(
    BaseOptions(
      baseUrl: '${ApiConfig.baseUrl}/api',
      connectTimeout: const Duration(seconds: 10),
      receiveTimeout: const Duration(seconds: 10),
      headers: {
        'Accept': 'application/json',
      },
    ),
  );

  // panggil ini habis login jika endpoint kamu pakai sanctum
  void setToken(String token) {
    _dio.options.headers['Authorization'] = 'Bearer $token';
  }

  // GET /api/pengguna
  Future<List<Pengguna>> getAll() async {
    final res = await _dio.get('/pengguna');

    // laravel kamu bentuknya:
    // { status: true, message: "...", data: [...] }
    final body = res.data as Map<String, dynamic>;
    final listJson = body['data'] as List;

    return listJson
        .map((e) => Pengguna.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  // POST /api/pengguna
  Future<Pengguna> create({
    required String nama,
    required String email,
    required String password,
    String? nim,
    String? jurusan,
    String? role,
  }) async {
    final res = await _dio.post('/pengguna', data: {
      'nama': nama,
      'email': email,
      'password': password,
      'nim': nim,
      'jurusan': jurusan,
      'role': role ?? 'mahasiswa',
    });

    final body = res.data as Map<String, dynamic>;
    final data = (body['data'] ?? body) as Map<String, dynamic>;
    return Pengguna.fromJson(data);
  }

  // GET /api/pengguna/{id}
  Future<Pengguna> getById(int id) async {
    final res = await _dio.get('/pengguna/$id');
    final body = res.data as Map<String, dynamic>;
    final data = (body['data'] ?? body) as Map<String, dynamic>;
    return Pengguna.fromJson(data);
  }

  // PUT /api/pengguna/{id}
  Future<Pengguna> update(
    int id, {
    String? nama,
    String? email,
    String? password,
    String? nim,
    String? jurusan,
    String? role,
  }) async {
    final payload = <String, dynamic>{};
    if (nama != null) payload['nama'] = nama;
    if (email != null) payload['email'] = email;
    if (password != null) payload['password'] = password;
    if (nim != null) payload['nim'] = nim;
    if (jurusan != null) payload['jurusan'] = jurusan;
    if (role != null) payload['role'] = role;

    final res = await _dio.put('/pengguna/$id', data: payload);
    final body = res.data as Map<String, dynamic>;
    final data = (body['data'] ?? body) as Map<String, dynamic>;
    return Pengguna.fromJson(data);
  }

  // DELETE /api/pengguna/{id}
  Future<void> delete(int id) async {
    await _dio.delete('/pengguna/$id');
  }
}
