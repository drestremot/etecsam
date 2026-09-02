@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 py-4 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-4xl mx-auto space-y-4">

        <!-- Top Navigation -->
        <div class="flex items-center justify-between">
            <a href="{{ route('legal-leaves.show', $legalLeave->id) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white/90 px-3.5 py-2 text-xs font-bold text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Voltar à Visualização da Folga
            </a>
            <span class="rounded-lg bg-purple-100 px-3 py-1 text-xs font-bold text-purple-800">
                Edição • Diretoria de Serviços
            </span>
        </div>

        <!-- Form Card -->
        <div class="rounded-2xl border border-gray-300 bg-white p-6 sm:p-8 shadow-sm">
            <div class="border-b border-gray-100 pb-4 mb-6">
                <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-purple-100 text-purple-700 font-bold text-base">
                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>️
                    </span>
                    <span>Editar Registro de Folga Legal #{{ $legalLeave->id }}</span>
                </h1>
                <p class="text-xs text-gray-500 mt-1">
                    Qualquer alteração efetuada será registrada na trilha de auditoria confidencial com registro do autor e data/hora.
                </p>
            </div>

            <form action="{{ route('legal-leaves.update', $legalLeave->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Colaborador Selection -->
                <div>
                    <label for="user_id" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Colaborador Beneficiário <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500">
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id', $legalLeave->user_id) == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->registration_number ?? 'Matrícula N/A' }}) - {{ $u->role }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
                </div>

                <!-- Tipo & Doc -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Tipo de Folga Legal <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500">
                            <option value="eleicao" {{ old('type', $legalLeave->type) == 'eleicao' ? 'selected' : '' }}>Serviço Eleitoral / TRE (Folga em Dobro)</option>
                            <option value="juri_popular" {{ old('type', $legalLeave->type) == 'juri_popular' ? 'selected' : '' }}>Tribunal do Júri</option>
                            <option value="doacao_sangue" {{ old('type', $legalLeave->type) == 'doacao_sangue' ? 'selected' : '' }}>Doação de Sangue</option>
                            <option value="alistamento" {{ old('type', $legalLeave->type) == 'alistamento' ? 'selected' : '' }}>Alistamento Eleitoral</option>
                            <option value="casamento" {{ old('type', $legalLeave->type) == 'casamento' ? 'selected' : '' }}>Casamento</option>
                            <option value="luto" {{ old('type', $legalLeave->type) == 'luto' ? 'selected' : '' }}>Luto</option>
                            <option value="convocacao_judicial" {{ old('type', $legalLeave->type) == 'convocacao_judicial' ? 'selected' : '' }}>Convocação Judicial</option>
                            <option value="outro" {{ old('type', $legalLeave->type) == 'outro' ? 'selected' : '' }}>Outro Previsto em Lei</option>
                        </select>
                    </div>

                    <div>
                        <label for="document_number" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Nº da Declaração / Certidão
                        </label>
                        <input
                            type="text"
                            name="document_number"
                            id="document_number"
                            value="{{ old('document_number', $legalLeave->document_number) }}"
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500"
                        />
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label for="description" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Descrição do Motivo / Evento <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="description"
                        id="description"
                        required
                        value="{{ old('description', $legalLeave->description) }}"
                        class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500"
                    />
                </div>

                <!-- Grid Concedidos, Status, Datas -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label for="days_granted" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Dias Concedidos <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="days_granted"
                            id="days_granted"
                            required
                            min="1"
                            value="{{ old('days_granted', $legalLeave->days_granted) }}"
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs font-semibold text-purple-700 focus:border-purple-500"
                        />
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500">
                            <option value="ativo" {{ old('status', $legalLeave->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="esgotado" {{ old('status', $legalLeave->status) == 'esgotado' ? 'selected' : '' }}>Esgotado</option>
                            <option value="expirado" {{ old('status', $legalLeave->status) == 'expirado' ? 'selected' : '' }}>Expirado</option>
                        </select>
                    </div>

                    <div>
                        <label for="event_date" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Data do Evento
                        </label>
                        <input
                            type="date"
                            name="event_date"
                            id="event_date"
                            value="{{ old('event_date', $legalLeave->event_date?->format('Y-m-d')) }}"
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500"
                        />
                    </div>

                    <div>
                        <label for="expiration_date" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                            Data Limite de Gozo
                        </label>
                        <input
                            type="date"
                            name="expiration_date"
                            id="expiration_date"
                            value="{{ old('expiration_date', $legalLeave->expiration_date?->format('Y-m-d')) }}"
                            class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500"
                        />
                    </div>
                </div>

                <!-- Substituição de Comprovante (Opcional) -->
                <div>
                    <label for="attachment" class="block text-xs font-bold text-gray-700 uppercase mb-1">
                        Substituir Comprovante Anexo (Opcional)
                    </label>
                    <input
                        type="file"
                        name="attachment"
                        id="attachment"
                        accept=".pdf,.jpg,.jpeg,.png,.webp"
                        class="w-full rounded-xl border border-gray-300 p-2 text-xs"
                    />
                    <span class="text-[10px] text-gray-500 block mt-1">Deixe em branco caso deseje manter o documento original atual.</span>
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
                        class="w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-purple-500"
                    >{{ old('notes', $legalLeave->notes) }}</textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('legal-leaves.show', $legalLeave->id) }}" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-bold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-purple-500 transition"
                    >
                        <span>Salvar Alterações & Auditar</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
