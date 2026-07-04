@extends('admin.layouts.app')

@section('title', 'Detalhes da Reserva #' . $reservation->id)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('lab.reservations.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reserva #{{ $reservation->id }}</h1>
        <span class="px-3 py-1 rounded-full text-xs font-bold
            {{ match($reservation->status) {
                'aprovada'    => 'bg-blue-100 text-blue-700',
                'em_execucao' => 'bg-yellow-100 text-yellow-700',
                'concluida','finalizada' => 'bg-green-100 text-green-700',
                'recusada'    => 'bg-red-100 text-red-700',
                'aguardando_conferencia' => 'bg-orange-100 text-orange-700',
                default       => 'bg-gray-100 text-gray-600',
            } }}">
            {{ $reservation->status_label }}
        </span>
    </div>

    @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{{ session('error') }}</div>@endif

    {{-- Informações --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-4">Informações</h2>
        <dl class="grid sm:grid-cols-2 gap-4 text-sm">
            <div><dt class="text-gray-500 dark:text-gray-400 font-medium">Professor</dt><dd class="text-gray-900 dark:text-white mt-0.5">{{ $reservation->user->name ?? '—' }}</dd></div>
            <div><dt class="text-gray-500 dark:text-gray-400 font-medium">Espaço</dt><dd class="text-gray-900 dark:text-white mt-0.5">{{ $reservation->space->name ?? '—' }}</dd></div>
            <div><dt class="text-gray-500 dark:text-gray-400 font-medium">Data</dt><dd class="text-gray-900 dark:text-white mt-0.5">{{ $reservation->reservation_date->format('d/m/Y') }}</dd></div>
            <div><dt class="text-gray-500 dark:text-gray-400 font-medium">Horário</dt><dd class="text-gray-900 dark:text-white mt-0.5">{{ substr($reservation->start_time,0,5) }}{{ $reservation->end_time ? ' – '.substr($reservation->end_time,0,5) : '' }}</dd></div>
            @if($reservation->description)
            <div class="sm:col-span-2"><dt class="text-gray-500 dark:text-gray-400 font-medium">Descrição</dt><dd class="text-gray-900 dark:text-white mt-0.5">{{ $reservation->description }}</dd></div>
            @endif
            @if($reservation->obs)
            <div class="sm:col-span-2"><dt class="text-gray-500 dark:text-gray-400 font-medium">Observações do professor</dt><dd class="text-gray-900 dark:text-white mt-0.5">{{ $reservation->obs }}</dd></div>
            @endif
        </dl>
    </div>

    {{-- Materiais --}}
    @if($reservation->materials->count())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700"><h2 class="font-bold text-gray-900 dark:text-white">Materiais</h2></div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-bold text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Material</th>
                    <th class="px-6 py-3 text-center">Qtd. Solicitada</th>
                    <th class="px-6 py-3 text-center">Entregue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($reservation->materials as $m)
                <tr>
                    <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">{{ $m->name }}</td>
                    <td class="px-6 py-3 text-center text-gray-600 dark:text-gray-300">{{ $m->pivot->quantity_requested }}</td>
                    <td class="px-6 py-3 text-center">
                        @if($m->pivot->delivered)
                            <span class="text-green-600 font-bold text-xs">✓ Sim</span>
                        @else
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Ações por papel e status --}}
    <div class="flex flex-wrap gap-3">
        {{-- Admin: aprovar/recusar --}}
        @role('admin')
        @if($reservation->status === 'pre_alocada')
        <form action="{{ route('lab.reservations.approve', $reservation) }}" method="POST">
            @csrf @method('PATCH')
            <button class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 transition text-sm font-semibold">
                ✓ Aprovar
            </button>
        </form>
        <form action="{{ route('lab.reservations.reject', $reservation) }}" method="POST">
            @csrf @method('PATCH')
            <button class="inline-flex items-center gap-2 bg-red-600 text-white px-4 py-2.5 rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                ✗ Recusar
            </button>
        </form>
        @endif
        @endrole

        {{-- Professor: iniciar aula --}}
        @if($reservation->status === 'aprovada' && $reservation->user_id === auth()->id())
        <form action="{{ route('lab.reservations.start', $reservation) }}" method="POST">
            @csrf
            <button class="inline-flex items-center gap-2 bg-yellow-500 text-white px-4 py-2.5 rounded-lg hover:bg-yellow-600 transition text-sm font-semibold">
                ▶ Iniciar Aula
            </button>
        </form>
        @endif

        {{-- Professor: registrar obs --}}
        @if($reservation->status === 'em_execucao' && $reservation->user_id === auth()->id())
        <div class="w-full bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-4">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Registrar observações e encerrar</p>
            <form action="{{ route('lab.reservations.professor-obs', $reservation) }}" method="POST" class="flex gap-3">
                @csrf
                <textarea name="obs" rows="2" required placeholder="Descreva como foi a aula..."
                          class="flex-1 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white resize-none focus:ring-2 focus:ring-etec-dark outline-none"></textarea>
                <button class="px-4 py-2 bg-etec-dark text-white rounded-lg text-sm font-semibold hover:bg-etec-main transition self-end">Enviar</button>
            </form>
        </div>
        @endif

        {{-- Auxiliar: conferência --}}
        @if($reservation->status === 'aguardando_conferencia')
        @role('Auxiliar|admin')
        <form action="{{ route('lab.reservations.auxiliar-finalize', $reservation) }}" method="POST">
            @csrf
            <button class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2.5 rounded-lg hover:bg-purple-700 transition text-sm font-semibold">
                ✓ Conferir e Finalizar
            </button>
        </form>
        @endrole
        @endif

        {{-- PDF --}}
        <a href="{{ route('lab.reservations.pdf', $reservation) }}" target="_blank"
           class="inline-flex items-center gap-2 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 px-4 py-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            Gerar PDF
        </a>
    </div>
</div>
@endsection
