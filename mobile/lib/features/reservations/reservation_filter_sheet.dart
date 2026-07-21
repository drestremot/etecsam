import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

import '../../core/lab_repository.dart';
import 'reservations_list_controller.dart';
import 'reservations_provider.dart';

const Map<String, String> _statusOptions = {
  'pre_alocada': 'Aguardando aprovação',
  'aprovada': 'Aprovada',
  'em_execucao': 'Em execução',
  'aguardando_conferencia': 'Aguardando conferência',
  'aguardando_validacao': 'Aguardando validação',
  'recusada': 'Recusada',
};

Future<void> showReservationFilterSheet(BuildContext context, WidgetRef ref) {
  return showModalBottomSheet(
    context: context,
    isScrollControlled: true,
    builder: (_) => const _ReservationFilterSheet(),
  );
}

class _ReservationFilterSheet extends ConsumerStatefulWidget {
  const _ReservationFilterSheet();

  @override
  ConsumerState<_ReservationFilterSheet> createState() => _ReservationFilterSheetState();
}

class _ReservationFilterSheetState extends ConsumerState<_ReservationFilterSheet> {
  late final TextEditingController _buscaController;
  String? _status;
  int? _spaceId;
  DateTime? _dataInicio;
  DateTime? _dataFim;

  @override
  void initState() {
    super.initState();
    final filters = ref.read(reservationsListControllerProvider).filters;
    _status = filters.status;
    _spaceId = filters.spaceId;
    _dataInicio = filters.dataInicio != null ? DateTime.tryParse(filters.dataInicio!) : null;
    _dataFim = filters.dataFim != null ? DateTime.tryParse(filters.dataFim!) : null;
    _buscaController = TextEditingController(text: filters.busca);
  }

  @override
  void dispose() {
    _buscaController.dispose();
    super.dispose();
  }

  Future<void> _pickDate({required bool isStart}) async {
    final picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2020),
      lastDate: DateTime.now().add(const Duration(days: 730)),
    );
    if (picked == null) return;
    setState(() {
      if (isStart) {
        _dataInicio = picked;
      } else {
        _dataFim = picked;
      }
    });
  }

  void _apply() {
    final filters = ReservationFilters(
      status: _status,
      spaceId: _spaceId,
      dataInicio: _dataInicio != null ? DateFormat('yyyy-MM-dd').format(_dataInicio!) : null,
      dataFim: _dataFim != null ? DateFormat('yyyy-MM-dd').format(_dataFim!) : null,
      busca: _buscaController.text.trim().isEmpty ? null : _buscaController.text.trim(),
    );
    ref.read(reservationsListControllerProvider.notifier).applyFilters(filters);
    Navigator.of(context).pop();
  }

  void _clear() {
    ref.read(reservationsListControllerProvider.notifier).applyFilters(const ReservationFilters());
    Navigator.of(context).pop();
  }

  @override
  Widget build(BuildContext context) {
    final spacesAsync = ref.watch(spacesProvider);

    return Padding(
      padding: EdgeInsets.only(
        left: 16,
        right: 16,
        top: 16,
        bottom: MediaQuery.of(context).viewInsets.bottom + 16,
      ),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Row(
              children: [
                Text('Filtrar reservas', style: Theme.of(context).textTheme.titleLarge),
                const Spacer(),
                IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.of(context).pop()),
              ],
            ),
            const SizedBox(height: 8),
            TextField(
              controller: _buscaController,
              decoration: const InputDecoration(
                labelText: 'Buscar (professor, espaço, descrição)',
                prefixIcon: Icon(Icons.search),
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 16),
            DropdownButtonFormField<String>(
              initialValue: _status,
              decoration: const InputDecoration(labelText: 'Status', border: OutlineInputBorder()),
              items: [
                const DropdownMenuItem(value: null, child: Text('Todos')),
                ..._statusOptions.entries.map((e) => DropdownMenuItem(value: e.key, child: Text(e.value))),
              ],
              onChanged: (v) => setState(() => _status = v),
            ),
            const SizedBox(height: 16),
            spacesAsync.when(
              loading: () => const LinearProgressIndicator(),
              error: (e, _) => const SizedBox.shrink(),
              data: (spaces) => DropdownButtonFormField<int>(
                initialValue: _spaceId,
                decoration: const InputDecoration(labelText: 'Espaço', border: OutlineInputBorder()),
                items: [
                  const DropdownMenuItem(value: null, child: Text('Todos')),
                  ...spaces.map((s) => DropdownMenuItem(value: s.id, child: Text(s.name))),
                ],
                onChanged: (v) => setState(() => _spaceId = v),
              ),
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: () => _pickDate(isStart: true),
                    icon: const Icon(Icons.calendar_today, size: 16),
                    label: Text(_dataInicio == null ? 'De' : DateFormat('dd/MM/yyyy').format(_dataInicio!)),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: () => _pickDate(isStart: false),
                    icon: const Icon(Icons.calendar_today, size: 16),
                    label: Text(_dataFim == null ? 'Até' : DateFormat('dd/MM/yyyy').format(_dataFim!)),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 24),
            Row(
              children: [
                Expanded(
                  child: OutlinedButton(onPressed: _clear, child: const Text('Limpar')),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: FilledButton(onPressed: _apply, child: const Text('Aplicar')),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
