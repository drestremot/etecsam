<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">
                    🚐 VanTec • Solicitação de Viagem
                </span>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 mt-1">
                    Solicitar Reserva da Van Escolar
                </h1>
            </div>
            <a href="{{ route('van-reservations.index') }}" class="rounded-xl border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-2xs hover:bg-gray-50">
                &larr; Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Banner das 72 Horas -->
        <div class="rounded-2xl border border-blue-200 bg-blue-50/60 p-4 text-xs text-blue-900 shadow-sm flex items-start gap-3">
            <span class="text-xl">⏱️</span>
            <div>
                <h3 class="font-bold text-blue-950">Regra de Antecedência de 72 Horas</h3>
                <p class="mt-0.5">
                    As viagens com a Van Escolar devem ser solicitadas com <strong>no mínimo 72 horas de antecedência</strong> em relação à data e horário de saída. Solicitações feitas com prazo menor serão sinalizadas como urgência e dependerão de aprovação especial da <strong>Diretora de Serviços</strong>.
                </p>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-300 bg-white p-6 shadow-sm">
            <form action="{{ route('van-reservations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Veículo & Solicitante -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="vehicle_id" value="Veículo / Van Escolar *" />
                        <select name="vehicle_id" id="vehicle_id" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500">
                            @foreach($vehicles as $veh)
                                <option value="{{ $veh->id }}" {{ old('vehicle_id') == $veh->id ? 'selected' : '' }}>
                                    {{ $veh->name }} (Placa: {{ $veh->plate }} • {{ $veh->capacity }} lugares • Hodômetro: {{ number_format($veh->current_km, 0, ',', '.') }} km)
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('vehicle_id')" class="mt-1" />
                    </div>

                    @if($canManage)
                        <div>
                            <x-input-label for="user_id" value="Solicitante (Docente / Funcionário) *" />
                            <select name="user_id" id="user_id" class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500">
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ (old('user_id') == $u->id || Auth::id() == $u->id) ? 'selected' : '' }}>
                                        {{ $u->name }} ({{ $u->role ?? 'Colaborador' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
                        </div>
                    @else
                        <div>
                            <x-input-label value="Solicitante" />
                            <input type="text" disabled value="{{ Auth::user()->name }} ({{ Auth::user()->department?->name ?? 'Etec' }})" class="mt-1 block w-full rounded-xl border-gray-200 bg-gray-50 text-gray-500 shadow-2xs text-xs" />
                        </div>
                    @endif
                </div>

                <!-- Finalidade e Destino -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="purpose" value="Motivo / Finalidade da Viagem *" />
                        <input
                            type="text"
                            name="purpose"
                            id="purpose"
                            value="{{ old('purpose') }}"
                            required
                            placeholder="Ex: Visita Técnica à Feira Tecnológica, Transporte de Alunos"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500"
                        />
                        <x-input-error :messages="$errors->get('purpose')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="destination" value="Destino / Itinerário *" />
                        <input
                            type="text"
                            name="destination"
                            id="destination"
                            value="{{ old('destination') }}"
                            required
                            placeholder="Ex: São Paulo / Centro de Convenções Anhembi"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500"
                        />
                        <x-input-error :messages="$errors->get('destination')" class="mt-1" />
                    </div>
                </div>

                <!-- Datas e Horários -->
                <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-4 space-y-3">
                    <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Cronograma da Viagem</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                        <div>
                            <x-input-label for="departure_date" value="Data de Saída *" />
                            <input
                                type="date"
                                name="departure_date"
                                id="departure_date"
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('departure_date') }}"
                                required
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500"
                            />
                            <x-input-error :messages="$errors->get('departure_date')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="departure_time" value="Horário de Saída *" />
                            <input
                                type="time"
                                name="departure_time"
                                id="departure_time"
                                value="{{ old('departure_time', '07:30') }}"
                                required
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500"
                            />
                            <x-input-error :messages="$errors->get('departure_time')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="return_date" value="Data de Retorno *" />
                            <input
                                type="date"
                                name="return_date"
                                id="return_date"
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('return_date') }}"
                                required
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500"
                            />
                            <x-input-error :messages="$errors->get('return_date')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="return_time" value="Horário de Retorno *" />
                            <input
                                type="time"
                                name="return_time"
                                id="return_time"
                                value="{{ old('return_time', '18:00') }}"
                                required
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500"
                            />
                            <x-input-error :messages="$errors->get('return_time')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <!-- Condutor e Passageiros -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="driver_type" value="Tipo de Condutor *" />
                        <select name="driver_type" id="driver_type" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500">
                            <option value="servidor_habilitado" {{ old('driver_type') === 'servidor_habilitado' ? 'selected' : '' }}>Servidor Habilitado (Docente/Funcionário)</option>
                            <option value="motorista_oficial" {{ old('driver_type') === 'motorista_oficial' ? 'selected' : '' }}>Motorista Oficial da Instituição</option>
                            <option value="terceirizado" {{ old('driver_type') === 'terceirizado' ? 'selected' : '' }}>Condutor Terceirizado / Credenciado</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="driver_name" value="Nome Completo do Condutor *" />
                        <input
                            type="text"
                            name="driver_name"
                            id="driver_name"
                            value="{{ old('driver_name', Auth::user()->name) }}"
                            required
                            placeholder="Nome do motorista"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500"
                        />
                        <x-input-error :messages="$errors->get('driver_name')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="driver_cnh" value="CNH do Condutor (Nº / Categoria)" />
                        <input
                            type="text"
                            name="driver_cnh"
                            id="driver_cnh"
                            value="{{ old('driver_cnh') }}"
                            placeholder="Ex: 01234567890 - Cat D"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>
                </div>

                <!-- Quantidade e Lista de Passageiros -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="passengers_count" value="Quantidade de Passageiros *" />
                        <input
                            type="number"
                            name="passengers_count"
                            id="passengers_count"
                            min="1"
                            max="50"
                            value="{{ old('passengers_count', 1) }}"
                            required
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500"
                        />
                        <x-input-error :messages="$errors->get('passengers_count')" class="mt-1" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="passenger_list" value="Relação de Passageiros (Alunos / Docentes)" />
                        <textarea
                            name="passenger_list"
                            id="passenger_list"
                            rows="2"
                            placeholder="Insira os nomes dos passageiros ou turma participante..."
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('passenger_list') }}</textarea>
                    </div>
                </div>

                <!-- Observações e Checklist -->
                <div>
                    <x-input-label for="checklist_notes" value="Observações Adicionais / Pedágio / Rota" />
                    <textarea
                        name="checklist_notes"
                        id="checklist_notes"
                        rows="2"
                        placeholder="Informações sobre paradas, pedágios, transporte de materiais pedagógicos..."
                        class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500 focus:ring-blue-500"
                    >{{ old('checklist_notes') }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('van-reservations.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-800">
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-blue-500 focus:ring-2 focus:ring-blue-500 transition"
                    >
                        Enviar Solicitação de Reserva
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
