@extends('layouts.app')

@push('styles')
<style>
    .event-description p, #modal-description p { margin-bottom: 0.5em; }
    .event-description p:last-child, #modal-description p:last-child { margin-bottom: 0; }
    .event-description strong, #modal-description strong { font-weight: 700; }
    .event-description em, #modal-description em { font-style: italic; }
    .event-description u, #modal-description u { text-decoration: underline; }
    .event-description .ql-align-center, #modal-description .ql-align-center { text-align: center; }
    .event-description .ql-align-right, #modal-description .ql-align-right { text-align: right; }
    .event-description .ql-align-justify, #modal-description .ql-align-justify { text-align: justify; }
    .event-description .ql-indent-1, #modal-description .ql-indent-1 { padding-left: 2em; }
    .event-description .ql-indent-2, #modal-description .ql-indent-2 { padding-left: 4em; }
    .event-description .ql-size-small, #modal-description .ql-size-small { font-size: 0.75em; }
    .event-description .ql-size-large, #modal-description .ql-size-large { font-size: 1.25em; }
    .event-description .ql-size-huge, #modal-description .ql-size-huge { font-size: 1.5em; }
</style>
@endpush

@section('content')

<x-page-header compact :title="'Agenda Escolar ' . date('Y')" subtitle="Datas de provas, eventos e atividades letivas.">
    <x-slot:icon>
        <svg class="w-8 h-8 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </x-slot:icon>
</x-page-header>

<div class="bg-[#0b172a] text-white py-14" style="background-color: #0b172a; color: #ffffff;">
    <div class="container mx-auto px-4">

        @if($events->isEmpty())
            <div class="text-center py-16 bg-[#14284b] rounded-3xl border border-dashed border-white/15 shadow-sm max-w-3xl mx-auto">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-amber-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-1">Nenhum evento cadastrado para {{ date('Y') }}</h3>
                <p class="text-slate-300 text-sm">Os eventos do calendário escolar {{ date('Y') }} serão publicados em breve.</p>
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
                        <div class="bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-extrabold px-5 py-1.5 rounded-full uppercase tracking-widest text-xs shadow-sm whitespace-nowrap">
                            {{ ucfirst($monthName) }} / {{ $year }}
                        </div>
                        <div class="h-px bg-white/10 flex-grow"></div>
                    </div>

                    <div class="space-y-4">
                        @foreach($monthEvents as $event)
                        @php $hasPhotos = $event->photos->count() > 0; @endphp
                        <div class="flex flex-col md:flex-row bg-[#14284b] hover:bg-[#1a335f] rounded-3xl shadow-sm border border-white/10 overflow-hidden transition group
                                    {{ $hasPhotos ? 'cursor-pointer hover:shadow-xl hover:border-amber-400/50' : 'hover:shadow-md hover:border-white/20' }}"
                             @if($hasPhotos)
                             onclick="openEventModal({{ json_encode([
                                 'title'       => $event->title,
                                 'description' => $event->description,
                                 'location'    => $event->location,
                                 'date'        => \Carbon\Carbon::parse($event->start_date)->translatedFormat('d \d\e F \d\e Y'),
                                 'color'       => $event->color ?? '#f59e0b',
                                 'photos'      => $event->photos->map(fn($p) => [
                                     'url'     => Storage::url($p->path),
                                     'caption' => $p->caption,
                                 ])->values(),
                             ]) }})"
                             @endif>

                            <div class="flex-shrink-0 md:w-28 flex flex-col items-center justify-center py-4 md:py-0 border-b md:border-b-0 md:border-r border-white/10 text-center bg-[#0f223f]/80">
                                <span class="text-3xl font-black text-amber-300">
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d') }}
                                </span>
                                <span class="text-xs uppercase font-bold text-slate-300">
                                    {{ \Carbon\Carbon::parse($event->start_date)->locale('pt_BR')->dayName }}
                                </span>
                            </div>

                            <div class="p-5 flex-grow">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="text-lg font-bold text-white mb-1.5 group-hover:text-amber-300 transition">
                                        {{ $event->title }}
                                    </h3>
                                    @if($hasPhotos)
                                    <span class="flex-shrink-0 inline-flex items-center gap-1 text-xs text-amber-300 font-semibold bg-amber-400/15 border border-amber-400/25 px-2.5 py-1 rounded-full mt-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $event->photos->count() }} foto{{ $event->photos->count() > 1 ? 's' : '' }}
                                    </span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-300 mb-2">
                                    @if($event->location)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $event->location }}
                                    </span>
                                    @endif
                                    @if($event->end_date)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Até {{ \Carbon\Carbon::parse($event->end_date)->format('d/m') }}
                                    </span>
                                    @endif
                                </div>
                                @if($event->description)
                                <div class="event-description text-slate-200 text-sm leading-relaxed">{!! $event->description !!}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Seção de Aniversariantes --}}
        @if(isset($birthdays) && $birthdays->count() > 0)
        <div class="mt-16 max-w-4xl mx-auto space-y-8">

            {{-- DESTAQUE: Aniversariante(s) do DIA --}}
            @if($todayBirthdays->count() > 0)
            <div x-data="{
                    current: 0,
                    total: {{ $todayBirthdays->count() }},
                    timer: null,
                    start() {
                        if (this.total > 1) {
                            this.timer = setInterval(() => { this.current = (this.current + 1) % this.total }, 4000);
                        }
                    },
                    go(i) { this.current = i; clearInterval(this.timer); this.start(); }
                 }"
                 x-init="start()">

                <div class="relative rounded-3xl overflow-hidden shadow-xl bg-gradient-to-r from-amber-500 via-amber-400 to-amber-500 text-slate-950 p-8 text-center">
                    <div class="inline-flex items-center gap-2 bg-slate-950/20 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest mb-6">
                        <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Aniversariante de Hoje · {{ now()->translatedFormat('d \d\e F') }}</span>
                    </div>

                    @foreach($todayBirthdays as $i => $teacher)
                    <div x-show="current === {{ $i }}" class="space-y-4">
                        <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-4 border-slate-950/30 shadow-lg">
                            @if($teacher->photo)
                                <img src="{{ photo_url($teacher->photo) }}" alt="{{ $teacher->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-950 text-white font-bold flex items-center justify-center text-3xl">
                                    {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-950">{{ $teacher->name }}</h3>
                            <p class="text-xs font-bold text-slate-900/80 uppercase tracking-wider mt-0.5">{{ $teacher->role }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Grade: todos os aniversariantes do mês --}}
            <div class="bg-[#14284b] rounded-3xl shadow-sm border border-white/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 bg-[#0f223f] flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-400/15 text-amber-300 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-white">Aniversariantes de {{ \Carbon\Carbon::now()->translatedFormat('F') }}</h2>
                        <p class="text-xs text-slate-300">Professores e funcionários que fazem aniversário este mês</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($birthdays as $teacher)
                        @php $isToday = \Carbon\Carbon::parse($teacher->birth_date)->day === now()->day; @endphp
                        <div class="flex items-center gap-3 p-3 rounded-2xl border transition
                                    {{ $isToday ? 'bg-amber-400/20 border-amber-400/40 ring-1 ring-amber-400' : 'bg-[#0f223f] border-white/10' }}">
                            @if($teacher->photo)
                                <div class="w-10 h-10 rounded-full overflow-hidden border border-white/20 flex-shrink-0">
                                    <img src="{{ photo_url($teacher->photo) }}" alt="{{ $teacher->name }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-xs bg-amber-400/20 text-amber-300">
                                    {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-bold text-white text-xs leading-tight truncate">{{ $teacher->name }}</p>
                                <p class="text-[11px] font-medium mt-0.5 {{ $isToday ? 'text-amber-300 font-bold' : 'text-slate-300' }}">
                                    {{ $isToday ? ' Hoje!' : 'Dia ' . \Carbon\Carbon::parse($teacher->birth_date)->format('d') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>
</div>

{{-- Modal Carrossel de Fotos --}}
<div id="event-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeEventModal()"></div>
    <div class="relative z-10 flex items-center justify-center min-h-full p-4">
        <div class="bg-[#0f223f] rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-white/15">
            <div id="modal-header" class="px-6 py-4 border-b border-white/10 flex items-start justify-between gap-4">
                <div>
                    <p id="modal-date" class="text-xs font-bold text-amber-300 uppercase tracking-widest mb-1"></p>
                    <h2 id="modal-title" class="text-xl font-bold text-white leading-tight"></h2>
                    <p id="modal-location" class="text-sm text-slate-300 mt-0.5 hidden"></p>
                </div>
                <button onclick="closeEventModal()"
                        class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full text-slate-300 hover:bg-white/10 hover:text-white transition text-lg">
                    ×
                </button>
            </div>
            <div id="modal-carousel" class="relative bg-black/50 hidden">
                <div id="carousel-track" class="relative overflow-hidden" style="height: 360px;"></div>
                <button id="carousel-prev" onclick="carouselMove(-1)"
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/60 hover:bg-black/80 text-white rounded-full flex items-center justify-center transition z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button id="carousel-next" onclick="carouselMove(1)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/60 hover:bg-black/80 text-white rounded-full flex items-center justify-center transition z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/80 to-transparent px-5 py-4">
                    <p id="carousel-caption" class="text-white text-sm font-medium min-h-[1.25rem]"></p>
                    <p id="carousel-counter" class="text-slate-300 text-xs mt-0.5"></p>
                </div>
                <div id="carousel-dots" class="absolute top-3 inset-x-0 flex justify-center gap-1.5 z-10"></div>
            </div>
            <div id="modal-description-wrap" class="px-6 py-5 hidden">
                <p id="modal-description" class="text-slate-200 text-sm leading-relaxed"></p>
            </div>
            <div class="px-6 py-4 border-t border-white/10 flex justify-end">
                <button onclick="closeEventModal()"
                        class="px-5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let carouselPhotos = [];
let carouselIndex  = 0;

function openEventModal(data) {
    carouselPhotos = data.photos || [];
    carouselIndex  = 0;

    document.getElementById('modal-title').textContent    = data.title;
    document.getElementById('modal-date').textContent     = data.date;

    const locEl = document.getElementById('modal-location');
    if (data.location) {
        locEl.innerHTML = '<svg class="w-4 h-4 inline-block mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> ' + data.location;
        locEl.classList.remove('hidden');
    } else {
        locEl.classList.add('hidden');
    }

    const descWrap = document.getElementById('modal-description-wrap');
    const descEl   = document.getElementById('modal-description');
    if (data.description) {
        descEl.innerHTML = data.description;
        descWrap.classList.remove('hidden');
    } else {
        descWrap.classList.add('hidden');
    }

    const carousel = document.getElementById('modal-carousel');
    if (carouselPhotos.length > 0) {
        buildCarousel();
        carousel.classList.remove('hidden');
    } else {
        carousel.classList.add('hidden');
    }

    document.getElementById('event-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEventModal() {
    document.getElementById('event-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

function buildCarousel() {
    const track  = document.getElementById('carousel-track');
    const dots   = document.getElementById('carousel-dots');
    const prev   = document.getElementById('carousel-prev');
    const next   = document.getElementById('carousel-next');

    track.innerHTML = '';
    dots.innerHTML  = '';

    carouselPhotos.forEach((photo, i) => {
        const slide = document.createElement('div');
        slide.className = 'absolute inset-0 transition-opacity duration-300';
        slide.style.opacity = i === 0 ? '1' : '0';
        slide.style.zIndex  = i === 0 ? '1' : '0';
        slide.innerHTML = `<img src="${photo.url}" alt="${photo.caption || ''}"
                                class="w-full h-full object-contain">`;
        track.appendChild(slide);

        const dot = document.createElement('button');
        dot.className = 'w-2 h-2 rounded-full transition ' + (i === 0 ? 'bg-amber-400' : 'bg-white/40');
        dot.onclick = () => goToSlide(i);
        dots.appendChild(dot);
    });

    prev.style.display = carouselPhotos.length > 1 ? '' : 'none';
    next.style.display = carouselPhotos.length > 1 ? '' : 'none';
    dots.style.display = carouselPhotos.length > 1 ? '' : 'none';

    updateCarouselInfo();
}

function goToSlide(index) {
    const slides = document.getElementById('carousel-track').children;
    const dots   = document.getElementById('carousel-dots').children;

    slides[carouselIndex].style.opacity = '0';
    slides[carouselIndex].style.zIndex  = '0';
    if (dots[carouselIndex]) dots[carouselIndex].className = 'w-2 h-2 rounded-full transition bg-white/40';

    carouselIndex = (index + carouselPhotos.length) % carouselPhotos.length;

    slides[carouselIndex].style.opacity = '1';
    slides[carouselIndex].style.zIndex  = '1';
    if (dots[carouselIndex]) dots[carouselIndex].className = 'w-2 h-2 rounded-full transition bg-amber-400';

    updateCarouselInfo();
}

function carouselMove(dir) {
    goToSlide(carouselIndex + dir);
}

function updateCarouselInfo() {
    const photo = carouselPhotos[carouselIndex];
    document.getElementById('carousel-caption').textContent = photo.caption || '';
    document.getElementById('carousel-counter').textContent =
        carouselPhotos.length > 1
            ? (carouselIndex + 1) + ' / ' + carouselPhotos.length
            : '';
}

document.addEventListener('keydown', (e) => {
    if (document.getElementById('event-modal').classList.contains('hidden')) return;
    if (e.key === 'Escape')      closeEventModal();
    if (e.key === 'ArrowLeft')   carouselMove(-1);
    if (e.key === 'ArrowRight')  carouselMove(1);
});
</script>

@endsection
