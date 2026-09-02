@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10">
    <div class="w-full max-w-3xl mx-auto space-y-6">

        <!-- Top Navigation -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.work-schedules.index') }}" class="hover:text-indigo-600 transition">Grade de Horários</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-semibold">{{ $action === 'create' ? 'Novo Horário' : 'Editar Horário' }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-semibold tracking-tight text-gray-900">
                    {{ $action === 'create' ? 'Cadastrar Horário na Grade' : 'Editar Horário de Trabalho' }}
                </h1>
            </div>

            <a href="{{ route('admin.work-schedules.index') }}" class="rounded-xl bg-white border border-gray-300 px-4 py-2 text-xs font-medium text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                Voltar
            </a>
        </div>

        @if($errors->any())
            <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-xs font-medium text-rose-800 space-y-1">
                @foreach($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <!-- Form Card -->
        <form method="POST"
              action="{{ $action === 'create' ? route('admin.work-schedules.store') : route('admin.work-schedules.update', $schedule) }}"
              class="rounded-3xl border border-gray-200 bg-white p-6 sm:p-8 shadow-xs space-y-6">
            @csrf
            @if($action === 'edit')
                @method('PUT')
            @endif

            <!-- 1. Professor & Escola -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Professor / Colaborador *</label>
                    <select name="user_id" required class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-medium">
                        <option value="">Selecione o Professor</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ (old('user_id', $schedule->user_id) == $u->id) ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->role ?? 'Docente' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Unidade Escolar *</label>
                    <select name="unit_id" required class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-medium">
                        <option value="">Selecione a Unidade</option>
                        @foreach($units as $un)
                            <option value="{{ $un->id }}" {{ (old('unit_id', $schedule->unit_id) == $un->id) ? 'selected' : '' }}>
                                {{ $un->name }} ({{ $un->city }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- 2. Dias da Semana -->
            <div>
                <label class="block text-xs font-medium text-gray-700 uppercase mb-2">Dias da Semana *</label>
                @if($action === 'create')
                    <div class="grid grid-cols-2 sm:grid-cols-6 gap-2">
                        @foreach($daysList as $num => $dayName)
                        @if($num != 0)
                        <label class="cursor-pointer rounded-2xl border border-gray-200 p-3 text-center transition hover:bg-gray-50 flex flex-col items-center justify-center gap-1.5 has-checked:border-indigo-600 has-checked:bg-indigo-50/50 has-checked:text-indigo-900">
                            <input type="checkbox" name="days_of_week[]" value="{{ $num }}" class="rounded text-indigo-600 focus:ring-indigo-500" {{ in_array($num, old('days_of_week', [1])) ? 'checked' : '' }}>
                            <span class="text-xs font-semibold block">{{ substr($dayName, 0, 3) }}</span>
                            <span class="text-[10px] text-gray-500 block">{{ $dayName }}</span>
                        </label>
                        @endif
                        @endforeach
                    </div>
                @else
                    <select name="day_of_week" required class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-medium">
                        @foreach($daysList as $num => $dayName)
                            <option value="{{ $num }}" {{ (old('day_of_week', $schedule->day_of_week) == $num) ? 'selected' : '' }}>{{ $dayName }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            <!-- 3. Descrição do Turno -->
            <div>
                <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Identificação do Turno / Matéria (Opcional)</label>
                <input type="text" name="shift_name" value="{{ old('shift_name', $schedule->shift_name) }}"
                       placeholder="Ex: Manhã - Técnico em Informática / Tarde - Ensino Médio"
                       class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-normal">
            </div>

            <!-- 4. Horários de Entrada e Saída -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-200">
                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Horário de Entrada *</label>
                    <input type="time" name="start_time" required
                           value="{{ old('start_time', $schedule->start_time ? substr($schedule->start_time, 0, 5) : '07:10') }}"
                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Horário de Saída *</label>
                    <input type="time" name="end_time" required
                           value="{{ old('end_time', $schedule->end_time ? substr($schedule->end_time, 0, 5) : '12:35') }}"
                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>

            <!-- 5. Intervalo & Tolerância -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Início do Intervalo</label>
                    <input type="time" name="break_start_time"
                           value="{{ old('break_start_time', $schedule->break_start_time ? substr($schedule->break_start_time, 0, 5) : '') }}"
                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Fim do Intervalo</label>
                    <input type="time" name="break_end_time"
                           value="{{ old('break_end_time', $schedule->break_end_time ? substr($schedule->break_end_time, 0, 5) : '') }}"
                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Tolerância (minutos)</label>
                    <input type="number" name="tolerance_minutes" min="0" max="60"
                           value="{{ old('tolerance_minutes', $schedule->tolerance_minutes ?? 15) }}"
                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.work-schedules.index') }}" class="rounded-2xl border border-gray-300 px-5 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="rounded-2xl bg-indigo-600 px-6 py-2.5 text-xs font-semibold text-white hover:bg-indigo-500 shadow-md shadow-indigo-200 transition">
                    {{ $action === 'create' ? 'Salvar na Grade de Horários' : 'Atualizar Horário' }}
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
