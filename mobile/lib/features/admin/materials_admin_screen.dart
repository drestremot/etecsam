import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter/material.dart';

import '../../core/api_client.dart';
import '../../core/lab_repository.dart';
import '../../models/material.dart';
import '../reservations/reservations_provider.dart';
import 'material_form_screen.dart';

class MaterialsAdminScreen extends ConsumerWidget {
  const MaterialsAdminScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final materialsAsync = ref.watch(materialsProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('Materiais')),
      body: materialsAsync.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (e, _) => Center(child: Text('Erro ao carregar: $e')),
        data: (materials) => materials.isEmpty
            ? const Center(child: Text('Nenhum material cadastrado.'))
            : ListView.builder(
                itemCount: materials.length,
                itemBuilder: (context, i) => _MaterialTile(material: materials[i]),
              ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () async {
          final saved = await Navigator.of(context).push<bool>(
            MaterialPageRoute(builder: (_) => const MaterialFormScreen()),
          );
          if (saved == true) ref.invalidate(materialsProvider);
        },
        icon: const Icon(Icons.add),
        label: const Text('Novo material'),
      ),
    );
  }
}

class _MaterialTile extends ConsumerWidget {
  final LabMaterial material;
  const _MaterialTile({required this.material});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return ListTile(
      leading: CircleAvatar(
        backgroundImage: material.photoUrl != null ? NetworkImage(material.photoUrl!) : null,
        child: material.photoUrl == null ? const Icon(Icons.inventory_2_outlined) : null,
      ),
      title: Text(material.name),
      subtitle: Text('Estoque: ${material.stockQuantity} ${material.unit ?? ''}'),
      trailing: PopupMenuButton<String>(
        onSelected: (value) async {
          if (value == 'edit') {
            final saved = await Navigator.of(context).push<bool>(
              MaterialPageRoute(builder: (_) => MaterialFormScreen(material: material)),
            );
            if (saved == true) ref.invalidate(materialsProvider);
          } else if (value == 'delete') {
            final confirmed = await showDialog<bool>(
              context: context,
              builder: (ctx) => AlertDialog(
                title: const Text('Excluir material'),
                content: Text('Tem certeza que deseja excluir "${material.name}"?'),
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
                await ref.read(labRepositoryProvider).deleteMaterial(material.id);
                ref.invalidate(materialsProvider);
                if (context.mounted) {
                  ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Material excluído.')));
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
