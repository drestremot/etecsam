import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter/material.dart';

import '../shared/reservation_card.dart';
import 'history_controller.dart';
import 'reservation_detail_screen.dart';

class HistoryScreen extends ConsumerWidget {
  const HistoryScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(historyControllerProvider);
    final controller = ref.read(historyControllerProvider.notifier);

    return RefreshIndicator(
      onRefresh: controller.refresh,
      child: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : (state.error != null && state.items.isEmpty)
              ? ListView(
                  children: [
                    const SizedBox(height: 120),
                    Center(child: Text('Erro ao carregar: ${state.error}')),
                  ],
                )
              : state.items.isEmpty
                  ? ListView(
                      children: const [
                        SizedBox(height: 120),
                        Center(child: Text('Nenhuma reserva concluída ainda.')),
                      ],
                    )
                  : ListView(
                      padding: const EdgeInsets.symmetric(vertical: 8),
                      children: [
                        ...state.items.map((r) => ReservationCard(
                              reservation: r,
                              onTap: () => Navigator.of(context).push(
                                MaterialPageRoute(builder: (_) => ReservationDetailScreen(reservationId: r.id)),
                              ),
                            )),
                        if (state.hasMore)
                          Padding(
                            padding: const EdgeInsets.symmetric(vertical: 16),
                            child: Center(
                              child: state.isLoadingMore
                                  ? const CircularProgressIndicator()
                                  : OutlinedButton(
                                      onPressed: controller.loadMore,
                                      child: const Text('Carregar mais'),
                                    ),
                            ),
                          ),
                      ],
                    ),
    );
  }
}
