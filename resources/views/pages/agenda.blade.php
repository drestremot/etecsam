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
                    @php $hasPhotos = $event->photos->count() > 0; @endphp
                    <div class="flex flex-col md:flex-row bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition group
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
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-bold text-gray-800 mb-1.5 group-hover:text-etec-medium transition">
                                    {{ $event->title }}
                                </h3>
                                @if($hasPhotos)
                                <span class="flex-shrink-0 inline-flex items-center gap-1 text-xs text-etec-medium font-semibold bg-etec-light/30 px-2.5 py-1 rounded-full mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $event->photos->count() }} foto{{ $event->photos->count() > 1 ? 's' : '' }}
                                </span>
                                @endif
                            </div>
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


{{-- ═══════════════════════════════════════════════
     MODAL CARROSSEL DE FOTOS DO EVENTO
════════════════════════════════════════════════ --}}
<div id="event-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">

    {{-- Fundo escuro --}}
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeEventModal()"></div>

    {{-- Caixa do modal --}}
    <div class="relative z-10 flex items-center justify-center min-h-full p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">

            {{-- Cabeçalho --}}
            <div id="modal-header" class="px-6 py-4 border-b border-gray-100 flex items-start justify-between gap-4">
                <div>
                    <p id="modal-date" class="text-xs font-bold text-etec-medium uppercase tracking-widest mb-1"></p>
                    <h2 id="modal-title" class="text-xl font-bold text-gray-900 leading-tight"></h2>
                    <p id="modal-location" class="text-sm text-gray-500 mt-0.5 hidden"></p>
                </div>
                <button onclick="closeEventModal()"
                        class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition text-lg">
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
                <p id="modal-description" class="text-gray-600 text-sm leading-relaxed"></p>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end">
                <button onclick="closeEventModal()"
                        class="px-5 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold transition">
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
        descEl.textContent = data.description;
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
