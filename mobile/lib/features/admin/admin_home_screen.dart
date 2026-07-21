import 'package:flutter/material.dart';

import 'materials_admin_screen.dart';
import 'spaces_admin_screen.dart';

class AdminHomeScreen extends StatelessWidget {
  const AdminHomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        Card(
          child: ListTile(
            leading: const Icon(Icons.meeting_room_outlined),
            title: const Text('Espaços / Laboratórios'),
            subtitle: const Text('Cadastrar, editar e excluir espaços reserváveis'),
            trailing: const Icon(Icons.chevron_right),
            onTap: () => Navigator.of(context).push(
              MaterialPageRoute(builder: (_) => const SpacesAdminScreen()),
            ),
          ),
        ),
        const SizedBox(height: 12),
        Card(
          child: ListTile(
            leading: const Icon(Icons.inventory_2_outlined),
            title: const Text('Materiais'),
            subtitle: const Text('Cadastrar, editar e excluir materiais de laboratório'),
            trailing: const Icon(Icons.chevron_right),
            onTap: () => Navigator.of(context).push(
              MaterialPageRoute(builder: (_) => const MaterialsAdminScreen()),
            ),
          ),
        ),
      ],
    );
  }
}
