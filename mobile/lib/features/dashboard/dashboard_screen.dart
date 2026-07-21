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

/// Verde para reservas em andamento, vermelho para concluídas/validadas,
/// amarelo para o que está aguardando alguma ação, azul para o restante.
Color _statTileColor(String key) {
  if (key.contains('conclu') || key.contains('valida')) return const Color(0xFFE53E3E);
  if (key.contains('ativ') || key == 'minhas' || key == 'active' || key == 'spaces') {
    return const Color(0xFF2D6A4F);
  }
  if (key.contains('aguard') || key.contains('pend')) return const Color(0xFFF5A623);
  return const Color(0xFF3182CE);
}

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
                  childAspectRatio: 1.25,
                  children: stats.entries
                      .map((e) => _StatTile(
                            label: _statLabels[e.key] ?? e.key,
                            value: e.value,
                            color: _statTileColor(e.key),
                          ))
                      .toList(),
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
  final Color color;

  const _StatTile({required this.label, required this.value, required this.color});

  @override
  Widget build(BuildContext context) {
    return Card(
      clipBehavior: Clip.antiAlias,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Container(width: 6, color: color),
          Expanded(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    '$value',
                    style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                          fontWeight: FontWeight.bold,
                          color: color,
                        ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    label,
                    style: TextStyle(color: Colors.grey[700], fontSize: 12.5, height: 1.15),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
