import 'user.dart';

class Space {
  final int id;
  final String name;
  final String? description;
  final AppUser? auxiliar;

  Space({required this.id, required this.name, this.description, this.auxiliar});

  factory Space.fromJson(Map<String, dynamic> json) {
    return Space(
      id: json['id'] as int,
      name: json['name'] as String,
      description: json['description'] as String?,
      auxiliar: json['auxiliar'] != null ? AppUser.fromJson(json['auxiliar'] as Map<String, dynamic>) : null,
    );
  }
}
