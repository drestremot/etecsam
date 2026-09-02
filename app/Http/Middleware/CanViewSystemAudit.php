<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanViewSystemAudit
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->canViewSystemAudit()) {
            return $next($request);
        }

        abort(403, 'Acesso negado. A visualização dos logs de auditoria é restrita ao Superintendente, Diretora de Serviços e Administradores.');
    }
}

