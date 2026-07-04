<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class LabUserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('name')->paginate(20);
        $roles = Role::orderBy('name')->get();
        return view('lab.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'registration_number' => 'nullable|string|unique:users,registration_number',
            'role'                => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'                => $request->name,
            'email'               => $request->email,
            'registration_number' => $request->registration_number,
            'password'            => Hash::make('etec1234'),
            'is_active'           => true,
        ]);

        $user->syncRoles($request->role);

        return back()->with('success', "Usuário {$user->name} cadastrado com senha padrão: etec1234");
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);
        $user->syncRoles($request->role);
        return back()->with('success', "Perfil de {$user->name} atualizado para {$request->role}.");
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'ativado' : 'desativado';
        return back()->with('success', "Usuário {$status}.");
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
