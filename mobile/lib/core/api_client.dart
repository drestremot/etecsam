import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

import 'app_config.dart';

const _tokenStorageKey = 'auth_token';

final secureStorageProvider = Provider<FlutterSecureStorage>((ref) {
  return const FlutterSecureStorage();
});

/// Erro de API já traduzido para uma mensagem legível, extraída do corpo
/// JSON padrão do Laravel (`message` ou o primeiro erro de `errors`).
class ApiException implements Exception {
  final String message;
  final int? statusCode;

  ApiException(this.message, {this.statusCode});

  @override
  String toString() => message;

  static ApiException fromDioException(DioException e) {
    final data = e.response?.data;
    if (data is Map && data['errors'] is Map) {
      final errors = data['errors'] as Map;
      final first = errors.values.first;
      if (first is List && first.isNotEmpty) {
        return ApiException(first.first.toString(), statusCode: e.response?.statusCode);
      }
    }
    if (data is Map && data['message'] is String) {
      return ApiException(data['message'] as String, statusCode: e.response?.statusCode);
    }
    return ApiException('Não foi possível conectar ao servidor. Verifique sua conexão.', statusCode: e.response?.statusCode);
  }
}

final dioProvider = Provider<Dio>((ref) {
  final storage = ref.watch(secureStorageProvider);

  final dio = Dio(BaseOptions(
    baseUrl: AppConfig.apiBaseUrl,
    headers: {'Accept': 'application/json'},
    connectTimeout: const Duration(seconds: 15),
    receiveTimeout: const Duration(seconds: 15),
  ));

  dio.interceptors.add(InterceptorsWrapper(
    onRequest: (options, handler) async {
      final token = await storage.read(key: _tokenStorageKey);
      if (token != null) {
        options.headers['Authorization'] = 'Bearer $token';
      }
      handler.next(options);
    },
  ));

  return dio;
});

/// Helpers para persistir/ler/limpar o token Sanctum no armazenamento seguro.
class TokenStorage {
  final FlutterSecureStorage _storage;
  TokenStorage(this._storage);

  Future<void> save(String token) => _storage.write(key: _tokenStorageKey, value: token);
  Future<String?> read() => _storage.read(key: _tokenStorageKey);
  Future<void> clear() => _storage.delete(key: _tokenStorageKey);
}

final tokenStorageProvider = Provider<TokenStorage>((ref) {
  return TokenStorage(ref.watch(secureStorageProvider));
});
