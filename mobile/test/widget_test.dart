import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:etecsam_lab/features/auth/login_screen.dart';

void main() {
  testWidgets('Tela de login mostra os campos de e-mail e senha', (WidgetTester tester) async {
    await tester.pumpWidget(const ProviderScope(child: MaterialApp(home: LoginScreen())));
    await tester.pump();

    expect(find.text('Reserva de Laboratórios'), findsOneWidget);
    expect(find.text('Entrar'), findsOneWidget);
    expect(find.byType(TextFormField), findsNWidgets(2));
  });
}
