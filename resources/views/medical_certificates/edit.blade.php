@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 py-4 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-3xl mx-auto space-y-4">

        <!-- Top Navigation -->
        <div class="flex items-center justify-between">
            <a href="{{ route('medical-certificates.show', $medicalCertificate->id) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white/90 px-3.5 py-2 text-xs font-bold text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Voltar aos Detalhes
            </a>
            <span class="rounded-lg bg-amber-100 px-2.5 py-1 text-[11px] font-bold text-amber-800">
                <svg class="w-4 h-4 inline-block text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> Edição com Auditoria Ativa
            </span>
        </div>

        <!-- Form Card -->
        <div class="rounded-2xl border border-gray-300 bg-white p-6 sm:p-8 shadow-sm">
            <div class="border-b border-gray-100 pb-4 mb-6">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-blue-600 font-semibold text-xs">
                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>️
                    </span>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">
                            Editar Atestado Médico #{{ $medicalCertificate->id }}
                        </h1>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Diretoria de Serviços • Todas as alterações serão registradas no histórico de auditoria
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('medical-certificates.update', $medicalCertificate->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Colaborador Selection -->
                <div>
                    <label for="user_id" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Colaborador / Funcionário <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id', $medicalCertificate->user_id) == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->registration_number ?? 'Matrícula N/A' }}) - {{ $u->role }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
                </div>

                <!-- Tipo, Médico e CRM -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="type" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Tipo de Afastamento <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            <option value="medico" {{ old('type', $medicalCertificate->type) == 'medico' ? 'selected' : '' }}>Médico Geral</option>
                            <option value="odontologico" {{ old('type', $medicalCertificate->type) == 'odontologico' ? 'selected' : '' }}>Odontológico</option>
                            <option value="acompanhamento" {{ old('type', $medicalCertificate->type) == 'acompanhamento' ? 'selected' : '' }}>Acompanhamento Familiar</option>
                            <option value="declaracao_horas" {{ old('type', $medicalCertificate->type) == 'declaracao_horas' ? 'selected' : '' }}>Declaração de Horas / Consulta</option>
                            <option value="outro" {{ old('type', $medicalCertificate->type) == 'outro' ? 'selected' : '' }}>Outro</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-1" />
                    </div>

                    <div>
                        <label for="doctor_name" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Nome do Médico / Dentista
                        </label>
                        <input
                            type="text"
                            name="doctor_name"
                            id="doctor_name"
                            value="{{ old('doctor_name', $medicalCertificate->doctor_name) }}"
                            placeholder="Dr(a). Fulano de Tal"
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />
                        <x-input-error :messages="$errors->get('doctor_name')" class="mt-1" />
                    </div>

                    <div>
                        <label for="crm" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            CRM / CRO / Registro
                        </label>
                        <input
                            type="text"
                            name="crm"
                            id="crm"
                            value="{{ old('crm', $medicalCertificate->crm) }}"
                            placeholder="Ex: 123456-SP"
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />
                        <x-input-error :messages="$errors->get('crm')" class="mt-1" />
                    </div>
                </div>

                <!-- Datas & Dias Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="start_date" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Data de Início <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="start_date"
                            id="start_date"
                            value="{{ old('start_date', $medicalCertificate->start_date->format('Y-m-d')) }}"
                            required
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-1" />
                    </div>

                    <div>
                        <label for="end_date" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Data de Término <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            name="end_date"
                            id="end_date"
                            value="{{ old('end_date', $medicalCertificate->end_date->format('Y-m-d')) }}"
                            required
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-1" />
                    </div>

                    <div>
                        <label for="days" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Qtd. de Dias (Calculado)
                        </label>
                        <input
                            type="number"
                            name="days"
                            id="days"
                            value="{{ old('days', $medicalCertificate->days) }}"
                            min="1"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-2xs text-xs font-bold text-blue-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />
                        <x-input-error :messages="$errors->get('days')" class="mt-1" />
                    </div>
                </div>

                <!-- Status & CID Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="status" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Status da Homologação <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required class="w-full rounded-xl border-gray-300 shadow-2xs text-xs font-bold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" onchange="toggleRejectionReason(this.value)">
                            <option value="pendente" {{ old('status', $medicalCertificate->status) == 'pendente' ? 'selected' : '' }}><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Pendente</option>
                            <option value="homologado" {{ old('status', $medicalCertificate->status) == 'homologado' ? 'selected' : '' }}><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Homologado (Aprovado)</option>
                            <option value="rejeitado" {{ old('status', $medicalCertificate->status) == 'rejeitado' ? 'selected' : '' }}><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Rejeitado</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-1" />
                    </div>

                    <div class="sm:col-span-2">
                        <label for="cid" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Código CID (Opcional / Sigiloso)
                        </label>
                        <input
                            type="text"
                            name="cid"
                            id="cid"
                            value="{{ old('cid', $medicalCertificate->cid) }}"
                            placeholder="Ex: J06.9"
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />
                        <x-input-error :messages="$errors->get('cid')" class="mt-1" />
                    </div>
                </div>

                <!-- Rejection Reason (Conditional) -->
                <div id="rejection_reason_container" class="{{ old('status', $medicalCertificate->status) == 'rejeitado' ? '' : 'hidden' }}">
                    <label for="rejection_reason" class="block text-xs font-bold text-red-700 uppercase mb-1">
                        Motivo da Rejeição <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="rejection_reason"
                        id="rejection_reason"
                        value="{{ old('rejection_reason', $medicalCertificate->rejection_reason) }}"
                        placeholder="Ex: Documento ilegível, fora do prazo..."
                        class="w-full rounded-xl border-red-300 bg-red-50/50 text-xs shadow-2xs focus:border-red-500"
                    />
                </div>

                <!-- Observações -->
                <div>
                    <label for="description" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Observações / Detalhes do Afastamento
                    </label>
                    <input
                        type="text"
                        name="description"
                        id="description"
                        value="{{ old('description', $medicalCertificate->description) }}"
                        placeholder="Ex: Repouso médico pós-procedimento..."
                        class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                    />
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>

                <!-- Substituição de Anexo -->
                <div class="rounded-2xl border border-gray-200 bg-gray-50/80 p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-700 uppercase">Comprovante / Anexo Atual</span>
                        <a href="{{ route('medical-certificates.download', $medicalCertificate->id) }}" class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1">
                            <span>Baixar anexo atual</span>
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        </a>
                    </div>
                    <p class="text-[11px] text-gray-500">
                        Selecione um novo arquivo abaixo apenas se desejar <strong>substituir</strong> o documento atual.
                    </p>
                    <input
                        type="file"
                        name="attachment"
                        id="attachment"
                        accept=".pdf,.jpg,.jpeg,.png,.webp"
                        class="block w-full text-xs text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer"
                    />
                    <x-input-error :messages="$errors->get('attachment')" class="mt-1" />
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('medical-certificates.show', $medicalCertificate->id) }}" class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-6 py-2.5 text-xs font-semibold uppercase tracking-wide text-white shadow-md hover:bg-gray-800 transition"
                    >
                        <span>Salvar Alterações & Auditar</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    function calcularDias() {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;
        if (start && end) {
            const d1 = new Date(start);
            const d2 = new Date(end);
            const diffTime = d2 - d1;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            if (diffDays > 0) {
                document.getElementById('days').value = diffDays;
            }
        }
    }

    document.getElementById('start_date').addEventListener('change', function() {
        const start = this.value;
        const endInput = document.getElementById('end_date');
        if (start && (!endInput.value || endInput.value < start)) {
            endInput.value = start;
        }
        calcularDias();
    });

    document.getElementById('end_date').addEventListener('change', calcularDias);

    function toggleRejectionReason(status) {
        const container = document.getElementById('rejection_reason_container');
        if (status === 'rejeitado') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endsection