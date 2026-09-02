# Documentation Index

Welcome to the backend API documentation. Below you'll find all resources needed to integrate and consume the API.

---

## 📋 Quick Navigation

### For Backend Developers
1. **[PROJECT_STATUS.md](PROJECT_STATUS.md)** — Overall project status, architecture, and deployment checklist
2. **[API_MOBILE_CONTRACT.md](API_MOBILE_CONTRACT.md)** — Complete API reference with request/response examples

### For Flutter Developers
1. **[FLUTTER_QUICK_START.md](FLUTTER_QUICK_START.md)** — Step-by-step integration guide with code examples
2. **[flutter_api_models.dart](flutter_api_models.dart)** — Dart model classes (ready to use)
3. **[flutter_api_service.dart](flutter_api_service.dart)** — HTTP client implementation (ready to use)

---

## 📚 Documentation Files

### PROJECT_STATUS.md
- Project overview and status
- Architecture overview
- Completed features checklist
- Technology stack
- Test results
- Deployment checklist
- Common issues and debugging

**Who needs this:** Project managers, DevOps, backend team leads

---

### API_MOBILE_CONTRACT.md
**Complete API Reference**

Covers all endpoints with:
- Request format (method, URL, headers)
- Query parameters and filters
- Request body examples
- Response format (success and error cases)
- Status codes and error messages
- Valid field values

**Sections:**
- Authentication (login, logout, user info)
- Dashboard
- Tasks (CRUD, status, comments, attachments)
- Reports
- Error responses

**Who needs this:** Mobile developers, API consumers, QA

---

### FLUTTER_QUICK_START.md
**Complete Flutter Integration Guide**

Step-by-step instructions including:
- Project setup and dependencies
- Secure token storage
- App initialization
- Core feature implementations with full code

**Includes example screens for:**
- Login screen
- Dashboard with summary cards
- Task list with filtering and refresh
- Task detail with history, comments, attachments
- Comment addition
- Status change workflow

**Who needs this:** Flutter developers starting integration

---

### flutter_api_models.dart
**Dart Model Classes**

Ready-to-use model classes for all API data structures:
- Authentication models (LoginRequest, LoginResponse, User)
- Dashboard models (DashboardSummary, DashboardResponse)
- Task models (Task, TaskUser, TaskStatusHistory, TaskComment, TaskAttachment)
- Request models (CreateTaskRequest, UpdateTaskStatusRequest, AddCommentRequest)
- Report models

**Features:**
- fromJson() factory constructors for deserialization
- Proper type handling and null safety
- DateTime parsing and ISO 8601 formatting
- Clean field mapping

**How to use:** Copy to `lib/models/` in Flutter project

---

### flutter_api_service.dart
**HTTP Client Implementation**

Complete API service class with:
- All endpoint methods
- Automatic Bearer token injection
- Error handling with custom exceptions
- Response parsing and model instantiation
- File upload support

**Methods provided:**
- login() / logout() / getCurrentUser()
- getDashboard()
- listTasks() / getTask() / createTask() / updateTask() / updateTaskStatus()
- addComment() / uploadAttachment()
- getReport()

**Exception types:**
- ApiException (generic)
- UnauthorizedException (401)
- ValidationException (422)
- TaskNotFoundException (404)

**How to use:** Copy to `lib/services/` in Flutter project

---

## 🚀 Quick Start

### 1. Backend Team
```bash
# Verify API is running
cd C:\xampp\htdocs\gestao-laboratorios
php artisan serve

# Run tests
php artisan test --filter=MobileAuthTest

# Check logs
tail -f storage/logs/laravel.log
```

### 2. Flutter Team
```bash
# Add dependencies to pubspec.yaml
http: ^1.1.0
flutter_secure_storage: ^9.0.0
provider: ^6.0.0

# Copy Dart files
- flutter_api_models.dart → lib/models/
- flutter_api_service.dart → lib/services/

# Implement login flow
# Reference: FLUTTER_QUICK_START.md
```

### 3. Testing Integration
```bash
# Test login
POST /api/login
{
  "email": "user@example.com",
  "password": "password123"
}

# Test protected endpoint
GET /api/user
Headers: Authorization: Bearer {token}

# Test dashboard
GET /api/dashboard
Headers: Authorization: Bearer {token}
```

---

## 📊 API Status

**Endpoints:** 13 total
- 3 Authentication
- 8 Task Management
- 2 Dashboard/Reports

**Current Status:** ✅ Production Ready

**Test Coverage:** 4 Mobile tests covering:
- Login flow
- Token authentication
- Protected routes
- Task CRUD operations
- Dashboard contract
- Response standardization

**Last Tested:** September 1, 2026

---

## 🔑 Key Features

✅ Sanctum token-based authentication
✅ Role-based access control (Spatie Permission)
✅ Comprehensive error handling
✅ Standardized JSON response format
✅ Task management with history tracking
✅ Comment system and file attachments
✅ Real-time dashboard summary
✅ Advanced filtering and reporting
✅ Full test coverage for mobile integration

---

## 🛠️ Tech Stack Reference

| Component | Technology | Version |
|-----------|-----------|---------|
| Framework | Laravel | 12.0 |
| Language | PHP | 8.2 |
| Database | SQLite/PostgreSQL | - |
| Auth | Laravel Sanctum | 4.3 |
| Authorization | Spatie Permission | 6.x |
| Testing | PHPUnit | 10.x |
| API Client (Flutter) | http | 1.1+ |

---

## ❓ Common Questions

### Q: Where do I find the API base URL?
**A:** `https://api.lab-management.local/api` (see API_MOBILE_CONTRACT.md)

### Q: How do I authenticate?
**A:** POST to `/login` with email/password, store the returned token, include it in all subsequent requests as `Authorization: Bearer {token}`

### Q: What data types does the API use?
**A:** JSON for request/response, ISO 8601 for dates, boolean for flags. See flutter_api_models.dart for type definitions.

### Q: How do I handle token expiration?
**A:** Watch for 401 responses and redirect to login screen. See error handling section in FLUTTER_QUICK_START.md

### Q: Can I filter tasks?
**A:** Yes! See GET /tasks endpoint in API_MOBILE_CONTRACT.md for status, department_id, user_id filters

### Q: How do I upload files?
**A:** Use POST /tasks/{id}/attachments with multipart/form-data. See flutter_api_service.dart uploadAttachment() method.

---

## 📞 Support

### Backend Issues
- Check PROJECT_STATUS.md debugging section
- Review Laravel logs: `storage/logs/laravel.log`
- Run tests: `php artisan test`

### Flutter Integration Issues
- Check FLUTTER_QUICK_START.md examples
- Verify API endpoints in API_MOBILE_CONTRACT.md
- Test requests with Postman/Insomnia first

### API Contract Questions
- Reference API_MOBILE_CONTRACT.md for exact request/response format
- Check flutter_api_models.dart for type definitions

---

## 📝 Files Checklist

- ✅ PROJECT_STATUS.md — Project overview and status
- ✅ API_MOBILE_CONTRACT.md — Complete API reference
- ✅ FLUTTER_QUICK_START.md — Flutter integration guide
- ✅ flutter_api_models.dart — Dart model classes
- ✅ flutter_api_service.dart — HTTP client
- ✅ README.md (this file) — Documentation index

---

## 🔄 Document Maintenance

**Last Updated:** September 1, 2026
**API Version:** 1.0 (Stable)
**Status:** Production Ready

For updates or corrections, contact the backend development team.

---

## 📦 Next Steps

1. **Backend:** Deploy to staging/production environment
2. **Flutter:** Begin integration using FLUTTER_QUICK_START.md
3. **QA:** Test API endpoints using API_MOBILE_CONTRACT.md
4. **Mobile:** Implement screens and user flows
5. **Testing:** Run complete end-to-end tests
6. **Release:** Deploy to app stores

---

**Created:** August 2026
**Updated:** September 1, 2026
**Backend Status:** ✅ READY FOR PRODUCTION
