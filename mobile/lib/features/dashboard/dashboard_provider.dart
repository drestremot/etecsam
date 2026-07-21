import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/lab_repository.dart';

final dashboardProvider = FutureProvider.autoDispose((ref) {
  return ref.watch(labRepositoryProvider).dashboard();
});
