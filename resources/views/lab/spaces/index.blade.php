@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">
        
        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>Laboratórios & Ambientes</span>
                    <span class="rounded-xl bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 normal-case tracking-normal">{{ $spaces->count() }} cadastrados</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 font-medium mt-1">Gestão de espaços didáticos, vinculação de auxiliares e laboratórios do catálogo</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('lab.spaces.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Novo Espaço</span>
                </a>
                <a href="{{ route('lab.reservations.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>
                    <span>Quadro de Reservas</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500 text-white p-4 text-sm font-bold shadow-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        <!-- Card Grid of Spaces -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($spaces as $s)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </span>
                                <div>
                                    <h3 class="font-bold text-base text-gray-900 leading-snug">{{ $s->name }}</h3>
                                    <span class="text-xs text-gray-400 font-mono">#{{ $s->id }}</span>
                                </div>
                            </div>

                            @if($s->laboratory)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-lg">
                                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> {{ $s->laboratory->name }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-lg italic">
                                    Não vinculado
                                </span>
                            @endif
                        </div>

                        @if($s->description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                                {{ $s->description }}
                            </p>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            <span class="font-semibold text-gray-400">Auxiliar:</span>
                            <span class="font-bold text-gray-800">{{ $s->auxiliar->name ?? 'Não atribuído' }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('lab.spaces.edit', $s) }}" class="inline-flex items-center rounded-xl bg-gray-100 px-3 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-200 transition">
                                Editar
                            </a>
                            <form action="{{ route('lab.spaces.destroy', $s) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este espaço didático?')">
                                @csrf @method('DELETE')
                                <button class="inline-flex items-center rounded-xl bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-100 transition">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-white p-14 text-center text-gray-400">
                    <p class="text-base font-medium">Nenhum espaço didático cadastrado.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
