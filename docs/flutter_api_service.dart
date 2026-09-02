// lib/services/api_service.dart

import 'package:http/http.dart' as http;
import 'dart:convert';

class ApiService {
  static const String baseUrl = 'https://api.lab-management.local/api';
  
  String? _authToken;

  // =========================================================================
  // Authentication
  // =========================================================================

  /// POST /login
  /// Authenticate user and store token
  Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login'),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({
          'email': email,
          'password': password,
        }),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        _authToken = data['token'];
        // TODO: Store token in secure storage (e.g., flutter_secure_storage)
        return data;
      } else {
        throw ApiException(
          status: response.statusCode,
          message: _parseErrorMessage(response.body),
        );
      }
    } catch (e) {
      throw ApiException(message: 'Login failed: $e');
    }
  }

  /// POST /logout
  /// Invalidate current token
  Future<void> logout() async {
    try {
      await http.post(
        Uri.parse('$baseUrl/logout'),
        headers: _getHeaders(),
      );
      _authToken = null;
      // TODO: Remove token from secure storage
    } catch (e) {
      print('Logout error: $e');
    }
  }

  /// Restore token from secure storage (call this on app startup)
  Future<void> restoreToken(String token) async {
    _authToken = token;
  }

  // =========================================================================
  // User
  // =========================================================================

  /// GET /user
  /// Get current user info
  Future<Map<String, dynamic>> getCurrentUser() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/user'),
        headers: _getHeaders(),
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else if (response.statusCode == 401) {
        throw UnauthorizedException('Token expired or invalid');
      } else {
        throw ApiException(
          status: response.statusCode,
          message: _parseErrorMessage(response.body),
        );
      }
    } catch (e) {
      throw ApiException(message: 'Failed to get user: $e');
    }
  }

  // =========================================================================
  // Dashboard
  // =========================================================================

  /// GET /dashboard
  /// Get task summary for dashboard
  Future<Map<String, dynamic>> getDashboard({
    int? departmentId,
    int? userId,
  }) async {
    try {
      final params = <String, String>{};
      if (departmentId != null) params['department_id'] = departmentId.toString();
      if (userId != null) params['user_id'] = userId.toString();

      final uri = Uri.parse('$baseUrl/dashboard')
          .replace(queryParameters: params.isNotEmpty ? params : null);

      final response = await http.get(uri, headers: _getHeaders());

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else if (response.statusCode == 401) {
        throw UnauthorizedException('Unauthorized');
      } else {
        throw ApiException(
          status: response.statusCode,
          message: _parseErrorMessage(response.body),
        );
      }
    } catch (e) {
      throw ApiException(message: 'Failed to get dashboard: $e');
    }
  }

  // =========================================================================
  // Tasks
  // =========================================================================

  /// GET /tasks
  /// List tasks with optional filters
  Future<Map<String, dynamic>> listTasks({
    String? status,
    int? departmentId,
    int? userId,
  }) async {
    try {
      final params = <String, String>{};
      if (status != null) params['status'] = status;
      if (departmentId != null) params['department_id'] = departmentId.toString();
      if (userId != null) params['user_id'] = userId.toString();

      final uri = Uri.parse('$baseUrl/tasks')
          .replace(queryParameters: params.isNotEmpty ? params : null);

      final response = await http.get(uri, headers: _getHeaders());

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else if (response.statusCode == 401) {
        throw UnauthorizedException('Unauthorized');
      } else {
        throw ApiException(
          status: response.statusCode,
          message: _parseErrorMessage(response.body),
        );
      }
    } catch (e) {
      throw ApiException(message: 'Failed to list tasks: $e');
    }
  }

  /// GET /tasks/{id}
  /// Get full task detail
  Future<Map<String, dynamic>> getTask(int taskId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/tasks/$taskId'),
        headers: _getHeaders(),
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else if (response.statusCode == 401) {
        throw UnauthorizedException('Unauthorized');
      } else if (response.statusCode == 404) {
        throw TaskNotFoundException('Task not found');
      } else {
        throw ApiException(
          status: response.statusCode,
          message: _parseErrorMessage(response.body),
        );
      }
    } catch (e) {
      throw ApiException(message: 'Failed to get task: $e');
    }
  }

  /// POST /tasks
  /// Create a new task
  Future<Map<String, dynamic>> createTask({
    required String title,
    required String description,
    String? status,
    String? priority,
    DateTime? dueDate,
    int? departmentId,
    int? courseId,
    int? assignedTo,
    int? responsibleId,
  }) async {
    try {
      final body = <String, dynamic>{
        'title': title,
        'description': description,
      };

      if (status != null) body['status'] = status;
      if (priority != null) body['priority'] = priority;
      if (dueDate != null) body['due_date'] = dueDate.toIso8601String().split('T')[0];
      if (departmentId != null) body['department_id'] = departmentId;
      if (courseId != null) body['course_id'] = courseId;
      if (assignedTo != null) body['assigned_to'] = assignedTo;
      if (responsibleId != null) body['responsible_id'] = responsibleId;

      final response = await http.post(
        Uri.parse('$baseUrl/tasks'),
        headers: _getHeaders(),
        body: jsonEncode(body),
      );

      if (response.statusCode == 201) {
        return jsonDecode(response.body);
      } else if (response.statusCode == 401) {
        throw UnauthorizedException('Unauthorized');
      } else if (response.statusCode == 422) {
        throw ValidationException(_parseErrors(response.body));
      } else {
        throw ApiException(
          status: response.statusCode,
          message: _parseErrorMessage(response.body),
        );
      }
    } catch (e) {
      throw ApiException(message: 'Failed to create task: $e');
    }
  }

  /// PATCH /tasks/{id}
  /// Update task (except status)
  Future<Map<String, dynamic>> updateTask(
    int taskId, {
    int? assignedTo,
    String? priority,
    DateTime? dueDate,
  }) async {
    try {
      final body = <String, dynamic>{};

      if (assignedTo != null) body['assigned_to'] = assignedTo;
      if (priority != null) body['priority'] = priority;
      if (dueDate != null) body['due_date'] = dueDate.toIso8601String().split('T')[0];

      final response = await http.patch(
        Uri.parse('$baseUrl/tasks/$taskId'),
        headers: _getHeaders(),
        body: jsonEncode(body),
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else if (response.statusCode == 401) {
        throw UnauthorizedException('Unauthorized');
      } else if (response.statusCode == 404) {
        throw TaskNotFoundException('Task not found');
      } else {
        throw ApiException(
          status: response.statusCode,
          message: _parseErrorMessage(response.body),
        );
      }
    } catch (e) {
      throw ApiException(message: 'Failed to update task: $e');
    }
  }

  /// PATCH /tasks/{id}/status
  /// Change task status
  Future<Map<String, dynamic>> updateTaskStatus(
    int taskId, {
    required String status,
    String? comment,
  }) async {
    try {
      final body = <String, dynamic>{'status': status};
      if (comment != null) body['comment'] = comment;

      final response = await http.patch(
        Uri.parse('$baseUrl/tasks/$taskId/status'),
        headers: _getHeaders(),
        body: jsonEncode(body),
      );

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else if (response.statusCode == 401) {
        throw UnauthorizedException('Unauthorized');
      } else if (response.statusCode == 404) {
        throw TaskNotFoundException('Task not found');
      } else {
        throw ApiException(
          status: response.statusCode,
          message: _parseErrorMessage(response.body),
        );
      }
    } catch (e) {
      throw ApiException(message: 'Failed to update task status: $e');
    }
  }

  /// POST /tasks/{id}/comments
  /// Add a comment to task
  Future<Map<String, dynamic>> addComment(
    int taskId, {
    required String message,
  }) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/tasks/$taskId/comments'),
        headers: _getHeaders(),
        body: jsonEncode({'message': message}),
      );

      if (response.statusCode == 201) {
        return jsonDecode(response.body);
      } else if (response.statusCode == 401) {
        throw UnauthorizedException('Unauthorized');
      } else if (response.statusCode == 404) {
        throw TaskNotFoundException('Task not found');
      } else {
        throw ApiException(
          status: response.statusCode,
          message: _parseErrorMessage(response.body),
        );
      }
    } catch (e) {
      throw ApiException(message: 'Failed to add comment: $e');
    }
  }

  /// POST /tasks/{id}/attachments
  /// Upload a file attachment
  Future<Map<String, dynamic>> uploadAttachment(
    int taskId, {
    required String filePath,
  }) async {
    try {
      final request = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrl/tasks/$taskId/attachments'),
      );

      request.headers.addAll(_getHeaders());
      request.files.add(await http.MultipartFile.fromPath('file', filePath));

      final response = await request.send();
      final body = await response.stream.bytesToString();

      if (response.statusCode == 201) {
        return jsonDecode(body);
      } else if (response.statusCode == 401) {
        throw UnauthorizedException('Unauthorized');
      } else if (response.statusCode == 404) {
        throw TaskNotFoundException('Task not found');
      } else {
        throw ApiException(
          status: response.statusCode,
          message: _parseErrorMessage(body),
        );
      }
    } catch (e) {
      throw ApiException(message: 'Failed to upload attachment: $e');
    }
  }

  // =========================================================================
  // Reports
  // =========================================================================

  /// GET /reports
  /// Get tasks report
  Future<Map<String, dynamic>> getReport({
    int? departmentId,
    int? userId,
    DateTime? startDate,
    DateTime? endDate,
  }) async {
    try {
      final params = <String, String>{};
      if (departmentId != null) params['department_id'] = departmentId.toString();
      if (userId != null) params['user_id'] = userId.toString();
      if (startDate != null) params['start_date'] = startDate.toIso8601String().split('T')[0];
      if (endDate != null) params['end_date'] = endDate.toIso8601String().split('T')[0];

      final uri = Uri.parse('$baseUrl/reports')
          .replace(queryParameters: params.isNotEmpty ? params : null);

      final response = await http.get(uri, headers: _getHeaders());

      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else if (response.statusCode == 401) {
        throw UnauthorizedException('Unauthorized');
      } else {
        throw ApiException(
          status: response.statusCode,
          message: _parseErrorMessage(response.body),
        );
      }
    } catch (e) {
      throw ApiException(message: 'Failed to get report: $e');
    }
  }

  // =========================================================================
  // Helpers
  // =========================================================================

  Map<String, String> _getHeaders() {
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (_authToken != null) 'Authorization': 'Bearer $_authToken',
    };
  }

  String _parseErrorMessage(String responseBody) {
    try {
      final data = jsonDecode(responseBody);
      return data['message'] ?? 'Unknown error';
    } catch (_) {
      return 'Unknown error';
    }
  }

  Map<String, List<String>> _parseErrors(String responseBody) {
    try {
      final data = jsonDecode(responseBody);
      final errors = data['errors'] as Map<String, dynamic>? ?? {};
      return errors.map(
        (key, value) => MapEntry(
          key,
          List<String>.from(value is List ? value : [value.toString()]),
        ),
      );
    } catch (_) {
      return {};
    }
  }
}

// =========================================================================
// Custom Exceptions
// =========================================================================

class ApiException implements Exception {
  final int? status;
  final String message;

  ApiException({this.status, required this.message});

  @override
  String toString() => message;
}

class UnauthorizedException extends ApiException {
  UnauthorizedException(String message) : super(status: 401, message: message);
}

class ValidationException extends ApiException {
  final Map<String, List<String>> errors;

  ValidationException(this.errors)
      : super(
          status: 422,
          message: 'Validation failed: ${errors.entries.map((e) => '${e.key}: ${e.value.join(", ")}').join("; ")}',
        );
}

class TaskNotFoundException extends ApiException {
  TaskNotFoundException(String message) : super(status: 404, message: message);
}

// =========================================================================
// Usage Examples
// =========================================================================

/// Example: Login
/// ```dart
/// final apiService = ApiService();
/// try {
///   final response = await apiService.login(
///     email: 'user@example.com',
///     password: 'password123',
///   );
///   print('Token: ${response['token']}');
/// } on ValidationException catch (e) {
///   print('Validation errors: ${e.errors}');
/// } on ApiException catch (e) {
///   print('Error: ${e.message}');
/// }
/// ```

/// Example: Get Dashboard
/// ```dart
/// try {
///   final dashboard = await apiService.getDashboard();
///   print('Total tasks: ${dashboard['summary']['total']}');
/// } on UnauthorizedException {
///   // Redirect to login
/// } on ApiException catch (e) {
///   print('Error: ${e.message}');
/// }
/// ```

/// Example: List Tasks
/// ```dart
/// try {
///   final response = await apiService.listTasks(status: 'em_andamento');
///   final tasks = TaskListResponse.fromJson(response);
///   tasks.tasks.forEach((task) {
///     print('${task.id}: ${task.title} - ${task.status}');
///   });
/// } on ApiException catch (e) {
///   print('Error: ${e.message}');
/// }
/// ```

/// Example: Change Task Status
/// ```dart
/// try {
///   await apiService.updateTaskStatus(
///     taskId,
///     status: 'concluida',
///     comment: 'Finished as requested',
///   );
///   print('Task status updated');
/// } on ApiException catch (e) {
///   print('Error: ${e.message}');
/// }
/// ```

/// Example: Add Comment
/// ```dart
/// try {
///   await apiService.addComment(
///     taskId,
///     message: 'Great work!',
///   );
///   print('Comment added');
/// } on ApiException catch (e) {
///   print('Error: ${e.message}');
/// }
/// ```

/// Example: Upload Attachment
/// ```dart
/// try {
///   await apiService.uploadAttachment(
///     taskId,
///     filePath: '/path/to/file.pdf',
///   );
///   print('File uploaded');
/// } on ApiException catch (e) {
///   print('Error: ${e.message}');
/// }
/// ```
