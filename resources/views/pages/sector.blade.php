@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="text-6xl bg-white/10 p-4 rounded-xl backdrop-blur-sm">
            {{-- Ícone do Setor --}}
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
            @else 🚜 @endif
        </div>
        <div>
            <span class="text-etec-accent font-bold tracking-widest uppercase text-xs mb-2 block">
                Unidade Didática & Laboratório
            </span>
            <h1 class="text-3xl md:text-5xl font-serif font-bold">
                {{ $sector->name }}
            </h1>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="grid lg:grid-cols-3 gap-12">

        <div class="lg:col-span-2 space-y-8">

            @if(!empty($sector->images))
            <div class="relative group rounded-2xl overflow-hidden shadow-2xl bg-gray-900 aspect-video">
                <div class="flex overflow-x-auto snap-x snap-mandatory h-full w-full scrollbar-hide" style="scroll-behavior: smooth;">
                    @foreach($sector->images as $image)
                    <div class="snap-center flex-shrink-0 w-full h-full relative">
                        <img src="{{ $image }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    @endforeach
                </div>

                <div class="absolute bottom-4 left-0 right-0 text-center text-white/80 text-sm font-bold animate-pulse">
                    ← Deslize para ver mais fotos →
                </div>
            </div>
            @endif

            <div class="prose prose-lg max-w-none text-gray-700">
                <h3 class="text-2xl font-bold text-etec-dark mb-4 border-l-4 border-etec-accent pl-4">
                    Finalidade Pedagógica
                </h3>
                <p class="text-lg leading-relaxed">
                    {{ $sector->description ?? $sector->summary }}
                </p>

                <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 mt-6">
                    <strong class="block text-blue-800 mb-2">🎓 Cursos Atendidos:</strong>
                    <ul class="list-disc list-inside space-y-1 text-blue-700">
                        <li>MTEC-PI em Agropecuária</li>
                        <li>MTEC-PI em Agronegócio</li>
                        <li>Técnico em Florestas</li>
                        <li>Técnico em Zootecnia</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 sticky top-6">
                <h4 class="font-bold text-gray-800 text-xl mb-4">Destaques</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <span class="bg-green-100 text-green-700 p-2 rounded-lg text-xl">🌱</span>
                        <div>
                            <strong class="block text-gray-800 text-sm">Sustentabilidade</strong>
                            <span class="text-xs text-gray-500">Práticas alinhadas com o meio ambiente.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="bg-blue-100 text-blue-700 p-2 rounded-lg text-xl">⚙️</span>
                        <div>
                            <strong class="block text-gray-800 text-sm">Tecnologia</strong>
                            <span class="text-xs text-gray-500">Equipamentos modernos e digitais.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="bg-yellow-100 text-yellow-700 p-2 rounded-lg text-xl">👷</span>
                        <div>
                            <strong class="block text-gray-800 text-sm">Segurança</strong>
                            <span class="text-xs text-gray-500">Uso obrigatório de EPIs.</span>
                        </div>
                    </li>
                </ul>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('home') }}#fazenda" class="block w-full text-center py-3 border-2 border-etec-dark text-etec-dark font-bold rounded-lg hover:bg-etec-dark hover:text-white transition">
                        Voltar para Unidades
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
