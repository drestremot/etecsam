import 'package:intl/intl.dart';

String formatShortTime(String? time) {
  if (time == null || time.isEmpty) return '';
  return time.length >= 5 ? time.substring(0, 5) : time;
}

String formatBrDate(String isoDate) {
  try {
    final date = DateTime.parse(isoDate);
    return DateFormat('dd/MM/yyyy').format(date);
  } catch (_) {
    return isoDate;
  }
}

String formatBrDateWeekday(String isoDate) {
  try {
    final date = DateTime.parse(isoDate);
    return DateFormat("EEEE, dd/MM/yyyy", 'pt_BR').format(date);
  } catch (_) {
    return isoDate;
  }
}
