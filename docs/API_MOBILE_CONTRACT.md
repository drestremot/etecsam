# API Mobile Contract — Flutter Integration Guide

Base URL: `https://api.lab-management.local/api`

All authenticated endpoints require the `Authorization: Bearer {token}` header.

---

## Authentication

### POST /login
**Public endpoint. No authentication required.**

Request:
```json
{
  "email": "usuario@example.com",
  "password": "senha123"
}
```

Response (200 OK):
```json
{
  "token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz...",
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@example.com",
    "registration_number": "12345678",
    "role": "Docente",
    "department_id": 1,
    "course_id": 2,
    "phone": "11987654321",
    "profile_photo": null,
    "is_active": true
  }
}
```

---

### POST /logout
**Authenticated endpoint.**

Headers:
```
Authorization: Bearer {token}
```

Response (200 OK):
```json
{
  "message": "Logout realizado com sucesso."
}
```

---

## User Information

### GET /user
**Authenticated endpoint. Returns current user data.**

Headers:
```
Authorization: Bearer {token}
```

Response (200 OK):
```json
{
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@example.com",
    "registration_number": "12345678",
    "role": "Docente",
    "department_id": 1,
    "course_id": 2,
    "phone": "11987654321",
    "profile_photo": null,
    "is_active": true
  }
}
```

---

## Dashboard

### GET /dashboard
**Authenticated endpoint. Returns task summary for the current user.**

Query Parameters (all optional):
- `department_id`: Filter by department
- `user_id`: Filter by user (defaults to current user if omitted)

Request:
```
GET /dashboard?department_id=1
```

Headers:
```
Authorization: Bearer {token}
```

Response (200 OK):
```json
{
  "summary": {
    "total": 15,
    "atribuida": 3,
    "em_andamento": 5,
    "em_execucao": 2,
    "devolvida": 1,
    "concluida": 4
  }
}
```

---

## Tasks

### GET /tasks
**Authenticated endpoint. List tasks with optional filters.**

Query Parameters (all optional):
- `status`: Filter by status (atribuida, em_andamento, em_execucao, devolvida, concluida)
- `department_id`: Filter by department
- `user_id`: Filter by user (assigned_to, responsible_id, or created_by)

Request:
```
GET /tasks?status=em_andamento&department_id=1
```

Headers:
```
Authorization: Bearer {token}
```

Response (200 OK):
```json
{
  "tasks": [
    {
      "id": 42,
      "title": "Revisar cronograma de aulas",
      "description": "Validar datas e horários das aulas do semestre",
      "status": "em_andamento",
      "priority": "media",
      "due_date": "2026-09-15",
      "created_by": 1,
      "assigned_to": 3,
      "responsible_id": 2,
      "department_id": 1,
      "course_id": null,
      "created_at": "2026-08-20T10:30:00Z",
      "updated_at": "2026-08-25T14:45:00Z"
    },
    {
      "id": 43,
      "title": "Preparar material de aula",
      "description": "Slides, exercícios e referências bibliográficas",
      "status": "em_andamento",
      "priority": "alta",
      "due_date": "2026-09-10",
      "created_by": 2,
      "assigned_to": 1,
      "responsible_id": 1,
      "department_id": 1,
      "course_id": 5,
      "created_at": "2026-08-22T09:15:00Z",
      "updated_at": "2026-08-26T16:20:00Z"
    }
  ]
}
```

---

### GET /tasks/{id}
**Authenticated endpoint. Get full task detail with history, comments, and attachments.**

Request:
```
GET /tasks/42
```

Headers:
```
Authorization: Bearer {token}
```

Response (200 OK):
```json
{
  "task": {
    "id": 42,
    "title": "Revisar cronograma de aulas",
    "description": "Validar datas e horários das aulas do semestre",
    "status": "em_andamento",
    "priority": "media",
    "due_date": "2026-09-15",
    "created_by": 1,
    "assigned_to": 3,
    "responsible_id": 2,
    "department_id": 1,
    "course_id": null,
    "created_at": "2026-08-20T10:30:00Z",
    "updated_at": "2026-08-25T14:45:00Z",
    "creator": {
      "id": 1,
      "name": "Maria Coordenadora",
      "email": "maria@example.com"
    },
    "responsible": {
      "id": 2,
      "name": "Carlos Professor",
      "email": "carlos@example.com"
    },
    "assignee": {
      "id": 3,
      "name": "Ana Assistente",
      "email": "ana@example.com"
    },
    "history": [
      {
        "id": 1,
        "from_status": null,
        "to_status": "atribuida",
        "comment": "Atividade criada.",
        "created_at": "2026-08-20T10:30:00Z"
      },
      {
        "id": 2,
        "from_status": "atribuida",
        "to_status": "em_andamento",
        "comment": "Iniciada pela responsável",
        "created_at": "2026-08-22T08:00:00Z"
      }
    ],
    "comments": [
      {
        "id": 101,
        "message": "Já revisei as primeiras 5 aulas",
        "user": {
          "id": 2,
          "name": "Carlos Professor"
        },
        "created_at": "2026-08-24T14:20:00Z"
      },
      {
        "id": 102,
        "message": "Perfeito, continuem assim",
        "user": {
          "id": 1,
          "name": "Maria Coordenadora"
        },
        "created_at": "2026-08-24T15:00:00Z"
      }
    ],
    "attachments": [
      {
        "id": 201,
        "file_name": "cronograma_2026.pdf",
        "file_path": "task-attachments/cronograma_2026.pdf",
        "mime_type": "application/pdf",
        "type": "file",
        "created_at": "2026-08-20T10:35:00Z"
      },
      {
        "id": 202,
        "file_name": "layout_aulas.png",
        "file_path": "task-attachments/layout_aulas.png",
        "mime_type": "image/png",
        "type": "image",
        "created_at": "2026-08-21T11:00:00Z"
      }
    ]
  }
}
```

---

### POST /tasks
**Authenticated endpoint. Create a new task.**

Request:
```json
{
  "department_id": 1,
  "course_id": null,
  "assigned_to": 3,
  "responsible_id": 2,
  "title": "Avaliar provas finais",
  "description": "Corrigir e atribuir notas às provas do semestre",
  "status": null,
  "priority": "alta",
  "due_date": "2026-09-30"
}
```

Headers:
```
Authorization: Bearer {token}
Content-Type: application/json
```

Response (201 Created):
```json
{
  "id": 50,
  "title": "Avaliar provas finais",
  "description": "Corrigir e atribuir notas às provas do semestre",
  "status": "atribuida",
  "priority": "alta",
  "due_date": "2026-09-30",
  "created_by": 1,
  "assigned_to": 3,
  "responsible_id": 2,
  "department_id": 1,
  "course_id": null,
  "created_at": "2026-08-27T09:00:00Z",
  "updated_at": "2026-08-27T09:00:00Z"
}
```

---

### PATCH /tasks/{id}
**Authenticated endpoint. Update task fields (except status, use /tasks/{id}/status for status changes).**

Request:
```json
{
  "assigned_to": 4,
  "priority": "media",
  "due_date": "2026-09-25"
}
```

Headers:
```
Authorization: Bearer {token}
Content-Type: application/json
```

Response (200 OK):
```json
{
  "id": 42,
  "title": "Revisar cronograma de aulas",
  "description": "Validar datas e horários das aulas do semestre",
  "status": "em_andamento",
  "priority": "media",
  "due_date": "2026-09-25",
  "created_by": 1,
  "assigned_to": 4,
  "responsible_id": 2,
  "department_id": 1,
  "course_id": null,
  "created_at": "2026-08-20T10:30:00Z",
  "updated_at": "2026-08-27T10:15:00Z"
}
```

---

### PATCH /tasks/{id}/status
**Authenticated endpoint. Change task status and optionally add a comment.**

Request:
```json
{
  "status": "concluida",
  "comment": "Toda a revisão foi concluída conforme solicitado"
}
```

Headers:
```
Authorization: Bearer {token}
Content-Type: application/json
```

Response (200 OK):
```json
{
  "id": 42,
  "title": "Revisar cronograma de aulas",
  "description": "Validar datas e horários das aulas do semestre",
  "status": "concluida",
  "priority": "media",
  "due_date": "2026-09-15",
  "created_by": 1,
  "assigned_to": 3,
  "responsible_id": 2,
  "department_id": 1,
  "course_id": null,
  "created_at": "2026-08-20T10:30:00Z",
  "updated_at": "2026-08-27T16:45:00Z"
}
```

---

### POST /tasks/{id}/comments
**Authenticated endpoint. Add a comment to a task.**

Request:
```json
{
  "message": "Excelente trabalho realizado!"
}
```

Headers:
```
Authorization: Bearer {token}
Content-Type: application/json
```

Response (201 Created):
```json
{
  "id": 103,
  "message": "Excelente trabalho realizado!",
  "user": {
    "id": 1,
    "name": "Maria Coordenadora"
  },
  "created_at": "2026-08-27T17:00:00Z"
}
```

---

### POST /tasks/{id}/attachments
**Authenticated endpoint. Upload a file attachment to a task.**

Request (multipart/form-data):
```
file: <binary file data>
```

Headers:
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

Response (201 Created):
```json
{
  "id": 203,
  "file_name": "resultado_final.xlsx",
  "file_path": "task-attachments/resultado_final.xlsx",
  "mime_type": "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
  "type": "file",
  "created_at": "2026-08-27T17:15:00Z"
}
```

---

## Reports

### GET /reports
**Authenticated endpoint. Get tasks report with filters.**

Query Parameters (all optional):
- `department_id`: Filter by department
- `user_id`: Filter by user
- `start_date`: Filter from date (format: YYYY-MM-DD)
- `end_date`: Filter to date (format: YYYY-MM-DD)

Request:
```
GET /reports?department_id=1&start_date=2026-08-01&end_date=2026-08-31
```

Headers:
```
Authorization: Bearer {token}
```

Response (200 OK):
```json
{
  "items": [
    {
      "id": 40,
      "title": "Preparar edital de seleção",
      "description": "Elaborar o edital para seleção de monitores",
      "status": "concluida",
      "priority": "alta",
      "due_date": "2026-08-15",
      "created_by": 1,
      "assigned_to": 2,
      "responsible_id": 3,
      "department_id": 1,
      "course_id": null,
      "created_at": "2026-08-05T08:00:00Z",
      "updated_at": "2026-08-14T17:30:00Z"
    },
    {
      "id": 42,
      "title": "Revisar cronograma de aulas",
      "description": "Validar datas e horários das aulas do semestre",
      "status": "concluida",
      "priority": "media",
      "due_date": "2026-09-15",
      "created_by": 1,
      "assigned_to": 3,
      "responsible_id": 2,
      "department_id": 1,
      "course_id": null,
      "created_at": "2026-08-20T10:30:00Z",
      "updated_at": "2026-08-27T16:45:00Z"
    }
  ]
}
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "Você não tem autorização para esta ação."
}
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["O campo email é obrigatório."],
    "password": ["O campo password deve ter no mínimo 6 caracteres."]
  }
}
```

### 404 Not Found
```json
{
  "message": "Not Found"
}
```

### 500 Internal Server Error
```json
{
  "message": "Internal Server Error"
}
```

---

## Status Values

Valid task statuses:
- `atribuida` — Task assigned, not started yet
- `em_andamento` — Task in progress
- `em_execucao` — Task being executed
- `devolvida` — Task returned/rejected
- `concluida` — Task completed

---

## Priority Values

Valid task priorities:
- `baixa` — Low priority
- `media` — Medium priority
- `alta` — High priority

---

## Integration Checklist for Flutter

- [ ] Implement login screen with email/password → store token securely
- [ ] Add token to all authenticated request headers
- [ ] Implement dashboard view with summary counts
- [ ] List tasks with filter options (status, department)
- [ ] Show task detail with full history, comments, attachments
- [ ] Allow status changes with optional comments
- [ ] Allow file uploads for task attachments
- [ ] Implement logout functionality (DELETE token)
- [ ] Handle token expiration (401 responses)
- [ ] Add proper error handling for all endpoints
- [ ] Cache task data locally when appropriate

---

Last Updated: 2026-09-01
API Version: 1.0 (Stable)
