import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter/material.dart';

import '../../core/api_client.dart';
import '../../core/lab_repository.dart';
import '../../models/space.dart';
import '../reservations/reservations_provider.dart';

class SpaceFormScreen extends ConsumerStatefulWidget {
  final Space? space;
  const SpaceFormScreen({super.key, this.space});

  @override
  ConsumerState<SpaceFormScreen> createState() => _SpaceFormScreenState();
}

class _SpaceFormScreenState extends ConsumerState<SpaceFormScreen> {
  final _formKey = GlobalKey<FormState>();
  late final TextEditingController _nameController;
  late final TextEditingController _descriptionController;
  int? _auxiliarId;
  bool _submitting = false;
  String? _error;

  bool get _isEditing => widget.space != null;

  @override
  void initState() {
    super.initState();
    _nameController = TextEditingController(text: widget.space?.name);
    _descriptionController = TextEditingController(text: widget.space?.description);
    _auxiliarId = widget.space?.auxiliar?.id;
  }

  @override
  void dispose() {
    _nameController.dispose();
    _descriptionController.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() {
      _submitting = true;
      _error = null;
    });
    try {
      final repo = ref.read(labRepositoryProvider);
      final response = _isEditing
          ? await repo.updateSpace(
              widget.space!.id,
              name: _nameController.text.trim(),
              description: _descriptionController.text.trim(),
              auxiliarId: _auxiliarId,
            )
          : await repo.createSpace(
              name: _nameController.text.trim(),
              description: _descriptionController.text.trim(),
              auxiliarId: _auxiliarId,
            );
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(response.message)));
      Navigator.of(context).pop(true);
    } on ApiException catch (e) {
      setState(() => _error = e.message);
    } catch (_) {
      setState(() => _error = 'Não foi possível salvar o espaço.');
    } finally {
      if (mounted) setState(() => _submitting = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final auxiliaresAsync = ref.watch(auxiliaresProvider);

    return Scaffold(
      appBar: AppBar(title: Text(_isEditing ? 'Editar espaço' : 'Novo espaço')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              TextFormField(
                controller: _nameController,
                decoration: const InputDecoration(labelText: 'Nome', border: OutlineInputBorder()),
                validator: (v) => (v == null || v.trim().isEmpty) ? 'Informe o nome do espaço' : null,
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _descriptionController,
                maxLines: 3,
                decoration: const InputDecoration(
                  labelText: 'Descrição (opcional)',
                  border: OutlineInputBorder(),
                  alignLabelWithHint: true,
                ),
              ),
              const SizedBox(height: 16),
              auxiliaresAsync.when(
                loading: () => const LinearProgressIndicator(),
                error: (e, _) => Text('Erro ao carregar auxiliares: $e'),
                data: (auxiliares) => DropdownButtonFormField<int>(
                  initialValue: _auxiliarId,
                  decoration: const InputDecoration(labelText: 'Auxiliar responsável (opcional)', border: OutlineInputBorder()),
                  items: [
                    const DropdownMenuItem(value: null, child: Text('Nenhum')),
                    ...auxiliares.map((a) => DropdownMenuItem(value: a.id, child: Text(a.name))),
                  ],
                  onChanged: (v) => setState(() => _auxiliarId = v),
                ),
              ),
              if (_error != null) ...[
                const SizedBox(height: 16),
                Text(_error!, style: const TextStyle(color: Colors.red)),
              ],
              const SizedBox(height: 24),
              FilledButton(
                onPressed: _submitting ? null : _submit,
                style: FilledButton.styleFrom(padding: const EdgeInsets.symmetric(vertical: 16)),
                child: _submitting
                    ? const SizedBox(
                        height: 20,
                        width: 20,
                        child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white),
                      )
                    : const Text('Salvar'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
