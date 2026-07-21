import 'dart:io';

import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';

import '../../core/api_client.dart';
import '../../core/lab_repository.dart';
import '../../models/reservation.dart';
import '../auth/auth_controller.dart';
import '../shared/formatters.dart';
import '../shared/status_badge.dart';
import 'history_controller.dart';
import 'reservations_list_controller.dart';
import 'reservations_provider.dart';

class ReservationDetailScreen extends ConsumerStatefulWidget {
  final int reservationId;
  const ReservationDetailScreen({super.key, required this.reservationId});

  @override
  ConsumerState<ReservationDetailScreen> createState() => _ReservationDetailScreenState();
}

class _ReservationDetailScreenState extends ConsumerState<ReservationDetailScreen> {
  bool _busy = false;

  Future<void> _run(Future<ReservationResponse> Function() action) async {
    setState(() => _busy = true);
    try {
      final response = await action();
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(response.message)));
      ref.invalidate(reservationDetailProvider(widget.reservationId));
      ref.invalidate(reservationsListControllerProvider);
      ref.invalidate(historyControllerProvider);
    } on ApiException catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.message), backgroundColor: Colors.red));
    } finally {
      if (mounted) setState(() => _busy = false);
    }
  }

  Future<String?> _askForText(String title, String label) {
    final controller = TextEditingController();
    return showDialog<String>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: Text(title),
        content: TextField(
          controller: controller,
          maxLines: 4,
          autofocus: true,
          decoration: InputDecoration(labelText: label, border: const OutlineInputBorder()),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancelar')),
          FilledButton(onPressed: () => Navigator.pop(ctx, controller.text.trim()), child: const Text('Confirmar')),
        ],
      ),
    );
  }

  Future<void> _uploadPhoto(String type) async {
    final picker = ImagePicker();
    final picked = await picker.pickImage(source: ImageSource.gallery, imageQuality: 85);
    if (picked == null) return;
    await _run(() => ref.read(labRepositoryProvider).uploadImage(widget.reservationId, type, File(picked.path)));
  }

  @override
  Widget build(BuildContext context) {
    final reservationAsync = ref.watch(reservationDetailProvider(widget.reservationId));
    final currentUser = ref.watch(authControllerProvider).user;

    return Scaffold(
      appBar: AppBar(title: const Text('Detalhes da reserva')),
      body: reservationAsync.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (err, _) => Center(child: Text('Erro ao carregar: $err')),
        data: (r) => _buildBody(context, r, currentUser),
      ),
    );
  }

  Widget _buildBody(BuildContext context, Reservation r, dynamic currentUser) {
    final isOwner = currentUser != null && r.user?.id == currentUser.id;
    final isCoordenador = currentUser?.isCoordenador == true;
    final isAuxiliar = currentUser?.isAuxiliar == true;

    final canApprove = isCoordenador && r.status == 'pre_alocada';
    final canValidate = isCoordenador && r.status == 'aguardando_validacao';
    final canStart = isOwner && r.status == 'aprovada';
    final canProfessorObs = isOwner && r.status == 'em_execucao' && r.professorReleasedAt == null;
    final canAuxiliarFinalize = isAuxiliar && r.auxiliarReleasedAt == null &&
        ['aprovada', 'em_execucao', 'aguardando_conferencia'].contains(r.status);
    final canUploadPhoto = isAuxiliar && ['aprovada', 'em_execucao', 'aguardando_conferencia'].contains(r.status);

    return RefreshIndicator(
      onRefresh: () async => ref.invalidate(reservationDetailProvider(widget.reservationId)),
      child: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Expanded(
                child: Text(r.space?.name ?? 'Espaço não definido',
                    style: Theme.of(context).textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold)),
              ),
              StatusBadge(label: r.statusLabel, colorName: r.statusColor),
            ],
          ),
          const SizedBox(height: 4),
          Text('${formatBrDateWeekday(r.reservationDate)} às ${formatShortTime(r.startTime)}'
              '${r.endTime != null ? ' - ${formatShortTime(r.endTime)}' : ''}'),
          const Divider(height: 32),
          _sectionTitle('Professor'),
          Text(r.user?.name ?? '—'),
          const SizedBox(height: 16),
          _sectionTitle('Plano de aula'),
          Text(r.description),
          if (r.materials.isNotEmpty) ...[
            const SizedBox(height: 16),
            _sectionTitle('Materiais solicitados'),
            ...r.materials.map((m) => Padding(
                  padding: const EdgeInsets.only(bottom: 4),
                  child: Text('• ${m.name}${m.pivot != null ? ' (${m.pivot!.quantityRequested}x)' : ''}'),
                )),
          ],
          if (r.obs != null) ...[
            const SizedBox(height: 16),
            _sectionTitle('Observações do professor'),
            Text(r.obs!),
          ],
          if (r.auxiliarObs != null) ...[
            const SizedBox(height: 16),
            _sectionTitle('Observações do auxiliar'),
            Text(r.auxiliarObs!),
          ],
          if (r.coordenadorObs != null) ...[
            const SizedBox(height: 16),
            _sectionTitle('Observações do coordenador'),
            Text(r.coordenadorObs!),
          ],
          if (r.images.isNotEmpty) ...[
            const SizedBox(height: 16),
            _sectionTitle('Fotos'),
            SizedBox(
              height: 100,
              child: ListView(
                scrollDirection: Axis.horizontal,
                children: r.images
                    .map((img) => Padding(
                          padding: const EdgeInsets.only(right: 8),
                          child: ClipRRect(
                            borderRadius: BorderRadius.circular(8),
                            child: Image.network(img.url, width: 100, height: 100, fit: BoxFit.cover),
                          ),
                        ))
                    .toList(),
              ),
            ),
          ],
          const SizedBox(height: 24),
          if (canApprove || canValidate || canStart || canProfessorObs || canAuxiliarFinalize || canUploadPhoto) ...[
            const Divider(),
            const SizedBox(height: 8),
            Text('Ações', style: Theme.of(context).textTheme.titleMedium),
            const SizedBox(height: 12),
          ],
          if (canApprove)
            Row(
              children: [
                Expanded(
                  child: FilledButton.icon(
                    onPressed: _busy ? null : () => _run(() => ref.read(labRepositoryProvider).approve(r.id)),
                    icon: const Icon(Icons.check),
                    label: const Text('Aprovar'),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: _busy ? null : () => _run(() => ref.read(labRepositoryProvider).reject(r.id)),
                    icon: const Icon(Icons.close),
                    label: const Text('Recusar'),
                  ),
                ),
              ],
            ),
          if (canStart)
            _actionButton(
              icon: Icons.play_arrow,
              label: 'Iniciar aula',
              onPressed: () => _run(() => ref.read(labRepositoryProvider).start(r.id)),
            ),
          if (canProfessorObs)
            _actionButton(
              icon: Icons.edit_note,
              label: 'Registrar observações da aula',
              onPressed: () async {
                final text = await _askForText('Observações da aula', 'Descreva como foi a aula');
                if (text != null && text.length >= 10) {
                  await _run(() => ref.read(labRepositoryProvider).professorObs(r.id, text));
                } else if (text != null) {
                  if (!mounted) return;
                  ScaffoldMessenger.of(context)
                      .showSnackBar(const SnackBar(content: Text('Escreva ao menos 10 caracteres.')));
                }
              },
            ),
          if (canUploadPhoto)
            Row(
              children: [
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: _busy ? null : () => _uploadPhoto('delivery'),
                    icon: const Icon(Icons.upload),
                    label: const Text('Foto de entrega'),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: _busy ? null : () => _uploadPhoto('return'),
                    icon: const Icon(Icons.assignment_return),
                    label: const Text('Foto de devolução'),
                  ),
                ),
              ],
            ),
          if (canAuxiliarFinalize)
            _actionButton(
              icon: Icons.fact_check_outlined,
              label: 'Registrar conferência',
              onPressed: () async {
                final text = await _askForText('Conferência do auxiliar', 'Observações da conferência');
                if (text != null && text.length >= 5) {
                  await _run(() => ref.read(labRepositoryProvider).auxiliarFinalize(r.id, text));
                } else if (text != null) {
                  if (!mounted) return;
                  ScaffoldMessenger.of(context)
                      .showSnackBar(const SnackBar(content: Text('Escreva ao menos 5 caracteres.')));
                }
              },
            ),
          if (canValidate)
            _actionButton(
              icon: Icons.verified_outlined,
              label: 'Validar e arquivar',
              onPressed: () async {
                final text = await _askForText('Validação final', 'Observações do coordenador (opcional)');
                if (text != null) {
                  await _run(() => ref.read(labRepositoryProvider).validateActivity(r.id, coordenadorObs: text));
                }
              },
            ),
          if (_busy) const Padding(padding: EdgeInsets.only(top: 16), child: LinearProgressIndicator()),
        ],
      ),
    );
  }

  Widget _sectionTitle(String text) =>
      Text(text, style: const TextStyle(fontWeight: FontWeight.bold, color: Colors.grey));

  Widget _actionButton({required IconData icon, required String label, required VoidCallback onPressed}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: SizedBox(
        width: double.infinity,
        child: FilledButton.icon(
          onPressed: _busy ? null : onPressed,
          icon: Icon(icon),
          label: Text(label),
        ),
      ),
    );
  }
}
