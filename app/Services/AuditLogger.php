<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public static function log(
        string $action,
        string $module,
        ?string $description = null,
        ?Model $auditable = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null
    ): ?AuditLog {
        try {
            $user = $userId ? \App\Models\User::find($userId) : Auth::user();

            $userName = $user ? $user->name : 'Visitante / Sistema';
            $userEmail = $user ? $user->email : null;
            $userRole = null;
            if ($user) {
                $userRole = $user->roles->first()?->name ?? ($user->role ?? ($user->is_admin ? 'Administrador' : 'Usuário'));
            }

            return AuditLog::create([
                'user_id'        => $user?->id,
                'user_name'      => $userName,
                'user_email'     => $userEmail,
                'user_role'      => $userRole,
                'action'         => $action,
                'module'         => $module,
                'auditable_type' => $auditable ? get_class($auditable) : null,
                'auditable_id'   => $auditable ? $auditable->getKey() : null,
                'description'    => $description,
                'old_values'     => $oldValues,
                'new_values'     => $newValues,
                'ip_address'     => Request::ip(),
                'user_agent'     => Request::header('User-Agent'),
                'url'            => Request::fullUrl(),
                'method'         => Request::method(),
                'created_at'     => now(),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao gravar log de auditoria: ' . $e->getMessage());
            return null;
        }
    }
}

