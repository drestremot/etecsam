# Instruções de Deploy no Hostinger

## Resumo das Correções Aplicadas

Nesta versão foram implementadas as seguintes correções relacionadas a imagens e uploads:

1. **Aumento do limite de upload**: Fotos de professores agora suportam até 4 MB (coerente com unidades)
2. **Fallback visual**: Cartões de unidades mostram avatar gerado se a imagem não carregar
3. **Diretório de fotos**: Criada pasta `public/imagens/docentes` para fotos de professores
4. **Validações**: Testes PHPUnit e tinker confirmam funcionamento correto

---

## Pré-requisitos

Antes de fazer o deploy, certifique-se de que o Hostinger oferece:

- ✓ PHP 8.2+ (verificar na config do painel)
- ✓ Suporte a symbolic links (ou permissão para copiar conteúdo de `storage/app/public`)
- ✓ SSH/Terminal ou painel com gerenciador de arquivos
- ✓ Acesso ao PHP CLI (para rodar artisan commands)

---

## Passos de Deploy

### 1. Fazer Pull da Versão Mais Recente

**Via SSH/Terminal:**

```bash
cd /home/seu_usuario/seu_dominio.com
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
```

**Ou via FTP/Painel:**
- Fazer upload dos arquivos modificados (especialmente `app/Http/Controllers/Admin/TeacherController.php`, `resources/views/home.blade.php`)
- Fazer upload da nova pasta `public/imagens/docentes/`

---

### 2. Configuração de Ambiente (.env)

Editar ou verificar no arquivo `.env` do servidor (via SSH ou painel):

```env
APP_URL=https://seu_dominio.com
FILESYSTEM_DISK=public
```

**Importante:** Ajuste `APP_URL` para o domínio correto (sem barra no final).

---

### 3. Criar Symbolic Link para Storage

**Via SSH/Terminal (preferido):**

```bash
php artisan storage:link
```

Se o comando acima retornar erro sobre symlinks não suportados:

**Alternativa (copiar arquivos em vez de symlink):**

```bash
cp -r storage/app/public/* public/storage/
```

Depois, ainda assim rode:

```bash
php artisan storage:link
```

(Pode gerar aviso, mas a cópia já foi feita)

---

### 4. Executar Migrações e Cache

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### 5. Configurar Permissões de Diretórios

**Via SSH:**

```bash
chmod 755 storage bootstrap/cache
chmod -R 775 storage/app/public public/storage
chown -R www-data:www-data storage bootstrap/cache public/storage
```

**Ou via Painel Hostinger:**
- Usar gerenciador de arquivos para definir permissões:
  - `storage/` → 775
  - `bootstrap/cache/` → 775
  - `public/storage/` → 775

---

### 6. Adicionar Fotos de Professores

A pasta `public/imagens/docentes/` agora está criada e pronta para receber as fotos.

**Nomes esperados (conforme seeder):**
- `marissol-cristiane-barbosa-moco-veras.jpg`
- `edilis-michelli-dos-santos-cunha-richard.jpg`
- `paulo-roberto-de-freitas.jpg`
- ... (continue com os nomes do seeder)

**Como enviar:**
- Via FTP, fazer upload dos arquivos JPG/PNG para `public/imagens/docentes/`
- Nomes devem corresponder exatamente aos do seeder (case-sensitive no Linux/Hostinger)

---

### 7. Verificar Configuração de PHP

No **Painel Hostinger** → **Configurações PHP**:

```ini
upload_max_filesize = 8M        (ou maior)
post_max_size = 8M              (ou maior)
memory_limit = 256M             (recomendado)
max_execution_time = 300        (recomendado)
```

Se precisar ajustar, fazer via painel ou `.htaccess`.

---

## Teste de Funcionamento

### Checklist Pós-Deploy

- [ ] Acessar homepage e verificar se cartões de unidades carregam com imagem ou avatar
- [ ] Tentar fazer upload de foto de professor (máximo 4 MB) → deve salvar em `storage/app/public/teachers/`
- [ ] Verificar se foto aparece no formulário de edição do professor
- [ ] Tentar fazer upload de foto de unidade (máximo 4 MB) → deve salvar em `storage/app/public/units/`
- [ ] Acessar painel admin e verificar se lista de professores carrega sem erros

### Comando de Diagnóstico (SSH)

```bash
# Verificar se storage:link funcionou
ls -la public/storage

# Verificar se diretórios estão com permissões corretas
ls -la storage/app/public
ls -la bootstrap/cache

# Verificar se app_url está correto
php artisan tinker
>>> config('app.url')
```

---

## Troubleshooting

### Problema: Imagens não aparecem após upload

**Solução 1:** Verificar se `storage:link` foi criado
```bash
ls -la public/storage
# Deve ser um link simbólico apontando para storage/app/public
```

**Solução 2:** Se symlinks não funcionam, copiar conteúdo
```bash
cp -r storage/app/public/* public/storage/
```

**Solução 3:** Verificar `APP_URL` no `.env`
```bash
grep APP_URL .env
# Deve estar configurado para o domínio correto
```

---

### Problema: Upload falha com limite de tamanho

**Solução:** Aumentar limites no PHP (via painel Hostinger ou `.htaccess`)
```apache
php_value upload_max_filesize 8M
php_value post_max_size 8M
```

---

### Problema: Erro 500 ao fazer upload

**Verificar logs:**
```bash
tail -f storage/logs/laravel.log
```

Procurar por mensagens de erro relacionadas a `Storage::disk('public')->putFile()`.

---

## Rollback

Se algo der errado, o banco de dados pode ser restaurado:

```bash
# Ver backups disponíveis
ls -la ../db_backups_local/

# Restaurar backup (se tiver acesso SSH)
sqlite3 database/database.sqlite < ../db_backups_local/database_YYYYMMDD_HHMMSS.sqlite
```

---

## Suporte e Dúvidas

- Verificar `storage/logs/laravel.log` para erros
- Testar função `photo_url()` via `php artisan tinker`
- Consultar `DEPLOY.md` para instruções gerais (além das específicas de imagem)

---

**Data de criação:** 2026-06-18  
**Versão:** v2026.06.18 e posteriores  
**Compatibilidade:** PHP 8.2+, Laravel 11+
