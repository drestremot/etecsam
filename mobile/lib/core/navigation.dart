import 'package:flutter/material.dart';

/// Chave global de navegação, usada para navegar a partir de callbacks que não
/// têm um BuildContext à mão (ex.: toque em notificação push com o app em background).
final navigatorKey = GlobalKey<NavigatorState>();
