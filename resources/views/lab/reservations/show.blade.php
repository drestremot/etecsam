@extends('admin.layouts.app')
@section('title', 'Reserva #' . $reservation->id)
@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Cabeçalho --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('lab.reservations.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="flex-1 flex items-center gap-3 flex-wrap">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reserva #{{ $reservation->id }}</h1>
            <span class="px-3 py-1 rounded-full text-xs font-bold
                {{ match($reservation->status) {
                    'aprovada'               => 'bg-blue-100 text-blue-700',
                    'em_execucao'            => 'bg-yellow-100 text-yellow-700',
                    'aguardando_conferencia' => 'bg-orange-100 text-orange-700',
                    'conferida'              => 'bg-purple-100 text-purple-700',
                    'concluida','finalizada' => 'bg-green-100 text-green-700',
                    'recusada'               => 'bg-red-100 text-red-700',
                    default                  => 'bg-gray-100 text-gray-600',
                } }}">
                {{ $reservation->status_label }}
            </span>
        </div>
        <a href="{{ route('lab.reservations.pdf', $reservation) }}" target="_blank"
           class="flex-shrink-0 inline-flex items-center gap-1.5 border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 px-3 py-1.5 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-xs font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            PDF
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="flex-1">{{ session('success') }}</span>
        @if(session('print_pdf'))
        <a href="{{ route('lab.reservations.pdf', $reservation) }}" target="_blank"
           class="flex-shrink-0 inline-flex items-center gap-1.5 bg-etec-dark text-white text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-etec-main transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Imprimir / PDF
        </a>
        @endif
    </div>
    @endif
    @if(session('error'))<div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{{ session('error') }}</div>@endif

    {{-- Botão de impressão sempre visível --}}
    <div class="flex justify-end">
        <a href="{{ route('lab.reservations.pdf', $reservation) }}" target="_blank"
           class="inline-flex items-center gap-2 border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-300 px-4 py-2 rounded-lg hover:bg-etec-dark hover:text-white hover:border-etec-dark transition text-sm font-semibold">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Imprimir / Gerar PDF
        </a>
    </div>

    {{-- ── Informações principais ── --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <h2 class="font-bold text-gray-900 dark:text-white mb-5 text-base">Informações da Reserva</h2>
        <dl class="grid sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Professor Solicitante</dt>
                <dd class="flex items-center gap-2 text-gray-900 dark:text-white font-medium">
                    @php $prof = \App\Models\Teacher::where('email', $reservation->user->email ?? '')->first(); @endphp
                    @if($prof?->photo)
                        <img src="{{ photo_url($prof->photo) }}" class="w-7 h-7 rounded-full object-cover flex-shrink-0">
                    @endif
                    {{ $reservation->user->name ?? '—' }}
                </dd>
                @if($prof?->role)
                    <dd class="text-xs text-gray-400 mt-0.5">{{ $prof->role }}</dd>
                @endif
            </div>
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Local (Espaço)</dt>
                <dd class="text-gray-900 dark:text-white font-medium">{{ $reservation->space->name ?? '—' }}</dd>
                @if($reservation->space?->description)
                    <dd class="text-xs text-gray-400 mt-0.5">{{ $reservation->space->description }}</dd>
                @endif
            </div>
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Data</dt>
                <dd class="text-gray-900 dark:text-white font-medium">{{ $reservation->reservation_date->format('d/m/Y') }} ({{ $reservation->reservation_date->translatedFormat('l') }})</dd>
            </div>
            <div>
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Horário</dt>
                <dd class="text-gray-900 dark:text-white font-medium">
                    {{ substr($reservation->start_time, 0, 5) }}
                    @if($reservation->end_time) — {{ substr($reservation->end_time, 0, 5) }} @endif
                </dd>
            </div>
            @if($reservation->description)
            <div class="sm:col-span-2">
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Plano de Aula / Descrição</dt>
                <dd class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $reservation->description }}</dd>
            </div>
            @endif
            @if($reservation->obs)
            <div class="sm:col-span-2">
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Observações do Professor</dt>
                <dd class="text-gray-700 dark:text-gray-300 leading-relaxed bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-100 dark:border-blue-800">{{ $reservation->obs }}</dd>
            </div>
            @endif
            @if($reservation->auxiliar_obs)
            <div class="sm:col-span-2">
                <dt class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Observações do Auxiliar</dt>
                <dd class="text-gray-700 dark:text-gray-300 leading-relaxed bg-purple-50 dark:bg-purple-900/20 rounded-lg p-3 border border-purple-100 dark:border-purple-800">
                    {{ $reservation->auxiliar_obs }}
                    @if($reservation->auxiliar)
                        <span class="block text-xs text-gray-400 mt-1">— {{ $reservation->auxiliar->name }}</span>
                    @endif
                </dd>
            </div>
            @endif
        </dl>
    </div>

    {{-- ── Materiais e Recursos ── --}}
    @if($reservation->materials->count())
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <h2 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                <svg class="w-5 h-5 text-etec-main dark:text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                Materiais e Recursos Solicitados
            </h2>
        </div>
        <table class="w-full text-sm">
            <thead class="text-xs font-bold text-gray-500 uppercase tracking-wide bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left">Material</th>
                    <th class="px-6 py-3 text-center">Nº Patrimônio</th>
                    <th class="px-6 py-3 text-center">Qtd. Solicitada</th>
                    <th class="px-6 py-3 text-center">Disponível</th>
                    <th class="px-6 py-3 text-center">Entregue</th>
                    <th class="px-6 py-3 text-center">Devolvido</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($reservation->materials as $m)
                <tr class="{{ $m->pivot->delivered && !$m->pivot->returned ? 'bg-yellow-50 dark:bg-yellow-900/10' : '' }}">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            @if($m->photo)
                                <img src="{{ Storage::url($m->photo) }}" class="w-9 h-9 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-9 h-9 rounded-lg bg-etec-light flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-etec-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $m->name }}</p>
                                @if($m->description)
                                    <p class="text-xs text-gray-400 truncate">{{ $m->description }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-center font-mono text-xs text-gray-500">{{ $m->patrimony_number ?? '—' }}</td>
                    <td class="px-6 py-3 text-center font-bold text-gray-900 dark:text-white">{{ $m->pivot->quantity_requested }}</td>
                    <td class="px-6 py-3 text-center">
                        <span class="{{ $m->stock_quantity >= $m->pivot->quantity_requested ? 'text-green-600 font-bold' : 'text-red-500 font-bold' }}">
                            {{ $m->stock_quantity }}
                        </span>
                        @if($m->unit)<span class="text-gray-400 text-xs"> {{ $m->unit }}</span>@endif
                    </td>
                    <td class="px-6 py-3 text-center">
                        @if($m->pivot->delivered)
                            <span class="text-green-600 font-bold text-xs">✓ Sim</span>
                            @if($m->pivot->delivered_at)
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($m->pivot->delivered_at)->format('H:i') }}</p>
                            @endif
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-center">
                        @if($m->pivot->returned)
                            <span class="text-blue-600 font-bold text-xs">✓ Sim</span>
                            @if($m->pivot->returned_at)
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($m->pivot->returned_at)->format('H:i') }}</p>
                            @endif
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ── Ações ── --}}
    <div class="space-y-4">

        {{-- COORDENADOR / ADMIN: aprovar ou recusar --}}
        @if(in_array($reservation->status, ['pre_alocada']) && (auth()->user()->is_admin || auth()->user()->hasRole('Coordenador')))
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-5">
            <h3 class="font-bold text-blue-900 dark:text-blue-300 mb-1">Ação do Coordenador</h3>
            <p class="text-sm text-blue-700 dark:text-blue-400 mb-4">Analise a solicitação e aprove ou recuse. Ao aprovar, o auxiliar será notificado para preparar o laboratório.</p>
            <div class="flex gap-3">
                <form action="{{ route('lab.reservations.approve', $reservation) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="inline-flex items-center gap-2 bg-etec-dark text-white px-5 py-2.5 rounded-lg hover:bg-etec-main transition text-sm font-semibold">
                        ✓ Aprovar e encaminhar ao Auxiliar
                    </button>
                </form>
                <form action="{{ route('lab.reservations.reject', $reservation) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="inline-flex items-center gap-2 bg-red-600 text-white px-5 py-2.5 rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                        ✗ Recusar
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- AUXILIAR: entregar materiais + Professor assina --}}
        @if($reservation->status === 'aprovada' && (auth()->user()->is_admin || auth()->user()->hasRole('Auxiliar') || $reservation->user_id === auth()->id()))
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-800 p-5">
            <h3 class="font-bold text-yellow-900 dark:text-yellow-300 mb-1 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                Entrega de Materiais e Assinatura do Checklist
            </h3>
            <p class="text-sm text-yellow-700 dark:text-yellow-400 mb-4">
                O auxiliar entrega os materiais ao professor e ambos confirmam que o laboratório está em boas condições de uso. O professor assina o checklist ao clicar em confirmar.
            </p>
            <form action="{{ route('lab.reservations.start', $reservation) }}" method="POST">
                @csrf
                <button class="inline-flex items-center gap-2 bg-etec-accent text-etec-dark px-5 py-2.5 rounded-lg hover:bg-yellow-400 transition text-sm font-semibold">
                    ✓ Materiais entregues — Professor assina checklist e inicia aula
                </button>
            </form>
        </div>
        @endif

        {{-- PROFESSOR: observações e liberação --}}
        @php
            $professorCanRelease = $reservation->user_id === auth()->id()
                && !$reservation->professor_released_at
                && in_array($reservation->status, ['aprovada','em_execucao','aguardando_conferencia']);
        @endphp

        @if($professorCanRelease)
        <div class="bg-white dark:bg-gray-800 rounded-xl border-2 border-etec-light dark:border-etec-dark shadow-sm p-5">
            <h3 class="font-bold text-gray-900 dark:text-white mb-1 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-etec-main" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Registrar observações da atividade
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                Descreva como foi a aula, o uso dos materiais e qualquer intercorrência.
                Após enviar, o coordenador será notificado para validação.
            </p>
            <form action="{{ route('lab.reservations.professor-obs', $reservation) }}" method="POST" class="space-y-3">
                @csrf
                <textarea name="obs" rows="4" required
                          placeholder="Como foi a aula? Os materiais foram suficientes? Houve alguma intercorrência?"
                          class="w-full border @error('obs') border-red-400 @else border-gray-200 dark:border-gray-600 @enderror rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white resize-none focus:ring-2 focus:ring-etec-dark outline-none">{{ old('obs') }}</textarea>
                @error('obs')
                <p class="text-red-500 text-xs">{{ $message }}</p>
                @enderror
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-etec-dark text-white rounded-lg text-sm font-semibold hover:bg-etec-main transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Enviar observações e liberar
                </button>
            </form>
        </div>

        @elseif($reservation->professor_released_at && $reservation->user_id === auth()->id())
        <div class="flex items-center gap-2 text-sm text-green-700 bg-green-50 rounded-lg px-4 py-3 border border-green-200">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
            <span>Suas observações foram registradas em <strong>{{ $reservation->professor_released_at->format('d/m/Y H:i') }}</strong>.</span>
        </div>
        @endif

        {{-- AUXILIAR: conferência e liberação --}}
        @if(in_array($reservation->status, ['em_execucao','aguardando_conferencia','aguardando_validacao']) && (auth()->user()->is_admin || auth()->user()->hasRole('Auxiliar')) && !$reservation->auxiliar_released_at)
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl border-2 border-purple-200 dark:border-purple-800 p-5">
            <h3 class="font-bold text-purple-900 dark:text-purple-300 mb-1 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Conferência do Auxiliar
            </h3>
            <p class="text-sm text-purple-700 dark:text-purple-400 mb-4">
                Verifique os materiais devolvidos e registre suas observações sobre o estado do espaço e dos recursos utilizados.
            </p>
            <form action="{{ route('lab.reservations.auxiliar-finalize', $reservation) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-purple-800 dark:text-purple-300 mb-1.5">
                        Observações da conferência *
                    </label>
                    <textarea name="auxiliar_obs" rows="4" required
                              placeholder="Descreva o estado dos materiais, espaço, eventuais danos ou ocorrências durante a devolução..."
                              class="w-full border border-purple-200 dark:border-purple-700 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white resize-none focus:ring-2 focus:ring-purple-500 outline-none">{{ old('auxiliar_obs') }}</textarea>
                    @error('auxiliar_obs')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                @if($reservation->materials->count())
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-purple-100 dark:border-purple-800 p-3">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Confirmação de devolução dos materiais</p>
                    <div class="space-y-1.5">
                        @foreach($reservation->materials as $m)
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" name="materiais_conferidos[]" value="{{ $m->id }}"
                                   class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                   {{ $m->pivot->returned ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                {{ $m->name }}
                                <span class="text-gray-400">({{ $m->pivot->quantity_requested }} {{ $m->unit }})</span>
                                @if($m->patrimony_number)
                                    <span class="font-mono text-xs text-gray-400">— Patrim. {{ $m->patrimony_number }}</span>
                                @endif
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <button type="submit"
                        class="inline-flex items-center gap-2 bg-etec-main text-white px-5 py-2.5 rounded-lg hover:bg-etec-dark transition text-sm font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Confirmar Conferência
                </button>
            </form>
        </div>
        @elseif($reservation->auxiliar_released_at && (auth()->user()->is_admin || auth()->user()->hasRole('Auxiliar')))
        <div class="flex items-center gap-2 text-sm text-purple-600 bg-purple-50 rounded-lg px-4 py-2.5 border border-purple-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
            Conferência registrada em {{ $reservation->auxiliar_released_at->format('d/m H:i') }}.
        </div>
        @endif

        {{-- COORDENADOR / ADMIN: validar e arquivar --}}
        @if($reservation->status === 'aguardando_validacao' && (auth()->user()->is_admin || auth()->user()->hasRole('Coordenador')))
        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl border-2 border-green-200 dark:border-green-800 p-5">
            <h3 class="font-bold text-green-900 dark:text-green-300 mb-1 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Validação do Coordenador
            </h3>
            <p class="text-sm text-green-700 dark:text-green-400 mb-4">
                Analise as observações, adicione suas considerações e valide a atividade. Uma notificação com o documento será enviada ao professor e auxiliar.
            </p>

            {{-- Observações do professor e auxiliar --}}
            @if($reservation->obs || $reservation->auxiliar_obs)
            <div class="grid sm:grid-cols-2 gap-3 mb-4">
                @if($reservation->obs)
                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-green-100 dark:border-green-900">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-1">Obs. do Professor</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $reservation->obs }}</p>
                </div>
                @endif
                @if($reservation->auxiliar_obs)
                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-green-100 dark:border-green-900">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-1">Obs. do Auxiliar</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $reservation->auxiliar_obs }}</p>
                </div>
                @endif
            </div>
            @endif

            <form action="{{ route('lab.reservations.validate', $reservation) }}" method="POST" class="space-y-3">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-sm font-semibold text-green-800 dark:text-green-300 mb-1.5">
                        Considerações do Coordenador
                        <span class="font-normal text-green-600">(opcional — será enviado ao professor e auxiliar)</span>
                    </label>
                    <textarea name="coordenador_obs" rows="3"
                              placeholder="Observações gerais sobre a atividade, recomendações ou elogios..."
                              class="w-full border border-green-200 dark:border-green-700 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white resize-none focus:ring-2 focus:ring-green-500 outline-none">{{ old('coordenador_obs') }}</textarea>
                </div>
                <div class="flex items-center gap-3">
                    <button class="inline-flex items-center gap-2 bg-etec-dark text-white px-5 py-2.5 rounded-lg hover:bg-etec-main transition text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Validar, arquivar e notificar
                    </button>
                    <span class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8"/></svg>
                        E-mail enviado automaticamente ao professor e auxiliar
                    </span>
                </div>
            </form>
        </div>
        @endif

        {{-- Validada: exibir info de arquivamento --}}
        @if($reservation->status === 'validada')
        <div class="flex items-center gap-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl px-5 py-4">
            <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <div>
                <p class="font-semibold text-green-800 dark:text-green-300 text-sm">Atividade validada e arquivada</p>
                @if($reservation->coordenador)
                <p class="text-xs text-green-600 mt-0.5">Validada por {{ $reservation->coordenador->name }} em {{ $reservation->validated_at?->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        </div>
        @endif

        {{-- Admin: upload documento final --}}
        @if(auth()->user()->is_admin && in_array($reservation->status, ['conferida', 'concluida']))
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 dark:text-white mb-3 text-sm">Documento Assinado (finalização)</h3>
            @if($reservation->scanned_doc)
                <p class="text-sm text-green-600 mb-3">✓ Documento já enviado.</p>
            @endif
            <form action="{{ route('lab.reservations.upload-doc', $reservation) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                @csrf
                <input type="file" name="scanned_doc" accept=".pdf,.jpg,.png" required
                       class="border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-etec-light file:text-etec-dark">
                <button class="px-4 py-2 bg-etec-dark text-white rounded-lg text-sm font-semibold hover:bg-etec-main transition flex-shrink-0">
                    Enviar e Concluir
                </button>
            </form>
        </div>
        @endif

    </div>

</div>
@endsection
