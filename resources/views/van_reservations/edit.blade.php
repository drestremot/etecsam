<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">
                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg> VanTec • Edição de Reserva
                </span>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 mt-1">
                    Editar Reserva #{{ $vanReservation->id }}
                </h1>
            </div>
            <a href="{{ route('van-reservations.show', $vanReservation->id) }}" class="rounded-xl border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-2xs hover:bg-gray-50">
                &larr; Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="rounded-2xl border border-gray-300 bg-white p-6 shadow-sm">
            <form action="{{ route('van-reservations.update', $vanReservation->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Veículo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="vehicle_id" value="Veículo / Van Escolar *" />
                        <select name="vehicle_id" id="vehicle_id" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs focus:border-blue-500">
                            @foreach($vehicles as $veh)
                                <option value="{{ $veh->id }}" {{ old('vehicle_id', $vanReservation->vehicle_id) == $veh->id ? 'selected' : '' }}>
                                    {{ $veh->name }} (Placa: {{ $veh->plate }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label value="Solicitante" />
                        <input type="text" disabled value="{{ $vanReservation->user->name }}" class="mt-1 block w-full rounded-xl border-gray-200 bg-gray-50 text-gray-500 shadow-2xs text-xs" />
                    </div>
                </div>

                <!-- Finalidade e Destino -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="purpose" value="Motivo / Finalidade da Viagem *" />
                        <input
                            type="text"
                            name="purpose"
                            id="purpose"
                            value="{{ old('purpose', $vanReservation->purpose) }}"
                            required
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs"
                        />
                    </div>

                    <div>
                        <x-input-label for="destination" value="Destino / Itinerário *" />
                        <input
                            type="text"
                            name="destination"
                            id="destination"
                            value="{{ old('destination', $vanReservation->destination) }}"
                            required
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs"
                        />
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
                                value="{{ old('departure_date', $vanReservation->departure_date->format('Y-m-d')) }}"
                                required
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs"
                            />
                        </div>

                        <div>
                            <x-input-label for="departure_time" value="Horário de Saída *" />
                            <input
                                type="time"
                                name="departure_time"
                                id="departure_time"
                                value="{{ old('departure_time', $vanReservation->departure_time) }}"
                                required
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs"
                            />
                        </div>

                        <div>
                            <x-input-label for="return_date" value="Data de Retorno *" />
                            <input
                                type="date"
                                name="return_date"
                                id="return_date"
                                value="{{ old('return_date', $vanReservation->return_date->format('Y-m-d')) }}"
                                required
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs"
                            />
                        </div>

                        <div>
                            <x-input-label for="return_time" value="Horário de Retorno *" />
                            <input
                                type="time"
                                name="return_time"
                                id="return_time"
                                value="{{ old('return_time', $vanReservation->return_time) }}"
                                required
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs"
                            />
                        </div>
                    </div>
                </div>

                <!-- Condutor e Passageiros -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="driver_type" value="Tipo de Condutor *" />
                        <select name="driver_type" id="driver_type" required class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs">
                            <option value="servidor_habilitado" {{ old('driver_type', $vanReservation->driver_type) === 'servidor_habilitado' ? 'selected' : '' }}>Servidor Habilitado</option>
                            <option value="motorista_oficial" {{ old('driver_type', $vanReservation->driver_type) === 'motorista_oficial' ? 'selected' : '' }}>Motorista Oficial</option>
                            <option value="terceirizado" {{ old('driver_type', $vanReservation->driver_type) === 'terceirizado' ? 'selected' : '' }}>Terceirizado</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="driver_name" value="Nome do Condutor *" />
                        <input
                            type="text"
                            name="driver_name"
                            id="driver_name"
                            value="{{ old('driver_name', $vanReservation->driver_name) }}"
                            required
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs"
                        />
                    </div>

                    <div>
                        <x-input-label for="driver_cnh" value="CNH do Condutor" />
                        <input
                            type="text"
                            name="driver_cnh"
                            id="driver_cnh"
                            value="{{ old('driver_cnh', $vanReservation->driver_cnh) }}"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs"
                        />
                    </div>
                </div>

                <!-- Passageiros -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="passengers_count" value="Quantidade de Passageiros *" />
                        <input
                            type="number"
                            name="passengers_count"
                            id="passengers_count"
                            min="1"
                            max="50"
                            value="{{ old('passengers_count', $vanReservation->passengers_count) }}"
                            required
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs"
                        />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="passenger_list" value="Relação de Passageiros" />
                        <textarea
                            name="passenger_list"
                            id="passenger_list"
                            rows="2"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-2xs text-xs"
                        >{{ old('passenger_list', $vanReservation->passenger_list) }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('van-reservations.show', $vanReservation->id) }}" class="text-xs font-semibold text-gray-500 hover:text-gray-800">
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-blue-500 transition"
                    >
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
