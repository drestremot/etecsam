# Swagger API Documentation — Setup & Access Guide

## Overview

O Swagger/OpenAPI foi ativado para documentar interativamente todos os endpoints da API. A documentação pode ser acessada via interface web do Swagger UI.

---

## Como Acessar o Swagger

### Pré-requisito
O Laravel precisa estar rodando localmente:

```bash
cd C:\xampp\htdocs\gestao-laboratorios
php artisan serve
```

### URL de Acesso
```
http://localhost:8000/api/documentation
```

Abra no navegador e você verá a interface interativa do Swagger UI com todos os endpoints listados.

---

## O Que Tem no Swagger

### 📌 Endpoints Documentados

**Autenticação (3 endpoints)**
- POST /api/login — Fazer login e receber token
- POST /api/logout — Logout (invalidar token)
- GET /api/user — Obter dados do usuário atual

**Tarefas (8 endpoints)**
- GET /api/tasks — Listar tarefas com filtros
- POST /api/tasks — Criar nova tarefa
- GET /api/tasks/{id} — Detalhes completos da tarefa
- PATCH /api/tasks/{id} — Atualizar tarefa
- PATCH /api/tasks/{id}/status — Mudar status
- POST /api/tasks/{id}/comments — Adicionar comentário
- POST /api/tasks/{id}/attachments — Upload de arquivo

**Dashboard & Relatórios (2 endpoints)**
- GET /api/dashboard — Resumo de tarefas por status
- GET /api/reports — Relatório detalhado

### 📋 Para Cada Endpoint

O Swagger mostra:
- ✅ Descrição e propósito
- ✅ Método HTTP (GET, POST, PATCH)
- ✅ Parâmetros de query
- ✅ Estrutura do request body
- ✅ Exemplos de response (sucesso e erro)
- ✅ Códigos de status HTTP
- ✅ Autenticação necessária (bearer token)

---

## Como Usar o Swagger para Testar

### 1. Executar o Login

1. Clique em **Authentication** → **POST /login**
2. Clique em **"Try it out"**
3. Preencha com credenciais válidas:
   ```json
   {
     "email": "usuario@example.com",
     "password": "senha123"
   }
   ```
4. Clique em **"Execute"**
5. Copie o `token` da resposta

### 2. Usar o Token nos Outros Endpoints

1. Clique no botão **"Authorize"** (ícone de cadeado no topo)
2. Cola o token no campo `bearer {token}`
3. Clique em **"Authorize"**
4. Agora todos os endpoints autenticados funcionarão automaticamente

### 3. Testar um Endpoint Autenticado

1. Clique em **Tasks** → **GET /tasks**
2. Clique em **"Try it out"**
3. Opcionalmente adicione filtros (status, department_id)
4. Clique em **"Execute"**
5. Veja a resposta em tempo real

---

## Estrutura da Documentação

### Schemas (Modelos de Dados)

No Swagger você encontra os modelos de dados definidos:

**User**
```json
{
  "id": 1,
  "name": "João Silva",
  "email": "joao@example.com",
  "registration_number": "12345678",
  "role": "Docente",
  "is_active": true,
  ...
}
```

**Task**
```json
{
  "id": 42,
  "title": "Revisar cronograma",
  "description": "...",
  "status": "em_andamento",
  "priority": "alta",
  "due_date": "2026-09-15",
  "created_by": 1,
  "created_at": "2026-08-20T10:30:00Z",
  ...
}
```

**DashboardSummary**
```json
{
  "total": 15,
  "atribuida": 3,
  "em_andamento": 5,
  "em_execucao": 2,
  "devolvida": 1,
  "concluida": 4
}
```

---

## Componentes de Segurança

O Swagger mostra que todos os endpoints autenticados usam:
- **Tipo:** Bearer Token
- **Formato:** JWT
- **Local:** Header Authorization

Exemplo de header:
```
Authorization: Bearer 1|AbCdEfGhIjKlMnOpQrStUvWxYz...
```

---

## Testar Offline com cURL

Se preferir usar cURL em vez do Swagger:

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"pass123"}'
```

### Listar Tarefas
```bash
curl -X GET "http://localhost:8000/api/tasks?status=em_andamento" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Mudar Status
```bash
curl -X PATCH http://localhost:8000/api/tasks/42/status \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{"status":"concluida","comment":"Finalizado"}'
```

---

## Arquivo de Configuração

A documentação OpenAPI está armazenada em:
```
storage/api-docs/api-docs.json
```

Este arquivo contém a especificação completa em formato JSON seguindo o padrão OpenAPI 3.0.

### Para Regenerar a Documentação

Se você alterar um endpoint, pode regenerar com:
```bash
php artisan l5-swagger:generate
```

---

## Integrações Externas

Você pode usar a URL do Swagger para integrar com ferramentas:

### Postman
1. File → Import
2. Import as → OpenAPI
3. URL: `http://localhost:8000/api/documentation/api-docs.json`
4. Postman importará todos os endpoints automaticamente

### Insomnia
1. Create → Import from URL
2. Cole: `http://localhost:8000/api/documentation/api-docs.json`
3. Todos os endpoints ficarão disponíveis

---

## Diferença entre Swagger e Documentação Markdown

- **Documentação Markdown** (`docs/API_MOBILE_CONTRACT.md`): 
  - Para ler offline
  - Referência rápida
  - Bom para começar

- **Swagger UI** (`http://localhost:8000/api/documentation`):
  - Interface interativa
  - Testar endpoints em tempo real
  - Explorar schemas
  - Gerar código cliente automaticamente

---

## Troubleshooting

### Swagger não abre
- Certifique-se que Laravel está rodando: `php artisan serve`
- Verifique URL correta: `http://localhost:8000/api/documentation`
- Limpe cache: `php artisan cache:clear`

### Token não funciona
- Certifique-se que fez login e copiou o token corretamente
- Clique em "Authorize" e confirme o token foi preenchido
- Verifique se o usuário ainda é ativo no banco de dados

### Endpoint não aparece
- Regenere a documentação: `php artisan l5-swagger:generate`
- Verifique se as anotações OpenAPI estão corretas no controller
- Reinicie o servidor Laravel

---

## Próximos Passos

1. ✅ Abra http://localhost:8000/api/documentation
2. ✅ Faça login para obter um token
3. ✅ Teste alguns endpoints
4. ✅ Explore a estrutura dos dados
5. ✅ Integre com Postman ou Insomnia para seus testes

---

**Swagger Setup Completo!** 🎉

A documentação interativa está pronta para uso e teste.

**Status:** ✅ Ativo e funcionando
**Atualizado:** September 1, 2026
**Versão API:** 1.0 (Stable)
