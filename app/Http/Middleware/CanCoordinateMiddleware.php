<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanCoordinateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || (!$user->is_admin && !$user->hasAnyRole(['Coordenador', 'Diretor', 'Superintendente', 'admin']))) {
            abort(403, 'Acesso restrito à Coordenação e Direção.');
        }

        return $next($request);
    }
}
