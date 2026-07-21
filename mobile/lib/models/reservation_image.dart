class ReservationImage {
  final int id;
  final String type;
  final String url;

  ReservationImage({required this.id, required this.type, required this.url});

  factory ReservationImage.fromJson(Map<String, dynamic> json) {
    return ReservationImage(
      id: json['id'] as int,
      type: json['type'] as String,
      url: json['url'] as String,
    );
  }
}
