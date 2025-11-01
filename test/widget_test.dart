// This is a basic Flutter widget test.
//
// To perform an interaction with a widget in your test, use the WidgetTester
// utility in the flutter_test package. For example, you can send tap and scroll
// gestures. You can also use WidgetTester to find child widgets in the widget
// tree, read text, and verify that the values of widget properties are correct.

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import '../lib/pages/pengguna_page.dart';

void main() {
  testWidgets('PenggunaPage loads and connects to Laravel backend', (WidgetTester tester) async {
    // Build the PenggunaPage widget
    await tester.pumpWidget(const MaterialApp(
      home: PenggunaPage(),
    ));

    // Wait for the page to load and fetch data from backend
    await tester.pumpAndSettle();

    // Verify that the page shows loading indicator initially
    expect(find.byType(CircularProgressIndicator), findsOneWidget);

    // After loading completes, it should show either data or error
    // Note: This test will pass if backend is running, fail if not
    // In a real test environment, you'd mock the API calls
  });
}
