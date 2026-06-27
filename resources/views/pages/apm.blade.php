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
        <div class="flex-grow">
            <p class="text-etec-accent text-xs font-bold uppercase tracking-widest mb-1">Comunidade Escolar</p>
            <h1 class="text-3xl font-bold mb-1">APM</h1>
            <p class="text-gray-300 text-sm">Associação de Pais e Mestres</p>
        </div>
        <a href="{{ route('apm.finance') }}"
           class="hidden sm:inline-flex items-center gap-2 bg-etec-accent text-etec-dark font-bold text-sm px-5 py-3 rounded-lg hover:bg-amber-400 transition flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm6 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm6 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
            </svg>
            Dashboard Financeiro
        </a>
    </div>
    <div class="container mx-auto px-4 mt-4 sm:hidden">
        <a href="{{ route('apm.finance') }}"
           class="inline-flex items-center gap-2 bg-etec-accent text-etec-dark font-bold text-sm px-5 py-3 rounded-lg hover:bg-amber-400 transition w-full justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm6 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm6 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
            </svg>
            Dashboard Financeiro
        </a>
    </div>
</div>

<div class="container mx-auto px-4 py-12">

    <div class="grid lg:grid-cols-3 gap-10">

    {{-- Responsável --}}
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
                <p class="text-xs text-blue-100 mb-4 leading-relaxed italic">"{{ $director->specialty }}"</p>
                @endif
                <div class="bg-white/10 rounded-xl p-4 text-left space-y-3">
                    @if($director->phone)
                    <div class="flex items-center gap-2.5 text-sm text-blue-100">
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

    <div class="lg:col-span-2 space-y-12">

    {{-- Gestão e Cargos --}}
    <div>
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-accent pl-3">Gestão e Cargos</h2>

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
            <p class="text-gray-500 dark:text-gray-400 text-sm">Gestão em atualização.</p>
        </div>
        @endif
    </div>

    {{-- Estatuto --}}
    <div>
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 flex items-center gap-2.5 border-l-4 border-etec-accent pl-3">
            Estatuto
        </h2>
        @include('pages.partials.cooperative-document-list', ['documents' => $statutes, 'emptyMessage' => 'Estatuto em atualização.'])
    </div>

    {{-- Atas de Reunião --}}
    <div>
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 flex items-center gap-2.5 border-l-4 border-etec-medium pl-3">
            Atas de Reunião
        </h2>
        @include('pages.partials.cooperative-document-list', ['documents' => $minutes, 'emptyMessage' => 'Nenhuma ata de reunião publicada ainda.'])
    </div>

    {{-- Prestações de Contas --}}
    <div>
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 flex items-center gap-2.5 border-l-4 border-etec-accent pl-3">
            Prestações de Contas
        </h2>
        @include('pages.partials.cooperative-document-list', ['documents' => $reports, 'emptyMessage' => 'Nenhuma prestação de contas publicada ainda.'])
    </div>

    </div>
    </div>

</div>
@endsection
