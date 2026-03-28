@extends('layouts.app')

@section('content')

<section class="relative bg-etec-dark h-[600px] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1600&auto=format&fit=crop"
             alt="Campo agrícola"
             class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-r from-etec-dark via-etec-dark/80 to-transparent"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10 grid md:grid-cols-2">
        <div class="text-white space-y-6 animate-fade-in-up">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-1 rounded-full border border-white/20">
                <span class="w-2 h-2 bg-etec-accent rounded-full animate-pulse"></span>
                <span class="text-xs font-bold tracking-widest uppercase">Escola Agrícola</span>
            </div>

            <h1 class="text-5xl md:text-6xl font-serif font-bold leading-tight">
                Cultivando o <span class="text-etec-accent italic">Futuro</span> através do conhecimento.
            </h1>

            <p class="text-lg text-gray-200 max-w-xl border-l-4 border-etec-accent pl-4">
                Ensino médio integrado e cursos técnicos de excelência.
                Uma estrutura completa de fazenda para o aprendizado prático.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a href="#cursos" class="bg-etec-accent text-etec-dark px-8 py-4 rounded font-bold hover:bg-white transition shadow-lg text-center">
                    Conhecer Cursos
                </a>
                <a href="#" class="border border-white text-white px-8 py-4 rounded font-bold hover:bg-white hover:text-etec-dark transition text-center">
                    Área do Aluno
                </a>
            </div>
        </div>
    </div>
</section>

<div class="bg-etec-medium py-8 relative z-20 -mt-8 mx-4 md:mx-0 md:container md:mx-auto rounded-lg shadow-xl text-white">
    <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-white/20 text-center gap-y-4">
        <div>
            <span class="block text-3xl font-bold font-serif">40+</span>
            <span class="text-xs uppercase tracking-wide opacity-80">Anos de História</span>
        </div>
        <div>
            <span class="block text-3xl font-bold font-serif">8</span>
            <span class="text-xs uppercase tracking-wide opacity-80">Cursos Técnicos</span>
        </div>
        <div>
            <span class="block text-3xl font-bold font-serif">150ha</span>
            <span class="text-xs uppercase tracking-wide opacity-80">Área de Fazenda</span>
        </div>
        <div>
            <span class="block text-3xl font-bold font-serif">100%</span>
            <span class="text-xs uppercase tracking-wide opacity-80">Gratuito</span>
        </div>
    </div>
</div>

<section id="unidades" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">

        <div class="text-center mb-16">
            <h2 class="text-etec-medium font-bold uppercase tracking-widest text-sm mb-2">Onde Estudar</h2>
            <h3 class="text-4xl font-serif font-bold text-etec-dark">Nossas Unidades</h3>
            <div class="w-24 h-1 bg-etec-accent mx-auto mt-4"></div>
            <p class="mt-4 text-gray-500 max-w-2xl mx-auto">
                Selecione uma de nossas escolas abaixo para ver os cursos disponíveis nela.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($units as $unit)
                <a href="{{ route('units.show', $unit->id) }}" class="group bg-white rounded-xl shadow-sm hover:shadow-2xl transition duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">

                    <div class="h-40 bg-etec-dark relative overflow-hidden flex items-center justify-center">
                        <div class="absolute inset-0 bg-pattern opacity-10"></div> <div class="text-6xl group-hover:scale-110 transition duration-500">
                            @if(Str::contains($unit->name, 'Agrícola') || Str::contains($unit->name, 'Sede')) 🚜
                            @elseif(Str::contains($unit->name, 'FEA')) 🏢
                            @elseif(Str::contains($unit->name, 'Youssef')) 🏫
                            @else 🏛️ @endif
                        </div>

                        <div class="absolute bottom-3 left-4 text-white font-bold text-xs bg-black/30 px-2 py-1 rounded backdrop-blur-sm flex items-center gap-1">
                            📍 {{ $unit->city }}
                        </div>
                    </div>

                    <div class="p-6 flex flex-col flex-grow">
                        <h4 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-etec-medium transition leading-tight">
                            {{ $unit->name }}
                        </h4>

                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">
                            Unidade oficial da Etec em {{ $unit->city }}. Clique para ver a grade curricular.
                        </p>

                        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-100">
                            <span class="text-sm font-bold text-etec-dark bg-etec-light/30 px-3 py-1 rounded-full">
                                📚 {{ $unit->courses_count }} Curso(s)
                            </span>
                            <span class="w-8 h-8 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center group-hover:bg-etec-accent group-hover:text-etec-dark transition">
                                ➜
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>
<section id="fazenda" class="py-20 bg-etec-dark text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 p-10 opacity-5">
        <svg width="200" height="200" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/></svg>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-white/10 pb-6">
            <div>
                <h2 class="text-3xl font-serif font-bold">Unidades Didáticas</h2>
                <p class="text-etec-light mt-2">Aprenda na prática em nossa infraestrutura produtiva.</p>
            </div>
            <a href="#" class="mt-4 md:mt-0 px-6 py-2 border border-etec-accent text-etec-accent rounded hover:bg-etec-accent hover:text-white transition">
                Ver todas as unidades
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($sectors as $sector)
            <div class="bg-white/5 backdrop-blur-md p-6 rounded-lg hover:bg-white/10 transition border border-white/5 group cursor-pointer">
                <div class="text-3xl mb-4 group-hover:scale-110 transition transform text-etec-accent">
                    @if($sector->icon == 'cow') 🐮
                    @elseif($sector->icon == 'leaf') 🌱
                    @elseif($sector->icon == 'tractor') 🚜
                    @else ⚙️ @endif
                </div>
                <h3 class="font-bold text-lg mb-1">{{ $sector->name }}</h3>
                <p class="text-xs text-gray-400 line-clamp-2">{{ $sector->summary }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-etec-dark mb-10">Agenda Escolar</h2>

        <div class="grid md:grid-cols-2 gap-10">
            @forelse($nextEvents as $event)
            <div class="flex gap-6 items-start">
                <div class="flex-shrink-0 bg-etec-light text-etec-dark w-20 h-20 rounded-lg flex flex-col items-center justify-center border border-etec-medium/20">
                    <span class="text-2xl font-bold">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</span>
                    <span class="text-xs uppercase font-bold">{{ \Carbon\Carbon::parse($event->start_date)->format('M') }}</span>
                </div>
                <div>
                    <h3 class="font-bold text-xl text-gray-800 hover:text-etec-medium transition">
                        <a href="#">{{ $event->title }}</a>
                    </h3>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mt-2">
                        <span class="flex items-center gap-1">🕒 {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}</span>
                        <span class="flex items-center gap-1">📍 {{ $event->location }}</span>
                    </div>
                    <p class="text-gray-600 mt-2 text-sm">{{ Str::limit($event->description, 100) }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-500">Nenhum evento agendado para os próximos dias.</p>
            @endforelse
        </div>
    </div>
</section>

@endsection
