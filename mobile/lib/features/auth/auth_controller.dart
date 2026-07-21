import 'dart:async';

import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/api_client.dart';
import '../../core/lab_repository.dart';
import '../../core/push_notifications_service.dart';
import '../../models/user.dart';

enum AuthStatus { initializing, authenticated, unauthenticated }

class AuthState {
  final AuthStatus status;
  final AppUser? user;
  const AuthState(this.status, [this.user]);
}

class AuthController extends Notifier<AuthState> {
  @override
  AuthState build() {
    Future.microtask(_restoreSession);
    return const AuthState(AuthStatus.initializing);
  }

  Future<void> _restoreSession() async {
    final token = await ref.read(tokenStorageProvider).read();
    if (token == null) {
      state = const AuthState(AuthStatus.unauthenticated);
      return;
    }
    try {
      final user = await ref.read(labRepositoryProvider).me();
      state = AuthState(AuthStatus.authenticated, user);
      unawaited(ref.read(pushNotificationsServiceProvider).registerToken());
    } catch (_) {
      await ref.read(tokenStorageProvider).clear();
      state = const AuthState(AuthStatus.unauthenticated);
    }
  }

  Future<void> login(String email, String password) async {
    final result = await ref.read(labRepositoryProvider).login(email, password);
    await ref.read(tokenStorageProvider).save(result.token);
    state = AuthState(AuthStatus.authenticated, result.user);
    unawaited(ref.read(pushNotificationsServiceProvider).registerToken());
  }

  Future<void> logout() async {
    await ref.read(pushNotificationsServiceProvider).unregisterToken();
    try {
      await ref.read(labRepositoryProvider).logout();
    } catch (_) {
      // mesmo se a chamada falhar, limpamos a sessão local
    }
    await ref.read(tokenStorageProvider).clear();
    state = const AuthState(AuthStatus.unauthenticated);
  }

  /// Chamado quando qualquer chamada de API recebe 401 fora do fluxo de login.
  Future<void> forceLogout() async {
    await ref.read(tokenStorageProvider).clear();
    state = const AuthState(AuthStatus.unauthenticated);
  }
}

final authControllerProvider = NotifierProvider<AuthController, AuthState>(AuthController.new);
