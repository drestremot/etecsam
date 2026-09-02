@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10" x-data="adminTimeClock()">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-semibold">Ponto Eletrônico</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-semibold tracking-tight text-gray-900 flex items-center gap-2.5">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Radar de Presença & Auditoria do Ponto</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1 font-normal">
                    Acompanhamento em tempo real de batidas, reconhecimento facial, geolocalização e conformidade
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.timeclock.mirror') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-semibold text-white shadow-xs hover:bg-indigo-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Ver Espelho de Ponto Mensal</span>
                </a>

                <a href="{{ route('admin.work-schedules.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white border border-gray-300 px-4 py-2.5 text-xs font-medium text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Grade de Horários</span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-medium text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Total de Batidas</span>
                <div class="text-xl sm:text-2xl font-semibold tracking-tight text-gray-900 mt-1">{{ $stats['total_punches'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Docentes Presentes</span>
                <div class="text-xl sm:text-2xl font-semibold tracking-tight text-indigo-700 mt-1">{{ $stats['distinct_users'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Validadas Regularmente</span>
                <div class="text-xl sm:text-2xl font-semibold tracking-tight text-emerald-700 mt-1">{{ $stats['approved_punches'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Alertas de GPS (Fora)</span>
                <div class="text-xl sm:text-2xl font-semibold tracking-tight text-rose-600 mt-1">{{ $stats['flagged_outside'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs col-span-2 sm:col-span-1">
                <span class="text-xs font-medium text-gray-500 block">Atrasos Registrados</span>
                <div class="text-xl sm:text-2xl font-semibold tracking-tight text-amber-600 mt-1">{{ $stats['flagged_late'] }}</div>
            </div>
        </div>

        <!-- Live Filters Bar -->
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
            <form method="GET" action="{{ route('admin.timeclock.index') }}" class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                <div>
                    <label class="block text-[11px] font-medium text-gray-600 uppercase mb-1">Data</label>
                    <input type="date" name="date" value="{{ $date }}"
                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-mono">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-gray-600 uppercase mb-1">Professor / Colaborador</label>
                    <select name="user_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Todos os Colaboradores</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-gray-600 uppercase mb-1">Unidade Escolar</label>
                    <select name="unit_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Todas as Unidades</option>
                        @foreach($units as $un)
                            <option value="{{ $un->id }}" {{ request('unit_id') == $un->id ? 'selected' : '' }}>{{ $un->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-gray-600 uppercase mb-1">Status da Batida</label>
                    <select name="status" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Todos os Status</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Regular / Validado</option>
                        <option value="flagged_outside_unit" {{ request('status') == 'flagged_outside_unit' ? 'selected' : '' }}>Fora da Unidade (GPS)</option>
                        <option value="flagged_late" {{ request('status') == 'flagged_late' ? 'selected' : '' }}>Atraso Registrado</option>
                        <option value="justified" {{ request('status') == 'justified' ? 'selected' : '' }}>Justificado</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 rounded-xl bg-gray-900 px-4 py-2 text-xs font-semibold text-white hover:bg-gray-800 transition">
                        Filtrar
                    </button>
                    <a href="{{ route('admin.timeclock.index') }}" class="rounded-xl border border-gray-300 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-100 transition">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Punches Table -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/90 text-[11px] font-semibold uppercase text-gray-500 border-b border-gray-200 tracking-wider">
                        <tr>
                            <th class="px-3.5 py-3 min-w-[70px]">Foto</th>
                            <th class="px-3 py-3 min-w-[100px]">Horário & Tipo</th>
                            <th class="px-3 py-3 min-w-[160px]">Colaborador</th>
                            <th class="px-3 py-3 text-center min-w-[130px]">Unidade</th>
                            <th class="px-3 py-3 text-center min-w-[110px]">GPS / Local</th>
                            <th class="px-3 py-3 text-center min-w-[100px]">Grade / Atraso</th>
                            <th class="px-3 py-3 text-center min-w-[90px]">Status</th>
                            <th class="px-3.5 py-3 text-right min-w-[110px]">Auditoria</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($records as $rec)
                        <tr class="hover:bg-gray-50/80 transition">
                            <!-- Facial Photo -->
                            <td class="px-3.5 py-2.5 whitespace-nowrap">
                                @if($rec->photo_url)
                                    <button type="button" @click="openPhotoModal('{{ $rec->photo_url }}', '{{ $rec->user->name }}', '{{ $rec->recorded_at->format('d/m/Y H:i:s') }}')"
                                            class="group relative inline-block">
                                        <img src="{{ $rec->photo_url }}" alt="Snapshot" class="w-8 h-8 rounded-lg object-cover border border-indigo-200 shadow-2xs group-hover:scale-105 transition">
                                        <span class="absolute -bottom-1 -right-1 bg-indigo-600 text-white rounded-full p-0.5">
                                            <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        </span>
                                    </button>
                                @else
                                    <span class="rounded-md bg-gray-100 px-1.5 py-1 text-gray-400 text-[10px] inline-block">Sem foto</span>
                                @endif
                            </td>

                            <!-- Time & Type -->
                            <td class="px-3 py-2.5 whitespace-nowrap">
                                <div class="font-mono font-medium text-gray-900 text-xs">
                                    {{ $rec->recorded_at->format('H:i:s') }}
                                </div>
                                <span class="inline-block mt-0.5 rounded px-1.5 py-0.2 text-[10.5px] font-medium border {{ $rec->getRecordTypeBadgeClass() }}">
                                    {{ $rec->getRecordTypeLabel() }}
                                </span>
                            </td>

                            <!-- Employee -->
                            <td class="px-3 py-2.5">
                                <div class="font-semibold text-gray-900 truncate max-w-[180px]" title="{{ $rec->user->name }}">{{ $rec->user->name }}</div>
                                <div class="text-[11px] text-gray-500 font-normal truncate max-w-[180px]">{{ $rec->user->role ?? 'Colaborador' }}</div>
                            </td>

                            <!-- Unit -->
                            <td class="px-3 py-2.5 text-center">
                                <span class="inline-block rounded-md bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-700 leading-snug break-words max-w-[130px]">
                                    {{ $rec->unit->name ?? 'Unidade Geral' }}
                                </span>
                            </td>

                            <!-- GPS -->
                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                @if($rec->distance_to_unit_meters !== null)
                                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.2 text-[10.5px] font-medium {{ $rec->is_within_geofence ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        {{ $rec->is_within_geofence ? 'Na Escola' : 'Fora' }} ({{ $rec->distance_to_unit_meters }}m)
                                    </span>
                                @elseif($rec->verification_method === 'totem_kiosk')
                                    <span class="rounded-full bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.2 text-[10.5px] font-medium">
                                        Totem Físico
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>

                            <!-- Schedule & Delay -->
                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                @if($rec->delay_minutes > 0)
                                    <span class="rounded-full bg-amber-100 text-amber-800 px-2 py-0.2 text-[10.5px] font-medium">
                                        +{{ $rec->delay_minutes }}m atraso
                                    </span>
                                @elseif($rec->is_within_schedule)
                                    <span class="text-emerald-600 font-medium text-[11px]">No Horário</span>
                                @else
                                    <span class="text-purple-600 font-medium text-[10.5px]">Extra</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                <span class="inline-block rounded-full px-2 py-0.5 text-[11px] font-medium border {{ $rec->getStatusBadgeClass() }}">
                                    {{ $rec->getStatusLabel() }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                @if($rec->status !== 'approved' && $rec->status !== 'justified')
                                    <button type="button" @click="openJustifyModal({{ $rec->id }}, '{{ $rec->user->name }}')"
                                            class="rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 px-3 py-1.5 text-xs font-medium transition">
                                        Justificar
                                    </button>
                                @elseif($rec->justification)
                                    <span class="text-[11px] text-gray-500 italic block truncate max-w-[120px]" title="{{ $rec->justification }}">
                                        "{{ $rec->justification }}"
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs font-mono">OK</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm font-normal text-gray-500">Nenhum registro de ponto encontrado para esta data.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($records->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $records->links() }}
                </div>
            @endif
        </div>

        <!-- Photo Inspection Modal -->
        <div x-show="photoModal.show" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div @click="photoModal.show = false" class="fixed inset-0 bg-gray-900/70 backdrop-blur-xs"></div>
                <div class="relative transform overflow-hidden rounded-3xl bg-white p-6 text-center shadow-2xl transition-all sm:my-8 max-w-sm w-full space-y-4">
                    <h3 class="font-semibold text-gray-900 text-base" x-text="photoModal.title"></h3>
                    <p class="text-xs text-gray-500 font-normal" x-text="photoModal.subtitle"></p>
                    <img :src="photoModal.src" class="w-full aspect-square rounded-2xl object-cover border-2 border-indigo-100 shadow-md">
                    <button type="button" @click="photoModal.show = false" class="w-full rounded-xl bg-gray-900 py-2.5 text-xs font-semibold text-white hover:bg-gray-800">
                        Fechar
                    </button>
                </div>
            </div>
        </div>

        <!-- Justification Modal -->
        <div x-show="justifyModal.show" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div @click="justifyModal.show = false" class="fixed inset-0 bg-gray-900/70 backdrop-blur-xs"></div>
                <div class="relative transform overflow-hidden rounded-3xl bg-white p-6 text-left shadow-2xl transition-all sm:my-8 max-w-md w-full space-y-4">
                    <h3 class="font-semibold text-gray-900 text-base">Justificar Ocorrência de Ponto</h3>
                    <p class="text-xs text-gray-500 font-normal" x-text="'Colaborador: ' + justifyModal.userName"></p>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 uppercase mb-1">Motivo / Despacho da Direção *</label>
                        <textarea x-model="justifyModal.text" rows="3" placeholder="Ex: Problema técnico de GPS / Atividade pedagógica externa autorizada..."
                                  class="w-full rounded-2xl border border-gray-300 p-3 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-normal"></textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="justifyModal.show = false" class="rounded-xl border border-gray-300 px-4 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="button" @click="submitJustification()" class="rounded-xl bg-blue-600 px-5 py-2 text-xs font-semibold text-white hover:bg-blue-500">
                            Aprovar Justificativa
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function adminTimeClock() {
    return {
        photoModal: { show: false, src: '', title: '', subtitle: '' },
        justifyModal: { show: false, recordId: null, userName: '', text: '' },

        openPhotoModal(src, name, time) {
            this.photoModal.src = src;
            this.photoModal.title = name;
            this.photoModal.subtitle = 'Snapshot facial registrado em ' + time;
            this.photoModal.show = true;
        },

        openJustifyModal(id, name) {
            this.justifyModal.recordId = id;
            this.justifyModal.userName = name;
            this.justifyModal.text = '';
            this.justifyModal.show = true;
        },

        submitJustification() {
            if (!this.justifyModal.text) {
                alert('Informe a justificativa.');
                return;
            }

            fetch('/admin/ponto-gestao/' + this.justifyModal.recordId + '/justificar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ justification: this.justifyModal.text })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Erro ao justificar.');
                }
            });
        }
    }
}
</script>
@endsection
