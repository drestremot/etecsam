@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 py-4 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-3xl mx-auto space-y-4">

        <!-- Top Navigation -->
        <div class="flex items-center justify-between">
            <a href="{{ route('medical-certificates.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white/90 px-3.5 py-2 text-xs font-bold text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Voltar à Lista de Atestados
            </a>
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">AtestadosTec • Formulário</span>
        </div>

        <!-- Form Card -->
        <div class="rounded-2xl border border-gray-300 bg-white p-6 sm:p-8 shadow-sm">
            <div class="border-b border-gray-100 pb-4 mb-6">
                <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-red-100 text-red-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </span>
                    <span>Registrar Novo Atestado Médico</span>
                </h1>
                <p class="text-xs text-gray-500 mt-1">
                    Envie o comprovante de afastamento digitalizado para homologação da equipe pedagógica e direção
                </p>
            </div>

            <form action="{{ route('medical-certificates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Colaborador Selection (Diretoria de Serviços) -->
                <div>
                    <label for="user_id" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Colaborador / Docente / Funcionário <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                        <option value="">Selecione o colaborador que apresentou o atestado...</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->registration_number ?? 'Matrícula N/A' }}) - {{ $u->role }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
                </div>

                <!-- Tipo & Médico / CRM Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="type" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Tipo de Afastamento <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                            <option value="medico" {{ old('type') == 'medico' ? 'selected' : '' }}>Médico Geral</option>
                            <option value="odontologico" {{ old('type') == 'odontologico' ? 'selected' : '' }}>Odontológico</option>
                            <option value="acompanhamento" {{ old('type') == 'acompanhamento' ? 'selected' : '' }}>Acompanhamento Familiar</option>
                            <option value="declaracao_horas" {{ old('type') == 'declaracao_horas' ? 'selected' : '' }}>Declaração de Horas / Consulta</option>
                            <option value="outro" {{ old('type') == 'outro' ? 'selected' : '' }}>Outro</option>
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
                            value="{{ old('doctor_name') }}"
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
                            value="{{ old('crm') }}"
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
                            value="{{ old('start_date', date('Y-m-d')) }}"
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
                            value="{{ old('end_date', date('Y-m-d')) }}"
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
                            value="{{ old('days', 1) }}"
                            min="1"
                            class="w-full rounded-xl border-gray-300 bg-gray-50 shadow-2xs text-xs font-bold text-blue-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />
                        <x-input-error :messages="$errors->get('days')" class="mt-1" />
                    </div>
                </div>

                <!-- CID & Observações -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="sm:col-span-1">
                        <label for="cid" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            CID (Opcional)
                        </label>
                        <input
                            type="text"
                            name="cid"
                            id="cid"
                            value="{{ old('cid') }}"
                            placeholder="Ex: J06.9"
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />
                        <span class="text-[10px] text-gray-400 block mt-0.5">Sigiloso</span>
                        <x-input-error :messages="$errors->get('cid')" class="mt-1" />
                    </div>

                    <div class="sm:col-span-3">
                        <label for="description" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Observações / Detalhes do Afastamento
                        </label>
                        <input
                            type="text"
                            name="description"
                            id="description"
                            value="{{ old('description') }}"
                            placeholder="Ex: Repouso médico pós-procedimento cirúrgico, consulta de rotina..."
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                        />
                        <x-input-error :messages="$errors->get('description')" class="mt-1" />
                    </div>
                </div>

                <!-- Upload de Arquivo / Anexo -->
                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 bg-gray-50/70 hover:bg-gray-50 transition text-center">
                    <label for="attachment" class="cursor-pointer block">
                        <svg class="h-10 w-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                        <span class="font-bold text-xs text-blue-600 hover:underline block">
                            Clique para selecionar ou arraste o arquivo do atestado aqui
                        </span>
                        <span class="text-[11px] text-gray-500 block mt-1">
                            Formatos aceitos: <strong>PDF, JPG, PNG, WEBP</strong> (Tamanho máximo: 10MB)
                        </span>
                        <input
                            type="file"
                            name="attachment"
                            id="attachment"
                            required
                            accept=".pdf,.jpg,.jpeg,.png,.webp"
                            class="hidden"
                            onchange="mostrarNomeArquivo(this)"
                        />
                    </label>
                    <div id="file-chosen-preview" class="hidden mt-3 inline-flex items-center gap-2 rounded-xl bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700 border border-blue-200">
                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> <span id="file-chosen-name">Nenhum arquivo</span>
                    </div>
                    <x-input-error :messages="$errors->get('attachment')" class="mt-2 text-xs" />
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('medical-certificates.index') }}" class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-xs font-semibold uppercase tracking-wide text-white shadow-md hover:bg-blue-500 hover:shadow-lg transition"
                    >
                        <span>Salvar e Enviar Atestado</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
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

    function mostrarNomeArquivo(input) {
        if (input.files && input.files[0]) {
            const preview = document.getElementById('file-chosen-preview');
            const nameEl = document.getElementById('file-chosen-name');
            nameEl.innerText = input.files[0].name + " (" + (input.files[0].size / 1024 / 1024).toFixed(2) + " MB)";
            preview.classList.remove('hidden');
        }
    }
</script>
@endsection
