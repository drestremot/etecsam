@extends('layouts.app')

@section('content')

{{-- Hero --}}
<div class="bg-etec-dark text-white py-14 border-b-4 border-etec-accent relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/></pattern></defs>
            <rect width="100%" height="100%" fill="url(#grid)"/>
        </svg>
    </div>
    <div class="container mx-auto px-4 relative z-10 flex items-center gap-6">
        <div class="w-16 h-16 bg-etec-accent/20 rounded-xl flex items-center justify-center flex-shrink-0 border border-etec-accent/30">
            <svg class="w-8 h-8 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
        </div>
        <div>
            <p class="text-etec-accent text-xs font-bold uppercase tracking-widest mb-1">Etec Sebastiana Augusta de Moraes</p>
            <h1 class="text-3xl font-bold mb-1">Gestão Pedagógica</h1>
            <p class="text-gray-300 text-sm">Coordenação Pedagógica, Orientação Educacional e Cursos Técnicos</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12 space-y-14">

    {{-- Perfil da Coordenadora / Responsável Pedagógica --}}
    @if($director)
    <div class="bg-etec-main rounded-2xl shadow-sm overflow-hidden border border-etec-dark/30 dark:border-white/10">
        <div class="flex flex-col md:flex-row">
            {{-- Foto --}}
            <div class="md:w-80 flex-shrink-0 bg-etec-dark flex items-center justify-center p-8 md:p-10">
                <div class="relative hover:z-20 w-48 h-48 md:w-56 md:h-56 rounded-full border-4 border-white/10 shadow-xl flex-shrink-0">
                    <img src="{{ photo_url($director->photo) }}"
                         onerror="this.src='{{ avatar_url($director->name, '0c1f3f', 'fff', ['bold' => 'true', 'size' => 512]) }}'"
                         class="w-full h-full object-cover rounded-full scale-[1.15] hover:scale-[1.4375] transition duration-700 ease-in-out">
                </div>
            </div>
            {{-- Conteúdo --}}
            <div class="p-8 md:p-12 flex flex-col justify-center">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-1.5 bg-etec-accent/20 text-etec-accent text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Coordenação Pedagógica Geral
                    </span>
                </div>
                <h2 class="text-3xl font-bold text-white mb-1">{{ $director->name }}</h2>
                <p class="text-etec-light font-semibold mb-4">{{ $director->role }}</p>

                @if($director->specialty)
                <p class="text-green-100 leading-relaxed mb-6 max-w-xl italic">"{{ $director->specialty }}"</p>
                @endif

                <div class="flex flex-wrap gap-3 mb-6">
                    @if($director->email)
                    <a href="mailto:{{ $director->email }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 border border-white/20 text-green-100 rounded-lg font-semibold text-sm hover:border-etec-accent hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $director->email }}
                    </a>
                    @endif
                    @if($director->phone)
                    <a href="tel:{{ preg_replace('/\D/', '', $director->phone) }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 text-white rounded-lg font-semibold text-sm hover:bg-etec-accent hover:text-etec-dark transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/>
                        </svg>
                        {{ $director->phone }}
                    </a>
                    @endif
                    @if($director->lattes_url)
                    <a href="{{ $director->lattes_url }}" target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 text-green-100 rounded-lg font-semibold text-sm hover:bg-white/20 hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Currículo Lattes
                    </a>
                    @endif
                </div>

                @if($director->bio)
                <div class="bg-white/10 rounded-xl p-4 text-left">
                    <p class="text-xs font-bold text-etec-accent uppercase tracking-wide mb-1.5">Mini-currículo</p>
                    <div class="text-sm text-green-100 leading-relaxed [&_p]:mb-2 [&_p:last-child]:mb-0 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&_a]:underline [&_a]:text-etec-accent">{!! $director->bio !!}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="bg-white/50 dark:bg-white/5 rounded-2xl p-10 text-center border border-dashed border-gray-300 dark:border-white/10">
        <svg class="w-12 h-12 text-gray-300 dark:text-gray-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <p class="text-gray-500 dark:text-gray-400">Informações da Coordenação Pedagógica em atualização.</p>
    </div>
    @endif

    {{-- Equipe por Grupos de Atuação / Cargos --}}
    @if($staffGroups->isNotEmpty())
        @foreach($staffGroups as $roleName => $members)
        <div>
            <h2 class="text-xl font-bold text-white mb-6 border-l-4 border-etec-accent pl-3">{{ $roleName }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                @foreach($members as $member)
                <div class="bg-[#14284b] rounded-2xl shadow-sm border border-white/10 p-5 flex gap-4 hover:border-amber-400/40 hover:shadow-lg transition items-start min-w-0">
                    <div class="relative hover:z-20 w-[60px] h-[60px] rounded-full border-2 border-white/10 flex-shrink-0 bg-[#0b172a] overflow-hidden">
                        <img src="{{ photo_url($member->photo) }}"
                             onerror="this.src='{{ avatar_url($member->name, '14284b', 'fff') }}'"
                             class="w-full h-full object-cover rounded-full scale-[1.05] hover:scale-[1.25] transition duration-700 ease-in-out">
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="font-semibold text-white leading-tight text-sm sm:text-base">{{ $member->name }}</h4>
                        <span class="text-xs font-semibold text-amber-300 uppercase tracking-wide block mb-1.5">{{ $member->role }}</span>
                        @if($member->specialty)
                        <p class="text-xs text-slate-300 mb-2 leading-relaxed">{{ $member->specialty }}</p>
                        @endif
                        <div class="space-y-1">
                            @if($member->phone)
                            <div class="flex items-center gap-1.5 text-xs text-slate-300">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                                <span>{{ $member->phone }}</span>
                            </div>
                            @endif
                            @if($member->email)
                            <a href="mailto:{{ $member->email }}" class="inline-flex items-center gap-1.5 text-xs text-slate-300 hover:text-amber-300 hover:underline max-w-full min-w-0 truncate" title="{{ $member->email }}">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span class="truncate block min-w-0">{{ $member->email }}</span>
                            </a>
                            @endif
                        </div>
                        @if($member->bio)
                        <div x-data="{ open: false }" class="mt-1.5">
                            <button type="button" @click="open = !open" class="text-xs text-amber-400 hover:underline">
                                <span x-text="open ? 'Ocultar mini-currículo' : 'Ver mini-currículo'"></span>
                            </button>
                            <div x-show="open" x-cloak class="bg-black/20 border border-white/5 rounded-xl p-3 mt-1.5 text-xs text-slate-200 leading-relaxed [&_p]:mb-2 [&_p:last-child]:mb-0 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&_a]:underline [&_a]:text-amber-300">{!! $member->bio !!}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    @endif

    {{-- Áreas de Atuação Pedagógica --}}
    <div>
        <h2 class="text-xl font-bold text-white mb-6 border-l-4 border-etec-accent pl-3 flex items-center gap-2.5">
            <svg class="w-5 h-5 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Atribuições & Áreas de Atuação Pedagógica
        </h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach([
                ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Grade Curricular e Planos de Ensino'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Acompanhamento Pedagógico dos Docentes'],
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'label' => 'Avaliação e Desempenho Escolar'],
                ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Orientação Educacional aos Alunos'],
                ['icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'label' => 'Coordenação dos Cursos Técnicos'],
                ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Calendário Acadêmico e Eventos'],
            ] as $item)
            <div class="flex items-center gap-3.5 bg-etec-main rounded-2xl p-4 shadow-sm border border-etec-dark/30 dark:border-white/10 hover:border-etec-accent transition">
                <div class="w-10 h-10 bg-white/10 text-etec-accent rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                </div>
                <span class="text-sm text-white font-medium">{{ $item['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Navegação para as demais estruturas de Gestão --}}
    <div>
        <h2 class="text-xl font-bold text-white mb-6 border-l-4 border-etec-accent pl-3">Estrutura de Gestão</h2>
        <div class="grid md:grid-cols-3 gap-5">
            <a href="{{ route('superintendence') }}"
               class="group bg-etec-main rounded-xl border border-etec-dark/30 dark:border-white/10 shadow-sm p-6 flex items-center gap-5 hover:border-etec-accent hover:shadow-md hover:shadow-etec-dark/30 transition">
                <div class="w-14 h-14 bg-white/10 text-etec-accent rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-etec-accent/30 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="flex-grow">
                    <h3 class="font-bold text-white group-hover:text-etec-accent transition">Superintendência</h3>
                    <p class="text-sm text-green-100">Direção Geral da Unidade</p>
                </div>
                <svg class="w-5 h-5 text-green-200/70 group-hover:text-etec-accent transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('academic') }}"
               class="group bg-etec-main rounded-xl border border-etec-dark/30 dark:border-white/10 shadow-sm p-6 flex items-center gap-5 hover:border-etec-accent hover:shadow-md hover:shadow-etec-dark/30 transition">
                <div class="w-14 h-14 bg-white/10 text-etec-accent rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-etec-accent/30 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <div class="flex-grow">
                    <h3 class="font-bold text-white group-hover:text-etec-accent transition">Secretaria Acadêmica</h3>
                    <p class="text-sm text-green-100">Vida escolar e matrículas</p>
                </div>
                <svg class="w-5 h-5 text-green-200/70 group-hover:text-etec-accent transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            <a href="{{ route('administrative') }}"
               class="group bg-etec-main rounded-xl border border-etec-dark/30 dark:border-white/10 shadow-sm p-6 flex items-center gap-5 hover:border-etec-accent hover:shadow-md hover:shadow-etec-dark/30 transition">
                <div class="w-14 h-14 bg-white/10 text-etec-accent rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-etec-accent/30 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-grow">
                    <h3 class="font-bold text-white group-hover:text-etec-accent transition">Diretoria de Serviços</h3>
                    <p class="text-sm text-green-100">Administrativo e financeiro</p>
                </div>
                <svg class="w-5 h-5 text-green-200/70 group-hover:text-etec-accent transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- Documentos Acadêmicos --}}
    @if($downloads->isNotEmpty())
    <div class="bg-white/50 dark:bg-white/5 rounded-2xl p-8 border border-gray-200 dark:border-white/10">
        <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            Documentos Pedagógicos & Calendários
        </h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($downloads as $file)
            <div class="bg-etec-main p-4 rounded-xl shadow-sm border border-etec-dark/30 dark:border-white/10 flex items-start gap-4 hover:border-etec-accent transition group">
                <div class="w-10 h-10 bg-white/10 text-red-300 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-red-500 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-white text-sm mb-1">{{ $file->title }}</h4>
                    <a href="{{ $file->file_path }}" class="inline-flex items-center gap-1 text-xs font-bold text-etec-accent hover:text-white hover:underline">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Baixar Arquivo
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

