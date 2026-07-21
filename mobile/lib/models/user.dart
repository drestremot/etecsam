class AppUser {
  final int id;
  final String name;
  final String email;
  final String? registrationNumber;
  final bool isAdmin;
  final List<String> roles;

  AppUser({
    required this.id,
    required this.name,
    required this.email,
    this.registrationNumber,
    required this.isAdmin,
    required this.roles,
  });

  factory AppUser.fromJson(Map<String, dynamic> json) {
    return AppUser(
      id: json['id'] as int,
      name: json['name'] as String,
      email: json['email'] as String,
      registrationNumber: json['registration_number'] as String?,
      isAdmin: json['is_admin'] as bool? ?? false,
      roles: (json['roles'] as List<dynamic>? ?? []).map((r) => r.toString()).toList(),
    );
  }

  bool get isCoordenador => roles.contains('Coordenador') || isAdmin;
  bool get isAuxiliar => roles.contains('Auxiliar');
  bool get isProfessor => roles.contains('Professor');
}
