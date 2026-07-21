import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter/material.dart';

import '../admin/admin_home_screen.dart';
import '../auth/auth_controller.dart';
import '../dashboard/dashboard_screen.dart';
import '../reservations/history_screen.dart';
import '../reservations/reservation_filter_sheet.dart';
import '../reservations/reservations_list_screen.dart';
import 'user_avatar.dart';

class HomeShell extends ConsumerStatefulWidget {
  const HomeShell({super.key});

  @override
  ConsumerState<HomeShell> createState() => _HomeShellState();
}

class _HomeShellState extends ConsumerState<HomeShell> {
  int _index = 0;

  @override
  Widget build(BuildContext context) {
    final user = ref.watch(authControllerProvider).user;
    final isAdmin = user?.isCoordenador == true;

    final titles = ['Início', 'Reservas', 'Histórico', if (isAdmin) 'Admin'];
    final screens = [
      const DashboardScreen(),
      const ReservationsListScreen(),
      const HistoryScreen(),
      if (isAdmin) const AdminHomeScreen(),
    ];
    final destinations = [
      const NavigationDestination(icon: Icon(Icons.dashboard_outlined), selectedIcon: Icon(Icons.dashboard), label: 'Início'),
      const NavigationDestination(icon: Icon(Icons.event_note_outlined), selectedIcon: Icon(Icons.event_note), label: 'Reservas'),
      const NavigationDestination(icon: Icon(Icons.history), label: 'Histórico'),
      if (isAdmin) const NavigationDestination(icon: Icon(Icons.admin_panel_settings_outlined), selectedIcon: Icon(Icons.admin_panel_settings), label: 'Admin'),
    ];

    final safeIndex = _index < screens.length ? _index : 0;

    return Scaffold(
      appBar: AppBar(
        title: Text(titles[safeIndex]),
        actions: [
          if (safeIndex == 1)
            IconButton(
              icon: const Icon(Icons.filter_alt_outlined),
              tooltip: 'Filtrar reservas',
              onPressed: () => showReservationFilterSheet(context, ref),
            ),
          PopupMenuButton<String>(
            onSelected: (value) {
              if (value == 'logout') {
                ref.read(authControllerProvider.notifier).logout();
              }
            },
            itemBuilder: (context) => [
              PopupMenuItem(enabled: false, child: Text(user?.name ?? '')),
              const PopupMenuDivider(),
              const PopupMenuItem(value: 'logout', child: Text('Sair')),
            ],
            icon: UserAvatar(user: user, radius: 16),
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: IndexedStack(index: safeIndex, children: screens),
      bottomNavigationBar: NavigationBar(
        selectedIndex: safeIndex,
        onDestinationSelected: (i) => setState(() => _index = i),
        destinations: destinations,
      ),
    );
  }
}
