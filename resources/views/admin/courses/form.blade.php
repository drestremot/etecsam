@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1200px] mx-auto space-y-6">

        <!-- Top Header & Breadcrumbs -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-gray-500 mb-1">
                    <a href="{{ route('admin.courses.index') }}" class="hover:text-amber-600 transition">Cursos Técnicos</a>
                    <span>/</span>
                    <span class="text-amber-600 font-extrabold">{{ $action === 'create' ? 'Novo Curso' : 'Editar: ' . $course->title }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                    {{ $action === 'create' ? 'Cadastrar Novo Curso' : 'Editar Curso Técnico' }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 font-medium mt-0.5">
                    Configuração geral da modalidade, coordenadores técnicos, dados institucionais e grade
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Voltar à Lista</span>
                </a>
                @if($action === 'edit')
                <a href="{{ route('admin.courses.subjects.index', $course) }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Grade & Professores ({{ $course->subjects->count() }})</span>
                </a>
                @endif
            </div>
        </div>

        @if($action === 'edit')
        <!-- Shortcut Banner to Grade & Disciplinas -->
        <div class="rounded-2xl border border-indigo-200 bg-indigo-50/80 p-5 shadow-sm flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white font-semibold text-lg shadow-sm">
                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                <div>
                    <h3 class="text-sm font-bold text-indigo-950">Grade Curricular & Atribuição de Professores</h3>
                    <p class="text-xs text-indigo-800 font-medium">Este curso possui <strong>{{ $course->subjects->count() }}</strong> disciplinas cadastradas.</p>
                </div>
            </div>
            <a href="{{ route('admin.courses.subjects.index', $course) }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-indigo-500 transition">
                <span>Gerenciar Grade & Professores &rarr;</span>
            </a>
        </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="rounded-2xl bg-red-600 text-white p-4 text-xs font-bold shadow-md">
                <p class="font-semibold mb-1">Por favor, corrija os erros abaixo:</p>
                <ul class="list-disc list-inside space-y-0.5 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $action === 'create' ? route('admin.courses.store') : route('admin.courses.update', $course) }}"
              method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if($action === 'edit') @method('PUT') @endif

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                <h2 class="text-sm font-bold tracking-wide text-gray-800 border-b border-gray-100 pb-3">
                    Dados do Curso Técnico
                </h2>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                        Título do Curso <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}" required
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 transition"
                           placeholder="Ex: Técnico em Desenvolvimento de Sistemas, Agropecuária...">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                            Modalidade <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="type" value="{{ old('type', $course->type) }}" required
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 transition"
                               placeholder="Ex: Integrado ao Médio (M-Tec), Noturno, AMS...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Unidade</label>
                        <select name="unit_id"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 transition">
                            <option value="">— Selecione a Unidade —</option>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}" {{ old('unit_id', $course->unit_id) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @php
                    $selectedTecnico = old('technical_coordinators',
                        $course->technicalCoordinators?->pluck('id')->toArray() ?? []);
                    $selectedDesc = old('decentralized_coordinators',
                        $course->decentralizedCoordinators?->pluck('id')->toArray() ?? []);
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Coordenadores Técnicos do Curso
                        </label>
                        <select name="technical_coordinators[]" multiple
                                class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 transition"
                                size="5">
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ in_array($t->id, $selectedTecnico) ? 'selected' : '' }}>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-[11px] text-gray-400">Pressione Ctrl para selecionar múltiplos coordenadores.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Coords. Descentralizados / Extensão
                        </label>
                        <select name="decentralized_coordinators[]" multiple
                                class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 transition"
                                size="5">
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ in_array($t->id, $selectedDesc) ? 'selected' : '' }}>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-[11px] text-gray-400">Pressione Ctrl para selecionar múltiplos coordenadores.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                <h2 class="text-sm font-bold tracking-wide text-gray-800 border-b border-gray-100 pb-3">
                    Conteúdo Institucional & Descrição
                </h2>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                        Descrição Resumida <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="3" required
                              class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 transition resize-none"
                              placeholder="Breve descrição do curso para exibição no catálogo...">{{ old('description', $course->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Conteúdo Completo</label>
                    <textarea name="content" rows="6"
                              class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 transition font-mono"
                              placeholder="Perfil profissional, mercado de trabalho, requisitos...">{{ old('content', $course->content) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Horários de Aulas</label>
                        <input type="text" name="schedule" value="{{ old('schedule', $course->schedule) }}"
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 transition"
                               placeholder="Ex: Seg a Sex, das 7h30 às 12h50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Foto / Imagem do Curso</label>
                        @if($action === 'edit' && $course->image)
                            <div class="flex items-center gap-3 mb-2 p-2 bg-gray-50 rounded-xl border border-gray-200">
                                <img src="{{ photo_url($course->image) }}" class="w-16 h-12 rounded-lg object-cover">
                                <p class="text-[11px] text-gray-500">Imagem atual. Envie outra para substituir.</p>
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*"
                               class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 transition">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $course->is_active ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <span>
                            <span class="text-xs font-bold text-gray-800">Curso Ativo</span>
                            <span class="block text-[11px] text-gray-400">Exibido na grade e no portal público</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.courses.index') }}"
                   class="px-5 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-200 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs sm:text-sm font-bold px-6 py-2.5 shadow-sm transition">
                    <span>{{ $action === 'create' ? '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Cadastrar Curso' : '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Salvar Alterações' }}</span>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
