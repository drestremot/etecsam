<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            $exemptRoutes = [
                'password.change',
                'password.change.update',
                'logout',
            ];

            $currentRoute = $request->route()?->getName();

            if (!in_array($currentRoute, $exemptRoutes) && !$request->is('alterar-senha*') && !$request->is('logout')) {
                return redirect()->route('password.change')
                    ->with('warning', 'Por motivos de segurança, como este é o seu primeiro acesso (ou sua senha é temporária), por favor defina uma nova senha para continuar no sistema.');
            }
        }

        return $next($request);
    }
}

