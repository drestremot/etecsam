<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::orderByDesc('is_active')->orderBy('name')->get();
        $userEmails = User::pluck('email')->filter()->toArray();
        $pendingUsersCount = Teacher::whereNotNull('email')
            ->where('email', '!=', '')
            ->whereNotIn('email', $userEmails)
            ->count();

        return view('admin.teachers.index', compact('teachers', 'userEmails', 'pendingUsersCount'));
    }

    public function syncAllToUsers()
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

    public function syncSingleUser(Teacher $teacher)
    {
        if (empty($teacher->email)) {
            return back()->with('error', 'Este colaborador não possui e-mail cadastrado para criar uma conta.');
        }

        $user = User::where('email', $teacher->email)->first();
        if ($user) {
            return back()->with('info', "O colaborador {$teacher->name} já possui conta de usuário ativa.");
        }

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

        return back()->with('success', "Conta de usuário criada para {$teacher->name} com papel '{$roleName}' e senha padrão 'etec1234'. A troca de senha será solicitada no primeiro acesso.");
    }

    public function toggle(Teacher $teacher)
    {
        $teacher->update(['is_active' => !$teacher->is_active]);
        if ($teacher->email) {
            User::where('email', $teacher->email)->update(['is_active' => $teacher->is_active]);
        }
        return back()->with('success', '"' . $teacher->name . '" ' . ($teacher->is_active ? 'ativado' : 'desativado') . '.');
    }

    public function create()
    {
        return view('admin.teachers.form', ['teacher' => new Teacher(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'role'       => 'required|string|max:255',
            'specialty'  => 'nullable|string|max:255',
            'bio'        => 'nullable|string|max:20000',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:30',
            'lattes_url' => 'nullable|url|max:500',
            'birth_date' => 'nullable|date',
            'photo'      => 'nullable|image|max:4096',
        ]);

        if (!empty($data['bio'])) {
            $data['bio'] = clean($data['bio']);
        }

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher = Teacher::create($data);

        // Sincronização automática: cria conta de usuário no sistema se tiver e-mail
        if (!empty($data['email'])) {
            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                $roleLower = strtolower($data['role'] ?? '');
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
                    'name'                 => $data['name'],
                    'email'                => $data['email'],
                    'role'                 => $data['role'],
                    'phone'                => $data['phone'] ?? null,
                    'password'             => Hash::make('etec1234'),
                    'must_change_password' => true,
                    'is_active'            => true,
                    'is_admin'             => in_array($roleName, ['Superintendente', 'Diretor', 'admin']),
                ]);

                if (Role::where('name', $roleName)->exists()) {
                    $user->assignRole($roleName);
                }
            }
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Colaborador cadastrado e sincronizado com sucesso!');
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.form', compact('teacher') + ['action' => 'edit']);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'role'       => 'required|string|max:255',
            'specialty'  => 'nullable|string|max:255',
            'bio'        => 'nullable|string|max:20000',
            'email'      => 'nullable|email|max:255',
            'phone'      => 'nullable|string|max:30',
            'lattes_url' => 'nullable|url|max:500',
            'birth_date' => 'nullable|date',
            'photo'      => 'nullable|image|max:4096',
        ]);

        if (!empty($data['bio'])) {
            $data['bio'] = clean($data['bio']);
        }

        if ($request->hasFile('photo')) {
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $oldEmail = $teacher->email;
        $teacher->update($data);

        // Atualiza usuário correspondente
        if (!empty($data['email'])) {
            $user = User::where('email', $oldEmail)->orWhere('email', $data['email'])->first();
            if ($user) {
                $user->update([
                    'name'  => $data['name'],
                    'email' => $data['email'],
                    'role'  => $data['role'],
                    'phone' => $data['phone'] ?? $user->phone,
                ]);
            }
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Colaborador atualizado com sucesso!');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Colaborador removido!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:teachers,id',
        ]);

        $teachers = Teacher::whereIn('id', $request->ids)->get();
        $count = $teachers->count();
        $emails = $teachers->pluck('email')->filter();

        if ($request->action === 'activate') {
            Teacher::whereIn('id', $request->ids)->update(['is_active' => true]);
            if ($emails->isNotEmpty()) {
                User::whereIn('email', $emails)->update(['is_active' => true]);
            }
            return back()->with('success', "{$count} docente(s) ativado(s) com sucesso!");
        }

        if ($request->action === 'deactivate') {
            Teacher::whereIn('id', $request->ids)->update(['is_active' => false]);
            if ($emails->isNotEmpty()) {
                User::whereIn('email', $emails)->where('id', '!=', auth()->id())->update(['is_active' => false]);
            }
            return back()->with('success', "{$count} docente(s) desativado(s) com sucesso!");
        }

        if ($request->action === 'delete') {
            foreach ($teachers as $teacher) {
                if ($teacher->photo) {
                    Storage::disk('public')->delete($teacher->photo);
                }
                $teacher->delete();
            }
            return back()->with('success', "{$count} docente(s) excluído(s) com sucesso!");
        }

        return back();
    }
}
