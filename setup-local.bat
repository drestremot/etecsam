@echo off
chcp 65001 > nul
echo ================================================
echo    SETUP LOCAL - Etec SAM (XAMPP)
echo    C:\xampp\htdocs\etecsam
echo ================================================
echo.

REM Verificar se estamos na pasta correta
if not exist "artisan" (
    echo [ERRO] Execute este script na pasta raiz do projeto!
    echo [ERRO] Caminho esperado: C:\xampp\htdocs\etecsam
    pause
    exit /b 1
)

REM ===================================================
REM PASSO 1: Sincronizar com GitHub
REM ===================================================
echo [1/7] Baixando ultima versao do GitHub...
git pull origin main
if errorlevel 1 (
    echo [AVISO] Erro no git pull - continuando...
)

REM ===================================================
REM PASSO 2: Garantir que o banco NAO e rastreado pelo Git
REM ===================================================
echo [2/7] Removendo banco do rastreamento Git (preservando dados)...
git rm --cached database/database.sqlite 2>nul
git rm --cached database/database.sqlite-wal 2>nul
git rm --cached database/database.sqlite-shm 2>nul
echo     Banco protegido - nao sera mais commitado.

REM ===================================================
REM PASSO 3: Configurar o .env local
REM ===================================================
echo [3/7] Configurando arquivo .env...
if not exist ".env" (
    copy .env.example .env
    echo     .env criado a partir do .env.example
) else (
    echo     .env ja existe - mantendo configuracoes atuais
)

REM ===================================================
REM PASSO 4: Instalar dependencias PHP
REM ===================================================
echo [4/7] Instalando dependencias do Composer...
call composer install
if errorlevel 1 (
    echo [ERRO] Falha no composer install
    pause
    exit /b 1
)

REM ===================================================
REM PASSO 5: Gerar chave da aplicacao
REM ===================================================
echo [5/7] Gerando APP_KEY...
REM Verificar se APP_KEY ja esta definida
findstr /C:"APP_KEY=base64" .env > nul 2>&1
if errorlevel 1 (
    php artisan key:generate
    echo     Chave gerada com sucesso!
) else (
    echo     APP_KEY ja configurada - mantendo.
)

REM ===================================================
REM PASSO 6: Configurar banco de dados
REM ===================================================
echo [6/7] Configurando banco de dados...

REM Criar arquivo SQLite se nao existir
if not exist "database\database.sqlite" (
    type nul > database\database.sqlite
    echo     Arquivo database.sqlite criado.
) else (
    echo     database.sqlite ja existe - dados preservados.
)

REM Rodar migrations (so novas, nao apaga dados)
php artisan migrate --force
echo     Migrations executadas.

REM Criar usuario admin se necessario
php artisan db:seed --class=AdminSeeder --force
echo     Admin: admin@etecsam.com.br / Admin@2025

REM ===================================================
REM PASSO 7: Configurar storage (imagens)
REM ===================================================
echo [7/7] Configurando link do storage...
if exist "public\storage" (
    echo     Link public/storage ja existe.
) else (
    php artisan storage:link
    echo     Link criado: public/storage -> storage/app/public
)

REM Limpar caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo ================================================
echo    SETUP CONCLUIDO COM SUCESSO!
echo ================================================
echo.
echo Acesse o projeto em:
echo   http://localhost/etecsam/public
echo.
echo Painel Admin:
echo   http://localhost/etecsam/public/admin
echo   Login: admin@etecsam.com.br
echo   Senha: Admin@2025
echo.
echo IMPORTANTE - O que NAO e sincronizado com Git:
echo   - database/database.sqlite (banco de dados local)
echo   - storage/app/public/ (imagens enviadas)
echo   - .env (configuracoes locais)
echo.
echo Para enviar codigo ao servidor:
echo   git add .
echo   git commit -m "sua mensagem"
echo   git push origin main
echo   (O deploy no servidor acontece automaticamente)

REM ===================================================
REM PASSO EXTRA: Instalar Git Hooks (backup automatico)
REM ===================================================
echo [+] Instalando Git Hooks para protecao do banco...

REM Criar pasta de backups e adicionar .gitkeep
if not exist "database\backups" (
    mkdir "database\backups"
)
if not exist "database\backups\.gitkeep" (
    echo. > "database\backups\.gitkeep"
)

REM Instalar hooks (copiar de scripts/hooks para .git/hooks)
if exist "scripts\hooks\pre-commit" (
    copy /Y "scripts\hooks\pre-commit" ".git\hooks\pre-commit" > nul
    echo     Hook pre-commit instalado!
) else (
    echo     AVISO: scripts/hooks/pre-commit nao encontrado
    echo     Execute: git pull origin main
)

if exist "scripts\hooks\pre-push" (
    copy /Y "scripts\hooks\pre-push" ".git\hooks\pre-push" > nul
    echo     Hook pre-push instalado!
) else (
    echo     AVISO: scripts/hooks/pre-push nao encontrado
    echo     Execute: git pull origin main
)

echo     Hooks instalados com sucesso!
echo     - pre-commit: protege contra commitar o banco
echo     - pre-push: faz backup automatico antes do push

REM Remover banco do rastreamento Git (operacao segura)
echo [+] Garantindo que banco nao seja rastreado pelo Git...
git rm --cached database/database.sqlite 2>nul
git rm --cached database/database.sqlite-wal 2>nul
git rm --cached database/database.sqlite-shm 2>nul
echo     Banco protegido!

echo.
pause
