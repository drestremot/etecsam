@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8"
     x-data="{
         search: '',
         statusFilter: 'all',
         roleFilter: 'all',
         createOpen: false,
         editOpen: false,
         selected: [],
         editManager: {
             id: null,
             name: '',
             role: '',
             email: '',
             phone: '',
             is_active: true,
             photoUrl: '',
             updateUrl: ''
         },
         openEdit(m, updateUrl, photoUrl) {
             this.editManager = {
                 id: m.id,
                 name: m.name || '',
                 role: m.role || '',
                 email: m.email || '',
                 phone: m.phone || '',
                 is_active: Boolean(m.is_active),
                 photoUrl: photoUrl || '',
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
             const rf = this.roleFilter;

             document.querySelectorAll('tbody tr[data-id]').forEach(row => {
                 const searchData = (row.getAttribute('data-search') || '').toLowerCase();
                 const rowStatus = row.getAttribute('data-status');
                 const rowRole = row.getAttribute('data-role');

                 const matchQ = !q || searchData.includes(q);
                 const matchStatus = sf === 'all' || rowStatus === sf;
                 const matchRole = rf === 'all' || rowRole === rf;

                 row.style.display = (matchQ && matchStatus && matchRole) ? '' : 'none';
             });
         }
     }"
     x-init="$watch('search', () => filterRows()); $watch('statusFilter', () => filterRows()); $watch('roleFilter', () => filterRows())">

    <div class="w-full max-w-[1850px] mx-auto space-y-5">

        <!-- Top Header (Padrão Usuários & Colaboradores) -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <a href="{{ route('admin.apm-dashboard') }}" class="hover:text-indigo-600 transition">APM</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-bold">Gestores da APM</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        Gestores & Diretoria da APM
                    </span>
                    <span class="rounded-xl bg-indigo-100 border border-indigo-200 px-2.5 py-0.5 text-xs font-semibold text-indigo-700">
                        {{ count($apmManagers) }} membros cadastrados
                    </span>
                </h1>
                <p class="text-xs text-gray-600 mt-0.5 font-normal">
                    Gestão da diretoria executiva, conselho fiscal, cargos estatutários e contatos oficiais da APM
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button @click="createOpen = !createOpen"
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-indigo-500 active:scale-95">
                    <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="createOpen ? 'rotate-45' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span x-text="createOpen ? 'Fechar Formulário' : '+ Novo Gestor'"></span>
                </button>

                <a href="{{ route('admin.apm-dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-emerald-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Financeiro APM</span>
                </a>

                <a href="{{ route('admin.apm-reports.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-purple-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-purple-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Documentos & Atas</span>
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
        @if($errors->any())
            <div class="rounded-xl bg-rose-600 text-white px-4 py-3 text-xs font-semibold shadow-sm space-y-1">
                <div class="flex items-center justify-between font-bold">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Existem erros no formulário:
                    </span>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-[11px] font-normal pl-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Card de Cadastro Rápido de Novo Gestor (Padrão Accordion Moderno) --}}
        <div x-show="createOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="rounded-2xl border border-indigo-200 bg-white p-5 sm:p-6 shadow-sm"
             style="display: none;">

            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-800">Cadastrar Novo Gestor / Membro da Diretoria da APM</h2>
                        <p class="text-[11px] text-gray-500 font-normal">Preencha as informações do membro da diretoria executiva ou conselho fiscal</p>
                    </div>
                </div>
                <button @click="createOpen = false" type="button" class="text-gray-400 hover:text-gray-600 text-xs font-semibold">&times; Fechar</button>
            </div>

            <form action="{{ route('admin.apm-managers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Nome Completo --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Nome Completo <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                               placeholder="Ex: João da Silva">
                    </div>

                    {{-- Cargo Estatutário na APM --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Cargo na APM <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input list="apm-roles-list" type="text" name="role" value="{{ old('role') }}" required
                                   class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                   placeholder="Selecione ou digite o cargo...">
                            <datalist id="apm-roles-list">
                                <option value="Presidente">
                                <option value="Vice-Presidente">
                                <option value="1º Diretor Financeiro (Tesoureiro)">
                                <option value="2º Diretor Financeiro">
                                <option value="1º Secretário">
                                <option value="2º Secretário">
                                <option value="Diretor Cultural">
                                <option value="Diretor de Patrimônio">
                                <option value="Conselho Fiscal (Titular)">
                                <option value="Conselho Fiscal (Suplente)">
                                <option value="Diretor Executivo">
                                <option value="Membro Colaborador">
                            </datalist>
                        </div>
                    </div>

                    {{-- E-mail --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            E-mail de Contato
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                               placeholder="joao@etecsam.com.br">
                    </div>

                    {{-- Telefone / WhatsApp --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Telefone / WhatsApp
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                               placeholder="(18) 99999-9999">
                    </div>

                    {{-- Foto do Gestor --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Foto de Perfil
                        </label>
                        <input type="file" name="photo" accept="image/*"
                               class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                    </div>

                    {{-- Status Inicial --}}
                    <div class="flex items-center gap-2 pt-5">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ml-2 text-xs font-bold text-gray-700">Membro Ativo</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <button @click="createOpen = false" type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-indigo-500 active:scale-95 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Salvar e Cadastrar</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Filtros, Busca & Seleção em Lote (Padrão de Usuários) --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                
                {{-- Campo de Busca --}}
                <div class="relative flex-1 min-w-[240px] max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input x-model="search"
                           type="text"
                           placeholder="Buscar por nome, cargo, e-mail ou telefone..."
                           class="w-full pl-9 pr-8 py-2 rounded-xl border border-gray-300 bg-gray-50/50 text-xs text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <button x-show="search" @click="search = ''" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 hover:text-gray-600 text-xs">
                        &times;
                    </button>
                </div>

                {{-- Filtros de Status e Cargo --}}
                <div class="flex flex-wrap items-center gap-2">
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

                    @php
                        $uniqueRoles = $apmManagers->pluck('role')->unique()->sort()->values();
                    @endphp
                    @if($uniqueRoles->count() > 1)
                    <select x-model="roleFilter"
                            class="rounded-xl border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-700 shadow-2xs focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <option value="all">Todos os Cargos ({{ $uniqueRoles->count() }})</option>
                        @foreach($uniqueRoles as $role)
                            <option value="{{ strtolower($role) }}">{{ $role }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
            </div>

            {{-- Floating / Top Bulk Actions Bar --}}
            <div x-show="selected.length > 0"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="flex flex-wrap items-center justify-between gap-3 bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-2.5 text-xs text-indigo-900"
                 style="display: none;">
                <div class="flex items-center gap-2 font-bold">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-white text-[10px]" x-text="selected.length"></span>
                    <span>gestores selecionados</span>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.apm-managers.bulk-action') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <input type="hidden" name="action" value="activate">
                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-emerald-500 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Ativar Selecionados</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.apm-managers.bulk-action') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <input type="hidden" name="action" value="deactivate">
                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-amber-500 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            <span>Desativar</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.apm-managers.bulk-action') }}" method="POST" class="inline"
                          onsubmit="return confirm('Tem certeza que deseja excluir os gestores selecionados?')">
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

            {{-- Tabela no Padrão de Usuários --}}
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs text-gray-700">
                    <thead class="bg-gray-50 text-gray-600 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="w-10 px-3.5 py-3">
                                <input type="checkbox"
                                       :checked="isAllSelected()"
                                       :indeterminate.prop="isIndeterminate()"
                                       @change="toggleSelectAll($event.target.checked)"
                                       class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition cursor-pointer">
                            </th>
                            <th class="px-3.5 py-3">Membro / Gestor</th>
                            <th class="px-3.5 py-3">Cargo Estatutário</th>
                            <th class="px-3.5 py-3">Contatos (E-mail & Telefone)</th>
                            <th class="px-3.5 py-3 text-center">Status</th>
                            <th class="px-3.5 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($apmManagers as $manager)
                            @php
                                $initials = strtoupper(substr($manager->name, 0, 1));
                                if (preg_match('/\s+(\S)/u', $manager->name, $matches)) {
                                    $initials .= strtoupper($matches[1]);
                                }
                                $photoSrc = $manager->photo ? Storage::url($manager->photo) : null;
                                $searchString = strtolower($manager->name . ' ' . $manager->role . ' ' . $manager->email . ' ' . $manager->phone);
                            @endphp
                            <tr data-id="{{ $manager->id }}"
                                data-search="{{ $searchString }}"
                                data-status="{{ $manager->is_active ? 'ativo' : 'inativo' }}"
                                data-role="{{ strtolower($manager->role) }}"
                                class="hover:bg-indigo-50/30 transition {{ !$manager->is_active ? 'bg-gray-50/70 text-gray-400' : '' }}"
                                :class="selected.includes({{ $manager->id }}) ? 'bg-indigo-50/50' : ''">

                                {{-- Checkbox Individual --}}
                                <td class="px-3.5 py-3">
                                    <input type="checkbox"
                                           :value="{{ $manager->id }}"
                                           x-model.number="selected"
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 transition cursor-pointer">
                                </td>

                                {{-- Foto e Nome --}}
                                <td class="px-3.5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex-shrink-0">
                                            @if($photoSrc)
                                                <img src="{{ $photoSrc }}" alt="{{ $manager->name }}"
                                                     class="h-10 w-10 rounded-full object-cover border-2 border-indigo-100 shadow-2xs">
                                            @else
                                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 text-white font-bold text-xs shadow-2xs">
                                                    {{ $initials }}
                                                </div>
                                            @endif
                                            <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-white {{ $manager->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                                                <span>{{ $manager->name }}</span>
                                            </div>
                                            <div class="text-[11px] text-gray-500 flex items-center gap-2 mt-0.5">
                                                <span>Membro da APM</span>
                                                <span class="text-gray-300">•</span>
                                                <span>Cadastrado em {{ $manager->created_at ? $manager->created_at->format('d/m/Y') : '—' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Cargo Estatutário --}}
                                <td class="px-3.5 py-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-semibold bg-indigo-50 text-indigo-800 border border-indigo-200">
                                        <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                        {{ $manager->role }}
                                    </span>
                                </td>

                                {{-- Contatos --}}
                                <td class="px-3.5 py-3">
                                    <div class="space-y-0.5">
                                        @if($manager->email)
                                            <div class="flex items-center gap-1.5 text-gray-700">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                <a href="mailto:{{ $manager->email }}" class="hover:text-indigo-600 hover:underline">{{ $manager->email }}</a>
                                            </div>
                                        @endif
                                        @if($manager->phone)
                                            <div class="flex items-center gap-1.5 text-gray-600">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                <span>{{ $manager->phone }}</span>
                                            </div>
                                        @endif
                                        @if(!$manager->email && !$manager->phone)
                                            <span class="text-gray-400 italic">Nenhum contato registrado</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-3.5 py-3 text-center">
                                    <form action="{{ route('admin.apm-managers.toggle', $manager) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                title="Clique para {{ $manager->is_active ? 'desativar' : 'ativar' }}"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold transition {{ $manager->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $manager->is_active ? 'bg-emerald-600' : 'bg-gray-400' }}"></span>
                                            <span>{{ $manager->is_active ? 'Ativo' : 'Inativo' }}</span>
                                        </button>
                                    </form>
                                </td>

                                {{-- Ações --}}
                                <td class="px-3.5 py-3 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <button @click="openEdit({{ json_encode($manager) }}, '{{ route('admin.apm-managers.update', $manager) }}', '{{ $photoSrc }}')"
                                                type="button"
                                                title="Editar Informações"
                                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <form action="{{ route('admin.apm-managers.destroy', $manager) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Deseja realmente remover o(a) gestor(a) {{ addslashes($manager->name) }} da APM?')">
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
                                        <div class="flex h-12 w-12 mx-auto items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">Nenhum gestor cadastrado na APM</p>
                                        <p class="text-xs text-gray-500">Clique no botão acima para adicionar o presidente, tesoureiro, conselheiros ou secretários.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    {{-- Modal de Edição Rápida (Padrão Usuários) --}}
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
             class="w-full max-w-xl rounded-2xl bg-white shadow-2xl border border-gray-100 overflow-hidden flex flex-col max-h-[90vh]">

            {{-- Modal Header --}}
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Editar Membro da APM</h2>
                        <p class="text-[11px] text-gray-500 font-normal">Atualize os dados e cargos estatutários</p>
                    </div>
                </div>
                <button @click="editOpen = false" type="button" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
            </div>

            {{-- Modal Body --}}
            <form :action="editManager.updateUrl" method="POST" enctype="multipart/form-data" class="p-5 space-y-4 overflow-y-auto flex-1">
                @csrf @method('PUT')

                {{-- Nome Completo --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Nome Completo <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" x-model="editManager.name" required
                           class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 shadow-2xs"
                           placeholder="Nome completo">
                </div>

                {{-- Cargo Estatutário --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Cargo na APM <span class="text-rose-500">*</span>
                    </label>
                    <input list="modal-apm-roles-list" type="text" name="role" x-model="editManager.role" required
                           class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 shadow-2xs"
                           placeholder="Ex: Presidente, Diretor Financeiro, etc.">
                    <datalist id="modal-apm-roles-list">
                        <option value="Presidente">
                        <option value="Vice-Presidente">
                        <option value="1º Diretor Financeiro (Tesoureiro)">
                        <option value="2º Diretor Financeiro">
                        <option value="1º Secretário">
                        <option value="2º Secretário">
                        <option value="Diretor Cultural">
                        <option value="Diretor de Patrimônio">
                        <option value="Conselho Fiscal (Titular)">
                        <option value="Conselho Fiscal (Suplente)">
                        <option value="Diretor Executivo">
                        <option value="Membro Colaborador">
                    </datalist>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- E-mail --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            E-mail de Contato
                        </label>
                        <input type="email" name="email" x-model="editManager.email"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 shadow-2xs"
                               placeholder="email@etecsam.com.br">
                    </div>

                    {{-- Telefone --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Telefone / WhatsApp
                        </label>
                        <input type="text" name="phone" x-model="editManager.phone"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 shadow-2xs"
                               placeholder="(18) 99999-9999">
                    </div>
                </div>

                {{-- Foto --}}
                <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-3.5 space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">Foto de Perfil</label>
                    <div class="flex items-center gap-3">
                        <template x-if="editManager.photoUrl">
                            <img :src="editManager.photoUrl" class="h-12 w-12 rounded-full object-cover border border-gray-300 shadow-2xs">
                        </template>
                        <div class="flex-1">
                            <input type="file" name="photo" accept="image/*"
                                   class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                            <p class="text-[10px] text-gray-400 mt-0.5">Selecione para substituir a foto existente (PNG, JPG ou WebP até 4MB)</p>
                        </div>
                    </div>
                </div>

                {{-- Status Ativo --}}
                <div class="pt-1">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" :checked="editManager.is_active" @change="editManager.is_active = $event.target.checked">
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="ml-2 text-xs font-bold text-gray-700" x-text="editManager.is_active ? 'Membro Ativo' : 'Membro Inativo'"></span>
                    </label>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button @click="editOpen = false" type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-indigo-500 active:scale-95 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Salvar Alterações</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
