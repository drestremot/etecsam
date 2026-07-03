@extends('layouts.app')

@section('content')

{{-- Hero --}}
<div class="bg-etec-dark text-white py-14 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-8 h-8 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
        </div>
        <div>
            <p class="text-etec-accent text-xs font-bold uppercase tracking-widest mb-1">Gestão Escolar</p>
            <h1 class="text-3xl font-bold mb-1">Gestão Pedagógica</h1>
            <p class="text-gray-300 text-sm">Coordenação, Orientação Educacional e Cursos Técnicos</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">

    <div class="grid lg:grid-cols-3 gap-10">

        {{-- Diretora --}}
        <div class="lg:col-span-1">
            <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-accent pl-3">Responsável</h2>

            @if($director)
            <div class="bg-etec-main rounded-2xl shadow-sm overflow-hidden border border-etec-dark/30 dark:border-white/10 sticky top-24">
                <div class="h-24 bg-gradient-to-r from-etec-dark to-etec-medium"></div>
                <div class="px-6">
                    <div class="relative hover:z-20 w-[110px] h-[110px] mx-auto -mt-[55px] bg-white rounded-full p-1 shadow-lg">
                        <img src="{{ photo_url($director->photo) }}"
                             onerror="this.src='{{ avatar_url($director->name, '1a3a6e', 'fff', ['bold' => 'true', 'size' => 256]) }}'"
                             class="w-full h-full object-cover rounded-full scale-[1.15] hover:scale-[1.4375] transition duration-700 ease-in-out">
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-lg font-bold text-white">{{ $director->name }}</h3>
                    <span class="text-sm font-bold text-etec-light uppercase tracking-wide block mb-1">{{ $director->role }}</span>
                    @if($director->specialty)
                    <p class="text-xs text-green-100 mb-4 leading-relaxed italic">"{{ $director->specialty }}"</p>
                    @endif
                    <div class="bg-white/10 rounded-xl p-4 text-left space-y-3">
                        @if($director->phone)
                        <div class="flex items-center gap-2.5 text-sm text-green-100">
                            <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                            <a href="tel:{{ preg_replace('/\D/', '', $director->phone) }}" class="hover:text-etec-accent transition">{{ $director->phone }}</a>
                        </div>
                        @endif
                        @if($director->email)
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $director->email }}" class="text-white hover:text-etec-accent hover:underline text-sm truncate">{{ $director->email }}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white/50 dark:bg-white/5 rounded-2xl p-8 text-center border border-dashed border-gray-300 dark:border-white/10">
                <svg class="w-10 h-10 text-gray-300 dark:text-gray-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Informações em atualização.</p>
            </div>
            @endif
        </div>

        {{-- Equipe por grupos de cargo --}}
        <div class="lg:col-span-2 space-y-8">

            @if($staffGroups->isNotEmpty())
                @foreach($staffGroups as $roleName => $members)
                <div>
                    <h2 class="text-base font-bold text-gray-700 dark:text-gray-300 mb-4 border-l-4 border-etec-medium pl-3 uppercase tracking-wide">
                        {{ $roleName }}
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach($members as $member)
                        <div class="bg-etec-main rounded-xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-5 flex gap-4 hover:shadow-md hover:shadow-etec-dark/30 transition items-start">
                            <div class="relative hover:z-20 w-[64px] h-[64px] rounded-full border-2 border-white/10 flex-shrink-0">
                                <img src="{{ photo_url($member->photo) }}"
                                     onerror="this.src='{{ avatar_url($member->name, 'dbeafe', '1a3a6e') }}'"
                                     class="w-full h-full object-cover rounded-full scale-[1.15] hover:scale-[1.4375] transition duration-700 ease-in-out">
                            </div>
                            <div class="min-w-0 flex-grow">
                                <h4 class="font-bold text-white leading-tight">{{ $member->name }}</h4>
                                @if($member->specialty)
                                <p class="text-xs text-green-100 mt-1 mb-2 leading-relaxed line-clamp-2">{{ $member->specialty }}</p>
                                @endif
                                <div class="space-y-1 mt-1">
                                    @if($member->phone)
                                    <div class="flex items-center gap-1.5 text-xs text-green-200/70">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                                        {{ $member->phone }}
                                    </div>
                                    @endif
                                    @if($member->email)
                                    <a href="mailto:{{ $member->email }}" class="inline-flex items-center gap-1 text-xs text-green-200/70 hover:text-etec-accent hover:underline">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ $member->email }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            @else
            <div class="bg-white/50 dark:bg-white/5 rounded-xl p-8 text-center border border-dashed border-gray-200 dark:border-white/10">
                <p class="text-gray-500 dark:text-gray-400 text-sm">Equipe em atualização.</p>
            </div>
            @endif

            {{-- Áreas de Atuação --}}
            <div class="bg-white/50 dark:bg-white/5 rounded-2xl p-8 border border-blue-100 dark:border-white/10">
                <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-5 flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-etec-medium dark:text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Áreas de Atuação
                </h2>
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach([
                        ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Grade Curricular e Planos de Ensino'],
                        ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Acompanhamento Pedagógico dos Docentes'],
                        ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'label' => 'Avaliação e Desempenho Escolar'],
                        ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Orientação Educacional aos Alunos'],
                        ['icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'label' => 'Coordenação dos Cursos Técnicos'],
                        ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Calendário Acadêmico e Eventos'],
                    ] as $item)
                    <div class="flex items-center gap-3 bg-etec-main rounded-lg px-4 py-3 shadow-sm border border-etec-dark/30 dark:border-white/10">
                        <div class="w-8 h-8 bg-white/10 text-etec-accent rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-sm text-white font-medium">{{ $item['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Documentos --}}
    @if($downloads->isNotEmpty())
    <div class="mt-12 bg-white/50 dark:bg-white/5 rounded-2xl p-8 border border-gray-200 dark:border-white/10">
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 flex items-center gap-2.5">
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            Documentos Acadêmicos
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
                        Baixar
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
