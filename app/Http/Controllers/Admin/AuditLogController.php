<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderByDesc('id');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhere('user_name', 'like', "%{$q}%")
                    ->orWhere('user_email', 'like', "%{$q}%")
                    ->orWhere('ip_address', 'like', "%{$q}%")
                    ->orWhere('auditable_id', 'like', "%{$q}%");
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total'      => AuditLog::count(),
            'logins_today' => AuditLog::where('action', 'login')->whereDate('created_at', today())->count(),
            'updates'    => AuditLog::where('action', 'updated')->count(),
            'deletes'    => AuditLog::where('action', 'deleted')->count(),
            'approvals'  => AuditLog::where('action', 'approved')->count(),
        ];

        $perPage = $request->input('per_page', 25);
        if ($perPage === 'all') {
            $perPage = max(1, $query->count());
        } else {
            $perPage = in_array((int)$perPage, [10, 25, 50, 100]) ? (int)$perPage : 25;
        }

        $logs = $query->paginate($perPage)->withQueryString();

        $modules = AuditLog::select('module')->distinct()->orderBy('module')->pluck('module');
        $actions = AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $users   = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.audit.index', compact('logs', 'stats', 'modules', 'actions', 'users'));
    }

    public function show(AuditLog $auditLog)
    {
        return response()->json([
            'id'             => $auditLog->id,
            'action'         => $auditLog->action,
            'action_label'   => $auditLog->getActionLabel(),
            'badge_class'    => $auditLog->getActionBadgeClass(),
            'module'         => $auditLog->module,
            'module_icon'    => $auditLog->getModuleIcon(),
            'user_name'      => $auditLog->user_name ?? ($auditLog->user?->name ?? 'Sistema'),
            'user_email'     => $auditLog->user_email ?? ($auditLog->user?->email ?? '—'),
            'user_role'      => $auditLog->user_role ?? 'Usuário',
            'description'    => $auditLog->description,
            'old_values'     => $auditLog->old_values,
            'new_values'     => $auditLog->new_values,
            'ip_address'     => $auditLog->ip_address,
            'user_agent'     => $auditLog->user_agent,
            'url'            => $auditLog->url,
            'method'         => $auditLog->method,
            'created_at'     => $auditLog->created_at ? $auditLog->created_at->format('d/m/Y H:i:s') : '—',
            'time_ago'       => $auditLog->created_at ? $auditLog->created_at->diffForHumans() : '—',
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $query = AuditLog::orderByDesc('id');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhere('user_name', 'like', "%{$q}%")
                    ->orWhere('user_email', 'like', "%{$q}%")
                    ->orWhere('ip_address', 'like', "%{$q}%");
            });
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $filename = 'auditoria_sistema_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($handle, [
                'ID',
                'Data / Hora',
                'Ação',
                'Módulo',
                'Usuário',
                'E-mail',
                'Perfil',
                'Descrição do Evento',
                'Endereço IP',
                'Navegador / Dispositivo',
                'URL'
            ], ';');

            $query->chunk(500, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->id,
                        $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '',
                        $log->action,
                        $log->module,
                        $log->user_name,
                        $log->user_email,
                        $log->user_role,
                        $log->description,
                        $log->ip_address,
                        $log->user_agent,
                        $log->url,
                    ], ';');
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}

