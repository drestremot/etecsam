@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-8 h-8 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-3xl font-bold mb-1">Secretaria Acadêmica</h1>
            <p class="text-gray-300">Vida Escolar, Matrículas e Documentação</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">

    {{-- Área do Aluno --}}
    <div class="mb-12">
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-medium pl-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-etec-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Área do Aluno
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($links as $link)
            <a href="{{ $link['url'] }}" target="_blank"
               class="bg-etec-main border border-etec-dark/30 dark:border-white/10 p-4 rounded-xl hover:shadow-lg hover:shadow-etec-dark/30 hover:border-etec-accent hover:-translate-y-1 transition group text-center flex flex-col items-center gap-2 h-full">
                <div class="text-2xl bg-white/10 text-etec-accent w-14 h-14 rounded-xl flex items-center justify-center group-hover:bg-etec-accent/30 transition font-bold">
                    {{ $link['icon'] }}
                </div>
                <div>
                    <strong class="block text-sm text-white group-hover:text-etec-accent transition leading-tight">{{ $link['name'] }}</strong>
                    <span class="text-xs text-green-100">{{ $link['desc'] }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-10">

        {{-- Responsável --}}
        <div class="lg:col-span-1">
            <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-accent pl-3">Responsável</h2>

            @if($director)
            <div class="bg-etec-main rounded-2xl shadow-sm overflow-hidden border border-etec-dark/30 dark:border-white/10">
                <div class="h-20 bg-gradient-to-r from-etec-dark to-etec-medium"></div>
                <div class="px-6 relative">
                    <div class="relative hover:z-20 w-[92px] h-[92px] mx-auto -mt-[46px] bg-white rounded-full p-1 shadow-lg">
                        <img src="{{ photo_url($director->photo) }}"
                             onerror="this.src='{{ avatar_url($director->name, '2d5a27', 'fff') }}'"
                             class="w-full h-full object-cover rounded-full scale-[1.15] hover:scale-[1.4375] transition duration-700 ease-in-out">
                    </div>
                </div>
                <div class="p-6 pt-3 text-center">
                    <h3 class="text-lg font-bold text-white">{{ $director->name }}</h3>
                    <span class="text-xs font-bold text-etec-light uppercase tracking-wide block mb-1">{{ $director->role }}</span>
                    @if($director->specialty)
                    <p class="text-xs text-green-100 mb-4 leading-relaxed italic">"{{ $director->specialty }}"</p>
                    @endif

                    <div class="bg-white/10 rounded-xl p-4 text-sm text-left space-y-3">
                        <div class="flex items-center gap-2.5 text-green-100">
                            <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                            <span>{{ $director->phone }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $director->email }}" class="text-white hover:text-etec-accent hover:underline truncate">{{ $director->email }}</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Equipe --}}
        <div class="lg:col-span-2">
            <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-medium pl-3">Equipe de Atendimento</h2>

            <div class="grid md:grid-cols-2 gap-5">
                @foreach($staff as $member)
                <div class="bg-etec-main rounded-xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-5 flex gap-4 hover:shadow-md hover:shadow-etec-dark/30 transition">
                    <div class="relative hover:z-20 w-[64px] h-[64px] rounded-full border-2 border-white/10 flex-shrink-0">
                        <img src="{{ photo_url($member->photo) }}"
                             onerror="this.src='{{ avatar_url($member->name, 'eee', '333') }}'"
                             class="w-full h-full object-cover rounded-full scale-[1.15] hover:scale-[1.4375] transition duration-700 ease-in-out">
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-white leading-tight">{{ $member->name }}</h4>
                        <span class="text-xs font-bold text-green-200/70 uppercase tracking-wide block mb-1.5">{{ $member->role }}</span>
                        <p class="text-xs text-green-100 mb-2 leading-relaxed">{{ $member->specialty }}</p>
                        <a href="mailto:{{ $member->email }}" class="inline-flex items-center gap-1 text-xs text-green-200/70 hover:text-etec-accent hover:underline">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $member->email }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Documentos --}}
    <div class="mt-12 bg-white/50 dark:bg-white/5 rounded-2xl p-8 border border-blue-100 dark:border-white/10">
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 flex items-center gap-2.5">
            <svg class="w-5 h-5 text-green-600 dark:text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            Requerimentos e Documentos
        </h2>

        @if($downloads->isEmpty())
            <p class="text-gray-500 dark:text-gray-400">Nenhum arquivo disponível.</p>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($downloads as $file)
                <div class="bg-etec-main p-4 rounded-xl shadow-sm border border-etec-dark/30 dark:border-white/10 flex items-start gap-4 hover:border-etec-accent transition group">
                    <div class="w-10 h-10 bg-white/10 text-red-300 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-red-500 group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="flex-grow min-w-0">
                        <h4 class="font-bold text-white text-sm mb-1.5 leading-tight">{{ $file->title }}</h4>
                        <a href="{{ $file->file_path }}" class="inline-flex items-center gap-1 text-xs font-bold text-etec-accent hover:text-white transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Baixar PDF
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
