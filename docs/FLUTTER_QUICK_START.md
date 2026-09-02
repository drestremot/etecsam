# Flutter Integration Guide — Quick Start

## Overview

This is a complete backend API ready for mobile consumption. All endpoints are REST-based JSON APIs protected by Sanctum token authentication.

**API Base URL:** `https://api.lab-management.local/api`

---

## Setup Checklist

### 1. Dependencies
Add these to your `pubspec.yaml`:
```yaml
dependencies:
  http: ^1.1.0
  flutter_secure_storage: ^9.0.0
  provider: ^6.0.0
```

### 2. API Service Setup
1. Copy `flutter_api_service.dart` to `lib/services/`
2. Copy `flutter_api_models.dart` to `lib/models/`
3. Create an `ApiProvider` with Provider package to manage singleton instance

### 3. Secure Token Storage
```dart
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class TokenManager {
  static const _storage = FlutterSecureStorage();
  static const _tokenKey = 'auth_token';

  static Future<void> saveToken(String token) async {
    await _storage.write(key: _tokenKey, value: token);
  }

  static Future<String?> getToken() async {
    return await _storage.read(key: _tokenKey);
  }

  static Future<void> deleteToken() async {
    await _storage.delete(key: _tokenKey);
  }
}
```

### 4. App Initialization
On app startup, restore the token:
```dart
void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  final apiService = ApiService();
  final token = await TokenManager.getToken();
  
  if (token != null) {
    await apiService.restoreToken(token);
  }
  
  runApp(MyApp(apiService: apiService));
}
```

---

## Core Features

### Authentication Flow

**Login Screen:**
```dart
class LoginScreen extends StatefulWidget {
  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;

  Future<void> _handleLogin() async {
    setState(() => _isLoading = true);
    
    try {
      final apiService = Provider.of<ApiService>(context, listen: false);
      final response = await apiService.login(
        email: _emailController.text,
        password: _passwordController.text,
      );
      
      // Store token securely
      await TokenManager.saveToken(response['token']);
      
      // Navigate to home
      if (mounted) {
        Navigator.pushReplacementNamed(context, '/home');
      }
    } on ValidationException catch (e) {
      _showError('Validation error: ${e.message}');
    } on ApiException catch (e) {
      _showError(e.message);
    } finally {
      setState(() => _isLoading = false);
    }
  }

  void _showError(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message)),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Login')),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              controller: _emailController,
              decoration: InputDecoration(labelText: 'Email'),
              keyboardType: TextInputType.emailAddress,
            ),
            SizedBox(height: 16),
            TextField(
              controller: _passwordController,
              decoration: InputDecoration(labelText: 'Password'),
              obscureText: true,
            ),
            SizedBox(height: 24),
            ElevatedButton(
              onPressed: _isLoading ? null : _handleLogin,
              child: _isLoading
                  ? SizedBox(
                      height: 20,
                      width: 20,
                      child: CircularProgressIndicator(),
                    )
                  : Text('Login'),
            ),
          ],
        ),
      ),
    );
  }

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }
}
```

---

### Dashboard Screen

**Show task summary:**
```dart
class DashboardScreen extends StatefulWidget {
  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  late Future<DashboardResponse> _dashboardFuture;

  @override
  void initState() {
    super.initState();
    _dashboardFuture = _loadDashboard();
  }

  Future<DashboardResponse> _loadDashboard() async {
    final apiService = Provider.of<ApiService>(context, listen: false);
    final data = await apiService.getDashboard();
    return DashboardResponse.fromJson(data);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Dashboard')),
      body: FutureBuilder<DashboardResponse>(
        future: _dashboardFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return Center(child: CircularProgressIndicator());
          }
          
          if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          }
          
          final summary = snapshot.data!.summary;
          
          return GridView.count(
            crossAxisCount: 2,
            padding: EdgeInsets.all(16),
            children: [
              _SummaryCard('Total', summary.total.toString()),
              _SummaryCard('Atribuída', summary.atribuida.toString()),
              _SummaryCard('Em Andamento', summary.emAndamento.toString()),
              _SummaryCard('Em Execução', summary.emExecucao.toString()),
              _SummaryCard('Devolvida', summary.devolvida.toString()),
              _SummaryCard('Concluída', summary.concluida.toString()),
            ],
          );
        },
      ),
    );
  }
}

class _SummaryCard extends StatelessWidget {
  final String label;
  final String value;

  const _SummaryCard(this.label, this.value);

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text(value, style: Theme.of(context).textTheme.headlineSmall),
          SizedBox(height: 8),
          Text(label, style: Theme.of(context).textTheme.bodySmall),
        ],
      ),
    );
  }
}
```

---

### Task List Screen

**Display tasks with filters:**
```dart
class TaskListScreen extends StatefulWidget {
  @override
  State<TaskListScreen> createState() => _TaskListScreenState();
}

class _TaskListScreenState extends State<TaskListScreen> {
  String? _selectedStatus;
  late Future<TaskListResponse> _tasksFuture;

  @override
  void initState() {
    super.initState();
    _refreshTasks();
  }

  void _refreshTasks() {
    setState(() {
      _tasksFuture = _loadTasks();
    });
  }

  Future<TaskListResponse> _loadTasks() async {
    final apiService = Provider.of<ApiService>(context, listen: false);
    final data = await apiService.listTasks(status: _selectedStatus);
    return TaskListResponse.fromJson(data);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Tasks')),
      body: Column(
        children: [
          Padding(
            padding: EdgeInsets.all(16),
            child: DropdownButton<String?>(
              value: _selectedStatus,
              isExpanded: true,
              hint: Text('Filter by status'),
              items: [
                DropdownMenuItem(child: Text('All'), value: null),
                DropdownMenuItem(child: Text('Atribuída'), value: 'atribuida'),
                DropdownMenuItem(child: Text('Em Andamento'), value: 'em_andamento'),
                DropdownMenuItem(child: Text('Em Execução'), value: 'em_execucao'),
                DropdownMenuItem(child: Text('Devolvida'), value: 'devolvida'),
                DropdownMenuItem(child: Text('Concluída'), value: 'concluida'),
              ],
              onChanged: (value) => setState(() => _selectedStatus = value),
            ),
          ),
          Expanded(
            child: FutureBuilder<TaskListResponse>(
              future: _tasksFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return Center(child: CircularProgressIndicator());
                }
                
                if (snapshot.hasError) {
                  return Center(child: Text('Error: ${snapshot.error}'));
                }
                
                final tasks = snapshot.data!.tasks;
                
                if (tasks.isEmpty) {
                  return Center(child: Text('No tasks found'));
                }
                
                return RefreshIndicator(
                  onRefresh: (_) async => _refreshTasks(),
                  child: ListView.builder(
                    itemCount: tasks.length,
                    itemBuilder: (context, index) {
                      final task = tasks[index];
                      return ListTile(
                        title: Text(task.title),
                        subtitle: Text(task.description),
                        trailing: _StatusBadge(task.status),
                        onTap: () {
                          Navigator.pushNamed(
                            context,
                            '/task-detail',
                            arguments: task.id,
                          );
                        },
                      );
                    },
                  ),
                );
              },
            ),
          ),
        ],
      ),
    );
  }
}

class _StatusBadge extends StatelessWidget {
  final String status;

  const _StatusBadge(this.status);

  @override
  Widget build(BuildContext context) {
    final color = _statusColor(status);
    return Chip(
      label: Text(status, style: TextStyle(color: Colors.white, fontSize: 12)),
      backgroundColor: color,
    );
  }

  Color _statusColor(String status) {
    switch (status) {
      case 'atribuida':
        return Colors.blue;
      case 'em_andamento':
        return Colors.orange;
      case 'em_execucao':
        return Colors.purple;
      case 'devolvida':
        return Colors.red;
      case 'concluida':
        return Colors.green;
      default:
        return Colors.grey;
    }
  }
}
```

---

### Task Detail Screen

**Show full task with comments and attachments:**
```dart
class TaskDetailScreen extends StatefulWidget {
  final int taskId;

  const TaskDetailScreen({required this.taskId});

  @override
  State<TaskDetailScreen> createState() => _TaskDetailScreenState();
}

class _TaskDetailScreenState extends State<TaskDetailScreen> {
  late Future<TaskDetailResponse> _taskFuture;
  final _commentController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _taskFuture = _loadTask();
  }

  Future<TaskDetailResponse> _loadTask() async {
    final apiService = Provider.of<ApiService>(context, listen: false);
    final data = await apiService.getTask(widget.taskId);
    return TaskDetailResponse.fromJson(data);
  }

  Future<void> _changeStatus(String newStatus) async {
    try {
      final apiService = Provider.of<ApiService>(context, listen: false);
      await apiService.updateTaskStatus(
        widget.taskId,
        status: newStatus,
      );
      setState(() => _taskFuture = _loadTask());
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Status updated')),
      );
    } on ApiException catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: ${e.message}')),
      );
    }
  }

  Future<void> _addComment() async {
    if (_commentController.text.isEmpty) return;
    
    try {
      final apiService = Provider.of<ApiService>(context, listen: false);
      await apiService.addComment(
        widget.taskId,
        message: _commentController.text,
      );
      _commentController.clear();
      setState(() => _taskFuture = _loadTask());
    } on ApiException catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: ${e.message}')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Task Detail')),
      body: FutureBuilder<TaskDetailResponse>(
        future: _taskFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return Center(child: CircularProgressIndicator());
          }
          
          if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          }
          
          final task = snapshot.data!.task;
          
          return ListView(
            padding: EdgeInsets.all(16),
            children: [
              // Title
              Text(
                task.title,
                style: Theme.of(context).textTheme.headlineSmall,
              ),
              SizedBox(height: 8),
              
              // Status and Priority
              Row(
                children: [
                  _StatusBadge(task.status),
                  SizedBox(width: 8),
                  if (task.priority != null)
                    Chip(label: Text(task.priority!)),
                ],
              ),
              SizedBox(height: 16),
              
              // Description
              Text('Description', style: Theme.of(context).textTheme.titleMedium),
              SizedBox(height: 8),
              Text(task.description),
              SizedBox(height: 16),
              
              // Due Date
              if (task.dueDate != null)
                Text('Due: ${task.dueDate}'),
              SizedBox(height: 16),
              
              // Status Change
              ElevatedButton(
                onPressed: () => _showStatusDialog(context, task.status),
                child: Text('Change Status'),
              ),
              SizedBox(height: 24),
              
              // Comments Section
              Text('Comments', style: Theme.of(context).textTheme.titleMedium),
              SizedBox(height: 8),
              if (task.comments != null && task.comments!.isNotEmpty)
                ...task.comments!.map((c) => _CommentTile(c)),
              SizedBox(height: 16),
              
              // Add Comment
              TextField(
                controller: _commentController,
                decoration: InputDecoration(
                  hintText: 'Add a comment...',
                  border: OutlineInputBorder(),
                  suffixIcon: IconButton(
                    icon: Icon(Icons.send),
                    onPressed: _addComment,
                  ),
                ),
                maxLines: null,
              ),
              SizedBox(height: 24),
              
              // Attachments
              if (task.attachments != null && task.attachments!.isNotEmpty)
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('Attachments', style: Theme.of(context).textTheme.titleMedium),
                    SizedBox(height: 8),
                    ...task.attachments!.map((a) => _AttachmentTile(a)),
                  ],
                ),
            ],
          );
        },
      ),
    );
  }

  void _showStatusDialog(BuildContext context, String currentStatus) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Change Status'),
        content: SingleChildScrollView(
          child: Column(
            children: [
              'atribuida', 'em_andamento', 'em_execucao', 'devolvida', 'concluida'
            ].map((status) {
              return ListTile(
                title: Text(status),
                onTap: () {
                  Navigator.pop(context);
                  _changeStatus(status);
                },
              );
            }).toList(),
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _commentController.dispose();
    super.dispose();
  }
}

class _CommentTile extends StatelessWidget {
  final TaskComment comment;

  const _CommentTile(this.comment);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: EdgeInsets.only(bottom: 8),
      child: Card(
        child: Padding(
          padding: EdgeInsets.all(12),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                comment.user?.name ?? 'Anonymous',
                style: Theme.of(context).textTheme.bodySmall,
              ),
              SizedBox(height: 4),
              Text(comment.message),
              SizedBox(height: 4),
              Text(
                comment.createdAt?.toString() ?? '',
                style: Theme.of(context).textTheme.bodySmall,
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _AttachmentTile extends StatelessWidget {
  final TaskAttachment attachment;

  const _AttachmentTile(this.attachment);

  @override
  Widget build(BuildContext context) {
    return ListTile(
      leading: Icon(
        attachment.type == 'image' ? Icons.image : Icons.file_present,
      ),
      title: Text(attachment.fileName),
      onTap: () {
        // TODO: Open or download file
        print('Opening: ${attachment.filePath}');
      },
    );
  }
}
```

---

## Error Handling

Always wrap API calls in try-catch:

```dart
try {
  final response = await apiService.login(email: email, password: password);
  // Handle success
} on UnauthorizedException {
  // Show login error message
  Navigator.pushReplacementNamed(context, '/login');
} on ValidationException catch (e) {
  // Show validation errors
  print('Errors: ${e.errors}');
} on ApiException catch (e) {
  // Show generic error
  ScaffoldMessenger.of(context).showSnackBar(
    SnackBar(content: Text(e.message)),
  );
}
```

---

## Testing Workflow

1. **Test login:** POST /api/login with valid credentials
2. **Test dashboard:** GET /api/dashboard (should return summary counts)
3. **Test task list:** GET /api/tasks?status=em_andamento
4. **Test task detail:** GET /api/tasks/{id}
5. **Test status change:** PATCH /api/tasks/{id}/status
6. **Test comment:** POST /api/tasks/{id}/comments
7. **Test logout:** POST /api/logout

---

## Deployment Notes

- **SSL/TLS:** Always use HTTPS in production (https://api.lab-management.local/api)
- **CORS:** Backend is configured to accept requests from Flutter clients
- **Token Expiration:** Implement refresh token logic if tokens have TTL
- **Rate Limiting:** API may have rate limits; handle 429 responses gracefully
- **Offline Support:** Consider caching task data with Hive or similar

---

## Support

For API issues or changes, contact the backend team.

API Version: 1.0 (Stable)
Last Updated: 2026-09-01
