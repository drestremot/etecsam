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
        <div class="text-center py-16 bg-etec-main rounded-2xl border border-dashed border-etec-dark/30 dark:border-white/10 shadow-sm">
            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-200/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-1">Nenhum evento cadastrado para {{ date('Y') }}</h3>
            <p class="text-blue-200/70 text-sm">Os eventos do calendário escolar {{ date('Y') }} serão publicados em breve.</p>
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
                    <div class="h-px bg-gray-200 dark:bg-white/10 flex-grow"></div>
                </div>

                <div class="space-y-4">
                    @foreach($monthEvents as $event)
                    @php $hasPhotos = $event->photos->count() > 0; @endphp
                    <div class="flex flex-col md:flex-row bg-etec-main rounded-xl shadow-sm border border-etec-dark/30 dark:border-white/10 overflow-hidden transition group
                                {{ $hasPhotos ? 'cursor-pointer hover:shadow-lg hover:border-etec-accent/40' : 'hover:shadow-md' }}"
                         @if($hasPhotos)
                         onclick="openEventModal({{ json_encode([
                             'title'       => $event->title,
                             'description' => $event->description,
                             'location'    => $event->location,
                             'date'        => \Carbon\Carbon::parse($event->start_date)->translatedFormat('d \d\e F \d\e Y'),
                             'color'       => $event->color ?? '#2d5a27',
                             'photos'      => $event->photos->map(fn($p) => [
                                 'url'     => Storage::url($p->path),
                                 'caption' => $p->caption,
                             ])->values(),
                         ]) }})"
                         @endif>

                        <div class="flex-shrink-0 md:w-28 flex flex-col items-center justify-center py-4 md:py-0 border-b md:border-b-0 md:border-r border-white/10 text-center"
                             style="background-color: {{ $event->color ?? '#2d5a27' }}20; border-left: 4px solid {{ $event->color ?? '#2d5a27' }};">
                            <span class="text-3xl font-bold text-white">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('d') }}
                            </span>
                            <span class="text-xs uppercase font-bold text-blue-200/70">
                                {{ \Carbon\Carbon::parse($event->start_date)->locale('pt_BR')->dayName }}
                            </span>
                        </div>

                        <div class="p-5 flex-grow">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-bold text-white mb-1.5 group-hover:text-etec-accent transition">
                                    {{ $event->title }}
                                </h3>
                                @if($hasPhotos)
                                <span class="flex-shrink-0 inline-flex items-center gap-1 text-xs text-etec-accent font-semibold bg-white/10 px-2.5 py-1 rounded-full mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $event->photos->count() }} foto{{ $event->photos->count() > 1 ? 's' : '' }}
                                </span>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-blue-200/70 mb-2">
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
                            <div class="event-description text-blue-100 text-sm leading-relaxed">{!! $event->description !!}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif
</div>


{{-- Seção de Aniversariantes --}}
@if(isset($birthdays) && $birthdays->count() > 0)
<div class="container mx-auto px-4 pb-16">

    {{-- ★ DESTAQUE: Aniversariante(s) do DIA ★ --}}
    @if($todayBirthdays->count() > 0)
    <div class="mb-8"
         x-data="{
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

        <div class="relative rounded-2xl overflow-hidden shadow-lg"
             style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 40%, #fbbf24 100%);">

            {{-- Confetes decorativos --}}
            <div class="absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
                <span class="absolute text-3xl opacity-20 top-4 left-6">🎊</span>
                <span class="absolute text-2xl opacity-20 top-6 right-10">🎉</span>
                <span class="absolute text-2xl opacity-15 bottom-4 left-16">✨</span>
                <span class="absolute text-3xl opacity-20 bottom-3 right-8">🎈</span>
            </div>

            {{-- Badge do dia --}}
            <div class="absolute top-4 left-1/2 -translate-x-1/2">
                <span class="inline-flex items-center gap-1.5 bg-white/80 backdrop-blur-sm text-amber-700 text-xs font-bold px-4 py-1.5 rounded-full shadow-sm uppercase tracking-widest">
                    🎂 Aniversário Hoje · {{ now()->translatedFormat('d \d\e F') }}
                </span>
            </div>

            {{-- Slides dos aniversariantes do dia --}}
            <div class="pt-14 pb-8 px-6 flex flex-col items-center text-center min-h-[260px] justify-center">
                @foreach($todayBirthdays as $i => $teacher)
                <div x-show="current === {{ $i }}"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="flex flex-col items-center gap-4">

                    {{-- Foto grande centralizada --}}
                    <div class="relative">
                        <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-white shadow-xl ring-4 ring-amber-300/50">
                            @if($teacher->photo)
                                <img src="{{ photo_url($teacher->photo) }}" alt="{{ $teacher->name }}"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                                     class="w-full h-full object-cover">
                                <div style="display:none" class="w-full h-full flex items-center justify-center bg-amber-500 text-white font-bold text-4xl">
                                    {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-amber-500 text-white font-bold text-4xl">
                                    {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        {{-- Ícone de parabéns --}}
                        <span class="absolute -bottom-1 -right-1 text-2xl">🎂</span>
                    </div>

                    {{-- Nome e cargo --}}
                    <div>
                        <h3 class="text-xl font-bold text-amber-900 leading-tight">{{ $teacher->name }}</h3>
                        @if($teacher->role)
                        <p class="text-sm text-amber-700 font-medium mt-0.5">{{ $teacher->role }}</p>
                        @endif
                        <p class="mt-2 text-amber-800 font-semibold text-sm">🎉 Parabéns pelo seu aniversário!</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Dots do carrossel (só aparece se > 1 pessoa) --}}
            @if($todayBirthdays->count() > 1)
            <div class="flex justify-center gap-2 pb-5">
                @foreach($todayBirthdays as $i => $teacher)
                <button @click="go({{ $i }})"
                        :class="current === {{ $i }} ? 'w-6 bg-amber-700' : 'w-2 bg-amber-400/60'"
                        class="h-2 rounded-full transition-all duration-300 focus:outline-none"
                        :aria-label="'{{ $teacher->name }}'"></button>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Grade: todos os aniversariantes do mês --}}
    <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 overflow-hidden">
        <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center text-xl">🎂</div>
            <div>
                <h2 class="text-lg font-bold text-white">Aniversariantes de {{ \Carbon\Carbon::now()->translatedFormat('F') }}</h2>
                <p class="text-sm text-blue-100">Professores e funcionários que fazem aniversário este mês</p>
            </div>
        </div>
        <div class="p-6">
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($birthdays as $teacher)
                @php $isToday = \Carbon\Carbon::parse($teacher->birth_date)->day === now()->day; @endphp
                <div class="flex items-center gap-3 p-3 rounded-xl border transition
                            {{ $isToday ? 'bg-amber-50 border-amber-200 ring-1 ring-amber-300' : 'bg-yellow-50 border-yellow-100' }}">
                    @if($teacher->photo)
                        <img src="{{ photo_url($teacher->photo) }}" alt="{{ $teacher->name }}"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                             class="w-12 h-12 rounded-full object-cover border-2 {{ $isToday ? 'border-amber-300' : 'border-yellow-200' }} flex-shrink-0">
                        <div style="display:none" class="w-12 h-12 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-lg
                                    {{ $isToday ? 'bg-amber-300 text-amber-800' : 'bg-yellow-200 text-yellow-700' }}">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-lg
                                    {{ $isToday ? 'bg-amber-300 text-amber-800' : 'bg-yellow-200 text-yellow-700' }}">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-800 text-sm leading-tight truncate">{{ $teacher->name }}</p>
                        <p class="text-xs font-medium mt-0.5 {{ $isToday ? 'text-amber-600' : 'text-yellow-600' }}">
                            {{ $isToday ? '🎂 Hoje!' : '🎉 Dia ' . \Carbon\Carbon::parse($teacher->birth_date)->format('d') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endif


{{-- ═══════════════════════════════════════════════
     MODAL CARROSSEL DE FOTOS DO EVENTO
════════════════════════════════════════════════ --}}
<div id="event-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">

    {{-- Fundo escuro --}}
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeEventModal()"></div>

    {{-- Caixa do modal --}}
    <div class="relative z-10 flex items-center justify-center min-h-full p-4">
        <div class="bg-etec-main rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">

            {{-- Cabeçalho --}}
            <div id="modal-header" class="px-6 py-4 border-b border-white/10 flex items-start justify-between gap-4">
                <div>
                    <p id="modal-date" class="text-xs font-bold text-etec-accent uppercase tracking-widest mb-1"></p>
                    <h2 id="modal-title" class="text-xl font-bold text-white leading-tight"></h2>
                    <p id="modal-location" class="text-sm text-blue-100 mt-0.5 hidden"></p>
                </div>
                <button onclick="closeEventModal()"
                        class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full text-blue-200/70 hover:bg-white/10 hover:text-white transition text-lg">
                    ✕
                </button>
            </div>

            {{-- Carrossel de fotos --}}
            <div id="modal-carousel" class="relative bg-gray-900 hidden">
                <div id="carousel-track" class="relative overflow-hidden" style="height: 360px;">
                    {{-- Slides injetados via JS --}}
                </div>

                {{-- Botão anterior --}}
                <button id="carousel-prev" onclick="carouselMove(-1)"
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                {{-- Botão próximo --}}
                <button id="carousel-next" onclick="carouselMove(1)"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                {{-- Legenda e contador --}}
                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent px-5 py-4">
                    <p id="carousel-caption" class="text-white text-sm font-medium min-h-[1.25rem]"></p>
                    <p id="carousel-counter" class="text-white/60 text-xs mt-0.5"></p>
                </div>

                {{-- Dots --}}
                <div id="carousel-dots" class="absolute top-3 inset-x-0 flex justify-center gap-1.5 z-10"></div>
            </div>

            {{-- Descrição do evento --}}
            <div id="modal-description-wrap" class="px-6 py-5 hidden">
                <p id="modal-description" class="text-blue-100 text-sm leading-relaxed"></p>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-white/10 flex justify-end">
                <button onclick="closeEventModal()"
                        class="px-5 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm font-semibold transition">
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
        locEl.textContent = '📍 ' + data.location;
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
        dot.className = 'w-2 h-2 rounded-full transition ' + (i === 0 ? 'bg-white' : 'bg-white/40');
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
    if (dots[carouselIndex]) dots[carouselIndex].className = 'w-2 h-2 rounded-full transition bg-white';

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
