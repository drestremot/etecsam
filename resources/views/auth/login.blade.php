<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Painel Admin Etec SAM</title>
    <link rel="icon" type="image/png" href="{{ asset('imagens/logo/etec.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #111827; /* gray-900 igual ao sidebar do admin */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: ui-sans-serif, system-ui, sans-serif;
        }
        .login-card {
            background-color: #1f2937; /* gray-800 */
            border: 1px solid #374151; /* gray-700 */
            border-radius: 1rem;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        }
        .login-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
            gap: 0.75rem;
        }
        .login-logo img {
            height: 64px;
            width: auto;
        }
        .login-logo .titulo {
            color: #f9fafb;
            font-size: 1.125rem;
            font-weight: 700;
            letter-spacing: 0.025em;
        }
        .login-logo .subtitulo {
            color: #6b7280;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .divider {
            border-top: 1px solid #374151;
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #d1d5db;
            margin-bottom: 0.375rem;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.625rem 0.875rem;
            background-color: #111827;
            border: 1px solid #374151;
            border-radius: 0.5rem;
            color: #f9fafb;
            font-size: 0.875rem;
            transition: border-color 0.15s;
            box-sizing: border-box;
        }
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
        .field-group { margin-bottom: 1.25rem; }
        .error-msg {
            color: #f87171;
            font-size: 0.75rem;
            margin-top: 0.375rem;
        }
        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .remember-row input[type="checkbox"] {
            accent-color: #4f46e5;
            width: 1rem;
            height: 1rem;
        }
        .remember-row label {
            margin-bottom: 0;
            color: #9ca3af;
            font-size: 0.875rem;
        }
        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background-color: #4f46e5;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: background-color 0.15s;
            letter-spacing: 0.025em;
        }
        .btn-login:hover { background-color: #4338ca; }
        .alert-error {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            color: #fca5a5;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="login-card">

        <div class="login-logo">
            <img src="{{ asset('imagens/logo/etec.png') }}" alt="Logo Etec SAM">
            <div style="text-align:center">
                <p class="subtitulo">Painel Administrativo</p>
                <p class="titulo">Etec SAM — Andradina</p>
            </div>
        </div>

        <div class="divider"></div>

        {{-- Mensagem de sessão --}}
        @if (session('status'))
            <div class="alert-error" style="background:rgba(79,70,229,0.1);border-color:rgba(79,70,229,0.3);color:#a5b4fc;">
                {{ session('status') }}
            </div>
        @endif

        {{-- Erros de autenticação --}}
        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       required autofocus autocomplete="username"
                       placeholder="admin@etecsam.com.br">
                @error('email')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="field-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password"
                       required autocomplete="current-password"
                       placeholder="••••••••">
                @error('password')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember_me" name="remember">
                <label for="remember_me">Manter conectado</label>
            </div>

            <button type="submit" class="btn-login">
                ENTRAR NO PAINEL
            </button>
        </form>

    </div>
</body>
</html>
