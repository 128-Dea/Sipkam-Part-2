// lib/pages/login_page.dart
import 'package:flutter/material.dart';
import 'package:sipinjam_app/pages/register_page.dart';
import '../services/auth_api.dart';
import 'pengguna_page.dart'; // halaman setelah login

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  final _emailC = TextEditingController();
  final _passC = TextEditingController();
  final _auth = AuthApi();
  bool _loading = false;
  String? _error;

  Future<void> _doLogin() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() {
      _loading = true;
      _error = null;
    });

    try {
      final res = await _auth.login(
        email: _emailC.text.trim(),
        password: _passC.text,
      );

      if (!mounted) return;

      // pindah ke halaman utama (pengguna_page misalnya)
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(
          builder: (_) => PenggunaPage(
            // kalau kamu mau kirim token ke sana, bisa juga
            // token: res.token,
          ),
        ),
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
    _emailC.dispose();
    _passC.dispose();
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
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Text(
                      'Login SiPinjam',
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 20),
                    TextFormField(
                      controller: _emailC,
                      decoration: const InputDecoration(
                        labelText: 'Email',
                        border: OutlineInputBorder(),
                      ),
                      validator: (v) {
                        if (v == null || v.isEmpty) return 'Email wajib diisi';
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
                        onPressed: _loading ? null : _doLogin,
                        child: _loading
                            ? const SizedBox(
                                height: 16,
                                width: 16,
                                child: CircularProgressIndicator(strokeWidth: 2),
                              )
                            : const Text('Login'),
                      ),
                    ),
                    const SizedBox(height: 8),
                    TextButton(
  onPressed: () {
    Navigator.pushReplacement(
      context,
      MaterialPageRoute(builder: (_) => const RegisterPage()),
    );
  },
  child: const Text('Belum punya akun? Daftar'),
),

                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
