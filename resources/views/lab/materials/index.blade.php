@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">
        
        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>Inventário de Materiais</span>
                    <span class="rounded-xl bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700 normal-case tracking-normal">{{ $materials->total() }} itens</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 font-medium mt-1">Controle de estoque de materiais, insumos, equipamentos e patrimônios para aulas práticas</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('lab.materials.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-purple-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Novo Material</span>
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

        <!-- Card Grid of Materials -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse($materials as $m)
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-start gap-3.5 mb-3.5">
                            @if($m->photo)
                                <img src="{{ Storage::url($m->photo) }}" class="w-16 h-16 rounded-2xl object-cover border border-gray-200 flex-shrink-0">
                            @else
                                <div class="w-16 h-16 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center flex-shrink-0 border border-purple-100">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <span class="text-xs font-bold text-gray-400">#{{ $m->id }}</span>
                                <h3 class="font-bold text-sm text-gray-900 leading-snug line-clamp-2 mt-0.5">{{ $m->name }}</h3>
                                @if($m->patrimony_number)
                                    <span class="inline-block font-mono text-[10.5px] font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md mt-1 border border-gray-200">
                                        Patr: {{ $m->patrimony_number }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($m->description)
                            <p class="text-xs text-gray-600 line-clamp-2 mb-3.5 leading-relaxed">
                                {{ $m->description }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between bg-gray-50 rounded-2xl p-3 mb-3 border border-gray-200">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase block">Estoque</span>
                                <span class="text-base font-semibold {{ $m->stock_quantity > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $m->stock_quantity }} <span class="text-xs font-normal text-gray-500">{{ $m->unit ?? 'un' }}</span>
                                </span>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $m->stock_quantity > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                {{ $m->stock_quantity > 0 ? 'Disponível' : 'Esgotado' }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                        <a href="{{ route('lab.materials.edit', $m) }}" class="inline-flex items-center rounded-xl bg-gray-100 px-3 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-200 transition">
                            Editar
                        </a>
                        <form action="{{ route('lab.materials.destroy', $m) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este material?')">
                            @csrf @method('DELETE')
                            <button class="inline-flex items-center rounded-xl bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-100 transition">
                                Excluir
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-gray-300 bg-white p-14 text-center text-gray-400">
                    <p class="text-base font-medium">Nenhum material cadastrado no inventário.</p>
                </div>
            @endforelse
        </div>

        @if($materials->hasPages())
            <div class="mt-4 bg-white rounded-2xl p-4 shadow-sm border border-gray-200">
                {{ $materials->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
