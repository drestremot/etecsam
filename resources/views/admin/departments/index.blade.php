@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>Departamentos & Coordenações</span>
                    <span class="rounded-xl bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700 normal-case tracking-normal">{{ $departments->total() }} cadastrados</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 font-medium mt-1">Gestão de coordenações pedagógicas, secretarias e responsáveis de área</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.departments.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-purple-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Novo Departamento</span>
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
                <input x-model="q" @input="search()" type="text" placeholder="Buscar por departamento, responsável ou e-mail..."
                       class="flex-1 text-xs sm:text-sm border-0 outline-none bg-transparent text-gray-800 placeholder-gray-400">
                <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs font-bold">✕ limpar</button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-gray-50 text-xs font-bold uppercase text-gray-600 border-b border-gray-200">
                        <tr>
                            <th @click="sort('nome')" class="px-6 py-4 cursor-pointer hover:bg-gray-100 select-none">
                                Departamento <span class="ml-1 text-gray-400" x-text="icon('nome')"></span>
                            </th>
                            <th @click="sort('resp')" class="px-6 py-4 cursor-pointer hover:bg-gray-100 select-none">
                                Responsável <span class="ml-1 text-gray-400" x-text="icon('resp')"></span>
                            </th>
                            <th class="px-6 py-4">Contato</th>
                            <th @click="sort('status')" class="px-6 py-4 text-center cursor-pointer hover:bg-gray-100 select-none">
                                Status <span class="ml-1 text-gray-400" x-text="icon('status')"></span>
                            </th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($departments as $dept)
                        <tr class="hover:bg-gray-50/80 transition {{ !$dept->is_active ? 'opacity-60' : '' }}"
                            data-row="{{ strtolower($dept->name . ' ' . ($dept->responsible?->name ?? '') . ' ' . $dept->email) }}"
                            data-active="{{ $dept->is_active ? '1' : '0' }}"
                            data-nome="{{ strtolower($dept->name) }}"
                            data-resp="{{ strtolower($dept->responsible?->name ?? '') }}"
                            data-status="{{ $dept->is_active ? 'ativo' : 'inativo' }}">
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ $dept->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 font-semibold">
                                {{ $dept->responsible?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">
                                <div class="font-mono">{{ $dept->email ?? '' }}</div>
                                @if($dept->phone) <div class="text-gray-400">{{ $dept->phone }}</div> @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.departments.toggle', $dept) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1 rounded-full text-xs font-bold transition shadow-2xs {{ $dept->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $dept->is_active ? 'Ativo' : 'Inativo' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.departments.edit', $dept) }}" class="rounded-xl bg-gray-100 px-3 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-200 transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-xl bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-100 transition">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400">Nenhum departamento cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($departments->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $departments->links() }}</div>
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
