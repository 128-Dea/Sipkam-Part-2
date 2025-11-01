import 'package:flutter/material.dart';
import '../services/auth_api.dart';
import 'login_page.dart';
import 'pengguna_page.dart';

class RegisterPage extends StatefulWidget {
  const RegisterPage({super.key});

  @override
  State<RegisterPage> createState() => _RegisterPageState();
}

class _RegisterPageState extends State<RegisterPage> {
  final _formKey = GlobalKey<FormState>();
  final _namaC = TextEditingController();
  final _emailC = TextEditingController();
  final _passC = TextEditingController();
  final _pass2C = TextEditingController();
  final _auth = AuthApi();
  bool _loading = false;
  String? _error;

  Future<void> _doRegister() async {
    if (!_formKey.currentState!.validate()) return;

    if (_passC.text != _pass2C.text) {
      setState(() {
        _error = 'Konfirmasi password tidak sama';
      });
      return;
    }

    setState(() {
      _loading = true;
      _error = null;
    });

    try {
      // ini akan panggil /api/register Laravel kamu
      final res = await _auth.register(
        nama: _namaC.text.trim(),
        email: _emailC.text.trim(),
        password: _passC.text,
      );

      if (!mounted) return;

      // langsung masuk ke halaman utama
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (_) => const PenggunaPage()),
      );
    } catch (e) {
      setState(() {
        _error = e.toString();
      });
    } finally {
      if (mounted) {
        setState(() {
          _loading = false;
        });
      }
    }
  }

  @override
  void dispose() {
    _namaC.dispose();
    _emailC.dispose();
    _passC.dispose();
    _pass2C.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 360),
          child: Card(
            elevation: 2,
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Form(
                key: _formKey,
                child: SingleChildScrollView(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      const Text(
                        'Daftar Akun',
                        style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 20),
                      TextFormField(
                        controller: _namaC,
                        decoration: const InputDecoration(
                          labelText: 'Nama lengkap',
                          border: OutlineInputBorder(),
                        ),
                        validator: (v) {
                          if (v == null || v.isEmpty) return 'Nama wajib diisi';
                          return null;
                        },
                      ),
                      const SizedBox(height: 12),
                      TextFormField(
                        controller: _emailC,
                        decoration: const InputDecoration(
                          labelText: 'Email',
                          border: OutlineInputBorder(),
                        ),
                        validator: (v) {
                          if (v == null || v.isEmpty) return 'Email wajib diisi';
                          if (!v.contains('@')) return 'Email tidak valid';
                          return null;
                        },
                      ),
                      const SizedBox(height: 12),
                      TextFormField(
                        controller: _passC,
                        obscureText: true,
                        decoration: const InputDecoration(
                          labelText: 'Password',
                          border: OutlineInputBorder(),
                        ),
                        validator: (v) {
                          if (v == null || v.isEmpty) return 'Password wajib diisi';
                          if (v.length < 5) return 'Minimal 5 karakter';
                          return null;
                        },
                      ),
                      const SizedBox(height: 12),
                      TextFormField(
                        controller: _pass2C,
                        obscureText: true,
                        decoration: const InputDecoration(
                          labelText: 'Ulangi password',
                          border: OutlineInputBorder(),
                        ),
                        validator: (v) {
                          if (v == null || v.isEmpty) return 'Ulangi password';
                          return null;
                        },
                      ),
                      const SizedBox(height: 12),
                      if (_error != null)
                        Text(
                          _error!,
                          style: const TextStyle(color: Colors.red),
                        ),
                      const SizedBox(height: 12),
                      SizedBox(
                        width: double.infinity,
                        child: ElevatedButton(
                          onPressed: _loading ? null : _doRegister,
                          child: _loading
                              ? const SizedBox(
                                  height: 16,
                                  width: 16,
                                  child: CircularProgressIndicator(strokeWidth: 2),
                                )
                              : const Text('Daftar'),
                        ),
                      ),
                      const SizedBox(height: 8),
                      TextButton(
                        onPressed: () {
                          Navigator.pushReplacement(
                            context,
                            MaterialPageRoute(builder: (_) => const LoginPage()),
                          );
                        },
                        child: const Text('Sudah punya akun? Login'),
                      )
                    ],
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
