import 'package:flutter/material.dart';

import '../../models/user.dart';

class UserAvatar extends StatelessWidget {
  final AppUser? user;
  final double radius;

  const UserAvatar({super.key, required this.user, this.radius = 18});

  @override
  Widget build(BuildContext context) {
    final photoUrl = user?.photoUrl;
    if (photoUrl != null && photoUrl.isNotEmpty) {
      return CircleAvatar(
        radius: radius,
        backgroundImage: NetworkImage(photoUrl),
      );
    }

    final initial = (user?.name.isNotEmpty == true) ? user!.name[0].toUpperCase() : '?';
    return CircleAvatar(
      radius: radius,
      child: Text(initial, style: TextStyle(fontSize: radius * 0.8)),
    );
  }
}
