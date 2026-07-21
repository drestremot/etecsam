class MaterialPivot {
  final int quantityRequested;
  final int? quantityUsed;
  final bool delivered;
  final bool returned;

  MaterialPivot({
    required this.quantityRequested,
    this.quantityUsed,
    required this.delivered,
    required this.returned,
  });

  factory MaterialPivot.fromJson(Map<String, dynamic> json) {
    return MaterialPivot(
      quantityRequested: json['quantity_requested'] as int? ?? 0,
      quantityUsed: json['quantity_used'] as int?,
      delivered: json['delivered'] as bool? ?? false,
      returned: json['returned'] as bool? ?? false,
    );
  }
}

class LabMaterial {
  final int id;
  final String name;
  final String? description;
  final int stockQuantity;
  final String? unit;
  final String? photoUrl;
  final MaterialPivot? pivot;

  LabMaterial({
    required this.id,
    required this.name,
    this.description,
    required this.stockQuantity,
    this.unit,
    this.photoUrl,
    this.pivot,
  });

  factory LabMaterial.fromJson(Map<String, dynamic> json) {
    return LabMaterial(
      id: json['id'] as int,
      name: json['name'] as String,
      description: json['description'] as String?,
      stockQuantity: json['stock_quantity'] as int? ?? 0,
      unit: json['unit'] as String?,
      photoUrl: json['photo_url'] as String?,
      pivot: json['pivot'] != null ? MaterialPivot.fromJson(json['pivot'] as Map<String, dynamic>) : null,
    );
  }
}
