class Pengguna {
  final int id;
  final String nama;
  final String email;
  final String? nim;
  final String? jurusan;
  final String? role;

  Pengguna({
    required this.id,
    required this.nama,
    required this.email,
    this.nim,
    this.jurusan,
    this.role,
  });

  factory Pengguna.fromJson(Map<String, dynamic> json) {
    return Pengguna(
      id: json['id'] as int,
      nama: json['nama'] ?? '',
      email: json['email'] ?? '',
      role: json['role'],
    );
  }
}
