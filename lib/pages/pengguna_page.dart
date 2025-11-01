import 'package:flutter/material.dart';
import '../services/pengguna_api.dart';
import '../services/auth_api.dart';
import '../model/pengguna.dart';
import 'login_page.dart';

class PenggunaPage extends StatefulWidget {
  const PenggunaPage({super.key});

  @override
  State<PenggunaPage> createState() => _PenggunaPageState();
}

class _PenggunaPageState extends State<PenggunaPage> {
  final _api = PenggunaApi();
  final _auth = AuthApi();
  late Future<List<Pengguna>> _future;

  @override
  void initState() {
    super.initState();
    _future = _api.getAll();
  }

  Future<void> _refresh() async {
    setState(() {
      _future = _api.getAll();
    });
  }

  Future<void> _doLogout() async {
    await _auth.logout();
    if (!mounted) return;
    Navigator.pushReplacement(
      context,
      MaterialPageRoute(builder: (_) => const LoginPage()),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Data Pengguna'),
        actions: [
          IconButton(onPressed: _doLogout, icon: const Icon(Icons.logout)),
        ],
      ),
      body: FutureBuilder<List<Pengguna>>(
        future: _future,
        builder: (context, snap) {
          if (snap.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snap.hasError) {
            return Center(child: Text('Error: ${snap.error}'));
          }
          final data = snap.data ?? [];
          if (data.isEmpty) {
            return const Center(child: Text('Belum ada pengguna'));
          }
          return RefreshIndicator(
            onRefresh: _refresh,
            child: ListView.builder(
              itemCount: data.length,
              itemBuilder: (_, i) {
                final p = data[i];
                return ListTile(
                  title: Text(p.nama),
                  subtitle: Text('${p.email} • ${p.role ?? '-'}'),
                );
              },
            ),
          );
        },
      ),
    );
  }
}
