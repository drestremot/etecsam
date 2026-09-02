<?php

namespace App\Listeners;

use App\Services\AuditLogger;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;

class AuditAuthListener
{
    public function handleLogin(Login $event): void
    {
        if ($event->user) {
            AuditLogger::log(
                action: 'login',
                module: 'Autenticação',
                description: "O usuário '{$event->user->name}' acessou o sistema com sucesso.",
                auditable: $event->user,
                userId: $event->user->id
            );
        }
    }

    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            AuditLogger::log(
                action: 'logout',
                module: 'Autenticação',
                description: "O usuário '{$event->user->name}' encerrou a sessão no sistema.",
                auditable: $event->user,
                userId: $event->user->id
            );
        }
    }

    public function subscribe($events): array
    {
        return [
            Login::class  => 'handleLogin',
            Logout::class => 'handleLogout',
        ];
    }
}

