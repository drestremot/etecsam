@extends('admin.layouts.app')

@section('title', 'Nova Reserva')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('lab.reservations.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nova Reserva</h1>
    </div>

    <form action="{{ route('lab.reservations.store') }}" method="POST" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 space-y-5">
        @csrf

        <div class="grid sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Espaço *</label>
                <select name="space_id" required class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                    <option value="">Selecione o laboratório...</option>
                    @foreach($spaces as $s)
                    <option value="{{ $s->id }}" {{ old('space_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
                @error('space_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Data *</label>
                <input type="date" name="reservation_date" value="{{ old('reservation_date') }}" required
                       min="{{ date('Y-m-d') }}"
                       class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                @error('reservation_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Início *</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}" required
                           class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Fim</label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}"
                           class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                </div>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Descrição / Plano de aula</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none resize-none"
                          placeholder="Descreva brevemente a atividade...">{{ old('description') }}</textarea>
            </div>
        </div>

        @if($materials->count())
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Materiais necessários</label>
            <div class="grid sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-gray-100 dark:border-gray-700 rounded-lg p-3">
                @foreach($materials as $m)
                <label class="flex items-center gap-2.5 cursor-pointer p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <input type="checkbox" name="materials[{{ $m->id }}][id]" value="{{ $m->id }}"
                           class="rounded border-gray-300 text-etec-dark focus:ring-etec-dark">
                    <span class="text-sm text-gray-700 dark:text-gray-300 flex-1">{{ $m->name }}</span>
                    <input type="number" name="materials[{{ $m->id }}][qty]" value="1" min="1" max="{{ $m->stock_quantity }}"
                           class="w-14 border border-gray-200 dark:border-gray-600 rounded px-2 py-1 text-xs text-center dark:bg-gray-700 dark:text-white">
                </label>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('lab.reservations.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Cancelar</a>
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-etec-dark text-white text-sm font-semibold hover:bg-etec-main transition">Solicitar Reserva</button>
        </div>
    </form>
</div>
@endsection
