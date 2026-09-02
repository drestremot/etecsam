@extends('layouts.app')

@section('content')

{{-- Hero --}}
<x-page-header label="Comunidade Escolar" title="APM" subtitle="Associação de Pais e Mestres">
    <x-slot:icon>
        <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </x-slot:icon>
    <x-slot:actions>
        <a href="{{ route('apm.finance') }}"
           class="hidden sm:inline-flex items-center gap-2 bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl hover:from-amber-300 hover:to-amber-400 transition shadow-sm flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm6 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm6 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
            </svg>
            Dashboard Financeiro
        </a>
    </x-slot:actions>
    <x-slot:mobileActions>
        <a href="{{ route('apm.finance') }}"
           class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl hover:from-amber-300 hover:to-amber-400 transition w-full justify-center shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm6 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm6 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
            </svg>
            Dashboard Financeiro
        </a>
    </x-slot:mobileActions>
</x-page-header>

<div class="container mx-auto px-4 py-12">

    <div class="grid lg:grid-cols-3 gap-8 sm:gap-10">

    {{-- Responsável --}}
    <div class="lg:col-span-1">
        <h2 class="text-lg sm:text-xl font-bold text-white mb-6 border-l-4 border-amber-400 pl-3">Responsável</h2>

        @if($director)
        <div class="bg-[#14284b] rounded-2xl shadow-sm overflow-hidden border border-white/10 sticky top-24">
            <div class="h-20 bg-gradient-to-r from-[#0c1b33] to-[#14284b] border-b border-white/10"></div>
            <div class="px-6">
                <div class="relative hover:z-20 w-[96px] h-[96px] mx-auto -mt-[48px] bg-[#0b172a] rounded-full p-1 border-2 border-white/10 shadow-lg">
                    <img src="{{ photo_url($director->photo) }}"
                         onerror="this.src='{{ avatar_url($director->name, '14284b', 'fff', ['bold' => 'true', 'size' => 256]) }}'"
                         class="w-full h-full object-cover rounded-full scale-[1.05] hover:scale-[1.25] transition duration-700 ease-in-out">
                </div>
            </div>
            <div class="p-6 text-center">
                <h3 class="text-base sm:text-lg font-semibold text-white">{{ $director->name }}</h3>
                <span class="text-xs font-semibold text-amber-300 uppercase tracking-wide block mb-1">{{ $director->role }}</span>
                @if($director->specialty)
                <p class="text-xs text-slate-300 mb-4 leading-relaxed italic">"{{ $director->specialty }}"</p>
                @endif
                <div class="bg-black/20 rounded-xl p-4 text-left space-y-2.5 border border-white/5">
                    @if($director->phone)
                    <div class="flex items-center gap-2 text-xs text-slate-200">
                        <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                        <a href="tel:{{ preg_replace('/\D/', '', $director->phone) }}" class="hover:text-amber-300 transition">{{ $director->phone }}</a>
                    </div>
                    @endif
                    @if($director->email)
                    <div class="flex items-center gap-2 min-w-0">
                        <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:{{ $director->email }}" class="text-slate-200 hover:text-amber-300 hover:underline text-xs truncate block min-w-0 flex-1" title="{{ $director->email }}">{{ $director->email }}</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="bg-[#14284b] rounded-2xl p-8 text-center border border-dashed border-white/10">
            <svg class="w-10 h-10 text-slate-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <p class="text-slate-400 text-xs">Informações em atualização.</p>
        </div>
        @endif
    </div>

    <div class="lg:col-span-2 space-y-12">

    {{-- Gestão e Cargos --}}
    <div>
        <h2 class="text-lg sm:text-xl font-bold text-white mb-6 border-l-4 border-amber-400 pl-3">Gestão e Cargos</h2>

        @if($managers->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
            @foreach($managers as $manager)
            <div class="bg-[#14284b] rounded-2xl shadow-sm border border-white/10 p-5 flex gap-4 hover:border-amber-400/40 hover:shadow-lg transition items-start min-w-0">
                <div class="relative hover:z-20 w-[60px] h-[60px] rounded-full border-2 border-white/10 flex-shrink-0 bg-[#0b172a] overflow-hidden">
                    <img src="{{ photo_url($manager->photo) }}"
                         onerror="this.src='{{ avatar_url($manager->name, '14284b', 'fff') }}'"
                         class="w-full h-full object-cover rounded-full scale-[1.05] hover:scale-[1.25] transition duration-700 ease-in-out">
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="font-semibold text-white leading-tight text-sm sm:text-base">{{ $manager->name }}</h4>
                    <span class="text-xs font-semibold text-amber-300 uppercase tracking-wide block mb-1.5">{{ $manager->role }}</span>
                    <div class="space-y-1">
                        @if($manager->phone)
                        <div class="flex items-center gap-1.5 text-xs text-slate-300">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                            <span>{{ $manager->phone }}</span>
                        </div>
                        @endif
                        @if($manager->email)
                        <a href="mailto:{{ $manager->email }}" class="inline-flex items-center gap-1.5 text-xs text-slate-300 hover:text-amber-300 hover:underline max-w-full min-w-0 truncate" title="{{ $manager->email }}">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="truncate block min-w-0">{{ $manager->email }}</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-[#14284b] rounded-2xl p-8 text-center border border-dashed border-white/10">
            <p class="text-slate-400 text-xs">Gestão em atualização.</p>
        </div>
        @endif
    </div>

    {{-- Estatuto --}}
    <div>
        <h2 class="text-lg sm:text-xl font-bold text-white mb-6 flex items-center gap-2.5 border-l-4 border-amber-400 pl-3">
            Estatuto
        </h2>
        @include('pages.partials.cooperative-document-list', ['documents' => $statutes, 'emptyMessage' => 'Estatuto em atualização.'])
    </div>

    {{-- Atas de Reunião --}}
    <div>
        <h2 class="text-lg sm:text-xl font-bold text-white mb-6 flex items-center gap-2.5 border-l-4 border-amber-400 pl-3">
            Atas de Reunião
        </h2>
        @include('pages.partials.cooperative-document-list', ['documents' => $minutes, 'emptyMessage' => 'Nenhuma ata de reunião publicada ainda.'])
    </div>

    {{-- Prestações de Contas --}}
    <div>
        <h2 class="text-lg sm:text-xl font-bold text-white mb-6 flex items-center gap-2.5 border-l-4 border-amber-400 pl-3">
            Prestações de Contas
        </h2>
        @include('pages.partials.cooperative-document-list', ['documents' => $reports, 'emptyMessage' => 'Nenhuma prestação de contas publicada ainda.'])
    </div>

    </div>
    </div>

</div>
@endsection
