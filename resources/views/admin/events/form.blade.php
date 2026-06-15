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
          method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        {{-- Dados do Evento --}}
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

        {{-- Fotos do Evento --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Fotos do Evento</h2>
                <p class="text-xs text-gray-400 mt-0.5">Cada foto pode ter uma legenda exibida no carrossel.</p>
            </div>
            <div class="p-6 space-y-5">

                {{-- Fotos já salvas (modo edição) --}}
                @if($action === 'edit' && $event->photos->count() > 0)
                <div class="space-y-3">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Fotos cadastradas</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($event->photos as $photo)
                        <div class="relative group rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                            <img src="{{ Storage::url($photo->path) }}" alt="{{ $photo->caption }}"
                                 class="w-full h-28 object-cover">
                            @if($photo->caption)
                            <div class="px-2 py-1.5 text-xs text-gray-600 truncate">{{ $photo->caption }}</div>
                            @endif
                            <form action="{{ route('admin.event-photos.destroy', $photo) }}" method="POST"
                                  onsubmit="return confirm('Remover esta foto?')"
                                  class="absolute top-1.5 right-1.5">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-7 h-7 bg-red-600 hover:bg-red-700 text-white rounded-full flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition text-xs font-bold">
                                    ✕
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Adicionar novas fotos --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">
                        {{ $action === 'edit' && $event->photos->count() > 0 ? 'Adicionar mais fotos' : 'Selecionar fotos' }}
                    </p>
                    <div id="photo-rows" class="space-y-3">
                        <div class="photo-row flex gap-3 items-start">
                            <input type="file" name="photos[]" accept="image/*"
                                   class="flex-1 text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <input type="text" name="captions[]" placeholder="Legenda (opcional)"
                                   class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <button type="button" onclick="addPhotoRow()"
                            class="mt-3 inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Adicionar outra foto
                    </button>
                    @error('photos.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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

<script>
function addPhotoRow() {
    const container = document.getElementById('photo-rows');
    const row = document.createElement('div');
    row.className = 'photo-row flex gap-3 items-start';
    row.innerHTML = `
        <input type="file" name="photos[]" accept="image/*"
               class="flex-1 text-sm text-gray-600 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <input type="text" name="captions[]" placeholder="Legenda (opcional)"
               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <button type="button" onclick="this.parentElement.remove()"
                class="mt-1 text-gray-400 hover:text-red-500 transition text-lg leading-none">✕</button>
    `;
    container.appendChild(row);
}
</script>
@endsection
