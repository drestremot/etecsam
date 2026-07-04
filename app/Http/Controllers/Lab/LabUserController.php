<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
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
        $users = User::with('roles')->orderBy('name')->paginate(20);
        $roles = Role::orderBy('name')->get();

        // Professores/funcionários que ainda não têm conta de usuário
        $existingEmails = User::pluck('email')->toArray();
        $teachers = Teacher::whereNotNull('email')
            ->whereNotIn('email', $existingEmails)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'registration_number', 'role']);

        return view('lab.users.index', compact('users', 'roles', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'registration_number' => 'nullable|string|unique:users,registration_number',
            'role'                => 'required|exists:roles,name',
            'password'            => 'nullable|string|min:6|confirmed',
        ], [
            'password.min'       => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.',
        ]);

        $password = $request->filled('password')
            ? $request->password
            : 'etec1234';

        $user = User::create([
            'name'                => $request->name,
            'email'               => $request->email,
            'registration_number' => $request->registration_number,
            'password'            => Hash::make($password),
            'is_active'           => true,
        ]);

        $user->syncRoles($request->role);

        $msg = $request->filled('password')
            ? "Usuário {$user->name} cadastrado com a senha definida."
            : "Usuário {$user->name} cadastrado com senha padrão: etec1234";

        return back()->with('success', $msg);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);
        $user->syncRoles($request->role);
        return back()->with('success', "Papel de {$user->name} atualizado para {$request->role}.");
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'ativado' : 'desativado';
        return back()->with('success', "Usuário {$status}.");
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
        $user->delete();
        return back()->with('success', 'Usuário excluído.');
    }
}
