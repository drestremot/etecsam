import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

import '../../core/api_client.dart';
import '../../core/lab_repository.dart';
import '../../models/space.dart';
import 'reservations_provider.dart';

class ReservationCreateScreen extends ConsumerStatefulWidget {
  const ReservationCreateScreen({super.key});

  @override
  ConsumerState<ReservationCreateScreen> createState() => _ReservationCreateScreenState();
}

class _ReservationCreateScreenState extends ConsumerState<ReservationCreateScreen> {
  final _formKey = GlobalKey<FormState>();
  final _descriptionController = TextEditingController();
  Space? _selectedSpace;
  DateTime? _selectedDate;
  TimeOfDay? _startTime;
  TimeOfDay? _endTime;
  final Map<int, int> _materialQuantities = {};
  bool _submitting = false;
  String? _error;

  final DateTime _minDate = DateTime.now().add(const Duration(days: 2));

  @override
  void dispose() {
    _descriptionController.dispose();
    super.dispose();
  }

  Future<void> _pickDate() async {
    final picked = await showDatePicker(
      context: context,
      initialDate: _minDate,
      firstDate: _minDate,
      lastDate: DateTime.now().add(const Duration(days: 365)),
    );
    if (picked != null) setState(() => _selectedDate = picked);
  }

  Future<void> _pickStartTime() async {
    final picked = await showTimePicker(context: context, initialTime: const TimeOfDay(hour: 8, minute: 0));
    if (picked != null) setState(() => _startTime = picked);
  }

  Future<void> _pickEndTime() async {
    final picked = await showTimePicker(context: context, initialTime: const TimeOfDay(hour: 9, minute: 0));
    if (picked != null) setState(() => _endTime = picked);
  }

  String _formatTime(TimeOfDay t) => '${t.hour.toString().padLeft(2, '0')}:${t.minute.toString().padLeft(2, '0')}';

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    if (_selectedSpace == null || _selectedDate == null || _startTime == null) {
      setState(() => _error = 'Preencha espaço, data e horário de início.');
      return;
    }

    setState(() {
      _submitting = true;
      _error = null;
    });

    try {
      final response = await ref.read(labRepositoryProvider).create(
            spaceId: _selectedSpace!.id,
            reservationDate: DateFormat('yyyy-MM-dd').format(_selectedDate!),
            startTime: _formatTime(_startTime!),
            endTime: _endTime != null ? _formatTime(_endTime!) : null,
            description: _descriptionController.text.trim(),
            materialQuantities: _materialQuantities,
          );
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(response.message)));
      Navigator.of(context).pop(true);
    } on ApiException catch (e) {
      setState(() => _error = e.message);
    } catch (_) {
      setState(() => _error = 'Não foi possível criar a reserva.');
    } finally {
      if (mounted) setState(() => _submitting = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final spacesAsync = ref.watch(spacesProvider);
    final materialsAsync = ref.watch(materialsProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('Nova reserva')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              spacesAsync.when(
                loading: () => const LinearProgressIndicator(),
                error: (e, _) => Text('Erro ao carregar espaços: $e'),
                data: (spaces) => DropdownButtonFormField<Space>(
                  initialValue: _selectedSpace,
                  decoration: const InputDecoration(labelText: 'Espaço / Laboratório', border: OutlineInputBorder()),
                  items: spaces.map((s) => DropdownMenuItem(value: s, child: Text(s.name))).toList(),
                  onChanged: (v) => setState(() => _selectedSpace = v),
                  validator: (v) => v == null ? 'Selecione um espaço' : null,
                ),
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: OutlinedButton.icon(
                      onPressed: _pickDate,
                      icon: const Icon(Icons.calendar_today),
                      label: Text(_selectedDate == null
                          ? 'Escolher data'
                          : DateFormat('dd/MM/yyyy').format(_selectedDate!)),
                    ),
                  ),
                ],
              ),
              Padding(
                padding: const EdgeInsets.only(top: 4),
                child: Text(
                  'Mínimo 2 dias de antecedência (a partir de ${DateFormat('dd/MM/yyyy').format(_minDate)}).',
                  style: TextStyle(fontSize: 12, color: Colors.grey[600]),
                ),
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: OutlinedButton.icon(
                      onPressed: _pickStartTime,
                      icon: const Icon(Icons.access_time),
                      label: Text(_startTime == null ? 'Início' : _formatTime(_startTime!)),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: OutlinedButton.icon(
                      onPressed: _pickEndTime,
                      icon: const Icon(Icons.access_time_filled),
                      label: Text(_endTime == null ? 'Fim (opcional)' : _formatTime(_endTime!)),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _descriptionController,
                maxLines: 4,
                decoration: const InputDecoration(
                  labelText: 'Plano de aula / descrição',
                  border: OutlineInputBorder(),
                  alignLabelWithHint: true,
                ),
                validator: (v) {
                  if (v == null || v.trim().length < 10) {
                    return 'Descreva o plano de aula com pelo menos 10 caracteres.';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 24),
              Text('Materiais (opcional)', style: Theme.of(context).textTheme.titleMedium),
              const SizedBox(height: 8),
              materialsAsync.when(
                loading: () => const LinearProgressIndicator(),
                error: (e, _) => Text('Erro ao carregar materiais: $e'),
                data: (materials) => Column(
                  children: materials.map((m) {
                    final qty = _materialQuantities[m.id];
                    return CheckboxListTile(
                      value: qty != null,
                      title: Text(m.name),
                      subtitle: Text('Estoque: ${m.stockQuantity} ${m.unit ?? ''}'),
                      secondary: qty != null
                          ? SizedBox(
                              width: 90,
                              child: TextFormField(
                                initialValue: qty.toString(),
                                keyboardType: TextInputType.number,
                                decoration: const InputDecoration(labelText: 'Qtd.', isDense: true),
                                onChanged: (v) {
                                  final parsed = int.tryParse(v);
                                  if (parsed != null && parsed > 0) {
                                    setState(() => _materialQuantities[m.id] = parsed);
                                  }
                                },
                              ),
                            )
                          : null,
                      onChanged: (checked) {
                        setState(() {
                          if (checked == true) {
                            _materialQuantities[m.id] = 1;
                          } else {
                            _materialQuantities.remove(m.id);
                          }
                        });
                      },
                    );
                  }).toList(),
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
                    : const Text('Enviar reserva'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
