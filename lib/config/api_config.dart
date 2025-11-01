import 'package:flutter/foundation.dart';

class ApiConfig {
  // pilih baseUrl berdasarkan platform
  static String get baseUrl {
    if (kIsWeb) {
      // Flutter WEB / Chrome
      return 'http://127.0.0.1:8000';
    }

    // Flutter Android emulator
    return 'http://10.0.2.2:8000';

    // kalau nanti di device fisik:
    // return 'http://192.168.1.7:8000';
  }
}
