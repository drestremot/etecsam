@extends('admin.layouts.app')
@section('title', 'Usuários do Laboratório')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Usuários</h1>
    </div>

    @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>@endif

    {{-- Formulário cadastro rápido --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-4">Cadastrar usuário</h2>
        <form action="{{ route('lab.users.store') }}" method="POST" class="grid sm:grid-cols-4 gap-4">
            @csrf
            <input type="text" name="name" placeholder="Nome *" required value="{{ old('name') }}"
                   class="border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
            <input type="email" name="email" placeholder="E-mail *" required value="{{ old('email') }}"
                   class="border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
            <input type="text" name="registration_number" placeholder="Matrícula" value="{{ old('registration_number') }}"
                   class="border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
            <select name="role" required class="border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                <option value="">Papel *</option>
                @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            <div class="sm:col-span-4 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-etec-dark text-white text-sm font-semibold rounded-lg hover:bg-etec-main transition">
                    Cadastrar (senha padrão: etec1234)
                </button>
            </div>
        </form>
    </div>

    {{-- Tabela de usuários --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-bold text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Nome</th>
                    <th class="px-6 py-3 text-left">E-mail</th>
                    <th class="px-6 py-3 text-left">Papel</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($users as $u)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                    <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">{{ $u->name }}</td>
                    <td class="px-6 py-3 text-gray-500 dark:text-gray-400">{{ $u->email }}</td>
                    <td class="px-6 py-3">
                        <form action="{{ route('lab.users.role', $u) }}" method="POST" class="flex gap-2 items-center">
                            @csrf @method('PATCH')
                            <select name="role" class="border border-gray-200 dark:border-gray-600 rounded px-2 py-1 text-xs dark:bg-gray-700 dark:text-white focus:ring-1 focus:ring-etec-dark outline-none">
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $u->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <button class="text-xs text-etec-main dark:text-etec-accent hover:underline font-semibold">OK</button>
                        </form>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <form action="{{ route('lab.users.status', $u) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="px-2.5 py-1 rounded-full text-xs font-bold {{ $u->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $u->is_active ? 'Ativo' : 'Inativo' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-3 text-right">
                        @if($u->id !== auth()->id())
                        <form action="{{ route('lab.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Excluir este usuário?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline text-xs font-semibold">Excluir</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Nenhum usuário encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
