@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-5 sm:space-y-6">

        <!-- Mobile Floating Action Button (Diretoria de Serviços) -->
        @if($canManage)
            <a href="{{ route('legal-leaves.create') }}" class="lg:hidden fixed bottom-6 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-xl transition active:scale-95 hover:bg-blue-500 focus:outline-none" title="Nova Folga Legal">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
            </a>
        @endif

        <!-- Session Alert Messages -->
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/90 backdrop-blur-md p-4 text-xs font-bold text-emerald-800 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-semibold text-sm">&times;</button>
            </div>
        @endif

        @if(session('warning'))
            <div class="rounded-2xl border border-amber-200 bg-amber-50/90 backdrop-blur-md p-4 text-xs font-bold text-amber-800 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <span>{{ session('warning') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-900 font-semibold text-sm">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50/90 backdrop-blur-md p-4 text-xs font-bold text-red-800 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-900 font-semibold text-sm">&times;</button>
            </div>
        @endif

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>Folgas Previstas em Lei</span>
                    <span class="rounded-xl bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700 normal-case tracking-normal">Folgas Legais</span>
                </h1>
                <p class="text-[11px] text-gray-600 font-medium mt-0.5">
                    Banco de dias de folgas legais (TRE, Júri, Doação de Sangue e CLT) com solicitação antecipada de 72h
                </p>
            </div>

            <div class="hidden sm:flex flex-wrap items-center gap-2.5">
                @if($canManage)
                    <a href="{{ route('legal-leaves.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <span>Cadastrar Folga Legal (Diretoria)</span>
                    </a>
                @endif
                <a href="{{ route('medical-certificates.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-red-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-red-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span>Atestados Médicos</span>
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-800 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Section 1: KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-3">
            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs border-t-4 border-t-purple-600">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Concedido</span>
                    <span class="text-base">🌟</span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_granted'] }} <span class="text-xs font-semibold text-gray-500">dias</span></div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs border-t-4 border-t-blue-500">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Dias Já Usufruídos</span>
                    <span class="text-base">🏖️</span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-blue-600 mt-1">{{ $stats['total_used'] }} <span class="text-xs font-semibold text-gray-500">dias</span></div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs border-t-4 border-t-emerald-600">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Saldo Restante</span>
                    <span class="text-base">⚖️</span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-emerald-700 mt-1">{{ $stats['total_remaining'] }} <span class="text-xs font-semibold text-gray-500">dias</span></div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs border-t-4 border-t-amber-500">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Aguardando Ciência</span>
                    <span class="text-base">⏳</span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-amber-600 mt-1">{{ $stats['pending_requests'] }} <span class="text-xs font-semibold text-gray-500">pedidos</span></div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs border-t-4 border-t-teal-600">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Aprovadas / Tomadas</span>
                    <span class="text-base">✅</span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-teal-700 mt-1">{{ $stats['approved_requests'] }} <span class="text-xs font-semibold text-gray-500">gozos</span></div>
            </div>
        </div>

        <!-- Section 2: Guia das Regras Legais de Folga -->
        <div class="rounded-2xl border border-purple-200 bg-gradient-to-r from-purple-50 via-white to-indigo-50 p-4 sm:p-5 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-purple-600 text-white font-semibold text-sm">
                        ⚖️
                    </span>
                    <div>
                        <h2 class="text-xs font-semibold uppercase tracking-wide text-purple-900">
                            Regras e Diretrizes Legais de Concessão & Usufruto
                        </h2>
                        <p class="text-[10.5px] text-purple-700">
                            Resumo das garantias da legislação trabalhista, eleitoral e estatutária
                        </p>
                    </div>
                </div>
                <span class="rounded-full bg-purple-200 px-2.5 py-0.5 text-[10px] font-semibold text-purple-900 uppercase">
                    Normas Vigentes
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 pt-1 text-xs">
                <!-- Eleição -->
                <div class="rounded-xl bg-white p-3 border border-purple-100 shadow-2xs space-y-1">
                    <span class="font-extrabold text-purple-900 block flex items-center gap-1">
                        🗳️ <span>Eleição / TRE (Em Dobro)</span>
                    </span>
                    <p class="text-[10.5px] text-gray-600 leading-relaxed">
                        <strong>Art. 98 da Lei 9.504/97:</strong> Cada 1 dia convocado para serviço ou treinamento do TRE concede <strong>2 dias de folga</strong>.
                    </p>
                </div>

                <!-- Júri Popular -->
                <div class="rounded-xl bg-white p-3 border border-purple-100 shadow-2xs space-y-1">
                    <span class="font-extrabold text-indigo-900 block flex items-center gap-1">
                        🏛️ <span>Tribunal do Júri</span>
                    </span>
                    <p class="text-[10.5px] text-gray-600 leading-relaxed">
                        <strong>Art. 430 do CPP:</strong> Dias de comparecimento às sessões do júri garantem folga integral e preservação salarial conforme certidão.
                    </p>
                </div>

                <!-- Doação de Sangue -->
                <div class="rounded-xl bg-white p-3 border border-purple-100 shadow-2xs space-y-1">
                    <span class="font-extrabold text-red-900 block flex items-center gap-1">
                        🩸 <span>Doação de Sangue</span>
                    </span>
                    <p class="text-[10.5px] text-gray-600 leading-relaxed">
                        <strong>Art. 473, IV da CLT:</strong> <strong>1 dia de folga</strong> a cada 12 meses trabalhados, comprovado por atestado do hemocentro.
                    </p>
                </div>

                <!-- Regra 72 Horas -->
                <div class="rounded-xl bg-white p-3 border border-amber-200 shadow-2xs space-y-1 bg-amber-50/40">
                    <span class="font-extrabold text-amber-900 block flex items-center gap-1">
                        ⏱️ <span>Antecedência Mínima (72h)</span>
                    </span>
                    <p class="text-[10.5px] text-gray-700 leading-relaxed">
                        A solicitação do dia de folga deve ser feita com <strong>no mínimo 72 horas de antecedência</strong> para ciência do coordenador de curso / chefia.
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 3: Solicitações de Usufruto de Folga (Tabela Ativa / Ciência da Coordenação) -->
        <div class="rounded-2xl border border-gray-300 bg-white p-4 sm:p-5 shadow-sm space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 text-amber-700 font-bold text-xs">
                        📅
                    </span>
                    <div>
                        <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-900">
                            Solicitações de Gozo & Usufruto de Folgas
                        </h2>
                        <p class="text-[10.5px] text-gray-500">
                            Datas pretendidas pelos colaboradores com contagem de antecedência (72h) e parecer da coordenação
                        </p>
                    </div>
                </div>
                <span class="rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700">
                    {{ $leaveRequests->total() }} solicitações
                </span>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-2xs">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50/90 text-gray-600 font-bold text-[11px] uppercase tracking-wider">
                            <th class="py-3 px-3">Colaborador</th>
                            <th class="py-3 px-3">Origem da Folga Legal</th>
                            <th class="py-3 px-3">Data Solicitada</th>
                            <th class="py-3 px-3 text-center">Dias</th>
                            <th class="py-3 px-3">Antecedência (72h)</th>
                            <th class="py-3 px-3">Status / Ciência</th>
                            <th class="py-3 px-3 text-right">Parecer</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($leaveRequests as $req)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="font-extrabold text-gray-900 text-xs">{{ $req->user->name ?? 'Colaborador' }}</div>
                                    <div class="text-[10px] text-gray-400">
                                        {{ $req->user->department->name ?? $req->user->role }} • Matr: {{ $req->user->registration_number ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-bold text-gray-800 text-[11px] block">{{ $req->legalLeave->type_label }}</span>
                                    <span class="text-[10px] text-gray-500 line-clamp-1">{{ $req->legalLeave->description }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-semibold text-purple-700 text-xs block">
                                        {{ $req->requested_date->format('d/m/Y') }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">
                                        Solicitado em {{ $req->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="inline-flex rounded-md bg-purple-50 border border-purple-200 px-2 py-0.5 text-xs font-semibold text-purple-800">
                                        {{ $req->requested_days }} {{ $req->requested_days == 1 ? 'dia' : 'dias' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    @if($req->is_within_72h_deadline)
                                        <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-800 border border-emerald-200">
                                            ✅ Dentro do prazo (≥72h)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-800 border border-amber-200" title="Solicitado com menos de 72h de antecedência">
                                            ⚠️ Urgência (&lt;72h)
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-semibold uppercase {{ $req->status_badge_color }}">
                                        {{ $req->status_label }}
                                    </span>
                                    @if($req->reviewed_by && $req->reviewer)
                                        <div class="text-[9.5px] text-gray-400 mt-0.5">
                                            Por {{ $req->reviewer->name }} em {{ $req->reviewed_at?->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    @if($isCoordinator && $req->status === 'pendente')
                                        <div class="inline-flex items-center gap-1.5">
                                            <!-- Homologar / Deferir -->
                                            <form action="{{ route('legal-leaves.review-request', $req->id) }}" method="POST" onsubmit="return confirm('Tomar ciência e aprovar a folga para o dia {{ $req->requested_date->format('d/m/Y') }}?');" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="aprovado">
                                                <button type="submit" class="rounded-xl bg-emerald-600 px-2.5 py-1 text-[11px] font-bold text-white hover:bg-emerald-500 shadow-2xs transition">
                                                    Tomar Ciência & Deferir
                                                </button>
                                            </form>

                                            <!-- Rejeitar -->
                                            <form action="{{ route('legal-leaves.review-request', $req->id) }}" method="POST" onsubmit="return confirm('Recusar esta solicitação de folga?');" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejeitado">
                                                <button type="submit" class="rounded-xl bg-red-100 text-red-700 px-2 py-1 text-[11px] font-bold hover:bg-red-200 transition">
                                                    Recusar
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <a href="{{ route('legal-leaves.show', $req->legal_leave_id) }}" class="text-[11px] font-bold text-blue-600 hover:underline">
                                            Ver Folga Mãe
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-400">
                                    Nenhuma solicitação de usufruto de folga registrada até o momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leaveRequests->hasPages())
                <div class="pt-2">
                    {{ $leaveRequests->links() }}
                </div>
            @endif
        </div>

        <!-- Section 4: Banco de Saldos de Folgas Concedidas -->
        <div class="rounded-2xl border border-gray-300 bg-white p-4 sm:p-5 shadow-sm space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-100 text-purple-700 font-bold text-xs">
                        ⚖️
                    </span>
                    <div>
                        <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-900">
                            Banco de Saldos & Atestados de Folga Concedidos
                        </h2>
                        <p class="text-[10.5px] text-gray-500">
                            Créditos de folga cadastrados pela Diretoria de Serviços com comprovante anexado
                        </p>
                    </div>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('legal-leaves.index') }}" class="flex flex-wrap items-center gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar colaborador, evento..."
                        class="rounded-xl border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-800 shadow-2xs focus:border-blue-500"
                    />

                    @if($canManage)
                        <select name="user_id" class="rounded-xl border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-700 shadow-2xs">
                            <option value="">Todos os Colaboradores</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <select name="type" class="rounded-xl border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-700 shadow-2xs">
                        <option value="">Todos os Tipos</option>
                        <option value="eleicao" {{ request('type') == 'eleicao' ? 'selected' : '' }}>Serviço Eleitoral (TRE)</option>
                        <option value="juri_popular" {{ request('type') == 'juri_popular' ? 'selected' : '' }}>Tribunal do Júri</option>
                        <option value="doacao_sangue" {{ request('type') == 'doacao_sangue' ? 'selected' : '' }}>Doação de Sangue</option>
                        <option value="alistamento" {{ request('type') == 'alistamento' ? 'selected' : '' }}>Alistamento Eleitoral</option>
                        <option value="casamento" {{ request('type') == 'casamento' ? 'selected' : '' }}>Casamento</option>
                        <option value="luto" {{ request('type') == 'luto' ? 'selected' : '' }}>Luto</option>
                        <option value="outro" {{ request('type') == 'outro' ? 'selected' : '' }}>Outro</option>
                    </select>

                    <button type="submit" class="rounded-xl bg-gray-800 px-3 py-1.5 text-xs font-bold text-white hover:bg-gray-700 transition">
                        Filtrar
                    </button>
                </form>
            </div>

            <!-- Table of Granted Leaves -->
            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-2xs">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50/90 text-gray-600 font-bold text-[11px] uppercase tracking-wider">
                            <th class="py-3 px-3">Colaborador</th>
                            <th class="py-3 px-3">Tipo & Legislação</th>
                            <th class="py-3 px-3">Descrição / Documento</th>
                            <th class="py-3 px-3 text-center">Concedidos</th>
                            <th class="py-3 px-3 text-center">Usufruídos</th>
                            <th class="py-3 px-3 text-center">Saldo Restante</th>
                            <th class="py-3 px-3">Comprovante</th>
                            <th class="py-3 px-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($leaves as $leave)
                            <tr class="hover:bg-purple-50/20 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="font-extrabold text-gray-900 text-xs">{{ $leave->user->name ?? 'Colaborador' }}</div>
                                    <div class="text-[10px] text-gray-400">
                                        {{ $leave->user->department->name ?? $leave->user->role }} • Matr: {{ $leave->user->registration_number ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-bold text-gray-800 text-[11px] block">{{ $leave->type_label }}</span>
                                    <span class="text-[9.5px] text-purple-700 font-medium">{{ $leave->legal_basis }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-bold text-gray-800 block text-xs">{{ $leave->description }}</span>
                                    @if($leave->document_number)
                                        <span class="text-[10px] text-gray-400">Doc/Certidão: {{ $leave->document_number }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="font-semibold text-gray-800 text-xs">
                                        {{ $leave->days_granted }} {{ $leave->days_granted == 1 ? 'dia' : 'dias' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="font-semibold text-blue-600 text-xs">
                                        {{ $leave->days_used }} {{ $leave->days_used == 1 ? 'dia' : 'dias' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-semibold
                                        {{ $leave->days_remaining > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-500' }}
                                    ">
                                        {{ $leave->days_remaining }} {{ $leave->days_remaining == 1 ? 'dia' : 'dias' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <a
                                        href="{{ route('legal-leaves.download', $leave->id) }}"
                                        class="inline-flex items-center gap-1 rounded-lg bg-gray-100 px-2.5 py-1 text-[10.5px] font-bold text-gray-700 hover:bg-gray-200 transition"
                                        title="Baixar Comprovante"
                                    >
                                        <span>📄 Anexo</span>
                                        <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    </a>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a
                                            href="{{ route('legal-leaves.show', $leave->id) }}"
                                            class="inline-flex items-center gap-1 rounded-xl bg-purple-50 border border-purple-200 px-2.5 py-1 text-xs font-bold text-purple-700 hover:bg-purple-100 transition shadow-2xs"
                                        >
                                            Ver Extrato & Solicitar
                                        </a>

                                        @if($canManage)
                                            <a
                                                href="{{ route('legal-leaves.edit', $leave->id) }}"
                                                class="rounded-xl border border-gray-200 p-1 text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition"
                                                title="Editar Folga"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-10 text-center text-gray-400">
                                    Nenhum registro de folga legal encontrado.
                                    @if($canManage)
                                        <div class="mt-2">
                                            <a href="{{ route('legal-leaves.create') }}" class="text-xs font-bold text-blue-600 hover:underline">
                                                + Cadastrar primeiro registro pela Diretoria
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leaves->hasPages())
                <div class="pt-2">
                    {{ $leaves->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
