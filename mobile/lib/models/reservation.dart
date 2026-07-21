import 'material.dart';
import 'reservation_image.dart';
import 'space.dart';
import 'user.dart';

class Reservation {
  final int id;
  final String status;
  final String statusLabel;
  final String statusColor;
  final String reservationDate;
  final String startTime;
  final String? endTime;
  final String description;
  final String? obs;
  final String? auxiliarObs;
  final String? coordenadorObs;
  final String? checklistFile;
  final String? scannedDoc;
  final String? professorReleasedAt;
  final String? auxiliarReleasedAt;
  final String? validatedAt;
  final AppUser? user;
  final Space? space;
  final AppUser? auxiliar;
  final AppUser? coordenador;
  final List<LabMaterial> materials;
  final List<ReservationImage> images;

  Reservation({
    required this.id,
    required this.status,
    required this.statusLabel,
    required this.statusColor,
    required this.reservationDate,
    required this.startTime,
    this.endTime,
    required this.description,
    this.obs,
    this.auxiliarObs,
    this.coordenadorObs,
    this.checklistFile,
    this.scannedDoc,
    this.professorReleasedAt,
    this.auxiliarReleasedAt,
    this.validatedAt,
    this.user,
    this.space,
    this.auxiliar,
    this.coordenador,
    required this.materials,
    required this.images,
  });

  factory Reservation.fromJson(Map<String, dynamic> json) {
    return Reservation(
      id: json['id'] as int,
      status: json['status'] as String,
      statusLabel: json['status_label'] as String? ?? json['status'] as String,
      statusColor: json['status_color'] as String? ?? 'gray',
      reservationDate: json['reservation_date'] as String,
      startTime: json['start_time'] as String,
      endTime: json['end_time'] as String?,
      description: json['description'] as String? ?? '',
      obs: json['obs'] as String?,
      auxiliarObs: json['auxiliar_obs'] as String?,
      coordenadorObs: json['coordenador_obs'] as String?,
      checklistFile: json['checklist_file'] as String?,
      scannedDoc: json['scanned_doc'] as String?,
      professorReleasedAt: json['professor_released_at'] as String?,
      auxiliarReleasedAt: json['auxiliar_released_at'] as String?,
      validatedAt: json['validated_at'] as String?,
      user: json['user'] != null ? AppUser.fromJson(json['user'] as Map<String, dynamic>) : null,
      space: json['space'] != null ? Space.fromJson(json['space'] as Map<String, dynamic>) : null,
      auxiliar: json['auxiliar'] != null ? AppUser.fromJson(json['auxiliar'] as Map<String, dynamic>) : null,
      coordenador: json['coordenador'] != null ? AppUser.fromJson(json['coordenador'] as Map<String, dynamic>) : null,
      materials: (json['materials'] as List<dynamic>? ?? [])
          .map((m) => LabMaterial.fromJson(m as Map<String, dynamic>))
          .toList(),
      images: (json['images'] as List<dynamic>? ?? [])
          .map((i) => ReservationImage.fromJson(i as Map<String, dynamic>))
          .toList(),
    );
  }
}
