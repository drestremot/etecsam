@extends('admin.layouts.app')
@section('title', 'Nova Reserva de Laboratório')

@section('content')
<div class="max-w-4xl mx-auto space-y-6"
     x-data="{
         spaceId: '',
         date: '',
         loading: false,
         occupied: [],
         minDate: '{{ now()->addDays(2)->format('Y-m-d') }}',

         async loadAvailability() {
             if (!this.spaceId) { this.occupied = []; return; }
             this.loading = true;
             try {
                 const r = await fetch(`/laboratorio/api/disponibilidade/${this.spaceId}`);
                 this.occupied = await r.json();
             } finally { this.loading = false; }
         },

         isOccupied(d) {
             return this.occupied.some(o => o.date === d);
         },

         occupiedSlots(d) {
             return this.occupied.filter(o => o.date === d);
         },

         // Gera os próximos 60 dias (excluindo fins de semana)
         get days() {
             const days = [];
             const today = new Date();
             const min = new Date(today); min.setDate(today.getDate() + 2);
             const end  = new Date(today); end.setDate(today.getDate() + 60);
             let cur = new Date(min);
             while (cur <= end) {
                 const dow = cur.getDay();
                 if (dow !== 0 && dow !== 6) {
                     days.push(cur.toISOString().slice(0,10));
                 }
                 cur.setDate(cur.getDate() + 1);
             }
             return days;
         },

         // Agrupa dias por semana para exibição
         get weeks() {
             const weeks = [];
             let week = [];
             this.days.forEach((d, i) => {
                 week.push(d);
                 if (week.length === 5 || i === this.days.length - 1) {
                     weeks.push(week);
                     week = [];
                 }
             });
             return weeks;
         },

         selectDate(d) {
             if (d >= this.minDate) this.date = d;
         },

         formatDate(d) {
             const [y,m,day] = d.split('-');
             return `${day}/${m}`;
         },

         dayName(d) {
             const names = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
             return names[new Date(d+'T12:00:00').getDay()];
         },

         monthLabel(d) {
             const months = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
             return months[parseInt(d.slice(5,7))-1];
         }
     }">

    <div class="flex items-center gap-3">
        <a href="{{ route('lab.reservations.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nova Reserva de Laboratório</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Reservas com mínimo de 48 horas de antecedência</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3">
        <ul class="text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('lab.reservations.store') }}" method="POST">
        @csrf

        <div class="grid lg:grid-cols-2 gap-6">

            {{-- ── Coluna esquerda: formulário ── --}}
            <div class="space-y-5">

                {{-- Laboratório --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <h2 class="font-bold text-gray-900 dark:text-white mb-4 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-etec-main" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                        1. Selecione o Laboratório
                    </h2>
                    <select name="space_id" x-model="spaceId" @change="loadAvailability()" required
                            class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                        <option value="">Selecione o laboratório/espaço...</option>
                        @foreach($spaces as $s)
                        <option value="{{ $s->id }}" {{ old('space_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}{{ $s->description ? ' — '.$s->description : '' }}
                        </option>
                        @endforeach
                    </select>
                    @error('space_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                    <div x-show="loading" class="mt-2 text-xs text-gray-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                        Carregando disponibilidade...
                    </div>
                </div>

                {{-- Data e horário --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <h2 class="font-bold text-gray-900 dark:text-white mb-4 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-etec-main" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        2. Data e Horário
                    </h2>

                    <input type="hidden" name="reservation_date" :value="date">

                    <div x-show="!spaceId" class="text-sm text-gray-400 italic py-4 text-center">
                        Selecione um laboratório para ver a disponibilidade
                    </div>

                    {{-- Data selecionada --}}
                    <div x-show="date" class="mb-4 flex items-center gap-2 bg-etec-light rounded-lg px-3 py-2">
                        <svg class="w-4 h-4 text-etec-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                        <span class="text-sm font-semibold text-etec-dark">Data selecionada:
                            <span x-text="date ? new Date(date+'T12:00:00').toLocaleDateString('pt-BR',{weekday:'long',day:'2-digit',month:'long'}) : ''"></span>
                        </span>
                        <button type="button" @click="date=''" class="ml-auto text-etec-dark/60 hover:text-etec-dark text-xs">✕ limpar</button>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Início *</label>
                            <input type="time" name="start_time" value="{{ old('start_time') }}" required
                                   class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                            @error('start_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Fim</label>
                            <input type="time" name="end_time" value="{{ old('end_time') }}"
                                   class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                        </div>
                    </div>
                </div>

                {{-- Descrição --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <h2 class="font-bold text-gray-900 dark:text-white mb-4 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-etec-main" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        3. Plano de Aula
                    </h2>
                    <textarea name="description" rows="4" required
                              placeholder="Descreva o objetivo da aula, atividades previstas e como os recursos serão utilizados..."
                              class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none resize-none">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Materiais --}}
                @if($materials->count())
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <h2 class="font-bold text-gray-900 dark:text-white mb-4 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-etec-main" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                        4. Materiais Necessários
                    </h2>
                    <p class="text-xs text-gray-400 mb-3">Selecione apenas os materiais que serão utilizados e informe a quantidade</p>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($materials as $m)
                        <div class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 border border-transparent hover:border-gray-200 dark:hover:border-gray-600 transition">
                            <input type="checkbox" name="materials[{{ $m->id }}][id]" value="{{ $m->id }}" id="mat_{{ $m->id }}"
                                   class="rounded border-gray-300 text-etec-dark focus:ring-etec-dark flex-shrink-0">
                            <label for="mat_{{ $m->id }}" class="flex-1 min-w-0 cursor-pointer">
                                <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $m->name }}</span>
                                @if($m->patrimony_number)
                                    <span class="text-xs text-gray-400 ml-1">· {{ $m->patrimony_number }}</span>
                                @endif
                                <span class="text-xs text-green-600 ml-1">({{ $m->stock_quantity }} {{ $m->unit ?? 'unid.' }} disponíveis)</span>
                            </label>
                            <input type="number" name="materials[{{ $m->id }}][qty]" value="1" min="1" max="{{ $m->stock_quantity }}"
                                   class="w-16 border border-gray-200 dark:border-gray-600 rounded px-2 py-1 text-xs text-center dark:bg-gray-700 dark:text-white">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <button type="submit" :disabled="!date || !spaceId"
                        class="w-full py-3 px-6 bg-etec-dark text-white font-bold rounded-xl hover:bg-etec-main transition disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Solicitar Reserva
                </button>
            </div>

            {{-- ── Coluna direita: calendário de disponibilidade ── --}}
            <div x-show="spaceId" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 h-fit sticky top-4">
                <h2 class="font-bold text-gray-900 dark:text-white mb-4 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-etec-main" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Disponibilidade — próximos 60 dias
                </h2>

                {{-- Legenda --}}
                <div class="flex items-center gap-4 mb-4 text-xs">
                    <div class="flex items-center gap-1.5">
                        <div class="w-4 h-4 rounded bg-green-100 border border-green-300"></div>
                        <span class="text-gray-600 dark:text-gray-400">Disponível</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-4 h-4 rounded bg-red-100 border border-red-300"></div>
                        <span class="text-gray-600 dark:text-gray-400">Ocupado</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-4 h-4 rounded bg-etec-dark border border-etec-dark"></div>
                        <span class="text-gray-600 dark:text-gray-400">Selecionado</span>
                    </div>
                </div>

                {{-- Calendário semanal --}}
                <div class="space-y-1 overflow-y-auto max-h-[500px] pr-1">
                    <template x-for="(week, wi) in weeks" :key="wi">
                        <div>
                            {{-- Label do mês quando muda --}}
                            <template x-if="wi === 0 || week[0].slice(0,7) !== weeks[wi-1]?.[0]?.slice(0,7)">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-3 mb-1 px-1"
                                   x-text="new Date(week[0]+'T12:00:00').toLocaleDateString('pt-BR',{month:'long',year:'numeric'})"></p>
                            </template>
                            <div class="grid grid-cols-5 gap-1">
                                <template x-for="d in week" :key="d">
                                    <button type="button"
                                            @click="selectDate(d)"
                                            :title="isOccupied(d) ? `Ocupado: ${occupiedSlots(d).map(o=>o.start+(o.end?'-'+o.end:'')).join(', ')}` : 'Clique para selecionar'"
                                            :class="{
                                                'bg-etec-dark text-white border-etec-dark': date === d,
                                                'bg-red-50 border-red-200 text-red-700 cursor-not-allowed': isOccupied(d) && date !== d,
                                                'bg-green-50 border-green-200 text-green-800 hover:bg-green-100': !isOccupied(d) && date !== d
                                            }"
                                            class="relative rounded-lg border p-1.5 text-center transition group">
                                        <span class="block text-xs font-bold leading-none" x-text="dayName(d)"></span>
                                        <span class="block text-sm font-bold mt-0.5" x-text="formatDate(d)"></span>
                                        <template x-if="isOccupied(d)">
                                            <svg class="w-3 h-3 mx-auto mt-0.5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                        </template>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Detalhes do dia selecionado --}}
                <template x-if="date && isOccupied(date)">
                    <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                        <p class="text-xs font-bold text-red-700 dark:text-red-400 mb-2">⚠ Horários já reservados neste dia:</p>
                        <template x-for="slot in occupiedSlots(date)" :key="slot.start">
                            <p class="text-xs text-red-600 dark:text-red-400"
                               x-text="`${slot.start}${slot.end ? ' — '+slot.end : ''}`"></p>
                        </template>
                        <p class="text-xs text-red-500 mt-2">Você ainda pode reservar em outro horário neste dia.</p>
                    </div>
                </template>
            </div>

        </div>
    </form>
</div>
@endsection
