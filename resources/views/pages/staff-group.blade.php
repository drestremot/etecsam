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
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($staff as $member)
        <div class="bg-etec-main rounded-xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-5 flex gap-4 hover:shadow-md hover:shadow-etec-dark/30 transition items-start">
            <div class="relative hover:z-20 w-[64px] h-[64px] rounded-full border-2 border-white/10 flex-shrink-0">
                <img src="{{ photo_url($member->photo) }}"
                     onerror="this.src='{{ avatar_url($member->name, 'dbeafe', '1a3a6e') }}'"
                     class="w-full h-full object-cover rounded-full scale-[1.15] hover:scale-[1.4375] transition duration-700 ease-in-out">
            </div>
            <div class="min-w-0 flex-grow">
                <h4 class="font-bold text-white leading-tight">{{ $member->name }}</h4>
                <span class="text-xs font-bold text-etec-light uppercase tracking-wide block mb-1.5">{{ $member->role }}</span>
                <div class="space-y-1">
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
