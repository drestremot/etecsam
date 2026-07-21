import 'dart:io';

import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../features/reservations/reservation_detail_screen.dart';
import 'lab_repository.dart';
import 'navigation.dart';

/// Handler de mensagens recebidas com o app em background/terminado.
/// Precisa ser uma função top-level (ou estática) porque roda em outro isolate.
@pragma('vm:entry-point')
Future<void> firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  try {
    await Firebase.initializeApp();
  } catch (_) {
    // Firebase pode já estar inicializado ou não configurado nessa plataforma.
  }
}

/// Encapsula toda a integração com Firebase Cloud Messaging.
/// Todas as operações falham em silêncio (com log em debug) se o Firebase
/// não estiver configurado na plataforma atual — o app continua funcionando
/// normalmente sem push, igual ao comportamento do backend sem credenciais.
class PushNotificationsService {
  final Ref ref;
  bool _initialized = false;
  String? _currentToken;

  PushNotificationsService(this.ref);

  Future<void> initialize() async {
    if (_initialized) return;
    try {
      await Firebase.initializeApp();
      FirebaseMessaging.onBackgroundMessage(firebaseMessagingBackgroundHandler);

      FirebaseMessaging.onMessage.listen(_showForegroundMessage);
      FirebaseMessaging.onMessageOpenedApp.listen(_handleNotificationTap);

      final initialMessage = await FirebaseMessaging.instance.getInitialMessage();
      if (initialMessage != null) {
        _handleNotificationTap(initialMessage);
      }

      _initialized = true;
    } catch (e) {
      debugPrint('Firebase não inicializado (push notifications desativadas): $e');
    }
  }

  void _showForegroundMessage(RemoteMessage message) {
    final context = navigatorKey.currentState?.context;
    final notification = message.notification;
    if (context == null || notification == null) return;

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('${notification.title}\n${notification.body}'),
        action: SnackBarAction(
          label: 'Ver',
          onPressed: () => _handleNotificationTap(message),
        ),
      ),
    );
  }

  void _handleNotificationTap(RemoteMessage message) {
    final reservationId = int.tryParse(message.data['reservation_id']?.toString() ?? '');
    if (reservationId == null) return;

    navigatorKey.currentState?.push(
      MaterialPageRoute(builder: (_) => ReservationDetailScreen(reservationId: reservationId)),
    );
  }

  /// Pede permissão, obtém o token do dispositivo e registra no backend.
  /// Chamar depois de um login bem-sucedido.
  Future<void> registerToken() async {
    if (!_initialized) await initialize();
    if (!_initialized) return;

    try {
      final settings = await FirebaseMessaging.instance.requestPermission(
        alert: true,
        badge: true,
        sound: true,
      );
      if (settings.authorizationStatus == AuthorizationStatus.denied) {
        return;
      }

      final token = await FirebaseMessaging.instance.getToken();
      if (token != null) {
        _currentToken = token;
        await ref.read(labRepositoryProvider).registerDeviceToken(token, platform: _platformName());
      }

      FirebaseMessaging.instance.onTokenRefresh.listen((newToken) {
        _currentToken = newToken;
        ref.read(labRepositoryProvider).registerDeviceToken(newToken, platform: _platformName());
      });
    } catch (e) {
      debugPrint('Falha ao registrar token de push: $e');
    }
  }

  /// Remove o token atual do backend. Chamar antes/depois do logout.
  Future<void> unregisterToken() async {
    final token = _currentToken;
    if (token == null) return;
    try {
      await ref.read(labRepositoryProvider).unregisterDeviceToken(token);
    } catch (e) {
      debugPrint('Falha ao remover token de push: $e');
    } finally {
      _currentToken = null;
    }
  }

  String _platformName() {
    if (kIsWeb) return 'web';
    if (Platform.isAndroid) return 'android';
    if (Platform.isIOS) return 'ios';
    return 'unknown';
  }
}

final pushNotificationsServiceProvider = Provider<PushNotificationsService>((ref) {
  return PushNotificationsService(ref);
});
