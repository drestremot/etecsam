@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>Professores & Equipe no Site</span>
                    <span class="rounded-xl bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 normal-case tracking-normal">{{ $teachers->total() }} registros</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 font-medium mt-1">Exibição pública da equipe escolar no site institucional, biografias, fotos e sincronização de acesso</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('admin.teachers.sync-all') }}" method="POST" class="inline" onsubmit="return confirm('Deseja converter e criar contas de usuários para todos os professores e colaboradores?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-500 active:scale-95">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>⚡ Converter Todos em Usuários</span>
                        @if(($pendingUsersCount ?? 0) > 0)
                            <span class="rounded-full bg-emerald-800 px-2 py-0.5 text-[10.5px] font-semibold">{{ $pendingUsersCount }} pendente(s)</span>
                        @endif
                    </button>
                </form>

                <a href="{{ route('lab.users.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Gestão de Usuários</span>
                </a>
                <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Novo Colaborador</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500 text-white p-4 text-sm font-bold shadow-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden" x-data="adminTable()">
            <!-- Search bar -->
            <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-3 bg-gray-50/50">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input x-model="q" @input="search()" type="text" placeholder="Buscar por nome, cargo ou e-mail..."
                       class="flex-1 text-xs sm:text-sm border-0 outline-none bg-transparent text-gray-800 placeholder-gray-400">
                <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs font-bold">✕ limpar</button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-gray-50/90 text-[11px] font-semibold uppercase text-gray-500 border-b border-gray-200 tracking-wider">
                        <tr>
                            <th @click="sort('nome')" class="px-6 py-3.5 cursor-pointer hover:bg-gray-100 select-none">
                                Nome <span class="ml-1 text-gray-400" x-text="icon('nome')"></span>
                            </th>
                            <th @click="sort('cargo')" class="px-6 py-3.5 cursor-pointer hover:bg-gray-100 select-none">
                                Cargo / Função <span class="ml-1 text-gray-400" x-text="icon('cargo')"></span>
                            </th>
                            <th @click="sort('email')" class="px-6 py-3.5 cursor-pointer hover:bg-gray-100 select-none">
                                E-mail <span class="ml-1 text-gray-400" x-text="icon('email')"></span>
                            </th>
                            <th class="px-6 py-3.5">Conta no Sistema</th>
                            <th @click="sort('status')" class="px-6 py-3.5 text-center cursor-pointer hover:bg-gray-100 select-none">
                                Status <span class="ml-1 text-gray-400" x-text="icon('status')"></span>
                            </th>
                            <th class="px-6 py-3.5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($teachers as $teacher)
                        @php
                            $hasUserAccount = $teacher->email && in_array($teacher->email, $userEmails ?? []);
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition {{ !$teacher->is_active ? 'opacity-60' : '' }}"
                            data-row="{{ strtolower($teacher->name . ' ' . $teacher->role . ' ' . $teacher->email) }}"
                            data-active="{{ $teacher->is_active ? '1' : '0' }}"
                            data-nome="{{ strtolower($teacher->name) }}"
                            data-cargo="{{ strtolower($teacher->role) }}"
                            data-email="{{ strtolower($teacher->email ?? '') }}"
                            data-status="{{ $teacher->is_active ? 'ativo' : 'inativo' }}">
                            <td class="px-6 py-3.5 font-semibold text-gray-900">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-semibold text-xs overflow-hidden flex-shrink-0 shadow-2xs">
                                        @if($teacher->photo)
                                            <img src="{{ photo_url($teacher->photo) }}" alt="{{ $teacher->name }}"
                                                 class="w-full h-full object-cover"
                                                 onerror="this.style.display='none'; this.parentElement.querySelector('.initials-fallback').classList.remove('hidden')">
                                            <span class="initials-fallback hidden">{{ strtoupper(substr($teacher->name, 0, 2)) }}</span>
                                        @else
                                            {{ strtoupper(substr($teacher->name, 0, 2)) }}
                                        @endif
                                    </span>
                                    <span>{{ $teacher->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-gray-600 font-normal">{{ $teacher->role }}</td>
                            <td class="px-6 py-3.5 text-gray-600 font-mono text-xs">{{ $teacher->email ?? '—' }}</td>
                            <td class="px-6 py-3.5">
                                @if($hasUserAccount)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Conta Ativa
                                    </span>
                                @else
                                    <a href="{{ route('admin.teachers.create-user', $teacher) }}"
                                       onclick="return confirm('Criar conta de usuário com o e-mail {{ $teacher->email }} e senha padrão (cpf ou 12345678)?')"
                                       class="inline-flex items-center gap-1 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-full border border-blue-200 transition shadow-2xs"
                                       title="Criar login de acesso para este professor">
                                        <span>+ Criar Conta</span>
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-center">
                                <form action="{{ route('admin.teachers.toggle', $teacher) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1 rounded-full text-xs font-medium transition shadow-2xs {{ $teacher->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $teacher->is_active ? 'Ativo' : 'Inativo' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="rounded-xl bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-200 transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-xl bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-100 transition">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">Nenhum professor cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($teachers->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $teachers->links() }}</div>
            @endif
        </div>

    </div>
</div>

<script>
function adminTable() {
    return {
        q: '',
        sortBy: '',
        sortDir: 'asc',
        search() {
            const query = this.q.toLowerCase();
            document.querySelectorAll('[data-row]').forEach(tr => {
                const text = tr.dataset.row || '';
                tr.style.display = (!query || text.includes(query)) ? '' : 'none';
            });
        },
        sort(col) {
            if (this.sortBy === col) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = col;
                this.sortDir = 'asc';
            }
            const tbody = document.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr[data-row]'));
            rows.sort((a, b) => {
                const va = a.dataset[col] || '';
                const vb = b.dataset[col] || '';
                return this.sortDir === 'asc' ? va.localeCompare(vb) : vb.localeCompare(va);
            });
            rows.forEach(r => tbody.appendChild(r));
        },
        icon(col) {
            if (this.sortBy !== col) return '↕';
            return this.sortDir === 'asc' ? '↑' : '↓';
        }
    }
}
</script>
@endsection
