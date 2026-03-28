@extends('layouts.app')

@section('content')

<section class="relative bg-etec-dark h-[500px] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1600&auto=format&fit=crop"
             alt="Campo agrícola" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-r from-etec-dark via-etec-dark/90 to-transparent"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10 grid md:grid-cols-2">
        <div class="text-white space-y-6 animate-fade-in-up">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-1 rounded-full border border-white/20">
                <span class="w-2 h-2 bg-etec-accent rounded-full animate-pulse"></span>
                <span class="text-xs font-bold tracking-widest uppercase">Portal Institucional</span>
            </div>

            <h1 class="text-4xl md:text-5xl font-serif font-bold leading-tight">
                Olá, <span class="text-etec-accent italic">Seja Bem-vindo</span>.
            </h1>



            <p class="text-lg text-gray-200 max-w-xl border-l-4 border-etec-accent pl-4">
                Bem-vindo ao sistema de gestão da Escola Agrícola.<br>
                Acesse abaixo as unidades escolares e os laboratórios didáticos.
            </p>
        </div>
    </div>
</section>

<section id="unidades" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-etec-medium font-bold uppercase tracking-widest text-sm mb-2">Locais de Ensino</h2>
            <h3 class="text-3xl font-serif font-bold text-etec-dark">Nossas Unidades</h3>
            <div class="w-24 h-1 bg-etec-accent mx-auto mt-4"></div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if(isset($units))
                @foreach($units as $unit)
                    <a href="{{ route('units.show', $unit->id) }}" class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">
                        <div class="h-40 bg-etec-dark relative overflow-hidden flex items-center justify-center">
                            <div class="absolute inset-0 bg-pattern opacity-10"></div>
                            <div class="text-6xl group-hover:scale-110 transition duration-500">
                                @if(Str::contains($unit->name, 'Agrícola') || Str::contains($unit->name, 'Sede')) 🚜
                                @elseif(Str::contains($unit->name, 'FEA')) 🏢
                                @elseif(Str::contains($unit->name, 'Youssef')) 🏫
                                @else 🏛️ @endif
                            </div>
                            <div class="absolute bottom-3 left-4 text-white font-bold text-xs bg-black/30 px-2 py-1 rounded backdrop-blur-sm">
                                📍 {{ $unit->city }}
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <h4 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-etec-medium transition leading-tight">
                                {{ $unit->name }}
                            </h4>
                            <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-100">
                                <span class="text-sm font-bold text-etec-dark bg-etec-light/30 px-3 py-1 rounded-full">
                                    📚 {{ $unit->courses_count }} Cursos
                                </span>
                                <span class="text-gray-400 group-hover:text-etec-accent transition">➜</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
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
                <h2 class="text-3xl font-serif font-bold">Laboratórios e Unidades Didáticas</h2>
                <p class="text-etec-light mt-2">Infraestrutura prática para os cursos técnicos.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @if(isset($sectors))
                @foreach($sectors as $sector)
                <a href="{{ route('sectors.show', $sector->slug) }}" class="block bg-white/5 backdrop-blur-md p-6 rounded-lg hover:bg-white/10 transition border border-white/5 group cursor-pointer">
                    <div class="text-3xl mb-4 group-hover:scale-110 transition transform text-etec-accent">
                        {{-- Ícones --}}
                        @if($sector->icon == 'cow') 🐮
                        @elseif($sector->icon == 'pig') 🐷
                        @elseif($sector->icon == 'chicken') 🐔
                        @elseif($sector->icon == 'fish') 🐟
                        @elseif($sector->icon == 'bee') 🐝
                        @elseif($sector->icon == 'leaf') 🌱
                        @elseif($sector->icon == 'tree') 🌳
                        @elseif($sector->icon == 'factory') 🏭
                        @elseif($sector->icon == 'computer') 💻
                        @elseif($sector->icon == 'flask') 🧪
                        @elseif($sector->icon == 'cheese') 🧀
                        @elseif($sector->icon == 'meat') 🥩
                        @else 🚜 @endif
                    </div>
                    <h3 class="font-bold text-lg mb-1">{{ $sector->name }}</h3>
                    <p class="text-xs text-gray-400 line-clamp-2">{{ $sector->summary }}</p>
                </a>
                @endforeach
            @endif
        </div>
    </div>
</section>

@endsection
