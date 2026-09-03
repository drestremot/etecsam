@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8"
     x-data="{
         search: '',
         statusFilter: 'all',
         categoryFilter: 'all',
         createOpen: false,
         editOpen: false,
         selected: [],
         editIncome: {
             id: null,
             description: '',
             category: '',
             amount: '',
             due_date: '',
             received_date: '',
             notes: '',
             updateUrl: ''
         },
         openEdit(inc, updateUrl) {
             this.editIncome = {
                 id: inc.id,
                 description: inc.description || '',
                 category: inc.category || '',
                 amount: inc.amount || '',
                 due_date: inc.due_date ? inc.due_date.substring(0, 10) : '',
                 received_date: inc.received_date ? inc.received_date.substring(0, 10) : '',
                 notes: inc.notes || '',
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
             const cf = this.categoryFilter;

             document.querySelectorAll('tbody tr[data-id]').forEach(row => {
                 const searchData = (row.getAttribute('data-search') || '').toLowerCase();
                 const rowStatus = row.getAttribute('data-status');
                 const rowCat = row.getAttribute('data-cat');

                 const matchQ = !q || searchData.includes(q);
                 const matchStatus = sf === 'all' || rowStatus === sf;
                 const matchCat = cf === 'all' || rowCat === cf;

                 row.style.display = (matchQ && matchStatus && matchCat) ? '' : 'none';
             });
         }
     }"
     x-init="$watch('search', () => filterRows()); $watch('statusFilter', () => filterRows()); $watch('categoryFilter', () => filterRows())">

    <div class="w-full max-w-[1850px] mx-auto space-y-5">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <a href="{{ route('admin.apm-dashboard') }}" class="hover:text-indigo-600 transition">APM</a>
                    <span>/</span>
                    <span class="text-emerald-700 font-bold">Receitas & Entradas</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </span>
                        Receitas & Entradas da APM
                    </span>
                    <span class="rounded-xl bg-emerald-100 border border-emerald-200 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                        {{ count($apmIncomes) }} lançamentos
                    </span>
                </h1>
                <p class="text-xs text-gray-600 mt-0.5 font-normal">
                    Controle de contribuições voluntárias, cantina, eventos e demais receitas da APM
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button @click="createOpen = !createOpen"
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-emerald-500 active:scale-95">
                    <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="createOpen ? 'rotate-45' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span x-text="createOpen ? 'Fechar Formulário' : '+ Nova Receita'"></span>
                </button>

                <a href="{{ route('admin.apm-expenses.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-rose-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    <span>Despesas / Saídas</span>
                </a>

                <a href="{{ route('admin.apm-dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-indigo-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
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

        {{-- Formulário Retrátil de Nova Entrada --}}
        <div x-show="createOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="rounded-2xl border border-emerald-200 bg-white p-5 sm:p-6 shadow-sm"
             style="display: none;">

            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-800">Lançar Nova Receita / Entrada da APM</h2>
                        <p class="text-[11px] text-gray-500 font-normal">Preencha a descrição, valor e data de vencimento/recebimento</p>
                    </div>
                </div>
                <button @click="createOpen = false" type="button" class="text-gray-400 hover:text-gray-600 text-xs font-semibold">&times; Fechar</button>
            </div>

            <form action="{{ route('admin.apm-incomes.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Descrição --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Descrição da Receita <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="description" value="{{ old('description') }}" required
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                               placeholder="Ex: Contribuição voluntária de associados - 1º Bimestre">
                    </div>

                    {{-- Categoria --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Categoria
                        </label>
                        <input list="apm-income-cats" type="text" name="category" value="{{ old('category') }}"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                               placeholder="Ex: Mensalidades, Cantina, Eventos...">
                        <datalist id="apm-income-cats">
                            <option value="Contribuição Voluntária">
                            <option value="Cantina Escolar">
                            <option value="Festa Junina / Eventos">
                            <option value="Doação">
                            <option value="Rendimento de Aplicações">
                            <option value="Outras Receitas">
                        </datalist>
                    </div>

                    {{-- Valor R$ --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Valor (R$) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}" required
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                               placeholder="0.00">
                    </div>

                    {{-- Data Prevista / Vencimento --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Data de Vencimento / Previsão <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d')) }}" required
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 shadow-2xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>

                    {{-- Data de Recebimento Efetivo --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Data de Recebimento (se já recebido)
                        </label>
                        <input type="date" name="received_date" value="{{ old('received_date') }}"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 shadow-2xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                    </div>

                    {{-- Observações --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Observações / Detalhes
                        </label>
                        <input type="text" name="notes" value="{{ old('notes') }}"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                               placeholder="Detalhes adicionais ou comprovante">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <button @click="createOpen = false" type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-emerald-500 active:scale-95 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Salvar Receita</span>
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
                           placeholder="Buscar por descrição, categoria ou notas..."
                           class="w-full pl-9 pr-8 py-2 rounded-xl border border-gray-300 bg-gray-50/50 text-xs text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                    <button x-show="search" @click="search = ''" class="absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 hover:text-gray-600 text-xs">
                        &times;
                    </button>
                </div>

                {{-- Filtros de Situação e Categoria --}}
                <div class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center gap-1.5 bg-gray-100 p-1 rounded-xl">
                        <button @click="statusFilter = 'all'"
                                :class="statusFilter === 'all' ? 'bg-white text-gray-900 shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                                class="px-2.5 py-1 rounded-lg text-xs transition">
                            Todas
                        </button>
                        <button @click="statusFilter = 'recebido'"
                                :class="statusFilter === 'recebido' ? 'bg-emerald-600 text-white shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                                class="px-2.5 py-1 rounded-lg text-xs transition">
                            Recebidas
                        </button>
                        <button @click="statusFilter = 'pendente'"
                                :class="statusFilter === 'pendente' ? 'bg-amber-500 text-white shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                                class="px-2.5 py-1 rounded-lg text-xs transition">
                            Pendentes
                        </button>
                        <button @click="statusFilter = 'atrasado'"
                                :class="statusFilter === 'atrasado' ? 'bg-rose-600 text-white shadow-2xs font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                                class="px-2.5 py-1 rounded-lg text-xs transition">
                            Atrasadas
                        </button>
                    </div>

                    @php
                        $uniqueIncomeCats = $apmIncomes->pluck('category')->filter()->unique()->sort()->values();
                    @endphp
                    @if($uniqueIncomeCats->count() > 1)
                    <select x-model="categoryFilter"
                            class="rounded-xl border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-700 shadow-2xs focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                        <option value="all">Todas as Categorias ({{ $uniqueIncomeCats->count() }})</option>
                        @foreach($uniqueIncomeCats as $cat)
                            <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
            </div>

            {{-- Floating Bulk Actions Bar --}}
            <div x-show="selected.length > 0"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="flex flex-wrap items-center justify-between gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-2.5 text-xs text-emerald-900"
                 style="display: none;">
                <div class="flex items-center gap-2 font-bold">
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-white text-[10px]" x-text="selected.length"></span>
                    <span>receitas selecionadas</span>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.apm-incomes.bulk-action') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <input type="hidden" name="action" value="mark_received">
                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-emerald-500 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Marcar como Recebidas</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.apm-incomes.bulk-action') }}" method="POST" class="inline">
                        @csrf
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <input type="hidden" name="action" value="mark_pending">
                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-amber-500 transition">
                            <span>Marcar Pendentes</span>
                        </button>
                    </form>

                    <form action="{{ route('admin.apm-incomes.bulk-action') }}" method="POST" class="inline"
                          onsubmit="return confirm('Tem certeza que deseja excluir as receitas selecionadas?')">
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
                                       class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 transition cursor-pointer">
                            </th>
                            <th class="px-3.5 py-3">Descrição da Receita</th>
                            <th class="px-3.5 py-3">Categoria</th>
                            <th class="px-3.5 py-3">Valor</th>
                            <th class="px-3.5 py-3">Vencimento / Previsão</th>
                            <th class="px-3.5 py-3 text-center">Situação</th>
                            <th class="px-3.5 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($apmIncomes as $income)
                            @php
                                $status = $income->status();
                                $statusKey = strtolower($status);
                                $searchString = strtolower($income->description . ' ' . ($income->category ?? '') . ' ' . ($income->notes ?? '') . ' ' . $status);
                            @endphp
                            <tr data-id="{{ $income->id }}"
                                data-search="{{ $searchString }}"
                                data-status="{{ $statusKey }}"
                                data-cat="{{ strtolower($income->category ?? '') }}"
                                class="hover:bg-emerald-50/30 transition {{ $statusKey === 'recebido' ? 'bg-emerald-50/10' : '' }}"
                                :class="selected.includes({{ $income->id }}) ? 'bg-emerald-50/50' : ''">

                                {{-- Checkbox --}}
                                <td class="px-3.5 py-3">
                                    <input type="checkbox"
                                           :value="{{ $income->id }}"
                                           x-model.number="selected"
                                           class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 transition cursor-pointer">
                                </td>

                                {{-- Descrição --}}
                                <td class="px-3.5 py-3">
                                    <div class="font-bold text-gray-900 text-sm">
                                        {{ $income->description }}
                                    </div>
                                    @if($income->notes)
                                        <div class="text-[11px] text-gray-500 mt-0.5">{{ $income->notes }}</div>
                                    @endif
                                </td>

                                {{-- Categoria --}}
                                <td class="px-3.5 py-3">
                                    @if($income->category)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                            {{ $income->category }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Valor --}}
                                <td class="px-3.5 py-3 font-bold text-emerald-700 text-sm">
                                    R$ {{ number_format($income->amount, 2, ',', '.') }}
                                </td>

                                {{-- Data Vencimento / Recebido --}}
                                <td class="px-3.5 py-3">
                                    <div class="text-gray-800 font-medium">{{ $income->due_date->format('d/m/Y') }}</div>
                                    @if($income->received_date)
                                        <div class="text-[10px] text-emerald-700">Recebido em {{ $income->received_date->format('d/m/Y') }}</div>
                                    @endif
                                </td>

                                {{-- Situação --}}
                                <td class="px-3.5 py-3 text-center">
                                    <form action="{{ route('admin.apm-incomes.mark-received', $income) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                title="Clique para alternar recebimento"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold transition
                                                {{ $status === 'Recebido' ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : ($status === 'Atrasado' ? 'bg-rose-100 text-rose-700 hover:bg-rose-200' : 'bg-amber-100 text-amber-800 hover:bg-amber-200') }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $status === 'Recebido' ? 'bg-emerald-600' : ($status === 'Atrasado' ? 'bg-rose-600' : 'bg-amber-500') }}"></span>
                                            <span>{{ $status }}</span>
                                        </button>
                                    </form>
                                </td>

                                {{-- Ações --}}
                                <td class="px-3.5 py-3 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <button @click="openEdit({{ json_encode($income) }}, '{{ route('admin.apm-incomes.update', $income) }}')"
                                                type="button"
                                                title="Editar Informações"
                                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 transition shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <form action="{{ route('admin.apm-incomes.destroy', $income) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Deseja realmente remover a receita {{ addslashes($income->description) }}?')">
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
                                        <div class="flex h-12 w-12 mx-auto items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">Nenhuma receita lançada na APM</p>
                                        <p class="text-xs text-gray-500">Clique no botão acima para adicionar um novo recebimento.</p>
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
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900">Editar Receita da APM</h2>
                        <p class="text-[11px] text-gray-500 font-normal">Atualize os dados e a data de liquidação</p>
                    </div>
                </div>
                <button @click="editOpen = false" type="button" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
            </div>

            {{-- Modal Body --}}
            <form :action="editIncome.updateUrl" method="POST" class="p-5 space-y-4 overflow-y-auto flex-1">
                @csrf @method('PUT')

                {{-- Descrição --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Descrição da Receita <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="description" x-model="editIncome.description" required
                           class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 shadow-2xs">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Categoria --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Categoria
                        </label>
                        <input list="modal-apm-income-cats" type="text" name="category" x-model="editIncome.category"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 shadow-2xs">
                        <datalist id="modal-apm-income-cats">
                            <option value="Contribuição Voluntária">
                            <option value="Cantina Escolar">
                            <option value="Festa Junina / Eventos">
                            <option value="Doação">
                            <option value="Rendimento de Aplicações">
                            <option value="Outras Receitas">
                        </datalist>
                    </div>

                    {{-- Valor R$ --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Valor (R$) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" name="amount" x-model="editIncome.amount" required
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 shadow-2xs">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Vencimento --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Data de Vencimento <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="due_date" x-model="editIncome.due_date" required
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 shadow-2xs">
                    </div>

                    {{-- Recebimento --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Data de Recebimento
                        </label>
                        <input type="date" name="received_date" x-model="editIncome.received_date"
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 shadow-2xs">
                    </div>
                </div>

                {{-- Notas --}}
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Observações
                    </label>
                    <input type="text" name="notes" x-model="editIncome.notes"
                           class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 shadow-2xs">
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button @click="editOpen = false" type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-emerald-500 active:scale-95 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Salvar Alterações</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
