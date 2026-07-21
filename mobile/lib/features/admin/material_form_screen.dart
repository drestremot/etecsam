import 'dart:io';

import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';

import '../../core/api_client.dart';
import '../../core/lab_repository.dart';
import '../../models/material.dart';

class MaterialFormScreen extends ConsumerStatefulWidget {
  final LabMaterial? material;
  const MaterialFormScreen({super.key, this.material});

  @override
  ConsumerState<MaterialFormScreen> createState() => _MaterialFormScreenState();
}

class _MaterialFormScreenState extends ConsumerState<MaterialFormScreen> {
  final _formKey = GlobalKey<FormState>();
  late final TextEditingController _nameController;
  late final TextEditingController _descriptionController;
  late final TextEditingController _stockController;
  late final TextEditingController _unitController;
  late final TextEditingController _patrimonyController;
  File? _newPhoto;
  bool _submitting = false;
  String? _error;

  bool get _isEditing => widget.material != null;

  @override
  void initState() {
    super.initState();
    _nameController = TextEditingController(text: widget.material?.name);
    _descriptionController = TextEditingController(text: widget.material?.description);
    _stockController = TextEditingController(text: widget.material?.stockQuantity.toString() ?? '0');
    _unitController = TextEditingController(text: widget.material?.unit);
    _patrimonyController = TextEditingController();
  }

  @override
  void dispose() {
    _nameController.dispose();
    _descriptionController.dispose();
    _stockController.dispose();
    _unitController.dispose();
    _patrimonyController.dispose();
    super.dispose();
  }

  Future<void> _pickPhoto() async {
    final picked = await ImagePicker().pickImage(source: ImageSource.gallery, imageQuality: 85);
    if (picked != null) setState(() => _newPhoto = File(picked.path));
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
          ? await repo.updateMaterial(
              widget.material!.id,
              name: _nameController.text.trim(),
              description: _descriptionController.text.trim(),
              stockQuantity: int.parse(_stockController.text.trim()),
              unit: _unitController.text.trim().isEmpty ? null : _unitController.text.trim(),
              patrimonyNumber: _patrimonyController.text.trim().isEmpty ? null : _patrimonyController.text.trim(),
              photo: _newPhoto,
            )
          : await repo.createMaterial(
              name: _nameController.text.trim(),
              description: _descriptionController.text.trim(),
              stockQuantity: int.parse(_stockController.text.trim()),
              unit: _unitController.text.trim().isEmpty ? null : _unitController.text.trim(),
              patrimonyNumber: _patrimonyController.text.trim().isEmpty ? null : _patrimonyController.text.trim(),
              photo: _newPhoto,
            );
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(response.message)));
      Navigator.of(context).pop(true);
    } on ApiException catch (e) {
      setState(() => _error = e.message);
    } catch (_) {
      setState(() => _error = 'Não foi possível salvar o material.');
    } finally {
      if (mounted) setState(() => _submitting = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(_isEditing ? 'Editar material' : 'Novo material')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Center(
                child: GestureDetector(
                  onTap: _pickPhoto,
                  child: CircleAvatar(
                    radius: 48,
                    backgroundImage: _newPhoto != null
                        ? FileImage(_newPhoto!)
                        : (widget.material?.photoUrl != null ? NetworkImage(widget.material!.photoUrl!) : null)
                            as ImageProvider?,
                    child: (_newPhoto == null && widget.material?.photoUrl == null)
                        ? const Icon(Icons.add_a_photo_outlined, size: 32)
                        : null,
                  ),
                ),
              ),
              const SizedBox(height: 24),
              TextFormField(
                controller: _nameController,
                decoration: const InputDecoration(labelText: 'Nome', border: OutlineInputBorder()),
                validator: (v) => (v == null || v.trim().isEmpty) ? 'Informe o nome do material' : null,
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
              Row(
                children: [
                  Expanded(
                    child: TextFormField(
                      controller: _stockController,
                      keyboardType: TextInputType.number,
                      decoration: const InputDecoration(labelText: 'Estoque', border: OutlineInputBorder()),
                      validator: (v) {
                        final n = int.tryParse(v ?? '');
                        if (n == null || n < 0) return 'Informe um número válido';
                        return null;
                      },
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: TextFormField(
                      controller: _unitController,
                      decoration: const InputDecoration(labelText: 'Unidade (un, kg...)', border: OutlineInputBorder()),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _patrimonyController,
                decoration: const InputDecoration(labelText: 'Número de patrimônio (opcional)', border: OutlineInputBorder()),
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
