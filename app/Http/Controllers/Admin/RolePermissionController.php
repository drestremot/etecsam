<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogger;
use Database\Seeders\PermissionSeeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('id')->get();
        $permissions = Permission::all();

        // Mapeamento dos módulos com rótulos e descrições em português
        $modules = [
            'Demandas (KanbanTec)' => [
                'icon' => '📋',
                'description' => 'Tarefas internas, chamados e ordens de serviço',
                'permissions' => [
                    'tasks.view'   => ['label' => 'Visualizar Quadro', 'desc' => 'Acesso ao quadro Kanban e visualização de demandas'],
                    'tasks.create' => ['label' => 'Criar Demandas', 'desc' => 'Abertura de novas tarefas e chamados'],
                    'tasks.edit'   => ['label' => 'Editar Demandas', 'desc' => 'Alteração de status, prioridade e responsáveis'],
                    'tasks.delete' => ['label' => 'Excluir Demandas', 'desc' => 'Remoção definitiva de tarefas do quadro'],
                    'tasks.report' => ['label' => 'Relatórios', 'desc' => 'Exportação e relatórios de produtividade'],
                ],
            ],
            'Reservas de Laboratórios' => [
                'icon' => '🔬',
                'description' => 'Aulas práticas, agendamento de salas e equipamentos',
                'permissions' => [
                    'lab.reservations.view'     => ['label' => 'Visualizar Reservas', 'desc' => 'Quadro e mapa de ocupação dos laboratórios'],
                    'lab.reservations.create'   => ['label' => 'Solicitar Reserva', 'desc' => 'Agendamento de aulas práticas'],
                    'lab.reservations.approve'  => ['label' => 'Aprovar / Deferir', 'desc' => 'Validação e autorização pelo Coordenador'],
                    'lab.reservations.finalize' => ['label' => 'Conferência de Aula', 'desc' => 'Checklist e liberação pelos Auxiliares'],
                ],
            ],
            'Atestados Médicos' => [
                'icon' => '🏥',
                'description' => 'Licenças médicas, odontológicas e declarações',
                'permissions' => [
                    'medical.view'   => ['label' => 'Visualizar Atestados', 'desc' => 'Acesso à lista e histórico de atestados'],
                    'medical.create' => ['label' => 'Enviar Atestado', 'desc' => 'Envio de comprovante médico pelo colaborador'],
                    'medical.manage' => ['label' => 'Homologar & Auditar', 'desc' => 'Aprovação institucional pela Diretoria de Serviços'],
                ],
            ],
            'Folgas Previstas em Lei' => [
                'icon' => '⚖️',
                'description' => 'TRE, Doação de Sangue, Concursos e Licenças',
                'permissions' => [
                    'leaves.view'    => ['label' => 'Visualizar Saldo', 'desc' => 'Consulta de extrato de dias concedidos'],
                    'leaves.request' => ['label' => 'Solicitar Folga', 'desc' => 'Pedido de usufruto com antecedência legal'],
                    'leaves.manage'  => ['label' => 'Conceder & Deferir', 'desc' => 'Gestão de créditos e aprovação de pedidos'],
                ],
            ],
            'Van Escolar' => [
                'icon' => '🚐',
                'description' => 'Transporte escolar e visitas técnicas',
                'permissions' => [
                    'van.view'    => ['label' => 'Visualizar Viagens', 'desc' => 'Calendário e viagens agendadas'],
                    'van.create'  => ['label' => 'Solicitar Viagem', 'desc' => 'Pedido de agendamento de veículo'],
                    'van.approve' => ['label' => 'Aprovar Viagem', 'desc' => 'Autorização da saída pela Direção'],
                    'van.manage'  => ['label' => 'Gestão & Veículos', 'desc' => 'Checklist, início/fim de viagem e frota'],
                ],
            ],
            'Cursos & Grade Curricular' => [
                'icon' => '🎓',
                'description' => 'Cursos técnicos, matriz curricular e professores',
                'permissions' => [
                    'courses.view'   => ['label' => 'Visualizar Cursos', 'desc' => 'Consulta de grade e disciplinas'],
                    'courses.manage' => ['label' => 'Gerenciar Grade', 'desc' => 'Criar cursos, disciplinas e associar docentes'],
                ],
            ],
            'Infraestrutura & Inventário' => [
                'icon' => '🏢',
                'description' => 'Salas, laboratórios físicos e estoque de materiais',
                'permissions' => [
                    'spaces.manage'    => ['label' => 'Gerenciar Espaços', 'desc' => 'Cadastro de laboratórios didáticos'],
                    'materials.manage' => ['label' => 'Gerenciar Estoque', 'desc' => 'Cadastro e conferência de materiais'],
                ],
            ],
            'Usuários & Governança' => [
                'icon' => '👥',
                'description' => 'Contas de acesso, papéis e segurança',
                'permissions' => [
                    'users.manage'       => ['label' => 'Gerenciar Usuários', 'desc' => 'Criar contas, alterar senhas e status'],
                    'permissions.manage' => ['label' => 'Gerenciar Permissões', 'desc' => 'Alteração da matriz de acessos'],
                ],
            ],
            'Ponto Eletrônico & Jornada' => [
                'icon' => '🕒',
                'description' => 'Frequência facial, geolocalização e grade de horários',
                'permissions' => [
                    'timeclock.punch'    => ['label' => 'Bater Ponto Facial', 'desc' => 'Registro de presença com reconhecimento facial e GPS'],
                    'timeclock.view_own' => ['label' => 'Ver Meu Ponto', 'desc' => 'Visualização do histórico e comprovantes individuais'],
                    'timeclock.manage'   => ['label' => 'Radar & Gestão do Ponto', 'desc' => 'Auditoria de fotos, justificativas e espelho mensal'],
                    'schedules.manage'   => ['label' => 'Grade de Horários', 'desc' => 'Definição dos turnos de professores por escola'],
                ],
            ],
            'Auditoria do Sistema' => [
                'icon' => '🛡️',
                'description' => 'Rastreamento e logs de conformidade',
                'permissions' => [
                    'audit.view' => ['label' => 'Visualizar Auditoria', 'desc' => 'Acesso aos logs de acessos, edições e exportação'],
                ],
            ],
        ];

        $stats = [
            'roles_count'       => $roles->count(),
            'permissions_count' => $permissions->count(),
            'modules_count'     => count($modules),
            'users_count'       => User::where('is_active', true)->count(),
        ];

        return view('admin.permissions.index', compact('roles', 'modules', 'stats'));
    }

    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'role_id'         => 'required|exists:roles,id',
            'permission_name' => 'required|exists:permissions,name',
        ]);

        $role = Role::findOrFail($request->role_id);
        $permissionName = $request->permission_name;

        // Impedir remoção de permissões críticas de admin para evitar bloqueio acidental
        if ($role->name === 'admin' && in_array($permissionName, ['permissions.manage', 'users.manage'])) {
            return response()->json([
                'success' => false,
                'message' => 'Não é permitido desativar permissões vitais do papel Administrador.',
            ], 422);
        }

        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
            $hasPermission = false;
            $actionDesc = "Revogou permissão '{$permissionName}' do papel '{$role->name}'";
        } else {
            $role->givePermissionTo($permissionName);
            $hasPermission = true;
            $actionDesc = "Concedeu permissão '{$permissionName}' ao papel '{$role->name}'";
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        AuditLogger::log(
            action: 'updated',
            module: 'Usuários & Governança',
            description: $actionDesc,
            auditable: $role,
            oldValues: ['has_permission' => !$hasPermission],
            newValues: ['has_permission' => $hasPermission]
        );

        return response()->json([
            'success'        => true,
            'has_permission' => $hasPermission,
            'message'        => 'Permissão atualizada com sucesso!',
        ]);
    }

    public function userPermissions(User $user): JsonResponse
    {
        $allPermissions = Permission::orderBy('name')->get();
        $directPermissions = $user->getDirectPermissions()->pluck('name')->toArray();
        $rolePermissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return response()->json([
            'user'               => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->roles->pluck('name')->implode(', ') ?: 'Sem papel',
            ],
            'direct_permissions' => $directPermissions,
            'role_permissions'   => $rolePermissions,
        ]);
    }

    public function toggleUserPermission(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'permission_name' => 'required|exists:permissions,name',
        ]);

        $permissionName = $request->permission_name;

        if ($user->hasDirectPermission($permissionName)) {
            $user->revokePermissionTo($permissionName);
            $hasDirect = false;
            $actionDesc = "Removeu permissão direta '{$permissionName}' do usuário '{$user->name}'";
        } else {
            $user->givePermissionTo($permissionName);
            $hasDirect = true;
            $actionDesc = "Concedeu permissão direta '{$permissionName}' ao usuário '{$user->name}'";
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        AuditLogger::log(
            action: 'updated',
            module: 'Usuários & Governança',
            description: $actionDesc,
            auditable: $user,
            oldValues: ['has_direct_permission' => !$hasDirect],
            newValues: ['has_direct_permission' => $hasDirect]
        );

        return response()->json([
            'success'              => true,
            'has_direct_permission'=> $hasDirect,
            'has_effective_access' => $user->can($permissionName),
            'message'              => 'Permissão individual atualizada!',
        ]);
    }

    public function resetDefaults(Request $request)
    {
        (new PermissionSeeder())->run();

        AuditLogger::log(
            action: 'sync',
            module: 'Usuários & Governança',
            description: "Restaurou a matriz padrão de papéis e permissões da escola."
        );

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Matriz de permissões restaurada para os padrões oficiais com sucesso!');
    }
}

