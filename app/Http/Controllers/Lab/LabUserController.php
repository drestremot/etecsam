<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;

class LabUserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'department', 'departments', 'courses', 'coordenadoresVinculados'])->orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $courses = Course::where('is_active', true)->orderBy('title')->get();
        $coordenadoresList = User::role('Coordenador')->where('is_active', true)->orderBy('name')->get();

        // Quantidade de professores que ainda não são usuários do sistema
        $existingUserEmails = User::pluck('email')->filter()->toArray();
        $pendingTeachersCount = Teacher::whereNotNull('email')
            ->where('email', '!=', '')
            ->whereNotIn('email', $existingUserEmails)
            ->count();

        return view('lab.users.index', compact('users', 'roles', 'departments', 'courses', 'coordenadoresList', 'pendingTeachersCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'registration_number' => 'nullable|string|unique:users,registration_number',
            'role'                => 'required|exists:roles,name',
            'job_title'           => 'nullable|string|max:255',
            'department_id'       => 'nullable|exists:departments,id',
            'department_ids'      => 'nullable|array',
            'department_ids.*'    => 'exists:departments,id',
            'course_ids'          => 'nullable|array',
            'course_ids.*'        => 'exists:courses,id',
            'phone'               => 'nullable|string|max:30',
            'password'            => 'nullable|string|min:6|confirmed',
            'show_on_site'        => 'nullable|boolean',
        ], [
            'password.min'       => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.',
        ]);

        $deptIds = $request->input('department_ids', []);
        if (empty($deptIds) && $request->filled('department_id')) {
            $deptIds = [(int)$request->department_id];
        }
        $courseIds = $request->input('course_ids', []);

        $password = $request->filled('password')
            ? $request->password
            : 'etec1234';

        $user = User::create([
            'name'                 => $request->name,
            'email'                => $request->email,
            'registration_number'  => $request->registration_number,
            'role'                 => $request->job_title ?: $request->role,
            'department_id'        => !empty($deptIds) ? $deptIds[0] : null,
            'course_id'            => !empty($courseIds) ? $courseIds[0] : null,
            'phone'                => $request->phone,
            'password'             => Hash::make($password),
            'must_change_password' => !$request->filled('password'), // Se usou senha padrão, exige troca no 1º login
            'is_active'            => true,
            'is_admin'             => $request->role === 'Administrador' || $request->role === 'admin',
        ]);

        $user->syncRoles($request->role);
        $user->departments()->sync($deptIds);
        $user->courses()->sync($courseIds);

        // Sincronização automática com a tabela de professores e funcionários do site institucional
        if ($request->boolean('show_on_site', true)) {
            Teacher::updateOrCreate(
                ['email' => $user->email],
                [
                    'name'       => $user->name,
                    'role'       => $request->job_title ?: $request->role,
                    'phone'      => $user->phone,
                    'is_active'  => true,
                ]
            );
        }

        $msg = $request->filled('password')
            ? "Usuário {$user->name} cadastrado com sucesso!"
            : "Usuário {$user->name} cadastrado com senha padrão: etec1234 (troca exigida no 1º acesso).";

        return back()->with('success', $msg);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email,' . $user->id,
            'registration_number' => 'nullable|string|unique:users,registration_number,' . $user->id,
            'role'                => 'required|exists:roles,name',
            'job_title'           => 'nullable|string|max:255',
            'department_id'       => 'nullable|exists:departments,id',
            'department_ids'      => 'nullable|array',
            'department_ids.*'    => 'exists:departments,id',
            'course_ids'          => 'nullable|array',
            'course_ids.*'        => 'exists:courses,id',
            'phone'               => 'nullable|string|max:30',
            'password'            => 'nullable|string|min:6|confirmed',
            'show_on_site'        => 'nullable|boolean',
        ], [
            'password.min'       => 'A nova senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.',
        ]);

        $deptIds = $request->input('department_ids', []);
        if (empty($deptIds) && $request->filled('department_id')) {
            $deptIds = [(int)$request->department_id];
        }
        $courseIds = $request->input('course_ids', []);

        $oldEmail = $user->email;

        $userData = [
            'name'                => $request->name,
            'email'               => $request->email,
            'registration_number' => $request->registration_number,
            'role'                => $request->job_title ?: $request->role,
            'department_id'       => !empty($deptIds) ? $deptIds[0] : null,
            'course_id'            => !empty($courseIds) ? $courseIds[0] : null,
            'phone'               => $request->phone,
            'is_admin'            => $request->role === 'Administrador' || $request->role === 'admin',
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
            $userData['must_change_password'] = false;
        }

        $user->update($userData);
        $user->syncRoles($request->role);
        $user->departments()->sync($deptIds);
        $user->courses()->sync($courseIds);

        if ($request->role === 'Auxiliar' && $request->has('coordenador_ids')) {
            $user->coordenadoresVinculados()->sync($request->coordenador_ids ?? []);
        }

        // Sincronizar com a tabela de professores e colaboradores do site
        $teacher = Teacher::where('email', $oldEmail)->first();
        if ($teacher) {
            $teacher->update([
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $request->job_title ?: $request->role,
                'phone'      => $user->phone,
            ]);
        } elseif ($request->boolean('show_on_site', true)) {
            Teacher::updateOrCreate(
                ['email' => $user->email],
                [
                    'name'       => $user->name,
                    'role'       => $request->job_title ?: $request->role,
                    'phone'      => $user->phone,
                    'is_active'  => $user->is_active,
                ]
            );
        }

        return back()->with('success', "Dados do usuário {$user->name} atualizados com sucesso!");
    }

    public function syncAllTeachers()
    {
        $existingEmails = User::whereNotNull('email')->pluck('email')->toArray();
        $teachers = Teacher::whereNotNull('email')
            ->where('email', '!=', '')
            ->whereNotIn('email', $existingEmails)
            ->get();

        if ($teachers->isEmpty()) {
            return back()->with('info', 'Todos os professores e colaboradores já possuem contas de usuário cadastradas no sistema.');
        }

        $created = 0;
        foreach ($teachers as $teacher) {
            $roleLower = strtolower($teacher->role ?? '');
            if (str_contains($roleLower, 'superintendente')) {
                $roleName = 'Superintendente';
            } elseif (str_contains($roleLower, 'diretor') || str_contains($roleLower, 'diretora')) {
                $roleName = 'Diretor';
            } elseif (str_contains($roleLower, 'coordenad')) {
                $roleName = 'Coordenador';
            } elseif (str_contains($roleLower, 'auxiliar')) {
                $roleName = 'Auxiliar';
            } else {
                $roleName = 'Professor';
            }

            $user = User::create([
                'name'                 => $teacher->name,
                'email'                => $teacher->email,
                'registration_number'  => $teacher->registration_number ?? null,
                'role'                 => $teacher->role ?? $roleName,
                'phone'                => $teacher->phone,
                'password'             => Hash::make('etec1234'),
                'must_change_password' => true,
                'is_active'            => $teacher->is_active ?? true,
                'is_admin'             => in_array($roleName, ['Superintendente', 'Diretor', 'admin']),
            ]);

            if (Role::where('name', $roleName)->exists()) {
                $user->assignRole($roleName);
            }
            $created++;
        }

        return back()->with('success', "Foram criadas {$created} novas contas de usuário com senha padrão 'etec1234'. A troca de senha será solicitada no primeiro acesso.");
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);
        $user->syncRoles($request->role);
        $user->update(['is_admin' => $request->role === 'Administrador' || $request->role === 'admin']);

        // Sincroniza cargo do docente caso exista
        Teacher::where('email', $user->email)->update([
            'role' => $user->role ?: $request->role
        ]);

        return back()->with('success', "Papel de {$user->name} atualizado para {$request->role}.");
    }

    public function updateVinculos(Request $request, User $user)
    {
        abort_unless($user->hasRole('Auxiliar'), 422);

        $request->validate([
            'coordenador_ids'   => 'nullable|array',
            'coordenador_ids.*' => ['exists:users,id', function ($attr, $val, $fail) {
                if (! User::find($val)?->hasRole('Coordenador')) {
                    $fail('Um dos usuários selecionados não é Coordenador.');
                }
            }],
        ]);

        $user->coordenadoresVinculados()->sync($request->coordenador_ids ?? []);

        return back()->with('success', "Vínculos de {$user->name} atualizados.");
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        Teacher::where('email', $user->email)->update(['is_active' => $user->is_active]);

        $status = $user->is_active ? 'ativado' : 'desativado';
        return back()->with('success', "Usuário e perfil {$status}.");
    }

    public function sendResetLink(User $user)
    {
        $status = Password::sendResetLink(['email' => $user->email]);

        return back()->with(
            $status === Password::RESET_LINK_SENT ? 'success' : 'error',
            $status === Password::RESET_LINK_SENT
                ? "Link de redefinição de senha enviado para {$user->email}."
                : 'Não foi possível enviar o link. Verifique se o e-mail está correto.'
        );
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }

        Teacher::where('email', $user->email)->delete();
        $user->delete();

        return back()->with('success', 'Usuário e perfil docente removidos com sucesso.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:users,id',
        ]);

        $ids = array_filter($request->ids, fn($id) => (int)$id !== (int)auth()->id());
        $count = count($ids);

        if ($count === 0) {
            return back()->with('error', 'Nenhum usuário válido selecionado para a ação.');
        }

        $users = User::whereIn('id', $ids)->get();

        if ($request->action === 'activate') {
            User::whereIn('id', $ids)->update(['is_active' => true]);
            $emails = $users->pluck('email')->filter();
            Teacher::whereIn('email', $emails)->update(['is_active' => true]);
            return back()->with('success', "{$count} usuário(s) ativado(s) com sucesso!");
        }

        if ($request->action === 'deactivate') {
            User::whereIn('id', $ids)->update(['is_active' => false]);
            $emails = $users->pluck('email')->filter();
            Teacher::whereIn('email', $emails)->update(['is_active' => false]);
            return back()->with('success', "{$count} usuário(s) desativado(s) com sucesso!");
        }

        if ($request->action === 'delete') {
            $emails = $users->pluck('email')->filter();
            Teacher::whereIn('email', $emails)->delete();
            User::whereIn('id', $ids)->delete();
            return back()->with('success', "{$count} usuário(s) excluído(s) com sucesso!");
        }

        return back();
    }
}
