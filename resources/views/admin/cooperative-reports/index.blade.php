@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8"
     x-data="{
         search: '',
         categoryFilter: 'all',
         createOpen: false,
         editOpen: false,
         selected: [],
         editReport: {
             id: null,
             title: '',
             category: 'Estatuto',
             period: '',
             published_at: '',
             url: '',
             file_path: null,
             updateUrl: ''
         },
         openEdit(rep, updateUrl) {
             this.editReport = {
                 id: rep.id,
                 title: rep.title || '',
                 category: rep.category || 'Estatuto',
                 period: rep.period || '',
                 published_at: rep.published_at ? rep.published_at.substring(0, 10) : '',
                 url: rep.url || '',
                 file_path: rep.file_path || null,
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
             const cf = this.categoryFilter;

             document.querySelectorAll('tbody tr[data-id]').forEach(row => {
                 const searchData = (row.getAttribute('data-search') || '').toLowerCase();
                 const rowCat = row.getAttribute('data-cat');

                 const matchQ = !q || searchData.includes(q);
                 const matchCat = cf === 'all' || rowCat === cf;

                 row.style.display = (matchQ && matchCat) ? '' : 'none';
             });
         }
     }"
     x-init="$watch('search', () => filterRows()); $watch('categoryFilter', () => filterRows())">

    <div class="w-full max-w-[1850px] mx-auto space-y-5">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <a href="{{ route('admin.cooperative-dashboard') }}" class="hover:text-amber-700 transition">Cooperativa Escola</a>
                    <span>/</span>
                    <span class="text-amber-700 font-bold">Documentos & Prestação de Contas</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-600 text-white shadow-sm">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        Documentos & Relatórios da Cooperativa Escola
                    </span>
                    <span class="rounded-xl bg-amber-100 border border-amber-200 px-2.5 py-0.5 text-xs font-semibold text-amber-800">
                        {{ count($cooperativeReports) }} cadastrados
                    </span>
                </h1>
                <p class="text-xs text-gray-600 mt-0.5 font-normal">
                    Gestão e transparência de Atas, Estatuto Social, Balanços Patrimoniais e Prestações de Contas
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button @click="createOpen = !createOpen"
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-amber-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-amber-500 active:scale-95">
                    <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="createOpen ? 'rotate-45' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span x-text="createOpen ? 'Fechar Formulário' : '+ Novo Documento'"></span>
                </button>

                <a href="{{ route('admin.cooperative-sales.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-emerald-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Vendas da Fazenda</span>
                </a>

                <a href="{{ route('admin.cooperative-members.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-teal-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-teal-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Cooperados</span>
                </a>

                <a href="{{ route('admin.cooperative-dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-900 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Painel Financeiro</span>
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

        {{-- Formulário Retrátil de Novo Documento --}}
        <div x-show="createOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="rounded-2xl border border-amber-200 bg-white p-5 sm:p-6 shadow-sm"
             style="display: none;">

            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-800">Publicar Novo Documento / Relatório da Cooperativa</h2>
                        <p class="text-[11px] text-gray-500 font-normal">Anexe arquivos em PDF ou indique o link oficial</p>
                    </div>
                </div>
                <button @click="createOpen = false" type="button" class="text-gray-400 hover:text-gray-600 text-xs font-semibold">&times; Fechar</button>
            </div>

            <form action="{{ route('admin.cooperative-reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Título --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Título do Documento <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20"
                               placeholder="Ex: Estatuto Social da Cooperativa Escola 2026">
                    </div>

                    {{-- Categoria --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Categoria <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" required
                                class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 shadow-2xs focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                            <option value="Estatuto" {{ old('category') == 'Estatuto' ? 'selected' : '' }}>Estatuto Social</option>
                            <option value="Ata de Reunião" {{ old('category') == 'Ata de Reunião' ? 'selected' : '' }}>Ata de Reunião / Assembleia</option>
                            <option value="Prestação de Contas" {{ old('category') == 'Prestação de Contas' ? 'selected' : '' }}>Prestação de Contas / Balancete</option>
                        </select>
                    </div>

                    {{-- Período --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Período de Referência
                        </label>
                        <input type="text" name="period" value="{{ old('period') }}"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20"
                               placeholder="Ex: 1º Semestre 2026 / Exercício 2025">
                    </div>

                    {{-- Data de Publicação --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Data de Publicação
                        </label>
                        <input type="date" name="published_at" value="{{ old('published_at', date('Y-m-d')) }}"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 shadow-2xs focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20">
                    </div>

                    {{-- Link Externo --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Link Externo (URL Opcional)
                        </label>
                        <input type="url" name="url" value="{{ old('url') }}"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20"
                               placeholder="https://drive.google.com/...">
                    </div>

                    {{-- Arquivo Upload --}}
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Upload do Arquivo (PDF, DOCX, XLSX até 10MB)
                        </label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 shadow-2xs">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <button @click="createOpen = false" type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-amber-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-amber-500 active:scale-95 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Salvar Documento</span>
                    </button>
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
                           placeholder="Buscar por título, categoria ou período..."
                           class="w-full pl-9 pr-8 py-2 rounded-xl border border-gray-300 bg-gray-50/50 text-xs text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition">
                    <button x-show="search" @click="search = ''" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 hover:text-gray-600 text-xs">
                        &times;
                    </button>
                </div>

                {{-- Filtros de Categoria --}}
                <div class="flex items-center gap-1.5 bg-gray-100 p-1 rounded-xl">
                    <button @click="categoryFilter = 'all'"
                            :class="categoryFilter === 'all' ? 'bg-white text-gray-900 shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                            class="px-2.5 py-1 rounded-lg text-xs transition">
                        Todos
                    </button>
                    <button @click="categoryFilter = 'estatuto'"
                            :class="categoryFilter === 'estatuto' ? 'bg-amber-600 text-white shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                            class="px-2.5 py-1 rounded-lg text-xs transition">
                        Estatuto
                    </button>
                    <button @click="categoryFilter = 'ata de reunião'"
                            :class="categoryFilter === 'ata de reunião' ? 'bg-amber-600 text-white shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                            class="px-2.5 py-1 rounded-lg text-xs transition">
                        Atas
                    </button>
                    <button @click="categoryFilter = 'prestação de contas'"
                            :class="categoryFilter === 'prestação de contas' ? 'bg-amber-600 text-white shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                            class="px-2.5 py-1 rounded-lg text-xs transition">
                        Prestações
                    </button>
                </div>
            </div>

            {{-- Floating Bulk Actions Bar --}}
            <div x-show="selected.length > 0"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="flex flex-wrap items-center justify-between gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5 text-xs text-amber-900"
                 style="display: none;">
                <div class="flex items-center gap-2 font-bold">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-600 text-white text-[10px]" x-text="selected.length"></span>
                    <span>documentos selecionados</span>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.cooperative-reports.bulk-action') }}" method="POST" class="inline"
                          onsubmit="return confirm('Tem certeza que deseja excluir os documentos selecionados?')">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-rose-500 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Excluir Selecionados</span>
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
                                       class="h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500 transition cursor-pointer">
                            </th>
                            <th class="px-3.5 py-3">Título do Documento</th>
                            <th class="px-3.5 py-3">Categoria</th>
                            <th class="px-3.5 py-3">Período</th>
                            <th class="px-3.5 py-3">Publicação</th>
                            <th class="px-3.5 py-3 text-center">Arquivo / Link</th>
                            <th class="px-3.5 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($cooperativeReports as $report)
                            @php
                                $searchString = strtolower($report->title . ' ' . $report->category . ' ' . ($report->period ?? ''));
                            @endphp
                            <tr data-id="{{ $report->id }}"
                                data-search="{{ $searchString }}"
                                data-cat="{{ strtolower($report->category) }}"
                                class="hover:bg-amber-50/30 transition"
                                :class="selected.includes({{ $report->id }}) ? 'bg-amber-50/50' : ''">

                                {{-- Checkbox --}}
                                <td class="px-3.5 py-3">
                                    <input type="checkbox"
                                           :value="{{ $report->id }}"
                                           x-model.number="selected"
                                           class="h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500 transition cursor-pointer">
                                </td>

                                {{-- Título --}}
                                <td class="px-3.5 py-3 font-bold text-gray-900 text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 text-amber-700 shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </span>
                                        <span>{{ $report->title }}</span>
                                    </div>
                                </td>

                                {{-- Categoria --}}
                                <td class="px-3.5 py-3 text-gray-700">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                        {{ $report->category === 'Estatuto' ? 'bg-blue-100 text-blue-800' : ($report->category === 'Ata de Reunião' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                        {{ $report->category }}
                                    </span>
                                </td>

                                {{-- Período --}}
                                <td class="px-3.5 py-3 font-semibold text-gray-700">
                                    {{ $report->period ?? '—' }}
                                </td>

                                {{-- Publicado em --}}
                                <td class="px-3.5 py-3 text-gray-600">
                                    {{ $report->published_at ? $report->published_at->format('d/m/Y') : '—' }}
                                </td>

                                {{-- Arquivo / Link --}}
                                <td class="px-3.5 py-3 text-center">
                                    @if($report->file_path)
                                        <a href="{{ photo_url($report->file_path) }}" target="_blank"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Baixar Arquivo</span>
                                        </a>
                                    @elseif($report->url)
                                        <a href="{{ $report->url }}" target="_blank"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            <span>Acessar Link</span>
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Ações --}}
                                <td class="px-3.5 py-3 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <button @click="openEdit({{ json_encode($report) }}, '{{ route('admin.cooperative-reports.update', $report) }}')"
                                                type="button"
                                                title="Editar Informações"
                                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 text-amber-800 hover:bg-amber-100 transition shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <form action="{{ route('admin.cooperative-reports.destroy', $report) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Deseja realmente remover o documento {{ addslashes($report->title) }}?')">
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
                                <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                                    <div class="max-w-sm mx-auto space-y-2">
                                        <div class="flex h-12 w-12 mx-auto items-center justify-center rounded-2xl bg-amber-50 text-amber-700">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">Nenhum documento cadastrado na Cooperativa</p>
                                        <p class="text-xs text-gray-500">Clique no botão acima para anexar atas, estatutos ou balancetes.</p>
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
             class="w-full max-w-xl rounded-2xl bg-white shadow-2xl border border-gray-100 overflow-hidden flex flex-col max-h-[90vh]">

            {{-- Modal Header --}}
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-600 text-white shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Editar Documento da Cooperativa</h2>
                        <p class="text-[11px] text-gray-500 font-normal">Atualize os dados, categoria ou anexo</p>
                    </div>
                </div>
                <button @click="editOpen = false" type="button" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
            </div>

            {{-- Modal Body --}}
            <form :action="editReport.updateUrl" method="POST" enctype="multipart/form-data" class="p-5 space-y-4 overflow-y-auto flex-1">
                @csrf @method('PUT')

                {{-- Título --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Título do Documento <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" x-model="editReport.title" required
                           class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20 shadow-2xs">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Categoria --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Categoria <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" x-model="editReport.category" required
                                class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20 shadow-2xs">
                            <option value="Estatuto">Estatuto Social</option>
                            <option value="Ata de Reunião">Ata de Reunião / Assembleia</option>
                            <option value="Prestação de Contas">Prestação de Contas / Balancete</option>
                        </select>
                    </div>

                    {{-- Período --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Período de Referência
                        </label>
                        <input type="text" name="period" x-model="editReport.period"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20 shadow-2xs">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Data de Publicação --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Data de Publicação
                        </label>
                        <input type="date" name="published_at" x-model="editReport.published_at"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20 shadow-2xs">
                    </div>

                    {{-- Link Externo --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Link Externo (Opcional)
                        </label>
                        <input type="url" name="url" x-model="editReport.url"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20 shadow-2xs">
                    </div>
                </div>

                {{-- Substituir Arquivo --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Substituir Arquivo Anexo (Opcional)
                    </label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx"
                           class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 shadow-2xs">
                    <p x-show="editReport.file_path" class="text-[11px] text-amber-700 mt-1 font-semibold">
                        * Já existe um arquivo anexado. Envie um novo apenas se desejar substituí-lo.
                    </p>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button @click="editOpen = false" type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-amber-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-amber-500 active:scale-95 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Salvar Alterações</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
