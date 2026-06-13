@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-8 h-8 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-3xl font-bold mb-1">Agenda Escolar {{ date('Y') }}</h1>
            <p class="text-gray-300">Datas de provas, eventos e atividades letivas.</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-16">

    @if($events->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-200 shadow-sm">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-600 mb-1">Nenhum evento cadastrado para {{ date('Y') }}</h3>
            <p class="text-gray-400 text-sm">Os eventos do calendário escolar {{ date('Y') }} serão publicados em breve.</p>
        </div>
    @else
        <div class="max-w-4xl mx-auto">
            @foreach($events as $monthYear => $monthEvents)
                @php
                    $dateObj = \Carbon\Carbon::createFromFormat('m/Y', $monthYear);
                    $monthName = $dateObj->locale('pt_BR')->monthName;
                    $year = $dateObj->year;
                @endphp

                <div class="flex items-center gap-4 mb-8 mt-12 first:mt-0">
                    <div class="bg-etec-accent text-etec-dark font-bold px-5 py-1.5 rounded-full uppercase tracking-widest text-xs shadow-sm whitespace-nowrap">
                        {{ ucfirst($monthName) }} / {{ $year }}
                    </div>
                    <div class="h-px bg-gray-200 flex-grow"></div>
                </div>

                <div class="space-y-4">
                    @foreach($monthEvents as $event)
                    <div class="flex flex-col md:flex-row bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">

                        <div class="flex-shrink-0 md:w-28 flex flex-col items-center justify-center py-4 md:py-0 border-b md:border-b-0 md:border-r border-gray-100 text-center"
                             style="background-color: {{ $event->color ?? '#2d5a27' }}20; border-left: 4px solid {{ $event->color ?? '#2d5a27' }};">
                            <span class="text-3xl font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('d') }}
                            </span>
                            <span class="text-xs uppercase font-bold text-gray-500">
                                {{ \Carbon\Carbon::parse($event->start_date)->locale('pt_BR')->dayName }}
                            </span>
                        </div>

                        <div class="p-5 flex-grow">
                            <h3 class="text-lg font-bold text-gray-800 mb-1.5 group-hover:text-etec-medium transition">
                                {{ $event->title }}
                            </h3>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500 mb-2">
                                @if($event->location)
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $event->location }}
                                </span>
                                @endif
                                @if($event->end_date)
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Até {{ \Carbon\Carbon::parse($event->end_date)->format('d/m') }}
                                </span>
                                @endif
                            </div>
                            @if($event->description)
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $event->description }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif
</div>


{{-- Seção de Aniversariantes do Mês --}}
@if(isset($birthdays) && $birthdays->count() > 0)
<div class="container mx-auto px-4 py-10">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center text-xl">🎂</div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Aniversariantes de {{ \Carbon\Carbon::now()->translatedFormat('F') }}</h2>
                <p class="text-sm text-gray-500">Professores e funcionários que fazem aniversário este mês</p>
            </div>
        </div>
        <div class="p-6">
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($birthdays as $teacher)
                <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-xl border border-yellow-100">
                    @if($teacher->photo)
                        <img src="{{ photo_url($teacher->photo) }}" alt="{{ $teacher->name }}"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                             class="w-12 h-12 rounded-full object-cover border-2 border-yellow-200">
                        <div style="display:none" class="w-12 h-12 rounded-full bg-yellow-200 flex items-center justify-center text-yellow-700 font-bold text-lg">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-full bg-yellow-200 flex items-center justify-center text-yellow-700 font-bold text-lg">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-800 text-sm leading-tight">{{ $teacher->name }}</p>
                        <p class="text-xs text-yellow-600 font-medium">
                            🎉 Dia {{ \Carbon\Carbon::parse($teacher->birth_date)->format('d') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

@endsection
