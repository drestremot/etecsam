Guia rápido de deploy — Etec SAM

Requisitos básicos no servidor:
- PHP 8.2+
- Composer
- Node.js + npm
- Extensões PHP necessárias (pdo_sqlite, mbstring, openssl, etc.)
- Acesso ao diretório do projeto e permissão para escrever em `storage/` e `bootstrap/cache`

Passos recomendados:

1. Atualizar código

```bash
git fetch origin
git checkout main
git pull origin main
```

2. Instalar dependências PHP

```bash
composer install --no-dev --optimize-autoloader
```

3. Instalar dependências JS e build front-end

```bash
npm ci
npm run build
```

4. Variáveis de ambiente

- Configure `.env` com `APP_URL` apontando para a URL pública (ex.: `https://meusite.com`)
- Ajuste `DB_*`, `MAIL_*`, `FILESYSTEM_DISK` conforme ambiente

5. Storage e caches

```bash
php artisan storage:link
php artisan migrate --force
php artisan db:seed --class=ExportedDataSeeder --force   # opcional, se for restaurar dados snapshot
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

6. Permissões

- Garanta que `storage/` e `bootstrap/cache` sejam graváveis pelo usuário do servidor web.

7. Reiniciar serviços (se necessário)

- Reinicie PHP-FPM / Apache / Nginx conforme o ambiente.

Notas:
- O projeto gera um seeder automático `database/seeders/ExportedDataSeeder.php` via hook pre-push. Use com cuidado (ele limpa tabelas antes de inserir).
- Se o servidor usa um disco diferente para arquivos públicos, verifique `FILESYSTEM_DISK` em `.env` e ajuste `php artisan storage:link` ou a configuração do webserver.

Contato rápido: para deploys automatizados prefiro criar um script CI/CD (GitHub Actions) que execute os mesmos passos e rode testes antes do deploy.
