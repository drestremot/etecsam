@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Evento' : 'Editar Evento')

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition">
            ← Voltar para lista
        </a>
    </div>

    <form action="{{ $action === 'create' ? route('admin.events.store') : route('admin.events.update', $event) }}"
          method="POST" class="space-y-6">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Dados do Evento</h2>
            </div>
            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Título <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Ex: Semana da Alimentação">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Data de início <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date"
                               value="{{ old('start_date', isset($event->start_date) ? \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') : '') }}"
                               required
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Data de encerramento</label>
                        <input type="date" name="end_date"
                               value="{{ old('end_date', isset($event->end_date) ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') : '') }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Local</label>
                        <input type="text" name="location" value="{{ old('location', $event->location) }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="Ex: Auditório, Sede Andradina">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Cor</label>
                        <input type="color" name="color" value="{{ old('color', $event->color ?? '#4F46E5') }}"
                               class="w-full h-10 rounded-lg border border-gray-300 px-1 py-1 cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Descrição</label>
                    <textarea name="description" rows="4"
                              class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                              placeholder="Detalhes sobre o evento, programação, informações adicionais...">{{ old('description', $event->description) }}</textarea>
                </div>

            </div>
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition">
                {{ $action === 'create' ? '✓ Cadastrar Evento' : '✓ Salvar Alterações' }}
            </button>
            <a href="{{ route('admin.events.index') }}"
               class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
