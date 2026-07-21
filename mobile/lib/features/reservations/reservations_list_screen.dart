import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter/material.dart';

import '../../core/lab_repository.dart';
import '../shared/reservation_card.dart';
import 'reservation_create_screen.dart';
import 'reservation_detail_screen.dart';
import 'reservations_list_controller.dart';

class ReservationsListScreen extends ConsumerWidget {
  const ReservationsListScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(reservationsListControllerProvider);
    final controller = ref.read(reservationsListControllerProvider.notifier);

    return Scaffold(
      body: RefreshIndicator(
        onRefresh: controller.refresh,
        child: state.isLoading
            ? const Center(child: CircularProgressIndicator())
            : (state.error != null && state.items.isEmpty && state.pendentes.isEmpty)
                ? ListView(
                    children: [
                      const SizedBox(height: 120),
                      Center(child: Text('Erro ao carregar: ${state.error}')),
                    ],
                  )
                : ListView(
                    padding: const EdgeInsets.only(bottom: 80, top: 8),
                    children: [
                      if (!state.filters.isEmpty)
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
                          child: Row(
                            children: [
                              const Icon(Icons.filter_alt, size: 16, color: Colors.grey),
                              const SizedBox(width: 6),
                              const Text('Filtros ativos', style: TextStyle(color: Colors.grey)),
                              const Spacer(),
                              TextButton(
                                onPressed: () => controller.applyFilters(const ReservationFilters()),
                                child: const Text('Limpar'),
                              ),
                            ],
                          ),
                        ),
                      if (state.pendentes.isNotEmpty) ...[
                        const Padding(
                          padding: EdgeInsets.symmetric(horizontal: 16),
                          child: Text('Aguardando sua ação', style: TextStyle(fontWeight: FontWeight.bold)),
                        ),
                        ...state.pendentes.map((r) => ReservationCard(
                              reservation: r,
                              onTap: () => _openDetail(context, r.id),
                            )),
                        const Divider(height: 24),
                      ],
                      Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 16),
                        child: Text(
                          'Todas as reservas',
                          style: Theme.of(context).textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
                        ),
                      ),
                      if (state.items.isEmpty)
                        const Padding(
                          padding: EdgeInsets.all(24),
                          child: Center(child: Text('Nenhuma reserva encontrada.')),
                        )
                      else
                        ...state.items.map((r) => ReservationCard(
                              reservation: r,
                              onTap: () => _openDetail(context, r.id),
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
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () async {
          final created = await Navigator.of(context).push<bool>(
            MaterialPageRoute(builder: (_) => const ReservationCreateScreen()),
          );
          if (created == true) {
            controller.refresh();
          }
        },
        icon: const Icon(Icons.add),
        label: const Text('Nova reserva'),
      ),
    );
  }

  void _openDetail(BuildContext context, int id) {
    Navigator.of(context).push(
      MaterialPageRoute(builder: (_) => ReservationDetailScreen(reservationId: id)),
    );
  }
}
