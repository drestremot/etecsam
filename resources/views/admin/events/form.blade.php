@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Evento' : 'Editar Evento')

@section('content')
<div class="max-w-2xl">
    <form action="{{ $action === 'create' ? route('admin.events.store') : route('admin.events.update', $event) }}"
          method="POST" class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div class="col-span-2">
                <label class="label">Título do Evento *</label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="input" placeholder="Ex: Semana da Alimentação">
            </div>
            <div>
                <label class="label">Data de início *</label>
                <input type="date" name="start_date" value="{{ old('start_date', isset($event->start_date) ? \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') : '') }}" required class="input">
            </div>
            <div>
                <label class="label">Data de encerramento</label>
                <input type="date" name="end_date" value="{{ old('end_date', isset($event->end_date) ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d') : '') }}" class="input">
            </div>
            <div>
                <label class="label">Local</label>
                <input type="text" name="location" value="{{ old('location', $event->location) }}" class="input" placeholder="Ex: Auditório, Sede">
            </div>
            <div>
                <label class="label">Cor (opcional)</label>
                <input type="color" name="color" value="{{ old('color', $event->color ?? '#4F46E5') }}" class="input h-10">
            </div>
            <div class="col-span-2">
                <label class="label">Descrição</label>
                <textarea name="description" rows="4" class="input">{{ old('description', $event->description) }}</textarea>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg">
                {{ $action === 'create' ? 'Cadastrar' : 'Salvar alterações' }}
            </button>
            <a href="{{ route('admin.events.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>

<style>
    .label { @apply block text-sm font-medium text-gray-700 mb-1; }
    .input  { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 outline-none; }
</style>
@endsection
