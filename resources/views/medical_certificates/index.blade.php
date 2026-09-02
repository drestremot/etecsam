@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-5 sm:space-y-6">

        <!-- Floating Action Button (Mobile Only, Diretoria de Serviços) -->
        @if($canManage)
            <a href="{{ route('medical-certificates.create') }}" class="lg:hidden fixed bottom-6 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-xl transition active:scale-95 hover:bg-blue-500 focus:outline-none" title="Novo Atestado">
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
                    <span>Atestados Médicos</span>
                    <span class="rounded-xl bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 normal-case tracking-normal">Afastamentos & Saúde</span>
                </h1>
                <p class="text-[11px] text-gray-600 font-medium mt-0.5">
                    Controle integrado de atestados médicos, licenças e declarações com anexo digital
                </p>
            </div>

            <div class="hidden sm:flex flex-wrap items-center gap-2.5">
                @if($canManage)
                    <a href="{{ route('medical-certificates.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <span>Novo Atestado</span>
                    </a>
                @endif
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-800 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Section 1: KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-3">
            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs border-t-4 border-t-gray-800">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Total Registrado</span>
                    <span class="text-xs">📋</span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs border-t-4 border-t-[#f2994a]">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Pendentes Homologação</span>
                    <span class="text-xs">⏳</span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-amber-600 mt-1">{{ $stats['pendentes'] }}</div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs border-t-4 border-t-[#27ae60]">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Homologados</span>
                    <span class="text-xs">✅</span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-emerald-600 mt-1">{{ $stats['homologados'] }}</div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs border-t-4 border-t-[#eb5757]">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Rejeitados</span>
                    <span class="text-xs">❌</span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-red-600 mt-1">{{ $stats['rejeitados'] }}</div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs border-t-4 border-t-purple-600 col-span-2 sm:col-span-1">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Afastados Hoje</span>
                    <span class="text-xs">🏥</span>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-purple-700 mt-1">{{ $stats['afastados_hoje'] }}</div>
            </div>
        </div>

        <!-- Section 2: Filters & Search Bar -->
        <div class="rounded-2xl border border-gray-300 bg-white/70 p-3.5 shadow-sm backdrop-blur-md">
            <form method="GET" action="{{ route('medical-certificates.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2.5 items-center">
                <!-- Search Input -->
                <div class="lg:col-span-4 relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar por colaborador, médico, CRM, CID..."
                        class="block w-full rounded-xl border border-gray-300 bg-white pl-9 pr-3 py-2 text-xs text-gray-800 placeholder-gray-400 shadow-2xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                    />
                </div>

                <!-- Colaborador Filter (If Manager / Diretoria de Serviços) -->
                @if($canManage)
                    <div class="lg:col-span-3">
                        <select name="user_id" class="block w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 shadow-2xs focus:border-blue-500 focus:outline-none">
                            <option value="">Todos os Colaboradores</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $u->registration_number ?? 'Matrícula' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Type Filter -->
                <div class="{{ $canManage ? 'lg:col-span-2' : 'lg:col-span-4' }}">
                    <select name="type" class="block w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 shadow-2xs focus:border-blue-500 focus:outline-none">
                        <option value="">Todos os Tipos</option>
                        <option value="medico" {{ request('type') == 'medico' ? 'selected' : '' }}>Médico</option>
                        <option value="odontologico" {{ request('type') == 'odontologico' ? 'selected' : '' }}>Odontológico</option>
                        <option value="acompanhamento" {{ request('type') == 'acompanhamento' ? 'selected' : '' }}>Acompanhamento</option>
                        <option value="declaracao_horas" {{ request('type') == 'declaracao_horas' ? 'selected' : '' }}>Declaração de Horas</option>
                        <option value="outro" {{ request('type') == 'outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="{{ $canManage ? 'lg:col-span-2' : 'lg:col-span-3' }}">
                    <select name="status" class="block w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 shadow-2xs focus:border-blue-500 focus:outline-none">
                        <option value="">Todos os Status</option>
                        <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="homologado" {{ request('status') == 'homologado' ? 'selected' : '' }}>Homologado</option>
                        <option value="rejeitado" {{ request('status') == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="lg:col-span-1 flex items-center gap-1.5">
                    <button type="submit" class="w-full inline-flex justify-center items-center rounded-xl bg-gray-900 py-2 px-3 text-xs font-bold text-white shadow-sm hover:bg-gray-800 transition" title="Filtrar">
                        Filtrar
                    </button>
                    @if(request()->anyFilled(['search', 'user_id', 'type', 'status']))
                        <a href="{{ route('medical-certificates.index') }}" class="inline-flex justify-center items-center rounded-xl bg-gray-200 py-2 px-2.5 text-xs font-bold text-gray-600 hover:bg-gray-300 transition" title="Limpar Filtros">
                            ✖
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Section 3: List / Table Card -->
        <div class="rounded-2xl border border-gray-300 bg-white/90 p-4 sm:p-5 shadow-sm backdrop-blur-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-gray-200/80 text-[10.5px] font-extrabold uppercase tracking-wider text-gray-500 bg-gray-50/50">
                            <th class="py-3 px-3">Colaborador</th>
                            <th class="py-3 px-3">Tipo & Detalhes</th>
                            <th class="py-3 px-3">Período de Afastamento</th>
                            <th class="py-3 px-3">Documento Anexo</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @forelse($certificates as $cert)
                            <tr class="hover:bg-blue-50/40 transition">
                                <!-- Colaborador -->
                                <td class="py-3.5 px-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold text-xs uppercase flex-shrink-0">
                                            {{ substr($cert->user->name ?? 'C', 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 leading-tight">{{ $cert->user->name ?? 'Colaborador' }}</div>
                                            <div class="text-[10px] text-gray-500">
                                                Matrícula: {{ $cert->user->registration_number ?? 'N/A' }}
                                                @if($cert->user->department)
                                                    • {{ $cert->user->department->name }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Tipo & Detalhes -->
                                <td class="py-3.5 px-3">
                                    <span class="inline-flex items-center rounded-lg bg-gray-100 px-2 py-0.5 text-[10.5px] font-bold text-gray-700">
                                        {{ $cert->type_label }}
                                    </span>
                                    <div class="text-[10.5px] text-gray-600 mt-1 font-medium">
                                        @if($cert->doctor_name)
                                            <span>Dr(a). {{ $cert->doctor_name }}</span>
                                        @endif
                                        @if($cert->crm)
                                            <span class="text-gray-400">• CRM: {{ $cert->crm }}</span>
                                        @endif
                                        @if($cert->cid)
                                            <span class="text-gray-400">• CID: {{ $cert->cid }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Período -->
                                <td class="py-3.5 px-3">
                                    <div class="font-bold text-gray-800">
                                        {{ $cert->start_date->format('d/m/Y') }} a {{ $cert->end_date->format('d/m/Y') }}
                                    </div>
                                    <div class="text-[10px] font-bold text-blue-600 mt-0.5">
                                        ⏱️ {{ $cert->days }} {{ $cert->days == 1 ? 'dia' : 'dias' }} de licença
                                    </div>
                                </td>

                                <!-- Anexo -->
                                <td class="py-3.5 px-3">
                                    <a
                                        href="{{ route('medical-certificates.download', $cert->id) }}"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-2.5 py-1.5 text-[11px] font-bold text-gray-700 shadow-2xs hover:bg-gray-50 hover:border-blue-400 transition"
                                        title="Baixar Anexo"
                                    >
                                        @if($cert->isPdf())
                                            <span class="text-red-500 font-extrabold text-xs">PDF</span>
                                        @else
                                            <span class="text-blue-500 font-extrabold text-xs">IMG</span>
                                        @endif
                                        <span class="truncate max-w-[120px]">Ver Atestado</span>
                                        <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    </a>
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-3">
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide
                                        {{ $cert->status === 'pendente' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $cert->status === 'homologado' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ $cert->status === 'rejeitado' ? 'bg-red-100 text-red-800' : '' }}
                                    ">
                                        <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $cert->status_color }}"></span>
                                        {{ $cert->status_label }}
                                    </span>
                                    @if($cert->reviewed_by && $cert->reviewer)
                                        <div class="text-[9.5px] text-gray-400 mt-1">
                                            Por {{ $cert->reviewer->name }} em {{ $cert->reviewed_at?->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Ações -->
                                <td class="py-3.5 px-3 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a
                                            href="{{ route('medical-certificates.show', $cert->id) }}"
                                            class="inline-flex items-center gap-1 rounded-xl bg-blue-50 border border-blue-200 px-3 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-100 transition shadow-2xs"
                                        >
                                            Detalhes
                                        </a>

                                        @if($canManage)
                                            <a
                                                href="{{ route('medical-certificates.edit', $cert->id) }}"
                                                class="inline-flex items-center gap-1 rounded-xl bg-amber-50 border border-amber-200 px-2.5 py-1.5 text-xs font-bold text-amber-700 hover:bg-amber-100 transition shadow-2xs"
                                                title="Editar Atestado"
                                            >
                                                Editar
                                            </a>

                                            <form action="{{ route('medical-certificates.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('Deseja realmente remover este atestado?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-gray-200 p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Excluir">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-400">
                                    <svg class="h-12 w-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <p class="font-bold text-xs text-gray-500">Nenhum atestado encontrado.</p>
                                    @if($canManage)
                                        <a href="{{ route('medical-certificates.create') }}" class="inline-block mt-3 text-xs font-bold text-blue-600 hover:underline">
                                            + Cadastrar o primeiro atestado
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($certificates->hasPages())
                <div class="mt-4 pt-3 border-t border-gray-200/70">
                    {{ $certificates->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
