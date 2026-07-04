<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->is_admin) {
            // Usuário logado mas sem is_admin → redireciona para o módulo de laboratórios
            return redirect()->route('lab.dashboard')
                ->with('info', 'Acesse o módulo de laboratórios para gerenciar suas reservas.');
        }

        return $next($request);
    }
}
