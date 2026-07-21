import 'package:flutter/material.dart';

import '../../models/reservation.dart';
import 'formatters.dart';
import 'status_badge.dart';

class ReservationCard extends StatelessWidget {
  final Reservation reservation;
  final VoidCallback onTap;

  const ReservationCard({super.key, required this.reservation, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(14),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Expanded(
                    child: Text(
                      reservation.space?.name ?? 'Espaço não definido',
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                  StatusBadge(label: reservation.statusLabel, colorName: reservation.statusColor),
                ],
              ),
              const SizedBox(height: 6),
              Row(
                children: [
                  Icon(Icons.calendar_today_outlined, size: 14, color: Colors.grey[600]),
                  const SizedBox(width: 4),
                  Text(formatBrDate(reservation.reservationDate), style: TextStyle(color: Colors.grey[700])),
                  const SizedBox(width: 12),
                  Icon(Icons.access_time, size: 14, color: Colors.grey[600]),
                  const SizedBox(width: 4),
                  Text(formatShortTime(reservation.startTime), style: TextStyle(color: Colors.grey[700])),
                ],
              ),
              if (reservation.user != null) ...[
                const SizedBox(height: 4),
                Row(
                  children: [
                    Icon(Icons.person_outline, size: 14, color: Colors.grey[600]),
                    const SizedBox(width: 4),
                    Text(reservation.user!.name, style: TextStyle(color: Colors.grey[700])),
                  ],
                ),
              ],
              const SizedBox(height: 6),
              Text(
                reservation.description,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: const TextStyle(fontSize: 13),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
