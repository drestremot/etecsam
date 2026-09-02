<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase {{ $vanReservation->status_badge_color }}">
                        {{ $vanReservation->status_label }}
                    </span>
                    <span class="text-xs text-gray-400 font-bold">#{{ $vanReservation->id }}</span>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 mt-1">
                    {{ $vanReservation->purpose }}
                </h1>
                <p class="text-xs text-blue-700 font-semibold mt-0.5">
                    📍 Destino: {{ $vanReservation->destination }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('van-reservations.index') }}" class="rounded-xl border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-2xs hover:bg-gray-50">
                    &larr; Voltar ao Painel
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-semibold text-emerald-800 shadow-sm flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-xs font-semibold text-red-800 shadow-sm flex items-center gap-2">
                <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Card de Status de Liberação da Diretora de Serviços -->
        @if($vanReservation->status === 'pendente')
            <div class="rounded-2xl border border-amber-300 bg-amber-50/70 p-5 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-500 text-white font-bold text-xs animate-pulse">
                                !
                            </span>
                            <h3 class="text-sm font-bold text-amber-950">Aguardando Liberação pela Diretora de Serviços</h3>
                        </div>
                        <p class="text-xs text-amber-900/90 mt-1">
                            A reserva foi solicitada em {{ $vanReservation->created_at->format('d/m/Y H:i') }} (Antecedência: {{ $vanReservation->hours_in_advance }}h - {{ $vanReservation->is_within_72h_deadline ? 'Dentro das 72h' : 'Caráter de urgência' }}).
                        </p>
                    </div>

                    @if($canManage)
                        <div class="flex items-center gap-2">
                            <!-- Form Aprovar -->
                            <form action="{{ route('van-reservations.approve', $vanReservation->id) }}" method="POST" onsubmit="return confirm('Confirmar liberação e autorização desta viagem?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-emerald-500 transition">
                                    ✅ Liberar e Autorizar Viagem
                                </button>
                            </form>

                            <!-- Form Recusar -->
                            <button
                                type="button"
                                onclick="document.getElementById('modal-reject').classList.remove('hidden')"
                                class="rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-red-500 transition"
                            >
                                ❌ Recusar
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @elseif($vanReservation->status === 'aprovada')
            <div class="rounded-2xl border border-emerald-300 bg-emerald-50/70 p-5 shadow-sm flex items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-600 text-lg">✅</span>
                        <h3 class="text-sm font-bold text-emerald-950">Viagem Liberada e Autorizada pela Diretoria de Serviços</h3>
                    </div>
                    <p class="text-xs text-emerald-800 mt-1">
                        Aprovado por <strong>{{ $vanReservation->approver?->name ?? 'Diretora de Serviços' }}</strong> em {{ $vanReservation->approved_at?->format('d/m/Y H:i') }}. O veículo está liberado para saída.
                    </p>
                    @if($vanReservation->director_notes)
                        <p class="text-xs text-emerald-900 bg-white/80 p-2 rounded-lg mt-2 border border-emerald-200">
                            <strong>Parecer da Diretoria:</strong> {{ $vanReservation->director_notes }}
                        </p>
                    @endif
                </div>
            </div>
        @elseif($vanReservation->status === 'rejeitada')
            <div class="rounded-2xl border border-red-300 bg-red-50/70 p-5 shadow-sm">
                <div class="flex items-center gap-2 text-red-950 font-bold text-sm">
                    <span>❌</span> Solicitação Recusada pela Diretoria de Serviços
                </div>
                <p class="text-xs text-red-800 mt-1">
                    <strong>Motivo da Recusa:</strong> {{ $vanReservation->rejection_reason }}
                </p>
            </div>
        @endif

        <!-- Grid Principal: Detalhes da Viagem & Lançamento de Hodômetro (KM) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Coluna 1 e 2: Dados da Reserva & Lançamento de KM -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Seção de Hodômetro (KM Inicial na Saída e KM Final no Retorno) -->
                <div class="rounded-2xl border border-gray-300 bg-white p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 font-bold text-xs">
                                🚗
                            </span>
                            <div>
                                <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-900">
                                    Controle de Hodômetro & Quilometragem (KM)
                                </h2>
                                <p class="text-[10.5px] text-gray-500">
                                    Registro obrigatório de saída e retorno do veículo
                                </p>
                            </div>
                        </div>

                        @if($vanReservation->total_km !== null)
                            <div class="text-right">
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">Distância Percorrida</span>
                                <span class="text-lg font-semibold text-emerald-700">{{ number_format($vanReservation->total_km, 0, ',', '.') }} km</span>
                            </div>
                        @endif
                    </div>

                    <!-- Etapa 1: Registro de KM Inicial na Saída -->
                    @if($vanReservation->status === 'aprovada')
                        <div class="rounded-xl border border-blue-200 bg-blue-50/40 p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold text-blue-950 uppercase tracking-wide">
                                    1. Registrar Saída do Veículo (KM Inicial)
                                </h3>
                                <span class="text-[10px] text-blue-700 font-semibold">Hodômetro Atual da Van: {{ number_format($vanReservation->vehicle?->current_km ?? 0, 0, ',', '.') }} km</span>
                            </div>
                            <form action="{{ route('van-reservations.start-trip', $vanReservation->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <x-input-label for="initial_km" value="KM Inicial (Hodômetro) *" />
                                        <input
                                            type="number"
                                            name="initial_km"
                                            id="initial_km"
                                            min="{{ $vanReservation->vehicle?->current_km ?? 0 }}"
                                            value="{{ old('initial_km', $vanReservation->vehicle?->current_km) }}"
                                            required
                                            class="mt-1 block w-full rounded-xl border-gray-300 text-xs shadow-2xs focus:border-blue-500"
                                        />
                                    </div>
                                    <div>
                                        <x-input-label for="fuel_level_departure" value="Combustível na Saída" />
                                        <select name="fuel_level_departure" id="fuel_level_departure" class="mt-1 block w-full rounded-xl border-gray-300 text-xs shadow-2xs">
                                            <option value="Cheio">Cheio (Tanque Completo)</option>
                                            <option value="3/4">3/4 Tanque</option>
                                            <option value="1/2">1/2 Tanque</option>
                                            <option value="1/4">1/4 Tanque</option>
                                            <option value="Reserva">Reserva</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="initial_km_photo" value="Foto do Painel (Opcional)" />
                                        <input
                                            type="file"
                                            name="initial_km_photo"
                                            id="initial_km_photo"
                                            accept="image/*"
                                            class="mt-1 block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200"
                                        />
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-blue-500 transition">
                                        🛫 Confirmar Saída da Van
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Etapa 2: Registro de KM Final no Retorno -->
                    @if($vanReservation->status === 'em_andamento')
                        <div class="rounded-xl border border-purple-200 bg-purple-50/40 p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold text-purple-950 uppercase tracking-wide">
                                    2. Registrar Retorno & Concluir Viagem (KM Final)
                                </h3>
                                <span class="text-[10px] text-purple-800 font-semibold">KM Inicial Registrada: {{ number_format($vanReservation->initial_km, 0, ',', '.') }} km</span>
                            </div>
                            <form action="{{ route('van-reservations.finish-trip', $vanReservation->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <x-input-label for="final_km" value="KM Final (Hodômetro) *" />
                                        <input
                                            type="number"
                                            name="final_km"
                                            id="final_km"
                                            min="{{ $vanReservation->initial_km ?? 0 }}"
                                            value="{{ old('final_km', ($vanReservation->initial_km ?? 0) + 10) }}"
                                            required
                                            class="mt-1 block w-full rounded-xl border-gray-300 text-xs shadow-2xs focus:border-purple-500"
                                        />
                                    </div>
                                    <div>
                                        <x-input-label for="fuel_level_return" value="Combustível no Retorno" />
                                        <select name="fuel_level_return" id="fuel_level_return" class="mt-1 block w-full rounded-xl border-gray-300 text-xs shadow-2xs">
                                            <option value="Cheio">Cheio (Tanque Completo)</option>
                                            <option value="3/4">3/4 Tanque</option>
                                            <option value="1/2">1/2 Tanque</option>
                                            <option value="1/4">1/4 Tanque</option>
                                            <option value="Reserva">Reserva</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="final_km_photo" value="Foto do Painel no Retorno" />
                                        <input
                                            type="file"
                                            name="final_km_photo"
                                            id="final_km_photo"
                                            accept="image/*"
                                            class="mt-1 block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200"
                                        />
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="checklist_notes" value="Observações de Devolução (Limpeza, avarias, etc.)" />
                                    <input
                                        type="text"
                                        name="checklist_notes"
                                        id="checklist_notes"
                                        value="{{ old('checklist_notes', $vanReservation->checklist_notes) }}"
                                        placeholder="Veículo devolvido limpo, abastecido, sem avarias..."
                                        class="mt-1 block w-full rounded-xl border-gray-300 text-xs shadow-2xs"
                                    />
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="rounded-xl bg-purple-700 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-purple-600 transition">
                                        🏁 Concluir Viagem & Atualizar Hodômetro
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Resumo dos KMs Registrados -->
                    <div class="grid grid-cols-3 gap-3 text-center bg-gray-50 p-3 rounded-xl">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 block">KM Inicial</span>
                            <span class="text-sm font-semibold text-gray-800">
                                {{ $vanReservation->initial_km ? number_format($vanReservation->initial_km, 0, ',', '.') . ' km' : 'Pendente' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 block">KM Final</span>
                            <span class="text-sm font-semibold text-gray-800">
                                {{ $vanReservation->final_km ? number_format($vanReservation->final_km, 0, ',', '.') . ' km' : 'Pendente' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 block">Total Percorrido</span>
                            <span class="text-sm font-semibold text-emerald-700">
                                {{ $vanReservation->total_km ? number_format($vanReservation->total_km, 0, ',', '.') . ' km' : '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Detalhes da Viagem -->
                <div class="rounded-2xl border border-gray-300 bg-white p-5 shadow-sm space-y-4">
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-900 border-b border-gray-100 pb-2.5">
                        Informações Completas da Viagem
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-gray-400 block text-[10.5px]">Veículo</span>
                            <span class="font-bold text-gray-800">{{ $vanReservation->vehicle->name }} (Placa: {{ $vanReservation->vehicle->plate }})</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10.5px]">Solicitante</span>
                            <span class="font-bold text-gray-800">{{ $vanReservation->user->name }} ({{ $vanReservation->user->department?->name ?? 'Etec' }})</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10.5px]">Saída Prevista</span>
                            <span class="font-bold text-gray-800">{{ $vanReservation->departure_date->format('d/m/Y') }} às {{ $vanReservation->departure_time }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10.5px]">Retorno Previsto</span>
                            <span class="font-bold text-gray-800">{{ $vanReservation->return_date->format('d/m/Y') }} às {{ $vanReservation->return_time }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10.5px]">Condutor</span>
                            <span class="font-bold text-gray-800">{{ $vanReservation->driver_name }} ({{ $vanReservation->driver_cnh ?? 'CNH não inf.' }})</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10.5px]">Passageiros</span>
                            <span class="font-bold text-gray-800">{{ $vanReservation->passengers_count }} pessoas</span>
                        </div>
                    </div>

                    @if($vanReservation->passenger_list)
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <span class="text-gray-400 block text-[10.5px] mb-1">Lista de Passageiros</span>
                            <div class="p-3 bg-gray-50 rounded-xl text-xs text-gray-700 whitespace-pre-line font-mono">
                                {{ $vanReservation->passenger_list }}
                            </div>
                        </div>
                    @endif

                    @if($vanReservation->checklist_notes)
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <span class="text-gray-400 block text-[10.5px] mb-1">Observações / Checklist</span>
                            <div class="p-3 bg-gray-50 rounded-xl text-xs text-gray-700">
                                {{ $vanReservation->checklist_notes }}
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Coluna 3: Trilha de Auditoria & Painel Lateral -->
            <div class="space-y-6">

                <!-- Ações do Solicitante / Gestor -->
                <div class="rounded-2xl border border-gray-300 bg-white p-4 sm:p-5 shadow-sm space-y-3">
                    <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-900 border-b border-gray-100 pb-2">
                        Ações da Reserva
                    </h3>

                    <div class="space-y-2">
                        @if(!in_array($vanReservation->status, ['concluida', 'cancelada']))
                            <a
                                href="{{ route('van-reservations.edit', $vanReservation->id) }}"
                                class="w-full flex items-center justify-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs font-bold text-gray-700 shadow-2xs hover:bg-gray-50 transition"
                            >
                                ✏️ Editar Solicitação
                            </a>

                            <form action="{{ route('van-reservations.cancel', $vanReservation->id) }}" method="POST" onsubmit="return confirm('Deseja realmente cancelar esta reserva?');">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 hover:bg-red-100 shadow-2xs transition"
                                >
                                    ❌ Cancelar Reserva
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Trilha de Auditoria -->
                @if($canViewAudit && $vanReservation->audits->count() > 0)
                    <div class="rounded-2xl border border-gray-300 bg-white p-4 sm:p-5 shadow-sm space-y-3">
                        <div class="flex items-center gap-1.5 border-b border-gray-100 pb-2">
                            <span class="text-xs">🛡️</span>
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-900">
                                Auditoria & Histórico
                            </h3>
                        </div>

                        <div class="space-y-2.5 max-h-96 overflow-y-auto pr-1">
                            @foreach($vanReservation->audits as $audit)
                                <div class="p-2.5 rounded-xl bg-gray-50 border border-gray-200/60 text-[11px] space-y-1">
                                    <div class="flex items-center justify-between text-[10px] text-gray-400 font-bold">
                                        <span>{{ $audit->action_label }}</span>
                                        <span>{{ $audit->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="text-gray-800 font-medium">{{ $audit->notes }}</p>
                                    <div class="text-[9.5px] text-gray-400">
                                        Por: {{ $audit->user?->name ?? 'Sistema' }} • IP: {{ $audit->ip_address }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>

    <!-- Modal de Recusa da Reserva -->
    @if($canManage)
        <div id="modal-reject" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-white rounded-2xl p-6 shadow-xl space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-red-700">Recusar Reserva da Van Escolar</h3>
                    <button type="button" onclick="document.getElementById('modal-reject').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                <form action="{{ route('van-reservations.reject', $vanReservation->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <div>
                        <x-input-label for="rejection_reason" value="Justificativa da Recusa *" />
                        <textarea
                            name="rejection_reason"
                            id="rejection_reason"
                            rows="3"
                            required
                            placeholder="Informe o motivo da não liberação da Van Escolar..."
                            class="mt-1 block w-full rounded-xl border-gray-300 text-xs shadow-2xs focus:border-red-500"
                        ></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="document.getElementById('modal-reject').classList.add('hidden')" class="rounded-xl border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700">
                            Cancelar
                        </button>
                        <button type="submit" class="rounded-xl bg-red-600 px-4 py-1.5 text-xs font-bold text-white hover:bg-red-500">
                            Confirmar Recusa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
