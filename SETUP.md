# Setup do Site Etec SAM

## Pré-requisitos
- XAMPP com MySQL e Apache rodando
- PHP 8.2+ no PATH

## Passos para iniciar

### 1. Iniciar XAMPP
Abra o painel do XAMPP e inicie **Apache** e **MySQL**.

### 2. Criar o banco de dados
Acesse `http://localhost/phpmyadmin` e crie um banco chamado `etecsam`  
(Charset: **utf8mb4_unicode_ci**)  
Ou execute no terminal:
```
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS etecsam CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 3. Abrir terminal na pasta do projeto
```
cd C:\xampp\htdocs\etecsam
```

### 4. Rodar as migrations e seeders
```
php artisan migrate:fresh --seed
```

### 5. Criar link do storage
```
php artisan storage:link
```

### 6. Compilar assets (se necessário)
```
npm run build
```

### 7. Acessar o sistema
- **Site público:** http://localhost/etecsam/public  
- **Login admin:** http://localhost/etecsam/public/login  
  - Email: `admin@etecsam.sp.gov.br`  
  - Senha: `Etec@2026!`  
- **Painel admin:** http://localhost/etecsam/public/admin  

> **Altere a senha** assim que fizer o primeiro login!

## Configuração do Apache (virtual host) — opcional
Para acessar via `http://etecsam.local`, configure um virtual host no Apache.
