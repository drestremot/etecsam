@extends('layouts.app')

@section('content')

{{-- Hero --}}
<x-page-header :label="$pageLabel" :title="$pageTitle" :subtitle="$pageSubtitle">
    <x-slot:icon>
        <svg class="w-8 h-8 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $iconPath }}"/>
        </svg>
    </x-slot:icon>
</x-page-header>

<div class="container mx-auto px-4 py-12">

    @if($staff->isNotEmpty())
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
        @foreach($staff as $member)
        <div class="bg-[#14284b] rounded-2xl shadow-sm border border-white/10 p-5 flex gap-4 hover:border-amber-400/40 hover:shadow-lg transition items-start min-w-0">
            <div class="relative hover:z-20 w-[60px] h-[60px] rounded-full border-2 border-white/10 flex-shrink-0 bg-[#0b172a] overflow-hidden">
                <img src="{{ photo_url($member->photo) }}"
                     onerror="this.src='{{ avatar_url($member->name, '14284b', 'fff') }}'"
                     class="w-full h-full object-cover rounded-full scale-[1.05] hover:scale-[1.25] transition duration-700 ease-in-out">
            </div>
            <div class="min-w-0 flex-1">
                <h4 class="font-semibold text-white leading-tight text-sm sm:text-base">{{ $member->name }}</h4>
                <span class="text-xs font-semibold text-amber-300 uppercase tracking-wide block mb-1.5">{{ $member->role }}</span>
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
    @else
    <div class="bg-white/50 dark:bg-white/5 rounded-xl p-8 text-center border border-dashed border-gray-200 dark:border-white/10">
        <svg class="w-10 h-10 text-gray-300 dark:text-gray-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $emptyMessage }}</p>
    </div>
    @endif

</div>
@endsection
