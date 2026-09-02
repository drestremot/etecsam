// lib/models/api_models.dart

// ============================================================================
// Authentication Models
// ============================================================================

class LoginRequest {
  final String email;
  final String password;

  LoginRequest({
    required this.email,
    required this.password,
  });

  Map<String, dynamic> toJson() => {
    'email': email,
    'password': password,
  };
}

class LoginResponse {
  final String token;
  final User user;

  LoginResponse({
    required this.token,
    required this.user,
  });

  factory LoginResponse.fromJson(Map<String, dynamic> json) {
    return LoginResponse(
      token: json['token'] ?? '',
      user: User.fromJson(json['user'] ?? {}),
    );
  }
}

class User {
  final int id;
  final String name;
  final String email;
  final String? registrationNumber;
  final String? role;
  final int? departmentId;
  final int? courseId;
  final String? phone;
  final String? profilePhoto;
  final bool isActive;

  User({
    required this.id,
    required this.name,
    required this.email,
    this.registrationNumber,
    this.role,
    this.departmentId,
    this.courseId,
    this.phone,
    this.profilePhoto,
    required this.isActive,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      registrationNumber: json['registration_number'],
      role: json['role'],
      departmentId: json['department_id'],
      courseId: json['course_id'],
      phone: json['phone'],
      profilePhoto: json['profile_photo'],
      isActive: json['is_active'] ?? true,
    );
  }
}

// ============================================================================
// Dashboard Models
// ============================================================================

class DashboardSummary {
  final int total;
  final int atribuida;
  final int emAndamento;
  final int emExecucao;
  final int devolvida;
  final int concluida;

  DashboardSummary({
    required this.total,
    required this.atribuida,
    required this.emAndamento,
    required this.emExecucao,
    required this.devolvida,
    required this.concluida,
  });

  factory DashboardSummary.fromJson(Map<String, dynamic> json) {
    return DashboardSummary(
      total: json['total'] ?? 0,
      atribuida: json['atribuida'] ?? 0,
      emAndamento: json['em_andamento'] ?? 0,
      emExecucao: json['em_execucao'] ?? 0,
      devolvida: json['devolvida'] ?? 0,
      concluida: json['concluida'] ?? 0,
    );
  }
}

class DashboardResponse {
  final DashboardSummary summary;

  DashboardResponse({required this.summary});

  factory DashboardResponse.fromJson(Map<String, dynamic> json) {
    return DashboardResponse(
      summary: DashboardSummary.fromJson(json['summary'] ?? {}),
    );
  }
}

// ============================================================================
// Task Models
// ============================================================================

class Task {
  final int id;
  final String title;
  final String description;
  final String status;
  final String? priority;
  final DateTime? dueDate;
  final int createdBy;
  final int? assignedTo;
  final int? responsibleId;
  final int? departmentId;
  final int? courseId;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  
  // Detail-only fields
  final TaskUser? creator;
  final TaskUser? responsible;
  final TaskUser? assignee;
  final List<TaskStatusHistory>? history;
  final List<TaskComment>? comments;
  final List<TaskAttachment>? attachments;

  Task({
    required this.id,
    required this.title,
    required this.description,
    required this.status,
    this.priority,
    this.dueDate,
    required this.createdBy,
    this.assignedTo,
    this.responsibleId,
    this.departmentId,
    this.courseId,
    this.createdAt,
    this.updatedAt,
    this.creator,
    this.responsible,
    this.assignee,
    this.history,
    this.comments,
    this.attachments,
  });

  factory Task.fromJson(Map<String, dynamic> json) {
    return Task(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      description: json['description'] ?? '',
      status: json['status'] ?? 'atribuida',
      priority: json['priority'],
      dueDate: json['due_date'] != null ? DateTime.parse(json['due_date']) : null,
      createdBy: json['created_by'] ?? 0,
      assignedTo: json['assigned_to'],
      responsibleId: json['responsible_id'],
      departmentId: json['department_id'],
      courseId: json['course_id'],
      createdAt: json['created_at'] != null ? DateTime.parse(json['created_at']) : null,
      updatedAt: json['updated_at'] != null ? DateTime.parse(json['updated_at']) : null,
      creator: json['creator'] != null ? TaskUser.fromJson(json['creator']) : null,
      responsible: json['responsible'] != null ? TaskUser.fromJson(json['responsible']) : null,
      assignee: json['assignee'] != null ? TaskUser.fromJson(json['assignee']) : null,
      history: json['history'] != null
          ? (json['history'] as List).map((h) => TaskStatusHistory.fromJson(h)).toList()
          : null,
      comments: json['comments'] != null
          ? (json['comments'] as List).map((c) => TaskComment.fromJson(c)).toList()
          : null,
      attachments: json['attachments'] != null
          ? (json['attachments'] as List).map((a) => TaskAttachment.fromJson(a)).toList()
          : null,
    );
  }
}

class TaskUser {
  final int id;
  final String name;
  final String email;

  TaskUser({
    required this.id,
    required this.name,
    required this.email,
  });

  factory TaskUser.fromJson(Map<String, dynamic> json) {
    return TaskUser(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      email: json['email'] ?? '',
    );
  }
}

class TaskStatusHistory {
  final int id;
  final String? fromStatus;
  final String toStatus;
  final String? comment;
  final DateTime? createdAt;

  TaskStatusHistory({
    required this.id,
    this.fromStatus,
    required this.toStatus,
    this.comment,
    this.createdAt,
  });

  factory TaskStatusHistory.fromJson(Map<String, dynamic> json) {
    return TaskStatusHistory(
      id: json['id'] ?? 0,
      fromStatus: json['from_status'],
      toStatus: json['to_status'] ?? '',
      comment: json['comment'],
      createdAt: json['created_at'] != null ? DateTime.parse(json['created_at']) : null,
    );
  }
}

class TaskComment {
  final int id;
  final String message;
  final TaskUser? user;
  final DateTime? createdAt;

  TaskComment({
    required this.id,
    required this.message,
    this.user,
    this.createdAt,
  });

  factory TaskComment.fromJson(Map<String, dynamic> json) {
    return TaskComment(
      id: json['id'] ?? 0,
      message: json['message'] ?? '',
      user: json['user'] != null ? TaskUser.fromJson(json['user']) : null,
      createdAt: json['created_at'] != null ? DateTime.parse(json['created_at']) : null,
    );
  }
}

class TaskAttachment {
  final int id;
  final String fileName;
  final String filePath;
  final String mimeType;
  final String type;
  final DateTime? createdAt;

  TaskAttachment({
    required this.id,
    required this.fileName,
    required this.filePath,
    required this.mimeType,
    required this.type,
    this.createdAt,
  });

  factory TaskAttachment.fromJson(Map<String, dynamic> json) {
    return TaskAttachment(
      id: json['id'] ?? 0,
      fileName: json['file_name'] ?? '',
      filePath: json['file_path'] ?? '',
      mimeType: json['mime_type'] ?? '',
      type: json['type'] ?? 'file',
      createdAt: json['created_at'] != null ? DateTime.parse(json['created_at']) : null,
    );
  }
}

class TaskListResponse {
  final List<Task> tasks;

  TaskListResponse({required this.tasks});

  factory TaskListResponse.fromJson(Map<String, dynamic> json) {
    final taskList = json['tasks'] as List? ?? [];
    return TaskListResponse(
      tasks: taskList.map((t) => Task.fromJson(t as Map<String, dynamic>)).toList(),
    );
  }
}

class TaskDetailResponse {
  final Task task;

  TaskDetailResponse({required this.task});

  factory TaskDetailResponse.fromJson(Map<String, dynamic> json) {
    return TaskDetailResponse(
      task: Task.fromJson(json['task'] ?? {}),
    );
  }
}

class CreateTaskRequest {
  final String title;
  final String description;
  final String? status;
  final String? priority;
  final DateTime? dueDate;
  final int? departmentId;
  final int? courseId;
  final int? assignedTo;
  final int? responsibleId;

  CreateTaskRequest({
    required this.title,
    required this.description,
    this.status,
    this.priority,
    this.dueDate,
    this.departmentId,
    this.courseId,
    this.assignedTo,
    this.responsibleId,
  });

  Map<String, dynamic> toJson() => {
    'title': title,
    'description': description,
    if (status != null) 'status': status,
    if (priority != null) 'priority': priority,
    if (dueDate != null) 'due_date': dueDate!.toIso8601String().split('T')[0],
    if (departmentId != null) 'department_id': departmentId,
    if (courseId != null) 'course_id': courseId,
    if (assignedTo != null) 'assigned_to': assignedTo,
    if (responsibleId != null) 'responsible_id': responsibleId,
  };
}

class UpdateTaskStatusRequest {
  final String status;
  final String? comment;

  UpdateTaskStatusRequest({
    required this.status,
    this.comment,
  });

  Map<String, dynamic> toJson() => {
    'status': status,
    if (comment != null) 'comment': comment,
  };
}

class AddCommentRequest {
  final String message;

  AddCommentRequest({required this.message});

  Map<String, dynamic> toJson() => {
    'message': message,
  };
}

// ============================================================================
// Reports Models
// ============================================================================

class ReportResponse {
  final List<Task> items;

  ReportResponse({required this.items});

  factory ReportResponse.fromJson(Map<String, dynamic> json) {
    final itemList = json['items'] as List? ?? [];
    return ReportResponse(
      items: itemList.map((i) => Task.fromJson(i as Map<String, dynamic>)).toList(),
    );
  }
}
