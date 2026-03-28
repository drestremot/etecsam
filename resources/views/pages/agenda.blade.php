@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl font-serif font-bold mb-2">Agenda Escolar {{ date('Y') }}</h1>
        <p class="text-gray-300">Fique por dentro das datas de provas, eventos e atividades letivas.</p>
    </div>
</div>

<div class="container mx-auto px-4 py-16">

    @if($events->isEmpty())
        <div class="text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
            <div class="text-4xl mb-4">📅</div>
            <h3 class="text-lg font-bold text-gray-600">Nenhum evento programado</h3>
            <p class="text-gray-500">A agenda será atualizada em breve.</p>
        </div>
    @else
        <div class="max-w-4xl mx-auto">
            @foreach($events as $monthYear => $monthEvents)
                @php
                    // Cria um objeto Carbon para pegar o nome do mês
                    $dateObj = \Carbon\Carbon::createFromFormat('m/Y', $monthYear);
                    $monthName = $dateObj->locale('pt_BR')->monthName; // Ex: "janeiro"
                    $year = $dateObj->year;
                @endphp

                <div class="flex items-center gap-4 mb-8 mt-12 first:mt-0">
                    <div class="bg-etec-accent text-etec-dark font-bold px-4 py-1 rounded-full uppercase tracking-widest text-sm shadow-md">
                        {{ $monthName }} / {{ $year }}
                    </div>
                    <div class="h-px bg-gray-200 flex-grow"></div>
                </div>

                <div class="space-y-6">
                    @foreach($monthEvents as $event)
                    <div class="flex flex-col md:flex-row bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">

                        <div class="bg-etec-light md:w-32 flex flex-col items-center justify-center py-4 md:py-0 border-b md:border-b-0 md:border-r border-gray-100 group-hover:bg-etec-dark group-hover:text-white transition duration-300">
                            <span class="text-3xl font-bold">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('d') }}
                            </span>
                            <span class="text-xs uppercase font-bold opacity-80">
                                {{ \Carbon\Carbon::parse($event->start_date)->locale('pt_BR')->dayName }}
                            </span>
                            <span class="text-xs mt-1 bg-white/20 px-2 rounded">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}
                            </span>
                        </div>

                        <div class="p-6 flex-grow">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-etec-medium transition">
                                {{ $event->title }}
                            </h3>
                            <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                                <span>📍 {{ $event->location }}</span>
                                @if($event->end_date)
                                    <span class="text-gray-300">|</span>
                                    <span>Até {{ \Carbon\Carbon::parse($event->end_date)->format('d/m H:i') }}</span>
                                @endif
                            </div>
                            <p class="text-gray-600 text-sm">
                                {{ $event->description }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
