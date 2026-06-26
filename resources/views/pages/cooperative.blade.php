@extends('layouts.app')

@section('content')

{{-- Hero --}}
<div class="bg-etec-dark text-white py-14 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-8 h-8 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-etec-accent text-xs font-bold uppercase tracking-widest mb-1">Escola Fazenda</p>
            <h1 class="text-3xl font-bold mb-1">Cooperativa Escola</h1>
            <p class="text-gray-300 text-sm">Gestores, Cooperados e Prestações de Contas</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12 space-y-12">

    {{-- Gestores da Cooperativa --}}
    <div>
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-accent pl-3">Gestores da Cooperativa</h2>

        @if($managers->isNotEmpty())
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($managers as $manager)
            <div class="bg-etec-main rounded-xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-5 flex gap-4 hover:shadow-md hover:shadow-etec-dark/30 transition items-start">
                <div class="relative hover:z-20 w-[64px] h-[64px] rounded-full border-2 border-white/10 flex-shrink-0">
                    <img src="{{ photo_url($manager->photo) }}"
                         onerror="this.src='{{ avatar_url($manager->name, 'dbeafe', '1a3a6e') }}'"
                         class="w-full h-full object-cover rounded-full scale-[1.15] hover:scale-[1.4375] transition duration-700 ease-in-out">
                </div>
                <div class="min-w-0 flex-grow">
                    <h4 class="font-bold text-white leading-tight">{{ $manager->name }}</h4>
                    <span class="text-xs font-bold text-etec-light uppercase tracking-wide block mb-1.5">{{ $manager->role }}</span>
                    <div class="space-y-1">
                        @if($manager->phone)
                        <div class="flex items-center gap-1.5 text-xs text-blue-200/70">
                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                            {{ $manager->phone }}
                        </div>
                        @endif
                        @if($manager->email)
                        <a href="mailto:{{ $manager->email }}" class="inline-flex items-center gap-1 text-xs text-blue-200/70 hover:text-etec-accent hover:underline">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $manager->email }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white/50 dark:bg-white/5 rounded-xl p-8 text-center border border-dashed border-gray-200 dark:border-white/10">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Gestores em atualização.</p>
        </div>
        @endif
    </div>

    {{-- Cooperados --}}
    <div>
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-medium pl-3">Cooperados</h2>

        @if($members->isNotEmpty())
        <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-6">
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($members as $member)
                <div class="flex items-center justify-between gap-2 bg-white/10 rounded-lg px-4 py-2.5">
                    <span class="text-sm font-medium text-white truncate">{{ $member->name }}</span>
                    @if($member->registration_number)
                    <span class="text-xs text-etec-light font-bold flex-shrink-0">#{{ $member->registration_number }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white/50 dark:bg-white/5 rounded-xl p-8 text-center border border-dashed border-gray-200 dark:border-white/10">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Lista de cooperados em atualização.</p>
        </div>
        @endif
    </div>

    {{-- Prestações de Contas --}}
    <div>
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 flex items-center gap-2.5 border-l-4 border-etec-accent pl-3">
            Prestações de Contas
        </h2>

        @if($reports->isNotEmpty())
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($reports as $report)
            <div class="bg-etec-main p-4 rounded-xl shadow-sm border border-etec-dark/30 dark:border-white/10 flex items-start gap-4 hover:border-etec-accent transition group">
                <div class="w-10 h-10 bg-white/10 text-etec-accent rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-etec-accent/30 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="flex-grow min-w-0">
                    <h4 class="font-bold text-white text-sm mb-1 leading-tight">{{ $report->title }}</h4>
                    <div class="flex items-center gap-2 text-xs text-blue-200/70 mb-2">
                        @if($report->period)
                        <span>{{ $report->period }}</span>
                        @endif
                        @if($report->published_at)
                        <span>{{ $report->published_at->format('d/m/Y') }}</span>
                        @endif
                    </div>
                    @if($report->file_path)
                    <a href="{{ photo_url($report->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-etec-accent hover:text-white hover:underline">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Baixar Arquivo
                    </a>
                    @elseif($report->url)
                    <a href="{{ $report->url }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-etec-accent hover:text-white hover:underline">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Acessar
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white/50 dark:bg-white/5 rounded-xl p-8 text-center border border-dashed border-gray-200 dark:border-white/10">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhuma prestação de contas publicada ainda.</p>
        </div>
        @endif
    </div>

</div>
@endsection
