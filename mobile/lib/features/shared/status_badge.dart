import 'package:flutter/material.dart';

Color statusColorFromName(String name) {
  switch (name) {
    case 'blue':
      return Colors.blue;
    case 'yellow':
      return Colors.amber.shade800;
    case 'orange':
      return Colors.orange;
    case 'purple':
      return Colors.purple;
    case 'green':
      return Colors.green;
    case 'red':
      return Colors.red;
    case 'gray':
    default:
      return Colors.grey;
  }
}

class StatusBadge extends StatelessWidget {
  final String label;
  final String colorName;

  const StatusBadge({super.key, required this.label, required this.colorName});

  @override
  Widget build(BuildContext context) {
    final color = statusColorFromName(colorName);
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: color.withValues(alpha: 0.12),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: color.withValues(alpha: 0.4)),
      ),
      child: Text(
        label,
        style: TextStyle(color: color, fontSize: 12, fontWeight: FontWeight.w600),
      ),
    );
  }
}
