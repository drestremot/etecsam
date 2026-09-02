@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10" x-data="auditModal()">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-extrabold">Auditoria do Sistema</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>🛡️ Auditoria Geral do Sistema</span>
                    <span class="rounded-xl bg-purple-100 border border-purple-200 px-3 py-1 text-xs font-semibold text-purple-800">
                        Superintendência & Diretoria
                    </span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1 font-normal">
                    Rastreabilidade completa de acessos, criações, alterações de dados, exclusões e aprovações em todos os módulos
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.audit.export', request()->query()) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-emerald-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Exportar CSV</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Voltar ao Painel</span>
                </a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Total de Eventos</span>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 mt-1">{{ number_format($stats['total'], 0, ',', '.') }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Acessos Hoje (Logins)</span>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-blue-700 mt-1">{{ number_format($stats['logins_today'], 0, ',', '.') }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Edições Registradas</span>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-amber-700 mt-1">{{ number_format($stats['updates'], 0, ',', '.') }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Exclusões Realizadas</span>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-rose-700 mt-1">{{ number_format($stats['deletes'], 0, ',', '.') }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Aprovações / Ações Críticas</span>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-emerald-700 mt-1">{{ number_format($stats['approvals'], 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs">
            <form method="GET" action="{{ route('admin.audit.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">

                    {{-- Search Input --}}
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Busca por Termo ou IP</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <input type="text" name="q" value="{{ request('q') }}"
                                   placeholder="Nome, e-mail, IP, termo da ação..."
                                   class="w-full rounded-xl border border-gray-300 bg-gray-50/50 pl-9 pr-3 py-2 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                        </div>
                    </div>

                    {{-- User filter --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Usuário / Autor</label>
                        <select name="user_id" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                            <option value="">Todos os Usuários</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Module filter --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Módulo</label>
                        <select name="module" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                            <option value="">Todos os Módulos</option>
                            @foreach($modules as $m)
                                <option value="{{ $m }}" {{ request('module') === $m ? 'selected' : '' }}>
                                    {{ $m }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action filter --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Tipo de Ação</label>
                        <select name="action" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                            <option value="">Todas as Ações</option>
                            @foreach($actions as $a)
                                <option value="{{ $a }}" {{ request('action') === $a ? 'selected' : '' }}>
                                    {{ ucfirst($a) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Data Inicial</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <div class="text-xs text-gray-500 font-medium">
                        Exibindo <strong>{{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }}</strong> de <strong>{{ $logs->total() }}</strong> registros
                    </div>
                    <div class="flex items-center gap-2">
                        @if(request()->anyFilled(['q', 'user_id', 'module', 'action', 'date_from', 'date_to']))
                            <a href="{{ route('admin.audit.index') }}" class="rounded-xl border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-100 transition">
                                Limpar Filtros
                            </a>
                        @endif
                        <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500 transition shadow-2xs">
                            Aplicar Filtros
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500 border-b border-gray-200 tracking-wider">
                        <tr>
                            <th class="px-5 py-3.5">Data / Hora</th>
                            <th class="px-5 py-3.5">Usuário / Autor</th>
                            <th class="px-5 py-3.5">Ação Realizada</th>
                            <th class="px-5 py-3.5">Módulo</th>
                            <th class="px-5 py-3.5">Descrição do Evento</th>
                            <th class="px-5 py-3.5">Origem (IP)</th>
                            <th class="px-5 py-3.5 text-right">Detalhes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="font-bold text-gray-900 block text-xs">
                                    {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '—' }}
                                </span>
                                <span class="text-[11px] text-gray-400 block font-normal">
                                    {{ $log->created_at ? $log->created_at->diffForHumans() : '' }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-900 truncate text-xs">{{ $log->user_name ?? 'Sistema' }}</div>
                                        @if($log->user_role)
                                            <span class="inline-block rounded-md bg-gray-100 px-1.5 py-0.2 text-[10px] font-medium text-gray-600 border border-gray-200">
                                                {{ $log->user_role }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-xs font-semibold border {{ $log->getActionBadgeClass() }}">
                                    {{ $log->getActionLabel() }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-700">
                                    <span>{{ $log->getModuleIcon() }}</span>
                                    <span>{{ $log->module }}</span>
                                </span>
                            </td>

                            <td class="px-5 py-3.5 max-w-md">
                                <p class="text-xs text-gray-800 line-clamp-2">{{ $log->description }}</p>
                            </td>

                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="text-xs font-mono text-gray-600 block">{{ $log->ip_address ?: '—' }}</span>
                                <span class="text-[10px] text-gray-400 truncate max-w-[120px] block" title="{{ $log->user_agent }}">
                                    {{ $log->method }} {{ $log->user_agent ? Str::limit($log->user_agent, 20) : '' }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <button @click="openModal({{ $log->id }})"
                                        class="inline-flex items-center gap-1 rounded-xl bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 border border-gray-200 transition shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Ver Diff</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center text-xl mx-auto mb-3">🛡️</div>
                                <p class="font-bold text-base text-gray-700 mb-1">Nenhum registro de auditoria encontrado.</p>
                                <p class="text-xs text-gray-500">Tente ajustar os filtros de busca ou período de datas.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $logs->links() }}</div>
            @endif
        </div>

    </div>

    <!-- Interactive Diff & Details Modal -->
    <div x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="show"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeModal()"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity"></div>

            <div x-show="show"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-3xl">

                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-xl" x-text="item.module_icon || '🛡️'"></span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900" x-text="'Log de Auditoria #' + item.id"></h3>
                            <p class="text-xs text-gray-500" x-text="item.module + ' · ' + item.created_at + ' (' + item.time_ago + ')'"></p>
                        </div>
                    </div>
                    <button @click="closeModal()" class="rounded-xl p-1.5 text-gray-400 hover:bg-gray-200 hover:text-gray-700 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                    {{-- User & Action info banner --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-gray-50/70 p-4 rounded-2xl border border-gray-200 text-xs">
                        <div>
                            <span class="text-gray-400 font-medium block">Usuário</span>
                            <span class="font-bold text-gray-900 block mt-0.5" x-text="item.user_name"></span>
                            <span class="text-[11px] text-gray-500 block truncate" x-text="item.user_email"></span>
                        </div>
                        <div>
                            <span class="text-gray-400 font-medium block">Perfil / Papel</span>
                            <span class="font-semibold text-indigo-700 block mt-0.5" x-text="item.user_role"></span>
                        </div>
                        <div>
                            <span class="text-gray-400 font-medium block">Ação</span>
                            <span class="inline-flex mt-1 items-center gap-1 rounded-md px-2 py-0.5 text-xs font-semibold border"
                                  :class="item.badge_class" x-text="item.action_label"></span>
                        </div>
                        <div>
                            <span class="text-gray-400 font-medium block">IP & Método</span>
                            <span class="font-mono text-gray-800 block mt-0.5" x-text="(item.method || 'POST') + ' ' + (item.ip_address || '—')"></span>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase block mb-1">Descrição do Evento</span>
                        <div class="p-3 bg-blue-50/50 rounded-xl border border-blue-100 text-xs sm:text-sm text-gray-800 font-medium" x-text="item.description"></div>
                    </div>

                    {{-- Values Diff (Old vs New) --}}
                    <template x-if="item.old_values || item.new_values">
                        <div class="space-y-3">
                            <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide border-b border-gray-100 pb-2">
                                Modificações nos Dados (Diff de Valores)
                            </h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Old Values --}}
                                <div class="rounded-2xl border border-rose-200 bg-rose-50/40 p-4">
                                    <div class="flex items-center gap-2 mb-2 text-rose-800 font-bold text-xs uppercase">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Valores Anteriores (Antes)</span>
                                    </div>
                                    <template x-if="item.old_values">
                                        <div class="space-y-1.5">
                                            <template x-for="(val, key) in item.old_values" :key="key">
                                                <div class="text-xs bg-white/80 p-2 rounded-lg border border-rose-100">
                                                    <span class="font-bold text-gray-700 block text-[11px]" x-text="key + ':'"></span>
                                                    <span class="text-rose-900 font-mono text-xs break-all" x-text="typeof val === 'object' ? JSON.stringify(val) : (val === null ? 'null' : val)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!item.old_values">
                                        <p class="text-xs text-gray-400 italic">Nenhum dado anterior (registro novo).</p>
                                    </template>
                                </div>

                                {{-- New Values --}}
                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/40 p-4">
                                    <div class="flex items-center gap-2 mb-2 text-emerald-800 font-bold text-xs uppercase">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Novos Valores (Depois)</span>
                                    </div>
                                    <template x-if="item.new_values">
                                        <div class="space-y-1.5">
                                            <template x-for="(val, key) in item.new_values" :key="key">
                                                <div class="text-xs bg-white/80 p-2 rounded-lg border border-emerald-100">
                                                    <span class="font-bold text-gray-700 block text-[11px]" x-text="key + ':'"></span>
                                                    <span class="text-emerald-900 font-mono text-xs break-all" x-text="typeof val === 'object' ? JSON.stringify(val) : (val === null ? 'null' : val)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!item.new_values">
                                        <p class="text-xs text-gray-400 italic">Registro excluído do banco de dados.</p>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Technical Request Metadata --}}
                    <div class="pt-2 border-t border-gray-100 text-xs text-gray-500 space-y-1">
                        <div><strong>URL da Requisição:</strong> <span class="font-mono text-[11px]" x-text="item.url"></span></div>
                        <div><strong>Navegador / User Agent:</strong> <span class="font-mono text-[11px]" x-text="item.user_agent || '—'"></span></div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-3.5 border-t border-gray-200 flex justify-end">
                    <button type="button" @click="closeModal()" class="rounded-xl bg-gray-900 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-gray-800 transition">
                        Fechar Detalhes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function auditModal() {
    return {
        show: false,
        item: {},
        openModal(id) {
            fetch('{{ url('/admin/auditoria') }}/' + id)
                .then(r => r.json())
                .then(data => {
                    this.item = data;
                    this.show = true;
                })
                .catch(err => {
                    alert('Erro ao carregar detalhes do log.');
                });
        },
        closeModal() {
            this.show = false;
        }
    }
}
</script>
@endsection

