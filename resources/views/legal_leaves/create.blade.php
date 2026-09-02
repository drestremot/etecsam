@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 py-4 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-4xl mx-auto space-y-4">

        <!-- Top Navigation -->
        <div class="flex items-center justify-between">
            <a href="{{ route('legal-leaves.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white/90 px-3.5 py-2 text-xs font-bold text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Voltar ao Banco de Folgas
            </a>
            <span class="rounded-lg bg-purple-100 px-3 py-1 text-xs font-bold text-purple-800">
                Diretoria de Serviços
            </span>
        </div>

        <!-- Form Card -->
        <div class="rounded-2xl border border-gray-300 bg-white p-6 sm:p-8 shadow-sm">
            <div class="border-b border-gray-100 pb-4 mb-6">
                <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-purple-100 text-purple-700 font-bold text-base">
                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </span>
                    <span>Conceder Crédito de Folga Prevista em Lei</span>
                </h1>
                <p class="text-xs text-gray-500 mt-1">
                    Cadastre o atestado/declaração oficial de serviço eleitoral, júri, doação de sangue ou outros direitos legais com anexo digital
                </p>
            </div>

            <form action="{{ route('legal-leaves.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Colaborador Selection -->
                <div>
                    <label for="user_id" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Colaborador Beneficiário <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20">
                        <option value="">Selecione o professor ou funcionário...</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->registration_number ?? 'Matrícula N/A' }}) - {{ $u->role }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
                </div>

                <!-- Tipo de Folga Legal & Legislação -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Tipo de Folga Legal <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required onchange="handleTypeChange(this.value)" class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500">
                            <option value="">Selecione o tipo previsto em lei...</option>
                            <option value="eleicao" {{ old('type') == 'eleicao' ? 'selected' : '' }}>Serviço Eleitoral / TRE (Folga em Dobro)</option>
                            <option value="juri_popular" {{ old('type') == 'juri_popular' ? 'selected' : '' }}>Tribunal do Júri (Júri Popular)</option>
                            <option value="doacao_sangue" {{ old('type') == 'doacao_sangue' ? 'selected' : '' }}>Doação de Sangue (1 dia/ano)</option>
                            <option value="alistamento" {{ old('type') == 'alistamento' ? 'selected' : '' }}>Alistamento Eleitoral</option>
                            <option value="casamento" {{ old('type') == 'casamento' ? 'selected' : '' }}>Casamento (Gala)</option>
                            <option value="luto" {{ old('type') == 'luto' ? 'selected' : '' }}>Luto (Nojo)</option>
                            <option value="convocacao_judicial" {{ old('type') == 'convocacao_judicial' ? 'selected' : '' }}>Convocação Judicial / Oficial</option>
                            <option value="outro" {{ old('type') == 'outro' ? 'selected' : '' }}>Outro Previsto em Lei</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-1" />
                    </div>

                    <div>
                        <label for="document_number" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Nº da Declaração / Certidão / Protocolo
                        </label>
                        <input
                            type="text"
                            name="document_number"
                            id="document_number"
                            value="{{ old('document_number') }}"
                            placeholder="Ex: Certidão TRE nº 12345/2024, Declaração Hemonúcleo..."
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500"
                        />
                        <x-input-error :messages="$errors->get('document_number')" class="mt-1" />
                    </div>
                </div>

                <!-- Info Box Dinâmico da Legislação -->
                <div id="legal-info-box" class="rounded-xl bg-purple-50 p-3.5 border border-purple-200 text-xs text-purple-900 space-y-1">
                    <span class="font-extrabold block text-purple-950" id="legal-title"><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg> Regra Legal Aplicável:</span>
                    <p id="legal-desc" class="text-[11.5px] leading-relaxed text-purple-800">
                        Selecione o tipo de folga acima para visualizar a fundamentação jurídica e o cálculo de dias.
                    </p>
                </div>

                <!-- Descrição do Evento -->
                <div>
                    <label for="description" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Descrição do Motivo / Evento <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="description"
                        id="description"
                        required
                        value="{{ old('description') }}"
                        placeholder="Ex: Mesário no 1º e 2º turno das Eleições Municipais 2024 (2 dias trabalhados = 4 dias de folga)"
                        class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500"
                    />
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>

                <!-- Data do Evento & Dias Concedidos Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="event_date" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Data do Evento / Serviço
                        </label>
                        <input
                            type="date"
                            name="event_date"
                            id="event_date"
                            value="{{ old('event_date') }}"
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500"
                        />
                        <x-input-error :messages="$errors->get('event_date')" class="mt-1" />
                    </div>

                    <div>
                        <label for="days_granted" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Total de Dias Concedidos <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="days_granted"
                            id="days_granted"
                            required
                            min="1"
                            max="60"
                            value="{{ old('days_granted', 1) }}"
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs font-semibold text-purple-700 focus:border-purple-500"
                        />
                        <x-input-error :messages="$errors->get('days_granted')" class="mt-1" />
                    </div>

                    <div>
                        <label for="expiration_date" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Data Limite de Gozo (Opcional)
                        </label>
                        <input
                            type="date"
                            name="expiration_date"
                            id="expiration_date"
                            value="{{ old('expiration_date') }}"
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500"
                        />
                        <x-input-error :messages="$errors->get('expiration_date')" class="mt-1" />
                    </div>
                </div>

                <!-- Botões de Cálculo Rápido (TRE / Doação) -->
                <div class="flex flex-wrap items-center gap-2 pt-1">
                    <span class="text-[11px] font-bold text-gray-500 uppercase">Preenchimento Rápido:</span>
                    <button type="button" onclick="setQuickDays('eleicao', 1, 2, 'Serviço Eleitoral TRE (1 Turno = 2 Folgas)')" class="rounded-lg bg-gray-100 hover:bg-purple-100 text-purple-900 border border-gray-200 px-2.5 py-1 text-[11px] font-bold transition">
                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> 1 Turno TRE (2 dias)
                    </button>
                    <button type="button" onclick="setQuickDays('eleicao', 2, 4, 'Serviço Eleitoral TRE (2 Turnos = 4 Folgas)')" class="rounded-lg bg-gray-100 hover:bg-purple-100 text-purple-900 border border-gray-200 px-2.5 py-1 text-[11px] font-bold transition">
                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> 2 Turnos TRE (4 dias)
                    </button>
                    <button type="button" onclick="setQuickDays('doacao_sangue', 1, 1, 'Doação de Sangue - Comprovante Hemocentro')" class="rounded-lg bg-gray-100 hover:bg-red-100 text-red-900 border border-gray-200 px-2.5 py-1 text-[11px] font-bold transition">
                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg> Doação de Sangue (1 dia)
                    </button>
                </div>

                <!-- Upload do Atestado / Declaração Oficial -->
                <div>
                    <label for="attachment" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Anexar Atestado / Declaração / Comprovante Oficial <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center rounded-2xl border-2 border-dashed border-gray-300 px-6 pt-5 pb-6 hover:border-purple-500 transition-colors bg-gray-50/50">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-xs text-gray-600 justify-center">
                                <label for="attachment" class="relative cursor-pointer rounded-md bg-white font-bold text-purple-600 focus-within:outline-none hover:text-purple-500">
                                    <span>Clique para selecionar o arquivo</span>
                                    <input id="attachment" name="attachment" type="file" required accept=".pdf,.jpg,.jpeg,.png,.webp" class="sr-only" onchange="previewFileName(this)">
                                </label>
                            </div>
                            <p class="text-[10.5px] text-gray-500">Formatos aceitos: PDF, JPG, PNG ou WEBP (Máximo: 10MB)</p>
                            <p id="file-name-preview" class="text-xs font-bold text-emerald-600 hidden pt-2"></p>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('attachment')" class="mt-1" />
                </div>

                <!-- Observações -->
                <div>
                    <label for="notes" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Observações Internas da Diretoria de Serviços
                    </label>
                    <textarea
                        name="notes"
                        id="notes"
                        rows="2"
                        placeholder="Informações adicionais, observações sobre o deferimento ou comunicação com o colaborador..."
                        class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500"
                    >{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                </div>

                <!-- Submit Buttons -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('legal-leaves.index') }}" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-purple-500 transition"
                    >
                        <span><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg> Conceder Saldo de Folga</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
const legalRules = {
    eleicao: {
        title: "<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Serviço Eleitoral (Art. 98 da Lei Federal nº 9.504/1997)",
        desc: "Cada 1 dia trabalhado ou convocado para treinamento da Justiça Eleitoral concede DIREITO A 2 DIAS DE FOLGA remunerada (Concessão em dobro)."
    },
    juri_popular: {
        title: "<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg> Tribunal do Júri (Art. 430 do Código de Processo Penal)",
        desc: "Nenhum desconto será feito nos vencimentos do jurado sorteado que comparecer à sessão do júri, conferindo folga correspondente aos dias do julgamento."
    },
    doacao_sangue: {
        title: "<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg> Doação Voluntária de Sangue (Art. 473, IV da CLT / Lei Estadual SP)",
        desc: "Dispensa de 1 dia de trabalho a cada 12 meses mediante apresentação de atestado do banco de sangue ou hemocentro."
    },
    alistamento: {
        title: "<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Alistamento Eleitoral (Art. 473, V da CLT)",
        desc: "Até 2 dias de folga para fins de alistamento eleitoral ou transferência de domicílio eleitoral."
    },
    casamento: {
        title: "<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Casamento / Gala (Art. 473, II da CLT / Estatuto dos Servidores)",
        desc: "Folga remunerada de 3 a 8 dias consecutivos em virtude de casamento."
    },
    luto: {
        title: "<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>️ Luto / Nojo (Art. 473, I da CLT / Estatuto dos Servidores)",
        desc: "Afastamento remunerado em caso de falecimento de cônjuge, ascendente, descendente ou dependente."
    },
    convocacao_judicial: {
        title: "<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg> Convocação Judicial / Testemunha (Art. 822 da CLT)",
        desc: "O empregado não sofrerá desconto salarial pelo tempo que comparecer como testemunha ou convocado pela Justiça."
    },
    outro: {
        title: "<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Outro Direito Previsto em Lei",
        desc: "Concessão fundamentada em legislação trabalhista ou estatutária específica."
    }
};

function handleTypeChange(type) {
    const box = document.getElementById('legal-info-box');
    const title = document.getElementById('legal-title');
    const desc = document.getElementById('legal-desc');
    const daysInput = document.getElementById('days_granted');

    if (legalRules[type]) {
        title.innerText = legalRules[type].title;
        desc.innerText = legalRules[type].desc;
        if (type === 'doacao_sangue') {
            daysInput.value = 1;
        } else if (type === 'eleicao' && (!daysInput.value || daysInput.value == 1)) {
            daysInput.value = 2; // Default 1 turno = 2 folgas
        }
    }
}

function setQuickDays(type, workedDays, grantedDays, desc) {
    document.getElementById('type').value = type;
    handleTypeChange(type);
    document.getElementById('days_granted').value = grantedDays;
    document.getElementById('description').value = desc;
}

function previewFileName(input) {
    const preview = document.getElementById('file-name-preview');
    if (input.files && input.files[0]) {
        preview.innerText = "<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Arquivo selecionado: " + input.files[0].name;
        preview.classList.remove('hidden');
    }
}
</script>
@endsection
