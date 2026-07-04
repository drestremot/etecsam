@extends('admin.layouts.app')
@section('title', 'Nova Reserva')

@section('content')
<div class="max-w-6xl mx-auto space-y-6"
     x-data="{
         spaceId: '',
         selectedDate: '',
         selectedStart: '',
         selectedEnd: '',
         loading: false,
         occupied: [],

         // Semana atual (base segunda-feira)
         weekStart: (() => {
             const d = new Date();
             d.setDate(d.getDate() + 2); // mínimo 48h
             // Avança para a próxima segunda se necessário
             const dow = d.getDay();
             if (dow === 0) d.setDate(d.getDate() + 1);
             if (dow === 6) d.setDate(d.getDate() + 2);
             // Recua para segunda da semana
             const diff = (d.getDay() + 6) % 7;
             d.setDate(d.getDate() - diff);
             return d.toISOString().slice(0,10);
         })(),

         minDate: '{{ now()->addDays(2)->format('Y-m-d') }}',
         // Slots de 30 em 30 minutos: 07:00 → 22:30
         hours: Array.from({length: 32}, (_, i) => {
             const t = 7*60 + i*30;
             return `${String(Math.floor(t/60)).padStart(2,'0')}:${String(t%60).padStart(2,'0')}`;
         }),

         get weekDays() {
             const days = [];
             const start = new Date(this.weekStart + 'T12:00:00');
             for (let i = 0; i < 5; i++) {
                 const d = new Date(start);
                 d.setDate(start.getDate() + i);
                 days.push(d.toISOString().slice(0,10));
             }
             return days;
         },

         prevWeek() {
             const d = new Date(this.weekStart + 'T12:00:00');
             d.setDate(d.getDate() - 7);
             this.weekStart = d.toISOString().slice(0,10);
         },

         nextWeek() {
             const d = new Date(this.weekStart + 'T12:00:00');
             d.setDate(d.getDate() + 7);
             this.weekStart = d.toISOString().slice(0,10);
         },

         canPrevWeek() {
             const prevMon = new Date(this.weekStart + 'T12:00:00');
             prevMon.setDate(prevMon.getDate() - 7);
             return prevMon.toISOString().slice(0,10) >= this.minDate.slice(0,7) + '-01';
         },

         monthLabel() {
             const d = new Date(this.weekStart + 'T12:00:00');
             return d.toLocaleDateString('pt-BR', {month:'long', year:'numeric'});
         },

         dayLabel(iso) {
             const d = new Date(iso + 'T12:00:00');
             return d.toLocaleDateString('pt-BR', {weekday:'short', day:'2-digit', month:'2-digit'});
         },

         isToday(iso) {
             return iso === new Date().toISOString().slice(0,10);
         },

         isPast(iso) {
             return iso < this.minDate;
         },

         // Verifica se determinado horário está ocupado
         isOccupied(date, hour) {
             return this.occupied.some(o => {
                 if (o.date !== date) return false;
                 const oStart = o.start;
                 const oEnd   = o.end || o.start;
                 return hour >= oStart && hour < oEnd;
             });
         },

         getOccupiedInfo(date, hour) {
             const slot = this.occupied.find(o => o.date === date && hour >= o.start && hour < (o.end || '23:59'));
             return slot ? `Reservado ${slot.start}${slot.end ? '–'+slot.end : ''}` : '';
         },

         isSelected(date, hour) {
             if (this.selectedDate !== date) return false;
             if (!this.selectedStart) return false;
             const end = this.selectedEnd || this.selectedStart;
             return hour >= this.selectedStart && hour <= end;
         },

         selectSlot(date, hour) {
             if (this.isPast(date) || this.isOccupied(date, hour)) return;
             if (this.selectedDate === date && this.selectedStart === hour) {
                 // Desmarca
                 this.selectedDate = ''; this.selectedStart = ''; this.selectedEnd = '';
                 return;
             }
             this.selectedDate  = date;
             this.selectedStart = hour;
             this.selectedEnd   = this.addHour(hour);
         },

         extendSlot(date, hour) {
             if (this.selectedDate !== date || !this.selectedStart) return;
             if (hour > this.selectedStart) this.selectedEnd = this.addHour(hour);
         },

         addHour(h) {
             // Avança 30 minutos (um slot)
             const [hh, mm] = h.split(':').map(Number);
             const t = hh*60 + mm + 30;
             return `${String(Math.floor(t/60)).padStart(2,'0')}:${String(t%60).padStart(2,'0')}`;
         },

         async loadAvailability() {
             if (!this.spaceId) { this.occupied = []; return; }
             this.loading = true;
             try {
                 const r = await fetch(`/laboratorio/api/disponibilidade/${this.spaceId}`);
                 this.occupied = await r.json();
             } finally { this.loading = false; }
         },

         get hasSelection() {
             return this.selectedDate && this.selectedStart;
         },

         get selectionLabel() {
             if (!this.hasSelection) return '';
             const d = new Date(this.selectedDate + 'T12:00:00');
             const dl = d.toLocaleDateString('pt-BR', {weekday:'long', day:'2-digit', month:'long'});
             return `${dl} · ${this.selectedStart}${this.selectedEnd ? ' – ' + this.selectedEnd : ''}`;
         }
     }">

    {{-- Cabeçalho --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('lab.reservations.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nova Reserva de Laboratório</h1>
            <p class="text-sm text-gray-500 mt-0.5">Mínimo 48 horas de antecedência · Selecione o laboratório e clique no horário desejado</p>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3">
        @foreach($errors->all() as $e)<p class="text-sm text-red-700">• {{ $e }}</p>@endforeach
    </div>
    @endif

    <form action="{{ route('lab.reservations.store') }}" method="POST">
        @csrf
        <input type="hidden" name="reservation_date" :value="selectedDate">
        <input type="hidden" name="start_time"       :value="selectedStart">
        <input type="hidden" name="end_time"         :value="selectedEnd">

        <div class="grid lg:grid-cols-3 gap-6">

            {{-- ── Painel lateral (laboratório + info seleção + materiais + plano) ── --}}
            <div class="space-y-5">

                {{-- Laboratório --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Laboratório / Espaço *</label>
                    @if($spaces->isEmpty())
                        <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-3 text-sm text-yellow-800">
                            ⚠ Nenhum espaço didático cadastrado ainda.
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('lab.spaces.create') }}" class="font-bold underline ml-1">Cadastrar agora →</a>
                            @else
                                Solicite ao administrador que cadastre os espaços.
                            @endif
                        </div>
                    @else
                        <select name="space_id" x-model="spaceId" @change="loadAvailability(); selectedDate=''; selectedStart=''; selectedEnd='';" required
                                class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                            <option value="">Selecione o laboratório...</option>
                            @foreach($spaces as $s)
                            <option value="{{ $s->id }}" {{ old('space_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}{{ $s->description ? ' — '.Str::limit($s->description, 40) : '' }}
                            </option>
                            @endforeach
                        </select>
                        <div x-show="loading" class="mt-2 text-xs text-gray-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                            Carregando disponibilidade...
                        </div>
                    @endif
                </div>

                {{-- Seleção atual --}}
                <div class="rounded-xl border-2 p-4 transition"
                     :class="hasSelection ? 'border-etec-dark bg-etec-light/50' : 'border-dashed border-gray-200 dark:border-gray-600'">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Horário selecionado</p>
                    <template x-if="hasSelection">
                        <div>
                            <p class="font-semibold text-etec-dark dark:text-white text-sm" x-text="selectionLabel"></p>
                            <button type="button" @click="selectedDate='';selectedStart='';selectedEnd=''"
                                    class="text-xs text-red-400 hover:text-red-600 mt-1">✕ Limpar seleção</button>
                        </div>
                    </template>
                    <template x-if="!hasSelection">
                        <p class="text-sm text-gray-400 italic">Clique em um horário no calendário →</p>
                    </template>
                </div>

                {{-- Plano de aula --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Plano de Aula *</label>
                    <textarea name="description" rows="4" required
                              placeholder="Descreva o objetivo da aula, atividades e como os recursos serão utilizados..."
                              class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none resize-none">{{ old('description') }}</textarea>
                </div>

                {{-- Materiais --}}
                {{-- Materiais (sempre visível) --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5"
                     x-data="{ sel: {} }">
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide">Materiais / Recursos Necessários</label>
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('lab.materials.create') }}" target="_blank"
                           class="text-xs text-etec-main hover:underline font-semibold">+ Cadastrar material</a>
                        @endif
                    </div>

                    @if($materials->isEmpty())
                        <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-4 text-sm text-yellow-800 mt-2">
                            <p class="font-semibold mb-1">⚠ Nenhum material cadastrado ainda.</p>
                            @if(auth()->user()->is_admin)
                                <p class="text-xs mb-2">Cadastre os equipamentos e materiais do laboratório para que os professores possam solicitá-los nas reservas.</p>
                                <a href="{{ route('lab.materials.create') }}"
                                   class="inline-flex items-center gap-1.5 bg-yellow-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-yellow-700 transition">
                                    Cadastrar materiais agora →
                                </a>
                            @else
                                <p class="text-xs">Solicite ao administrador que cadastre os materiais disponíveis. A atividade pode prosseguir sem materiais, se necessário.</p>
                            @endif
                        </div>
                    @else
                        <p class="text-xs text-gray-400 mb-3">Marque os itens necessários e informe a quantidade. Apenas itens com estoque disponível podem ser solicitados.</p>
                        <div class="space-y-1.5 max-h-72 overflow-y-auto pr-1">
                            @foreach($materials as $m)
                            <div class="flex items-center gap-3 p-2.5 rounded-lg border transition"
                                 :class="sel[{{ $m->id }}] ? 'border-etec-main bg-etec-light/40 dark:bg-etec-dark/30' : 'border-gray-100 dark:border-gray-700 hover:border-etec-light hover:bg-gray-50 dark:hover:bg-gray-700/50'">

                                <input type="checkbox"
                                       id="mat_{{ $m->id }}"
                                       name="mat_ids[]"
                                       value="{{ $m->id }}"
                                       {{ $m->stock_quantity == 0 ? 'disabled' : '' }}
                                       @change="sel[{{ $m->id }}] = $event.target.checked"
                                       class="rounded border-gray-300 text-etec-dark focus:ring-etec-dark flex-shrink-0 w-4 h-4 cursor-pointer disabled:opacity-40">

                                <label for="mat_{{ $m->id }}" class="flex-1 min-w-0 cursor-pointer select-none">
                                    <span class="text-sm font-medium text-gray-800 dark:text-white block leading-tight">
                                        {{ $m->name }}
                                        @if($m->stock_quantity == 0)
                                            <span class="text-xs font-normal text-red-400 ml-1">(sem estoque)</span>
                                        @endif
                                    </span>
                                    <span class="text-xs text-gray-400 flex items-center gap-1.5 mt-0.5">
                                        @if($m->patrimony_number)
                                            <span>Patrim. {{ $m->patrimony_number }}</span>
                                            <span class="text-gray-200">·</span>
                                        @endif
                                        Estoque disponível:
                                        <strong class="{{ $m->stock_quantity > 5 ? 'text-green-600' : ($m->stock_quantity > 0 ? 'text-yellow-600' : 'text-red-500') }}">
                                            {{ $m->stock_quantity }} {{ $m->unit ?? 'unid.' }}
                                        </strong>
                                    </span>
                                </label>

                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <span class="text-xs text-gray-500">Qtd:</span>
                                    <input type="number"
                                           name="mat_qty[{{ $m->id }}]"
                                           value="1"
                                           min="1"
                                           max="{{ $m->stock_quantity }}"
                                           :disabled="!sel[{{ $m->id }}]"
                                           @click.stop=""
                                           class="w-16 border rounded-lg px-2 py-1 text-sm text-center font-bold focus:ring-2 focus:ring-etec-dark outline-none transition"
                                           :class="sel[{{ $m->id }}]
                                               ? 'border-etec-main bg-white dark:bg-gray-700 dark:text-white text-etec-dark'
                                               : 'border-gray-200 bg-gray-50 text-gray-300 cursor-not-allowed'">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Resumo --}}
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <p class="text-xs text-gray-400">
                                <span x-text="Object.values(sel).filter(Boolean).length"></span> de {{ $materials->count() }} material(is) selecionado(s)
                            </p>
                            <button type="button" @click="sel = {}"
                                    x-show="Object.values(sel).some(Boolean)"
                                    class="text-xs text-red-400 hover:text-red-600">
                                Limpar seleção
                            </button>
                        </div>
                    @endif
                </div>

                <button type="submit" :disabled="!hasSelection || !spaceId"
                        class="w-full py-3 px-6 bg-etec-dark text-white font-bold rounded-xl hover:bg-etec-main transition disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Solicitar Reserva ao Coordenador
                </button>
            </div>

            {{-- ── Calendário estilo Google ── --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">

                {{-- Cabeçalho do calendário --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <button type="button" @click="prevWeek()" :disabled="!canPrevWeek()"
                            class="p-1.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition disabled:opacity-30">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="text-center">
                        <p class="font-bold text-gray-900 dark:text-white text-sm capitalize" x-text="monthLabel()"></p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <div class="flex items-center gap-1 text-xs"><div class="w-3 h-3 rounded bg-green-100 border border-green-300"></div><span class="text-gray-500">Disponível</span></div>
                            <div class="flex items-center gap-1 text-xs"><div class="w-3 h-3 rounded bg-red-100 border border-red-300"></div><span class="text-gray-500">Ocupado</span></div>
                            <div class="flex items-center gap-1 text-xs"><div class="w-3 h-3 rounded bg-etec-dark"></div><span class="text-gray-500">Selecionado</span></div>
                        </div>
                    </div>
                    <button type="button" @click="nextWeek()"
                            class="p-1.5 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                {{-- Grid do calendário --}}
                <div class="overflow-auto max-h-[600px]">
                    <div class="min-w-[480px]">

                        {{-- Header com dias --}}
                        <div class="grid sticky top-0 z-10 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700"
                             style="grid-template-columns: 56px repeat(5, 1fr)">
                            <div class="py-2 text-xs text-gray-400 text-center border-r border-gray-100 dark:border-gray-700"></div>
                            <template x-for="day in weekDays" :key="day">
                                <div class="py-2 text-center border-r border-gray-100 dark:border-gray-700 last:border-r-0"
                                     :class="isToday(day) ? 'bg-etec-light/50' : ''">
                                    <p class="text-xs font-bold uppercase tracking-wide"
                                       :class="isPast(day) ? 'text-gray-300' : 'text-gray-500 dark:text-gray-400'"
                                       x-text="new Date(day+'T12:00:00').toLocaleDateString('pt-BR',{weekday:'short'})"></p>
                                    <p class="text-lg font-bold mt-0.5 leading-none"
                                       :class="{
                                           'text-gray-300': isPast(day),
                                           'bg-etec-dark text-white w-8 h-8 rounded-full flex items-center justify-center mx-auto text-base': isToday(day) && !isPast(day),
                                           'text-gray-800 dark:text-white': !isToday(day) && !isPast(day)
                                       }"
                                       x-text="new Date(day+'T12:00:00').getDate()"></p>
                                </div>
                            </template>
                        </div>

                        {{-- Linhas de horário --}}
                        <template x-for="hour in hours" :key="hour">
                            <div class="grid border-b border-gray-50 dark:border-gray-700/50"
                                 style="grid-template-columns: 56px repeat(5, 1fr)">

                                {{-- Hora --}}
                                <div class="py-2 text-right pr-2 text-xs text-gray-400 border-r border-gray-100 dark:border-gray-700 self-start pt-1">
                                    <span x-text="hour"></span>
                                </div>

                                {{-- Células por dia --}}
                                <template x-for="day in weekDays" :key="day">
                                    <div class="h-7 border-r border-gray-50 dark:border-gray-700/50 last:border-r-0 relative cursor-pointer transition-all"
                                         :title="isPast(day) ? 'Data indisponível (mín. 48h)' : isOccupied(day, hour) ? getOccupiedInfo(day, hour) : 'Clique para selecionar'"
                                         :class="{
                                             'bg-gray-50 dark:bg-gray-700/20 cursor-not-allowed': isPast(day),
                                             'bg-red-50 dark:bg-red-900/20 cursor-not-allowed': !isPast(day) && isOccupied(day, hour),
                                             'bg-etec-dark': isSelected(day, hour),
                                             'bg-green-50 dark:bg-green-900/10 hover:bg-green-100 dark:hover:bg-green-900/30': !isPast(day) && !isOccupied(day, hour) && !isSelected(day, hour)
                                         }"
                                         @click="selectSlot(day, hour)"
                                         @mouseenter="extendSlot(day, hour)">

                                        {{-- Indicador de ocupado --}}
                                        <template x-if="!isPast(day) && isOccupied(day, hour)">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="w-full mx-1 bg-red-400/80 rounded text-white text-xs px-1 py-0.5 truncate text-center leading-tight"
                                                     x-text="getOccupiedInfo(day, hour)"></div>
                                            </div>
                                        </template>

                                        {{-- Indicador selecionado --}}
                                        <template x-if="isSelected(day, hour)">
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <span class="text-white text-xs font-bold" x-text="hour"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>

                    </div>
                </div>

                {{-- Rodapé com instrução --}}
                <div class="px-4 py-2.5 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        💡 <strong>Clique</strong> em um horário para selecionar · <strong>Arraste</strong> para selecionar múltiplas horas · Dias sombreados = mínimo 48h não atingido
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
