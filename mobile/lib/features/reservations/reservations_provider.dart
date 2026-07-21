import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/lab_repository.dart';
import '../../models/reservation.dart';

final spacesProvider = FutureProvider.autoDispose((ref) {
  return ref.watch(labRepositoryProvider).spaces();
});

final materialsProvider = FutureProvider.autoDispose((ref) {
  return ref.watch(labRepositoryProvider).materials();
});

final auxiliaresProvider = FutureProvider.autoDispose((ref) {
  return ref.watch(labRepositoryProvider).auxiliares();
});

final reservationDetailProvider = FutureProvider.autoDispose.family<Reservation, int>((ref, id) {
  return ref.watch(labRepositoryProvider).show(id);
});
