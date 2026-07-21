import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter/material.dart';
import 'package:intl/date_symbol_data_local.dart';

import 'core/navigation.dart';
import 'features/auth/auth_controller.dart';
import 'features/auth/login_screen.dart';
import 'features/shared/home_shell.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await initializeDateFormatting('pt_BR', null);
  runApp(const ProviderScope(child: EtecSamLabApp()));
}

// Paleta "Agro Moderno" usada no site institucional da Etec.
const _etecDark = Color(0xFF1A4D2E);
const _etecMain = Color(0xFF2D6A4F);
const _etecAccent = Color(0xFFF5A623);

class EtecSamLabApp extends StatelessWidget {
  const EtecSamLabApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      navigatorKey: navigatorKey,
      title: 'Reserva de Laboratórios',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        useMaterial3: true,
        colorScheme: ColorScheme.fromSeed(
          seedColor: _etecMain,
          primary: _etecMain,
          secondary: _etecAccent,
        ),
        appBarTheme: const AppBarTheme(
          backgroundColor: _etecDark,
          foregroundColor: Colors.white,
        ),
        navigationBarTheme: NavigationBarThemeData(
          indicatorColor: _etecMain.withValues(alpha: 0.15),
        ),
      ),
      home: const _AuthGate(),
    );
  }
}

class _AuthGate extends ConsumerWidget {
  const _AuthGate();

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final authState = ref.watch(authControllerProvider);

    switch (authState.status) {
      case AuthStatus.initializing:
        return const Scaffold(body: Center(child: CircularProgressIndicator()));
      case AuthStatus.unauthenticated:
        return const LoginScreen();
      case AuthStatus.authenticated:
        return const HomeShell();
    }
  }
}
