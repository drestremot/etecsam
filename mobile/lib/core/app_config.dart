/// Endereço base da API Laravel.
///
/// Aponta para produção (Hostinger) por padrão. Para testar contra o backend
/// local (`php artisan serve`), troque para http://127.0.0.1:8000/api/v1 —
/// num emulador Android use http://10.0.2.2:8000, num celular físico via
/// `adb reverse tcp:8000 tcp:8000` o 127.0.0.1 funciona normalmente.
class AppConfig {
  static const String apiBaseUrl = 'https://etecsam.com.br/api/v1';
}
