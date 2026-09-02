@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 py-4 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-6xl mx-auto space-y-4">

        <!-- Top Navigation -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('legal-leaves.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white/90 px-3.5 py-2 text-xs font-bold text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Voltar à Lista de Folgas
            </a>

            <div class="flex items-center gap-2">
                @if($canManage)
                    <a
                        href="{{ route('legal-leaves.edit', $legalLeave->id) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-purple-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm hover:bg-purple-500 transition"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        <span>Editar Saldo (Diretoria)</span>
                    </a>
                @endif

                <a
                    href="{{ route('legal-leaves.download', $legalLeave->id) }}"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-gray-900 px-3.5 py-2 text-xs font-bold text-white shadow-sm hover:bg-gray-800 transition"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    <span>Baixar Atestado Original</span>
                </a>
            </div>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/95 p-4 text-xs font-bold text-emerald-800 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-red-200 bg-red-50/95 p-4 text-xs font-bold text-red-800 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-700 font-bold">&times;</button>
            </div>
        @endif

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

            <!-- Left Column: Details, Extrato & Solicitação de Folga -->
            <div class="lg:col-span-5 space-y-4">
                <div class="rounded-2xl border border-gray-300 bg-white p-5 sm:p-6 shadow-sm space-y-4">
                    <!-- Status Header -->
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <span class="text-xs font-bold text-gray-400 uppercase">Crédito de Folga #{{ $legalLeave->id }}</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $legalLeave->status_badge_color }}">
                            {{ ucfirst($legalLeave->status) }}
                        </span>
                    </div>

                    <!-- Colaborador Card -->
                    <div class="rounded-xl bg-purple-50 p-3.5 border border-purple-100">
                        <span class="text-[10px] font-bold text-purple-600 uppercase block mb-1">Colaborador Beneficiário</span>
                        <div class="flex items-center gap-2.5">
                            <div class="h-9 w-9 rounded-full bg-purple-200 text-purple-800 flex items-center justify-center font-semibold text-sm uppercase">
                                {{ substr($legalLeave->user->name ?? 'C', 0, 2) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-xs text-gray-900">{{ $legalLeave->user->name ?? 'Colaborador' }}</h3>
                                <p class="text-[10.5px] text-gray-500">
                                    Matrícula: {{ $legalLeave->user->registration_number ?? 'N/A' }}
                                    @if($legalLeave->user->department)
                                        • {{ $legalLeave->user->department->name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Direito de Folga -->
                    <div class="space-y-2.5 text-xs">
                        <div class="rounded-xl bg-gray-50 p-3 border border-gray-100 space-y-1">
                            <span class="text-[10px] font-bold text-gray-400 uppercase block">Tipo & Fundamento Legal</span>
                            <span class="font-bold text-gray-900 block text-xs">{{ $legalLeave->type_label }}</span>
                            <span class="text-[11px] text-purple-700 font-medium block">{{ $legalLeave->legal_basis }}</span>
                        </div>

                        <!-- 3 KPIs do Saldo -->
                        <div class="grid grid-cols-3 gap-2">
                            <div class="rounded-xl bg-gray-50 p-2.5 border border-gray-100 text-center">
                                <span class="text-[9.5px] font-bold text-gray-400 uppercase block">Concedidos</span>
                                <span class="font-bold text-gray-900 mt-0.5 block text-sm">{{ $legalLeave->days_granted }} d</span>
                            </div>

                            <div class="rounded-xl bg-blue-50 p-2.5 border border-blue-100 text-center">
                                <span class="text-[9.5px] font-bold text-blue-500 uppercase block">Usufruídos</span>
                                <span class="font-semibold text-blue-700 mt-0.5 block text-sm">{{ $legalLeave->days_used }} d</span>
                            </div>

                            <div class="rounded-xl bg-emerald-50 p-2.5 border border-emerald-100 text-center">
                                <span class="text-[9.5px] font-bold text-emerald-600 uppercase block">Saldo Restante</span>
                                <span class="font-semibold text-emerald-800 mt-0.5 block text-sm">{{ $legalLeave->days_remaining }} d</span>
                            </div>
                        </div>

                        <div class="rounded-xl bg-gray-50 p-3 border border-gray-100 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Data do Evento / Serviço:</span>
                                <span class="font-bold text-gray-800">{{ $legalLeave->event_date?->format('d/m/Y') ?? 'Não informado' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase">Nº Declaração / Protocolo:</span>
                                <span class="font-bold text-gray-800">{{ $legalLeave->document_number ?? 'Não declarado' }}</span>
                            </div>
                            @if($legalLeave->expiration_date)
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Data Limite de Gozo:</span>
                                    <span class="font-bold text-amber-700">{{ $legalLeave->expiration_date->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="rounded-xl bg-gray-50 p-3 border border-gray-100">
                            <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Descrição / Motivo</span>
                            <p class="text-gray-700 leading-relaxed">{{ $legalLeave->description }}</p>
                        </div>

                        @if($legalLeave->creator)
                            <div class="rounded-xl bg-gray-50 p-2.5 border border-gray-100 text-[10.5px] text-gray-500">
                                Cadastrado por <strong>{{ $legalLeave->creator->name }}</strong> em {{ $legalLeave->created_at->format('d/m/Y H:i') }}.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Formulário: Solicitar Usufruto do Dia de Folga (Com Regra de 72h) -->
                @if($legalLeave->days_remaining > 0)
                    <div class="rounded-2xl border border-purple-200 bg-white p-5 shadow-sm space-y-3">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-2">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-100 text-purple-700 font-bold text-xs">
                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <div>
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-purple-900">
                                    Solicitar Dia de Folga
                                </h3>
                                <p class="text-[10px] text-gray-500">
                                    Saldo disponível para gozo: <strong>{{ $legalLeave->days_remaining }} dia(s)</strong>
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('legal-leaves.request-usage', $legalLeave->id) }}" method="POST" class="space-y-3 pt-1">
                            @csrf

                            <div>
                                <label for="requested_date" class="block text-[10.5px] font-bold text-gray-700 uppercase mb-1">
                                    Data Pretendida da Folga <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    name="requested_date"
                                    id="requested_date"
                                    required
                                    min="{{ now()->format('Y-m-d') }}"
                                    onchange="checkAntecedencia(this.value)"
                                    class="w-full rounded-xl border-gray-300 text-xs shadow-2xs focus:border-purple-500"
                                />
                                <div id="notice-72h" class="text-[11px] font-bold mt-1 hidden"></div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="requested_days" class="block text-[10.5px] font-bold text-gray-700 uppercase mb-1">
                                        Quantidade de Dias
                                    </label>
                                    <input
                                        type="number"
                                        name="requested_days"
                                        id="requested_days"
                                        required
                                        min="1"
                                        max="{{ $legalLeave->days_remaining }}"
                                        value="1"
                                        class="w-full rounded-xl border-gray-300 text-xs font-bold text-purple-700 focus:border-purple-500"
                                    />
                                </div>

                                <div>
                                    <label class="block text-[10.5px] font-bold text-gray-500 uppercase mb-1">
                                        Prazo Regulamentar
                                    </label>
                                    <div class="rounded-xl bg-purple-50 px-3 py-2 text-[10.5px] font-bold text-purple-800 border border-purple-100">
                                        Mínimo 72h prévias
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="reason" class="block text-[10.5px] font-bold text-gray-700 uppercase mb-1">
                                    Justificativa / Observação à Coordenação
                                </label>
                                <input
                                    type="text"
                                    name="reason"
                                    id="reason"
                                    placeholder="Ex: Alinhado com professor substituto..."
                                    class="w-full rounded-xl border-gray-300 text-xs shadow-2xs focus:border-purple-500"
                                />
                            </div>

                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-purple-600 py-2.5 px-4 text-xs font-bold text-white shadow-2xs hover:bg-purple-500 transition"
                            >
                                <span><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Enviar Solicitação para Ciência da Coordenação</span>
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Extrato Cronológico de Dias Usufruídos / Tirados -->
                <div class="rounded-2xl border border-gray-300 bg-white p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-800 flex items-center gap-1.5 border-b border-gray-100 pb-2">
                        <span><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Extrato de Dias Usufruídos & Tirados</span>
                    </h3>

                    <div class="space-y-2 text-xs">
                        @forelse($legalLeave->requests as $req)
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-3 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-purple-900 text-xs">
                                        Data do Gozo: {{ $req->requested_date->format('d/m/Y') }} ({{ $req->requested_days }} d)
                                    </span>
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[9.5px] font-bold uppercase {{ $req->status_badge_color }}">
                                        {{ $req->status_label }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-[10.5px] text-gray-500">
                                    <span>
                                        {{ $req->is_within_72h_deadline ? '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Solicitado com ≥72h de antecedência' : '<svg class="w-4 h-4 inline-block text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> Solicitado com <72h (Urgência)' }}
                                    </span>
                                    @if($req->reviewed_by && $req->reviewer)
                                        <span>Ciência por {{ $req->reviewer->name }}</span>
                                    @endif
                                </div>

                                @if($req->reason)
                                    <p class="text-[11px] text-gray-700 italic">"{{ $req->reason }}"</p>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-400 text-xs">
                                Nenhum dia usufruído deste crédito até o momento.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Document Viewer & Audit Trail -->
            <div class="lg:col-span-7 space-y-4">
                <!-- Document Viewer -->
                <div class="rounded-2xl border border-gray-300 bg-white p-5 sm:p-6 shadow-sm space-y-3 flex flex-col justify-between min-h-[500px]">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">Atestado / Declaração Anexa</span>
                            <span class="rounded-lg bg-gray-100 px-2 py-0.5 text-[10px] font-bold text-gray-600 uppercase">
                                {{ strtoupper(pathinfo($legalLeave->attachment_path, PATHINFO_EXTENSION)) }}
                            </span>
                        </div>
                        <a
                            href="{{ asset('storage/' . $legalLeave->attachment_path) }}"
                            target="_blank"
                            class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline inline-flex items-center gap-1"
                        >
                            <span>Abrir em tela cheia</span>
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        </a>
                    </div>

                    <!-- Viewer -->
                    <div class="flex-1 flex items-center justify-center bg-gray-50 rounded-xl overflow-hidden border border-gray-200 p-2 min-h-[420px]">
                        @if($legalLeave->isPdf())
                            <iframe
                                src="{{ asset('storage/' . $legalLeave->attachment_path) }}"
                                class="w-full h-[500px] rounded-lg border-0"
                            ></iframe>
                        @elseif($legalLeave->isImage())
                            <img
                                src="{{ asset('storage/' . $legalLeave->attachment_path) }}"
                                alt="Comprovante de Folga Legal"
                                class="max-h-[500px] w-auto max-w-full object-contain rounded-lg shadow-xs"
                            />
                        @else
                            <div class="text-center py-12 text-gray-400">
                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> <p class="text-xs font-bold text-gray-600 mt-2">Formato não suportado para visualização direta.</p>
                                <a href="{{ route('legal-leaves.download', $legalLeave->id) }}" class="mt-3 inline-block rounded-xl bg-purple-600 px-4 py-2 text-xs font-bold text-white shadow-2xs">
                                    Baixar Arquivo
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- AUDIT TRAIL SECTION (RESTRICTED ONLY TO DIRECTORS & SERVICE DIRECTOR) -->
                @if($canViewAudit)
                    <div class="rounded-2xl border border-gray-300 bg-white p-5 sm:p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-purple-100 text-purple-700 font-bold text-xs">
                                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>️
                                </span>
                                <div>
                                    <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-900">
                                        Trilha de Auditoria & Histórico de Modificações
                                    </h3>
                                    <p class="text-[10px] text-gray-500">
                                        Acesso restrito: Visível exclusivamente pela Diretora de Serviços e Diretor da Unidade
                                    </p>
                                </div>
                            </div>
                            <span class="rounded-lg bg-purple-100 px-2.5 py-0.5 text-[10px] font-bold text-purple-800 uppercase">
                                Confidencial
                            </span>
                        </div>

                        <!-- Audit Events Timeline -->
                        <div class="space-y-3">
                            @forelse($legalLeave->audits as $audit)
                                <div class="rounded-xl border border-gray-200 bg-gray-50/70 p-3.5 space-y-2 text-xs">
                                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-200/60 pb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-extrabold uppercase {{ $audit->action_badge_color }}">
                                                {{ $audit->action_label }}
                                            </span>
                                            <span class="font-bold text-gray-800 text-[11px]">
                                                {{ $audit->user->name ?? 'Sistema' }}
                                            </span>
                                        </div>
                                        <div class="text-[10px] text-gray-500 font-medium">
                                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> {{ $audit->created_at->format('d/m/Y H:i:s') }}
                                            @if($audit->ip_address)
                                                • IP: {{ $audit->ip_address }}
                                            @endif
                                        </div>
                                    </div>

                                    <p class="text-gray-700 text-[11.5px] font-medium leading-relaxed">
                                        {{ $audit->description }}
                                    </p>

                                    @if(!empty($audit->changes))
                                        <div class="rounded-lg bg-white p-2.5 border border-gray-200/80 mt-2 space-y-1.5">
                                            <span class="text-[9.5px] font-extrabold uppercase tracking-wider text-gray-400 block">Detalhes dos Registros:</span>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px]">
                                                @foreach($audit->changes as $field => $val)
                                                    <div class="bg-gray-50 p-2 rounded-md border border-gray-100">
                                                        <span class="font-bold text-gray-700 block text-[10.5px] capitalize">{{ str_replace('_', ' ', $field) }}:</span>
                                                        @if(is_array($val) && (isset($val['anterior']) || isset($val['novo'])))
                                                            <div class="text-[10.5px] text-gray-600 mt-0.5 space-y-0.5">
                                                                <div class="text-red-600 line-through">De: {{ $val['anterior'] ?? 'Vazio' }}</div>
                                                                <div class="text-emerald-700 font-bold">Para: {{ $val['novo'] ?? 'Vazio' }}</div>
                                                            </div>
                                                        @else
                                                            <span class="text-gray-600 text-[10.5px]">{{ is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val }}</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-400 text-xs">
                                    Nenhum registro de auditoria encontrado.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>

<script>
function checkAntecedencia(dateVal) {
    if (!dateVal) return;
    const notice = document.getElementById('notice-72h');
    const selectedDate = new Date(dateVal + "T00:00:00");
    const now = new Date();
    const diffHours = (selectedDate - now) / (1000 * 60 * 60);

    notice.classList.remove('hidden', 'text-emerald-700', 'text-amber-700', 'text-red-700');

    if (diffHours >= 72) {
        notice.classList.add('text-emerald-700');
        notice.innerText = "<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Prazo regulamentar atendido (" + Math.round(diffHours) + "h de antecedência).";
    } else if (diffHours > 0) {
        notice.classList.add('text-amber-700');
        notice.innerText = "<svg class="w-4 h-4 inline-block text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> Atenção: Solicitado com " + Math.round(diffHours) + "h de antecedência (< 72h regulamentares). Envio classificado como urgência.";
    } else {
        notice.classList.add('text-red-700');
        notice.innerText = "<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> A data selecionada já passou.";
    }
}
</script>
@endsection
