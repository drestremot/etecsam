@extends('admin.layouts.app')

@section('title', 'Laboratórios')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestão de Laboratórios</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Reservas, espaços e materiais didáticos</p>
        </div>
        <a href="{{ route('lab.reservations.create') }}"
           class="inline-flex items-center gap-2 bg-etec-dark text-white px-4 py-2.5 rounded-lg hover:bg-etec-main transition font-semibold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nova Reserva
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Espaços', 'value' => $stats['spaces'], 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5', 'color' => 'etec-dark'],
            ['label' => 'Materiais', 'value' => $stats['materials'], 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'color' => 'etec-main'],
            ['label' => 'Aguardando', 'value' => $stats['pending'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'yellow-600'],
            ['label' => 'Em andamento', 'value' => $stats['active'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'green-600'],
        ] as $stat)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ $stat['label'] }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stat['value'] }}</p>
                </div>
                <div class="w-12 h-12 bg-etec-light rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-etec-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Reservas recentes --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 dark:text-white">Reservas Recentes</h2>
            <a href="{{ route('lab.reservations.index') }}" class="text-sm text-etec-main dark:text-etec-accent hover:underline">Ver todas</a>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($recent as $r)
            <a href="{{ route('lab.reservations.show', $r) }}"
               class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                <div class="w-10 h-10 rounded-lg bg-etec-light flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-etec-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $r->space->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $r->reservation_date->format('d/m/Y') }} • {{ $r->user->name ?? '—' }}</p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full
                    {{ match($r->status) {
                        'aprovada'    => 'bg-blue-100 text-blue-700',
                        'em_execucao' => 'bg-yellow-100 text-yellow-700',
                        'concluida','finalizada' => 'bg-green-100 text-green-700',
                        'recusada'    => 'bg-red-100 text-red-700',
                        default       => 'bg-gray-100 text-gray-600',
                    } }}">
                    {{ $r->status_label }}
                </span>
            </a>
            @empty
            <div class="px-6 py-8 text-center text-sm text-gray-400">Nenhuma reserva ainda.</div>
            @endforelse
        </div>
    </div>

    {{-- Navegação rápida (admin) --}}
    @role('admin')
    <div class="grid sm:grid-cols-3 gap-4">
        @foreach([
            ['label' => 'Espaços didáticos', 'route' => 'lab.spaces.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16'],
            ['label' => 'Materiais', 'route' => 'lab.materials.index', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4'],
            ['label' => 'Usuários', 'route' => 'lab.users.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197'],
        ] as $nav)
        <a href="{{ route($nav['route']) }}"
           class="flex items-center gap-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:border-etec-main dark:hover:border-etec-accent transition group shadow-sm">
            <div class="w-10 h-10 bg-etec-light rounded-lg flex items-center justify-center group-hover:bg-etec-dark transition">
                <svg class="w-5 h-5 text-etec-dark group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $nav['icon'] }}"/>
                </svg>
            </div>
            <span class="font-semibold text-gray-800 dark:text-white text-sm">{{ $nav['label'] }}</span>
        </a>
        @endforeach
    </div>
    @endrole

</div>
@endsection
