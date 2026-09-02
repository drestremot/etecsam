@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 py-4 sm:py-6 pb-20 sm:pb-6">
    <div class="w-full max-w-2xl mx-auto space-y-5">
        
        <div class="flex items-center gap-3">
            <a href="{{ route('lab.spaces.index') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-800 uppercase">Novo Espaço Didático</h1>
                <p class="text-xs text-gray-500">Cadastre um novo laboratório ou sala prática</p>
            </div>
        </div>

        <form action="{{ route('lab.spaces.store') }}" method="POST" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Nome do Espaço *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ex: Laboratório de Informática 1"
                       class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Descrição & Recursos</label>
                <textarea name="description" rows="3" placeholder="Capacidade de alunos, computadores, bancadas, projetor..."
                          class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Laboratório Vinculado (Site)</label>
                    <select name="laboratory_id" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Nenhum (avulso)</option>
                        @foreach($laboratories as $lab)
                            <option value="{{ $lab->id }}" {{ old('laboratory_id') == $lab->id ? 'selected' : '' }}>{{ $lab->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Auxiliar Docente Responsável</label>
                    <select name="auxiliar_id" class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Nenhum</option>
                        @foreach($auxiliares as $a)
                            <option value="{{ $a->id }}" {{ old('auxiliar_id') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                <a href="{{ route('lab.spaces.index') }}" class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-100 transition">Cancelar</a>
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-blue-500 transition">Salvar Espaço</button>
            </div>
        </form>
    </div>
</div>
@endsection
