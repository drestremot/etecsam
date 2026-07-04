@extends('admin.layouts.app')
@section('title', 'Laboratórios')
@section('content')
<div class="space-y-6">

    @if(session('info'))
    <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-lg px-4 py-3 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('info') }}
    </div>
    @endif

    {{-- ───── PERFIL DO USUÁRIO ───── --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <div class="flex items-center gap-5">
            {{-- Foto --}}
            @if($teacher?->photo)
                <img src="{{ photo_url($teacher->photo) }}" alt="{{ $user->name }}"
                     class="w-20 h-20 rounded-full object-cover border-4 border-etec-light flex-shrink-0">
            @else
                <div class="w-20 h-20 rounded-full bg-etec-dark text-white flex items-center justify-center text-3xl font-bold border-4 border-etec-light flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white truncate">{{ $user->name }}</h2>
                    @if($user->is_admin)
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-etec-dark text-white">Admin</span>
                    @elseif($user->hasRole('Auxiliar'))
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-700">Auxiliar</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Professor</span>
                    @endif
                </div>
                @if($teacher)
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $teacher->role }}</p>
                    @if($teacher->specialty)
                        <p class="text-sm text-gray-400 dark:text-gray-500 truncate">{{ $teacher->specialty }}</p>
                    @endif
                @else
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">{{ $user->email }}</p>
                @endif
                <a href="{{ route('lab.profile.edit') }}" class="inline-flex items-center gap-1 text-xs text-etec-main dark:text-etec-accent hover:underline mt-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Editar meu perfil
                </a>
            </div>
            @if(!$user->is_admin)
            <a href="{{ route('lab.reservations.create') }}"
               class="flex-shrink-0 inline-flex items-center gap-2 bg-etec-dark text-white px-4 py-2.5 rounded-lg hover:bg-etec-main transition font-semibold text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nova Reserva
            </a>
            @endif
        </div>
    </div>

    {{-- ───── STATS ───── --}}
    @if($user->is_admin)
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Espaços', 'value' => $stats['spaces'], 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5'],
            ['label' => 'Materiais', 'value' => $stats['materials'], 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10'],
            ['label' => 'Aguardando aprovação', 'value' => $stats['pending'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Em andamento', 'value' => $stats['active'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as $s)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ $s['label'] }}</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $s['value'] }}</p>
        </div>
        @endforeach
    </div>

    @elseif($user->hasRole('Coordenador'))
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Aguardando aprovação', 'value' => $stats['aguardando_aprovacao']],
            ['label' => 'Aguardando validação', 'value' => $stats['aguardando_validacao']],
            ['label' => 'Ativas', 'value' => $stats['ativas']],
            ['label' => 'Validadas', 'value' => $stats['validadas']],
        ] as $s)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ $s['label'] }}</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $s['value'] }}</p>
        </div>
        @endforeach
    </div>

    @elseif($user->hasRole('Auxiliar'))
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Aguardando conferência', 'value' => $stats['aguardando'], 'color' => 'orange'],
            ['label' => 'Ativas', 'value' => $stats['ativas'], 'color' => 'yellow'],
            ['label' => 'Concluídas', 'value' => $stats['concluidas'], 'color' => 'green'],
            ['label' => 'Total', 'value' => $stats['total'], 'color' => 'gray'],
        ] as $s)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ $s['label'] }}</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $s['value'] }}</p>
        </div>
        @endforeach
    </div>

    @else
    {{-- Professor --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Minhas reservas', 'value' => $stats['minhas']],
            ['label' => 'Pendentes', 'value' => $stats['pendentes']],
            ['label' => 'Em andamento', 'value' => $stats['ativas']],
            ['label' => 'Concluídas', 'value' => $stats['concluidas']],
        ] as $s)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ $s['label'] }}</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $s['value'] }}</p>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ───── RESERVAS RECENTES ───── --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 dark:text-white">
                {{ $user->hasRole('Auxiliar') ? 'Reservas para conferência' : 'Reservas recentes' }}
            </h2>
            <a href="{{ route('lab.reservations.index') }}" class="text-sm text-etec-main dark:text-etec-accent hover:underline">Ver todas</a>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($recent as $r)
            <a href="{{ route('lab.reservations.show', $r) }}"
               class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                <div class="w-10 h-10 rounded-lg bg-etec-light flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-etec-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $r->space->name ?? '—' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $r->reservation_date->format('d/m/Y') }}
                        @if($user->is_admin || $user->hasRole('Auxiliar'))
                            • {{ $r->user->name ?? '—' }}
                        @endif
                    </p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0
                    {{ match($r->status) {
                        'aprovada'               => 'bg-blue-100 text-blue-700',
                        'em_execucao'            => 'bg-yellow-100 text-yellow-700',
                        'concluida','finalizada' => 'bg-green-100 text-green-700',
                        'recusada'               => 'bg-red-100 text-red-700',
                        'aguardando_conferencia' => 'bg-orange-100 text-orange-700',
                        default                  => 'bg-gray-100 text-gray-600',
                    } }}">
                    {{ $r->status_label }}
                </span>
            </a>
            @empty
            <div class="px-6 py-8 text-center text-sm text-gray-400">
                @if($user->is_admin || $user->hasRole('Auxiliar'))
                    Nenhuma reserva ainda.
                @else
                    Você ainda não fez nenhuma reserva.
                    <a href="{{ route('lab.reservations.create') }}" class="text-etec-main dark:text-etec-accent hover:underline ml-1">Criar agora</a>
                @endif
            </div>
            @endforelse
        </div>
    </div>

    {{-- ───── NAVEGAÇÃO RÁPIDA (admin) ───── --}}
    @if($user->is_admin)
    <div class="grid sm:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Espaços', 'route' => 'lab.spaces.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16'],
            ['label' => 'Materiais', 'route' => 'lab.materials.index', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4'],
            ['label' => 'Usuários', 'route' => 'lab.users.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197'],
            ['label' => 'Histórico', 'route' => 'lab.reservations.history', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ] as $nav)
        <a href="{{ route($nav['route']) }}"
           class="flex items-center gap-3 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:border-etec-dark transition group shadow-sm">
            <div class="w-10 h-10 bg-etec-light rounded-lg flex items-center justify-center group-hover:bg-etec-dark transition flex-shrink-0">
                <svg class="w-5 h-5 text-etec-dark group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $nav['icon'] }}"/>
                </svg>
            </div>
            <span class="font-semibold text-gray-800 dark:text-white text-sm">{{ $nav['label'] }}</span>
        </a>
        @endforeach
    </div>
    @endif

</div>
@endsection
