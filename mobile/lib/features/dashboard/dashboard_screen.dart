import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter/material.dart';

import '../../models/reservation.dart';
import '../reservations/reservation_detail_screen.dart';
import '../shared/reservation_card.dart';
import 'dashboard_provider.dart';

const Map<String, String> _statLabels = {
  'spaces': 'Espaços',
  'materials': 'Materiais',
  'pending': 'Pendentes',
  'active': 'Ativas',
  'aguardando_aprovacao': 'Aguard. aprovação',
  'aguardando_validacao': 'Aguard. validação',
  'ativas': 'Ativas',
  'validadas': 'Validadas',
  'aguardando': 'Aguardando',
  'concluidas': 'Concluídas',
  'total': 'Total',
  'minhas': 'Minhas reservas',
  'pendentes': 'Pendentes',
};

class DashboardScreen extends ConsumerWidget {
  const DashboardScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final dashboardAsync = ref.watch(dashboardProvider);

    return RefreshIndicator(
      onRefresh: () async => ref.refresh(dashboardProvider.future),
      child: dashboardAsync.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (err, _) => ListView(
          children: [
            const SizedBox(height: 120),
            Center(child: Text('Erro ao carregar: $err')),
          ],
        ),
        data: (data) {
          final stats = data['stats'] as Map<String, dynamic>;
          final recent = data['recent'] as List<Reservation>;

          return ListView(
            padding: const EdgeInsets.only(bottom: 24),
            children: [
              Padding(
                padding: const EdgeInsets.all(16),
                child: GridView.count(
                  crossAxisCount: 2,
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  crossAxisSpacing: 12,
                  mainAxisSpacing: 12,
                  childAspectRatio: 1.6,
                  children: stats.entries.map((e) => _StatTile(label: _statLabels[e.key] ?? e.key, value: e.value)).toList(),
                ),
              ),
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Text('Reservas recentes', style: Theme.of(context).textTheme.titleMedium),
              ),
              const SizedBox(height: 8),
              if (recent.isEmpty)
                const Padding(
                  padding: EdgeInsets.all(24),
                  child: Center(child: Text('Nenhuma reserva recente.')),
                )
              else
                ...recent.map((r) => ReservationCard(
                      reservation: r,
                      onTap: () => Navigator.of(context).push(
                        MaterialPageRoute(builder: (_) => ReservationDetailScreen(reservationId: r.id)),
                      ),
                    )),
            ],
          );
        },
      ),
    );
  }
}

class _StatTile extends StatelessWidget {
  final String label;
  final dynamic value;

  const _StatTile({required this.label, required this.value});

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              '$value',
              style: Theme.of(context).textTheme.headlineMedium?.copyWith(
                    fontWeight: FontWeight.bold,
                    color: Theme.of(context).colorScheme.primary,
                  ),
            ),
            const SizedBox(height: 4),
            Text(label, style: TextStyle(color: Colors.grey[700])),
          ],
        ),
      ),
    );
  }
}
