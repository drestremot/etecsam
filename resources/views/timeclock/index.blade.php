@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10" x-data="timeClockApp()">
    <div class="w-full max-w-5xl mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-gray-500 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-extrabold">Ponto Eletrônico</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Ponto Digital com Reconhecimento Facial</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1 font-normal">
                    Registro de frequência com captura facial, geolocalização e conferência de grade horária
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('timeclock.totem') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-white border border-gray-300 px-4 py-2.5 text-xs font-semibold text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Abrir Modo Totem</span>
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        <!-- Main Punching Terminal Card -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left / Main: Live Camera Stream & Biometric Capture (7 cols) -->
            <div class="lg:col-span-7 rounded-3xl border border-gray-200 bg-white p-5 sm:p-7 shadow-xs space-y-5">

                {{-- Live Clock & Geolocation Radar Bar --}}
                <div class="flex flex-wrap items-center justify-between gap-3 bg-gray-50/80 p-3.5 rounded-2xl border border-gray-200">
                    <div class="flex items-center gap-2.5">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <div>
                            <span class="text-xs font-bold text-gray-900" x-text="currentTime">--:--:--</span>
                            <span class="text-[11px] text-gray-500 block font-normal" x-text="currentDate"></span>
                        </div>
                    </div>

                    {{-- GPS Status Badge --}}
                    <div>
                        <template x-if="gps.status === 'loading'">
                            <span class="inline-flex items-center gap-1.5 rounded-xl bg-amber-50 border border-amber-200 px-3 py-1 text-[11px] font-semibold text-amber-700">
                                <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Obtendo GPS...</span>
                            </span>
                        </template>

                        <template x-if="gps.status === 'success'">
                            <span class="inline-flex items-center gap-1.5 rounded-xl px-3 py-1 text-[11px] font-semibold border"
                                  :class="gps.is_within ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'">
                                <span x-text="gps.is_within ? '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Na Unidade Escolar' : '<svg class="w-4 h-4 inline-block text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> Fora da Escola'"></span>
                                <span class="font-normal" x-text="'(' + gps.distance + 'm)'"></span>
                            </span>
                        </template>

                        <template x-if="gps.status === 'error'">
                            <span class="inline-flex items-center gap-1 rounded-xl bg-gray-100 border border-gray-200 px-3 py-1 text-[11px] font-semibold text-gray-600">
                                <span>GPS Indisponível</span>
                            </span>
                        </template>
                    </div>
                </div>

                {{-- Camera Viewfinder Container --}}
                <div class="relative w-full aspect-[4/3] rounded-2xl overflow-hidden bg-gray-900 border-2 border-indigo-200 shadow-inner flex items-center justify-center">
                    <!-- Video Element -->
                    <video x-ref="video" autoplay playsinline muted class="w-full h-full object-cover transform -scale-x-100"></video>

                    <!-- Hidden Canvas for frame snapshot -->
                    <canvas x-ref="canvas" class="hidden"></canvas>

                    <!-- Facial Scanning Overlay Frame -->
                    <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                        <div class="w-48 sm:w-56 h-60 sm:h-68 rounded-[50%] border-2 border-dashed border-indigo-400/80 shadow-[0_0_0_9999px_rgba(15,23,42,0.4)] flex flex-col items-center justify-between p-4">
                            <span class="text-[10px] font-bold text-white bg-indigo-600/90 px-2.5 py-0.5 rounded-full uppercase tracking-wider backdrop-blur-xs">
                                Posicione o Rosto
                            </span>
                            <div class="w-full h-0.5 bg-gradient-to-r from-transparent via-emerald-400 to-transparent animate-pulse"></div>
                            <span class="text-[10px] font-semibold text-gray-200 bg-black/60 px-2 py-0.5 rounded-full">
                                Câmera Ativa
                            </span>
                        </div>
                    </div>

                    <!-- Camera Error or Permission prompt -->
                    <div x-show="cameraError" class="absolute inset-0 bg-gray-900/90 flex flex-col items-center justify-center p-6 text-center text-white space-y-3" style="display: none;">
                        <span class="text-3xl"><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
                        <p class="text-xs font-semibold" x-text="cameraErrorMessage"></p>
                        <button type="button" @click="startCamera()" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold hover:bg-indigo-500 transition">
                            Permitir e Ativar Câmera
                        </button>
                    </div>
                </div>

                {{-- Unit Selection Dropdown --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Unidade Escolar de Atuação</label>
                    <select x-model="selectedUnitId" @change="recalculateDistance()"
                            class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition font-medium">
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" data-lat="{{ $unit->latitude }}" data-lon="{{ $unit->longitude }}" data-radius="{{ $unit->radius_meters ?: 300 }}">
                                {{ $unit->name }} ({{ $unit->city }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Record Type Selector Buttons --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Tipo de Registro do Ponto</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <button type="button" @click="recordType = 'entry_1'"
                                :class="recordType === 'entry_1' ? 'bg-emerald-600 text-white font-bold ring-2 ring-emerald-600 ring-offset-1' : 'bg-gray-100 text-gray-700 font-medium hover:bg-gray-200'"
                                class="rounded-xl px-3 py-2 text-xs transition shadow-2xs text-center">
                            1ª Entrada
                        </button>
                        <button type="button" @click="recordType = 'exit_1'"
                                :class="recordType === 'exit_1' ? 'bg-amber-600 text-white font-bold ring-2 ring-amber-600 ring-offset-1' : 'bg-gray-100 text-gray-700 font-medium hover:bg-gray-200'"
                                class="rounded-xl px-3 py-2 text-xs transition shadow-2xs text-center">
                            1ª Saída (Intervalo)
                        </button>
                        <button type="button" @click="recordType = 'entry_2'"
                                :class="recordType === 'entry_2' ? 'bg-emerald-600 text-white font-bold ring-2 ring-emerald-600 ring-offset-1' : 'bg-gray-100 text-gray-700 font-medium hover:bg-gray-200'"
                                class="rounded-xl px-3 py-2 text-xs transition shadow-2xs text-center">
                            2ª Entrada (Retorno)
                        </button>
                        <button type="button" @click="recordType = 'exit_2'"
                                :class="recordType === 'exit_2' ? 'bg-rose-600 text-white font-bold ring-2 ring-rose-600 ring-offset-1' : 'bg-gray-100 text-gray-700 font-medium hover:bg-gray-200'"
                                class="rounded-xl px-3 py-2 text-xs transition shadow-2xs text-center">
                            2ª Saída (Término)
                        </button>
                    </div>
                </div>

                {{-- Action Punch Button --}}
                <div>
                    <button type="button" @click="punch()" :disabled="submitting"
                            class="w-full flex items-center justify-center gap-3 rounded-2xl bg-indigo-600 hover:bg-indigo-500 active:scale-[0.99] text-white py-4 px-6 text-sm sm:text-base font-bold shadow-lg shadow-indigo-200 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="!submitting">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg> REGISTRAR PONTO COM RECONHECIMENTO FACIAL</span>
                            </span>
                        </template>
                        <template x-if="submitting">
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Processando biometria facial e GPS...</span>
                            </span>
                        </template>
                    </button>
                </div>
            </div>

            <!-- Right: Today's Timeline, Schedule & Reference (5 cols) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- User & Schedule Card -->
                <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-xs space-y-4">
                    <div class="flex items-center gap-3.5 pb-4 border-b border-gray-100">
                        @if($referencePhoto)
                            <img src="{{ $referencePhoto }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-2xl object-cover border-2 border-indigo-100 shadow-2xs">
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-base shadow-2xs">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-gray-900 text-sm truncate">{{ $user->name }}</h3>
                            <span class="inline-block rounded-md bg-indigo-50 border border-indigo-100 px-2 py-0.5 text-[10.5px] font-semibold text-indigo-700 mt-0.5">
                                {{ $user->role ?? ($user->roles->first()?->name ?? 'Docente / Colaborador') }}
                            </span>
                        </div>
                    </div>

                    <!-- Today's Work Schedule Details -->
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase block mb-2">Grade Programada para Hoje</span>
                        @if($todaySchedules->count() > 0)
                            <div class="space-y-2">
                                @foreach($todaySchedules as $sched)
                                <div class="p-3 bg-indigo-50/50 rounded-2xl border border-indigo-100 text-xs flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-indigo-950">{{ $sched->shift_name ?: 'Jornada Docente' }}</div>
                                        <div class="text-indigo-700 text-[11px] font-medium">{{ $sched->unit->name }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-indigo-900 font-mono">{{ $sched->formatted_schedule }}</div>
                                        <div class="text-[10px] text-gray-500 font-normal">Tolerância: {{ $sched->tolerance_minutes }}m</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-3 bg-gray-50 rounded-2xl border border-gray-200 text-xs text-gray-500 italic">
                                Nenhuma grade horária cadastrada para hoje. O ponto será registrado como horário extraordinário.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Today's Punches Timeline -->
                <div class="rounded-3xl border border-gray-200 bg-white p-5 shadow-xs space-y-4">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wide flex items-center justify-between">
                        <span>Batidas Registradas Hoje</span>
                        <span class="text-indigo-600 font-bold" x-text="todayRecords.length + ' registro(s)'"></span>
                    </h3>

                    <div class="space-y-2.5">
                        <template x-for="r in todayRecords" :key="r.id">
                            <div class="p-3 rounded-2xl border flex items-center justify-between"
                                 :class="r.status_badge || 'bg-gray-50 border-gray-200'">
                                <div class="flex items-center gap-2.5">
                                    <template x-if="r.photo_url">
                                        <img :src="r.photo_url" class="w-9 h-9 rounded-xl object-cover border border-white shadow-2xs">
                                    </template>
                                    <div>
                                        <div class="font-bold text-xs" x-text="r.type_label"></div>
                                        <div class="text-[10.5px] opacity-80" x-text="r.unit_name || 'Unidade'"></div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold font-mono text-xs" x-text="r.time"></div>
                                    <div class="text-[10px] font-semibold" x-text="r.status_label"></div>
                                </div>
                            </div>
                        </template>

                        <template x-if="todayRecords.length === 0">
                            <div class="py-6 text-center text-gray-400 text-xs">
                                <span class="text-2xl block mb-1"><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                                <span>Nenhum ponto registrado hoje.</span>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
        </div>

        <!-- Digital Receipt Modal -->
        <div x-show="receiptModal.show" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div @click="receiptModal.show = false" class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity"></div>

                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-md p-6 space-y-5">
                    <div class="text-center space-y-1">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl mx-auto shadow-xs">
                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Comprovante de Ponto Digital</h3>
                        <p class="text-xs text-gray-500">Registro de presença com reconhecimento facial concluído</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200 text-xs space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Colaborador:</span>
                            <span class="font-bold text-gray-900">{{ $user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Data / Hora:</span>
                            <span class="font-mono font-bold text-gray-900" x-text="receiptModal.data.date + ' às ' + receiptModal.data.time"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tipo da Batida:</span>
                            <span class="font-bold text-indigo-700" x-text="receiptModal.data.type_label"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Unidade Escolar:</span>
                            <span class="font-semibold text-gray-900" x-text="receiptModal.data.unit_name"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Autenticação Hash:</span>
                            <span class="font-mono text-[11px] font-bold text-gray-700" x-text="receiptModal.data.hash_receipt"></span>
                        </div>
                    </div>

                    <template x-if="receiptModal.data.photo_url">
                        <div class="text-center">
                            <span class="text-[11px] font-semibold text-gray-400 uppercase block mb-1">Snapshot Facial Gravado</span>
                            <img :src="receiptModal.data.photo_url" class="w-24 h-24 rounded-2xl object-cover mx-auto border-2 border-indigo-200 shadow-xs">
                        </div>
                    </template>

                    <button type="button" @click="receiptModal.show = false" class="w-full rounded-2xl bg-gray-900 text-white py-3 text-xs font-bold hover:bg-gray-800 transition shadow-xs">
                        Fechar Comprovante
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function timeClockApp() {
    return {
        currentTime: '',
        currentDate: '',
        recordType: '{{ $nextRecordType }}',
        selectedUnitId: '{{ $units->first()?->id ?? "" }}',
        submitting: false,
        cameraError: false,
        cameraErrorMessage: '',
        todayRecords: @json($formattedTodayRecords),
        gps: {
            status: 'loading',
            lat: null,
            lon: null,
            accuracy: null,
            distance: null,
            is_within: false,
        },
        receiptModal: {
            show: false,
            data: {}
        },

        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
            this.startCamera();
            this.getGPS();
        },

        updateClock() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('pt-BR');
            this.currentDate = now.toLocaleDateString('pt-BR', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
        },

        startCamera() {
            this.cameraError = false;
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                },
                audio: false
            })
            .then(stream => {
                this.stream = stream;
                this.$refs.video.srcObject = stream;
            })
            .catch(err => {
                this.cameraError = true;
                this.cameraErrorMessage = 'Permissão de câmera necessária para reconhecimento facial do ponto.';
            });
        },

        getGPS() {
            if (!navigator.geolocation) {
                this.gps.status = 'error';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                pos => {
                    this.gps.lat = pos.coords.latitude;
                    this.gps.lon = pos.coords.longitude;
                    this.gps.accuracy = pos.coords.accuracy;
                    this.gps.status = 'success';
                    this.recalculateDistance();
                },
                err => {
                    this.gps.status = 'error';
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        },

        recalculateDistance() {
            if (!this.gps.lat || !this.gps.lon) return;

            const select = document.querySelector('select[x-model="selectedUnitId"]');
            const selectedOpt = select ? select.selectedOptions[0] : null;

            if (selectedOpt && selectedOpt.dataset.lat && selectedOpt.dataset.lon) {
                const uLat = parseFloat(selectedOpt.dataset.lat);
                const uLon = parseFloat(selectedOpt.dataset.lon);
                const radius = parseInt(selectedOpt.dataset.radius || '300');

                // Haversine calculation in JS
                const dist = this.haversine(this.gps.lat, this.gps.lon, uLat, uLon);
                this.gps.distance = Math.round(dist);
                this.gps.is_within = this.gps.distance <= radius;
            }
        },

        haversine(lat1, lon1, lat2, lon2) {
            const R = 6371000;
            const toRad = deg => deg * Math.PI / 180;
            const dLat = toRad(lat2 - lat1);
            const dLon = toRad(lon2 - lon1);
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                      Math.sin(dLon/2) * Math.sin(dLon/2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        },

        punch() {
            if (this.submitting) return;

            // Capture frame from video element to canvas
            const video = this.$refs.video;
            const canvas = this.$refs.canvas;

            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const photoBase64 = canvas.toDataURL('image/jpeg', 0.85);

            this.submitting = true;

            fetch('{{ route('timeclock.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    unit_id: this.selectedUnitId,
                    record_type: this.recordType,
                    photo_base64: photoBase64,
                    latitude: this.gps.lat,
                    longitude: this.gps.lon,
                    accuracy: this.gps.accuracy,
                })
            })
            .then(res => res.json())
            .then(data => {
                this.submitting = false;
                if (data.success) {
                    this.todayRecords.unshift(data.record);
                    this.receiptModal.data = data.record;
                    this.receiptModal.show = true;

                    // Avançar sugestão do próximo tipo
                    if (this.recordType === 'entry_1') this.recordType = 'exit_1';
                    else if (this.recordType === 'exit_1') this.recordType = 'entry_2';
                    else if (this.recordType === 'entry_2') this.recordType = 'exit_2';
                } else {
                    alert(data.message || 'Erro ao registrar ponto.');
                }
            })
            .catch(err => {
                this.submitting = false;
                alert('Erro de comunicação ao registrar ponto.');
            });
        }
    }
}
</script>
@endsection
