@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8"
     x-data="{
         search: '',
         statusFilter: 'all',
         createOpen: false,
         editOpen: false,
         selected: [],
         editTenant: {
             id: null,
             name: '',
             room: '',
             is_active: true,
             updateUrl: ''
         },
         openEdit(tenant, updateUrl) {
             this.editTenant = {
                 id: tenant.id,
                 name: tenant.name || '',
                 room: tenant.room || '',
                 is_active: Boolean(tenant.is_active),
                 updateUrl: updateUrl
             };
             this.editOpen = true;
         },
         get visibleIds() {
             const rows = Array.from(document.querySelectorAll('tbody tr[data-id]'));
             return rows.filter(r => r.style.display !== 'none').map(r => Number(r.getAttribute('data-id')));
         },
         toggleSelectAll(checked) {
             const vIds = this.visibleIds;
             if (checked) {
                 this.selected = Array.from(new Set([...this.selected, ...vIds]));
             } else {
                 this.selected = this.selected.filter(id => !vIds.includes(id));
             }
         },
         isAllSelected() {
             const vIds = this.visibleIds;
             return vIds.length > 0 && vIds.every(id => this.selected.includes(id));
         },
         isIndeterminate() {
             const vIds = this.visibleIds;
             const count = vIds.filter(id => this.selected.includes(id)).length;
             return count > 0 && count < vIds.length;
         },
         filterRows() {
             const q = this.search.toLowerCase().trim();
             const sf = this.statusFilter;

             document.querySelectorAll('tbody tr[data-id]').forEach(row => {
                 const searchData = (row.getAttribute('data-search') || '').toLowerCase();
                 const rowStatus = row.getAttribute('data-status');

                 const matchQ = !q || searchData.includes(q);
                 const matchStatus = sf === 'all' || rowStatus === sf;

                 row.style.display = (matchQ && matchStatus) ? '' : 'none';
             });
         }
     }"
     x-init="$watch('search', () => filterRows()); $watch('statusFilter', () => filterRows())">

    <div class="w-full max-w-[1850px] mx-auto space-y-5">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <a href="{{ route('admin.cooperative-dashboard') }}" class="hover:text-amber-700 transition">Cooperativa Escola</a>
                    <span>/</span>
                    <span class="text-blue-700 font-bold">Moradia Estudantil</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </span>
                        Moradia Estudantil & Alojamentos
                    </span>
                    <span class="rounded-xl bg-blue-100 border border-blue-200 px-2.5 py-0.5 text-xs font-semibold text-blue-800">
                        {{ count($cooperativeHousingTenants) }} estudantes
                    </span>
                </h1>
                <p class="text-xs text-gray-600 mt-0.5 font-normal">
                    Controle de quartos, ocupação e cobrança de taxas de manutenção dos residentes do alojamento
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button @click="createOpen = !createOpen"
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-blue-500 active:scale-95">
                    <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="createOpen ? 'rotate-45' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span x-text="createOpen ? 'Fechar Formulário' : '+ Novo Morador'"></span>
                </button>

                <a href="{{ route('admin.cooperative-members.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-teal-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-teal-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Cooperados</span>
                </a>

                <a href="{{ route('admin.cooperative-dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-amber-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-amber-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Painel Financeiro</span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-900 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Hub</span>
                </a>
            </div>
        </div>

        {{-- Banners de Feedback --}}
        @if(session('success'))
            <div class="rounded-xl bg-emerald-500 text-white px-4 py-3 text-xs font-semibold shadow-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-xl bg-red-600 text-white px-4 py-3 text-xs font-semibold shadow-sm flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        {{-- Formulário Retrátil de Novo Morador --}}
        <div x-show="createOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="rounded-2xl border border-blue-200 bg-white p-5 sm:p-6 shadow-sm"
             style="display: none;">

            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-800">Cadastrar Novo Morador do Alojamento</h2>
                        <p class="text-[11px] text-gray-500 font-normal">Informe o nome do estudante residente e quarto/bloco</p>
                    </div>
                </div>
                <button @click="createOpen = false" type="button" class="text-gray-400 hover:text-gray-600 text-xs font-semibold">&times; Fechar</button>
            </div>

            <form action="{{ route('admin.cooperative-housing-tenants.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Nome do Estudante --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Nome Completo do Estudante <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                               placeholder="Ex: Lucas Ferreira de Souza">
                    </div>

                    {{-- Quarto / Vaga --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Quarto / Alojamento / Vaga
                        </label>
                        <input type="text" name="room" value="{{ old('room') }}"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                               placeholder="Ex: Bloco B - Quarto 04">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="ml-2 text-xs font-bold text-gray-700">Residente Ativo</span>
                    </label>

                    <div class="flex items-center gap-2">
                        <button @click="createOpen = false" type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Cancelar
                        </button>
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-blue-500 active:scale-95 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Salvar Morador</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Filtros, Busca & Seleção em Lote --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">

                {{-- Campo de Busca --}}
                <div class="relative flex-1 min-w-[240px] max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input x-model="search"
                           type="text"
                           placeholder="Buscar por nome ou quarto/alojamento..."
                           class="w-full pl-9 pr-8 py-2 rounded-xl border border-gray-300 bg-gray-50/50 text-xs text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    <button x-show="search" @click="search = ''" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 hover:text-gray-600 text-xs">
                        &times;
                    </button>
                </div>

                {{-- Filtros de Status --}}
                <div class="flex items-center gap-1.5 bg-gray-100 p-1 rounded-xl">
                    <button @click="statusFilter = 'all'"
                            :class="statusFilter === 'all' ? 'bg-white text-gray-900 shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                            class="px-2.5 py-1 rounded-lg text-xs transition">
                        Todos
                    </button>
                    <button @click="statusFilter = 'ativo'"
                            :class="statusFilter === 'ativo' ? 'bg-emerald-600 text-white shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                            class="px-2.5 py-1 rounded-lg text-xs transition">
                        Ativos
                    </button>
                    <button @click="statusFilter = 'inativo'"
                            :class="statusFilter === 'inativo' ? 'bg-gray-700 text-white shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                            class="px-2.5 py-1 rounded-lg text-xs transition">
                        Inativos
                    </button>
                </div>
            </div>

            {{-- Floating Bulk Actions Bar --}}
            <div x-show="selected.length > 0"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="flex flex-wrap items-center justify-between gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-2.5 text-xs text-blue-900"
                 style="display: none;">
                <div class="flex items-center gap-2 font-bold">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white text-[10px]" x-text="selected.length"></span>
                    <span>moradores selecionados</span>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.cooperative-housing-tenants.bulk-action') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <input type="hidden" name="action" value="activate">
                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-emerald-500 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Ativar</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.cooperative-housing-tenants.bulk-action') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <input type="hidden" name="action" value="deactivate">
                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-amber-500 transition">
                            <span>Desativar</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.cooperative-housing-tenants.bulk-action') }}" method="POST" class="inline"
                          onsubmit="return confirm('Tem certeza que deseja excluir os moradores selecionados?')">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-rose-500 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Excluir</span>
                        </button>
                    </form>

                    <button @click="selected = []" type="button" class="text-xs text-gray-500 hover:text-gray-800 ml-1">
                        Cancelar seleção
                    </button>
                </div>
            </div>

            {{-- Tabela --}}
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs text-gray-700">
                    <thead class="bg-gray-50 text-gray-600 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="w-10 px-3.5 py-3">
                                <input type="checkbox"
                                       :checked="isAllSelected()"
                                       :indeterminate.prop="isIndeterminate()"
                                       @change="toggleSelectAll($event.target.checked)"
                                       class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition cursor-pointer">
                            </th>
                            <th class="px-3.5 py-3">Nome do Estudante Residente</th>
                            <th class="px-3.5 py-3">Quarto / Alojamento</th>
                            <th class="px-3.5 py-3 text-center">Mensalidades</th>
                            <th class="px-3.5 py-3 text-center">Status</th>
                            <th class="px-3.5 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($cooperativeHousingTenants as $tenant)
                            @php
                                $initials = strtoupper(substr($tenant->name, 0, 1));
                                if (preg_match('/\s+(\S)/u', $tenant->name, $matches)) {
                                    $initials .= strtoupper($matches[1]);
                                }
                                $searchString = strtolower($tenant->name . ' ' . ($tenant->room ?? ''));
                            @endphp
                            <tr data-id="{{ $tenant->id }}"
                                data-search="{{ $searchString }}"
                                data-status="{{ $tenant->is_active ? 'ativo' : 'inativo' }}"
                                class="hover:bg-blue-50/30 transition {{ !$tenant->is_active ? 'bg-gray-50/70 text-gray-400' : '' }}"
                                :class="selected.includes({{ $tenant->id }}) ? 'bg-blue-50/50' : ''">

                                {{-- Checkbox --}}
                                <td class="px-3.5 py-3">
                                    <input type="checkbox"
                                           :value="{{ $tenant->id }}"
                                           x-model.number="selected"
                                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition cursor-pointer">
                                </td>

                                {{-- Nome --}}
                                <td class="px-3.5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-700 text-white font-bold text-xs shadow-2xs shrink-0">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm">
                                                {{ $tenant->name }}
                                            </div>
                                            <div class="text-[11px] text-gray-500 mt-0.5">
                                                Cadastrado em {{ $tenant->created_at ? $tenant->created_at->format('d/m/Y') : '—' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Quarto --}}
                                <td class="px-3.5 py-3 font-semibold text-gray-700">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-800">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        {{ $tenant->room ?? 'Quarto Geral' }}
                                    </span>
                                </td>

                                {{-- Situação de Mensalidades --}}
                                <td class="px-3.5 py-3 text-center">
                                    <a href="{{ route('admin.cooperative-housing-tenants.dues', $tenant) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold transition
                                       {{ $tenant->isUpToDate() ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 hover:bg-rose-200' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $tenant->isUpToDate() ? 'bg-emerald-600' : 'bg-rose-600' }}"></span>
                                        <span>{{ $tenant->isUpToDate() ? 'Em dia' : 'Pendente' }}</span>
                                        <svg class="w-3 h-3 ml-0.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>

                                {{-- Status Ativo/Inativo --}}
                                <td class="px-3.5 py-3 text-center">
                                    <form action="{{ route('admin.cooperative-housing-tenants.toggle', $tenant) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                title="Clique para alternar status"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold transition {{ $tenant->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $tenant->is_active ? 'bg-emerald-600' : 'bg-gray-400' }}"></span>
                                            <span>{{ $tenant->is_active ? 'Ativo' : 'Inativo' }}</span>
                                        </button>
                                    </form>
                                </td>

                                {{-- Ações --}}
                                <td class="px-3.5 py-3 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('admin.cooperative-housing-tenants.dues', $tenant) }}"
                                           title="Ver / Baixar Mensalidades"
                                           class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 text-blue-800 hover:bg-blue-100 transition shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        </a>

                                        <button @click="openEdit({{ json_encode($tenant) }}, '{{ route('admin.cooperative-housing-tenants.update', $tenant) }}')"
                                                type="button"
                                                title="Editar Informações"
                                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 text-amber-800 hover:bg-amber-100 transition shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <form action="{{ route('admin.cooperative-housing-tenants.destroy', $tenant) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Deseja realmente remover o morador {{ addslashes($tenant->name) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    title="Excluir"
                                                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 transition shadow-2xs">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                    <div class="max-w-sm mx-auto space-y-2">
                                        <div class="flex h-12 w-12 mx-auto items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">Nenhum morador cadastrado no alojamento</p>
                                        <p class="text-xs text-gray-500">Clique no botão acima para adicionar um residente.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    {{-- Modal de Edição Rápida --}}
    <div x-show="editOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-gray-900/60 backdrop-blur-xs"
         style="display: none;">

        <div @click.outside="editOpen = false"
             class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-gray-100 overflow-hidden flex flex-col max-h-[90vh]">

            {{-- Modal Header --}}
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Editar Morador do Alojamento</h2>
                        <p class="text-[11px] text-gray-500 font-normal">Atualize os dados e quarto do estudante</p>
                    </div>
                </div>
                <button @click="editOpen = false" type="button" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
            </div>

            {{-- Modal Body --}}
            <form :action="editTenant.updateUrl" method="POST" class="p-5 space-y-4 overflow-y-auto flex-1">
                @csrf @method('PUT')

                {{-- Nome --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Nome Completo <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" x-model="editTenant.name" required
                           class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 shadow-2xs">
                </div>

                {{-- Quarto --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Quarto / Alojamento / Vaga
                    </label>
                    <input type="text" name="room" x-model="editTenant.room"
                           class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 shadow-2xs">
                </div>

                {{-- Status Ativo --}}
                <div class="pt-1">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" :checked="editTenant.is_active" @change="editTenant.is_active = $event.target.checked">
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="ml-2 text-xs font-bold text-gray-700" x-text="editTenant.is_active ? 'Residente Ativo' : 'Residente Inativo'"></span>
                    </label>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button @click="editOpen = false" type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-blue-500 active:scale-95 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Salvar Alterações</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
