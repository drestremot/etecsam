import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter/material.dart';

import '../../core/api_client.dart';
import '../../core/lab_repository.dart';
import '../../models/space.dart';
import '../reservations/reservations_provider.dart';
import 'space_form_screen.dart';

class SpacesAdminScreen extends ConsumerWidget {
  const SpacesAdminScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final spacesAsync = ref.watch(spacesProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('Espaços / Laboratórios')),
      body: spacesAsync.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (e, _) => Center(child: Text('Erro ao carregar: $e')),
        data: (spaces) => spaces.isEmpty
            ? const Center(child: Text('Nenhum espaço cadastrado.'))
            : ListView.builder(
                itemCount: spaces.length,
                itemBuilder: (context, i) => _SpaceTile(space: spaces[i]),
              ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () async {
          final saved = await Navigator.of(context).push<bool>(
            MaterialPageRoute(builder: (_) => const SpaceFormScreen()),
          );
          if (saved == true) ref.invalidate(spacesProvider);
        },
        icon: const Icon(Icons.add),
        label: const Text('Novo espaço'),
      ),
    );
  }
}

class _SpaceTile extends ConsumerWidget {
  final Space space;
  const _SpaceTile({required this.space});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return ListTile(
      leading: const CircleAvatar(child: Icon(Icons.meeting_room_outlined)),
      title: Text(space.name),
      subtitle: Text([
        if (space.description != null && space.description!.isNotEmpty) space.description!,
        if (space.auxiliar != null) 'Auxiliar: ${space.auxiliar!.name}',
      ].join(' · ')),
      trailing: PopupMenuButton<String>(
        onSelected: (value) async {
          if (value == 'edit') {
            final saved = await Navigator.of(context).push<bool>(
              MaterialPageRoute(builder: (_) => SpaceFormScreen(space: space)),
            );
            if (saved == true) ref.invalidate(spacesProvider);
          } else if (value == 'delete') {
            final confirmed = await showDialog<bool>(
              context: context,
              builder: (ctx) => AlertDialog(
                title: const Text('Excluir espaço'),
                content: Text('Tem certeza que deseja excluir "${space.name}"?'),
                actions: [
                  TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Cancelar')),
                  FilledButton(
                    style: FilledButton.styleFrom(backgroundColor: Colors.red),
                    onPressed: () => Navigator.pop(ctx, true),
                    child: const Text('Excluir'),
                  ),
                ],
              ),
            );
            if (confirmed == true) {
              try {
                await ref.read(labRepositoryProvider).deleteSpace(space.id);
                ref.invalidate(spacesProvider);
                if (context.mounted) {
                  ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Espaço excluído.')));
                }
              } on ApiException catch (e) {
                if (context.mounted) {
                  ScaffoldMessenger.of(context)
                      .showSnackBar(SnackBar(content: Text(e.message), backgroundColor: Colors.red));
                }
              }
            }
          }
        },
        itemBuilder: (context) => [
          const PopupMenuItem(value: 'edit', child: Text('Editar')),
          const PopupMenuItem(value: 'delete', child: Text('Excluir')),
        ],
      ),
    );
  }
}
