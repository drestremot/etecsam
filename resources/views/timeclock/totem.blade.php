<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Totem de Ponto Institucional | Etec SAM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-950 text-white min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white" x-data="totemApp()">

    <!-- Top Header Bar -->
    <header class="bg-gray-900/90 border-b border-gray-800 px-6 py-4 flex items-center justify-between backdrop-blur-md">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-xl text-white shadow-lg shadow-indigo-600/30">
                E
            </div>
            <div>
                <h1 class="font-bold text-base sm:text-lg tracking-tight">Totem de Ponto Escolar</h1>
                <p class="text-xs text-indigo-400 font-medium" x-text="selectedUnitName"></p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <div class="text-right">
                <div class="text-xl sm:text-2xl font-bold font-mono text-white tracking-widest" x-text="currentTime">--:--:--</div>
                <div class="text-xs text-gray-400" x-text="currentDate"></div>
            </div>

            <a href="{{ route('timeclock.index') }}" class="rounded-xl bg-gray-800 border border-gray-700 px-3.5 py-2 text-xs font-semibold text-gray-300 hover:bg-gray-700 transition">
                Sair do Totem
            </a>
        </div>
    </header>

    <!-- Main Kiosk Body -->
    <main class="flex-1 max-w-6xl w-full mx-auto p-4 sm:p-8 grid grid-cols-1 md:grid-cols-12 gap-8 items-center">

        <!-- Left: Live Camera Viewfinder (6 cols) -->
        <div class="md:col-span-6 flex flex-col items-center justify-center">
            <div class="relative w-full max-w-md aspect-[4/3] rounded-3xl overflow-hidden bg-gray-900 border-2 border-indigo-500/50 shadow-2xl shadow-indigo-950 flex items-center justify-center">
                <video x-ref="video" autoplay playsinline muted class="w-full h-full object-cover transform -scale-x-100"></video>
                <canvas x-ref="canvas" class="hidden"></canvas>

                <!-- Face guide overlay -->
                <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                    <div class="w-48 h-60 rounded-[50%] border-2 border-dashed border-indigo-400/90 shadow-[0_0_0_9999px_rgba(3,7,18,0.5)] flex flex-col items-center justify-between p-4">
                        <span class="text-[10px] font-bold text-white bg-indigo-600 px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                            Olhe para a Câmera
                        </span>
                        <div class="w-full h-0.5 bg-gradient-to-r from-transparent via-emerald-400 to-transparent animate-pulse"></div>
                        <span class="text-[10px] font-semibold text-gray-300 bg-black/70 px-2 py-0.5 rounded-full">
                            Totem Ativo
                        </span>
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3 text-center">Posicione seu rosto dentro da moldura para validação biométrica</p>
        </div>

        <!-- Right: Employee Selection & Punch Controls (6 cols) -->
        <div class="md:col-span-6 bg-gray-900/80 border border-gray-800 rounded-3xl p-6 sm:p-8 space-y-5 shadow-2xl backdrop-blur-md">

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Selecione a Unidade</label>
                <select x-model="selectedUnitId" @change="updateUnitName()"
                        class="w-full rounded-2xl border border-gray-700 bg-gray-800 px-4 py-3 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->city }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Localize seu Nome ou Matrícula</label>
                <input type="text" x-model="searchQuery" placeholder="Digite seu nome..."
                       class="w-full rounded-2xl border border-gray-700 bg-gray-800 px-4 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">

                <!-- Filtered Employee Dropdown list -->
                <div class="max-h-40 overflow-y-auto mt-2 space-y-1.5 pr-1">
                    <template x-for="emp in filteredEmployees" :key="emp.id">
                        <button type="button" @click="selectedUser = emp"
                                :class="selectedUser?.id === emp.id ? 'bg-indigo-600 text-white font-bold ring-2 ring-indigo-400' : 'bg-gray-800/80 text-gray-300 hover:bg-gray-750 font-medium'"
                                class="w-full text-left px-3.5 py-2.5 rounded-xl text-xs flex items-center justify-between transition">
                            <span x-text="emp.name"></span>
                            <span class="text-[10px] text-gray-400" x-text="emp.role || 'Docente'"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Punch Type Selection -->
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tipo de Registro</label>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="recordType = 'entry_1'"
                            :class="recordType === 'entry_1' ? 'bg-emerald-600 text-white font-bold ring-2 ring-emerald-400' : 'bg-gray-800 text-gray-400'"
                            class="py-2.5 px-3 rounded-xl text-xs transition">1ª Entrada</button>
                    <button type="button" @click="recordType = 'exit_1'"
                            :class="recordType === 'exit_1' ? 'bg-amber-600 text-white font-bold ring-2 ring-amber-400' : 'bg-gray-800 text-gray-400'"
                            class="py-2.5 px-3 rounded-xl text-xs transition">1ª Saída</button>
                    <button type="button" @click="recordType = 'entry_2'"
                            :class="recordType === 'entry_2' ? 'bg-emerald-600 text-white font-bold ring-2 ring-emerald-400' : 'bg-gray-800 text-gray-400'"
                            class="py-2.5 px-3 rounded-xl text-xs transition">2ª Entrada</button>
                    <button type="button" @click="recordType = 'exit_2'"
                            :class="recordType === 'exit_2' ? 'bg-rose-600 text-white font-bold ring-2 ring-rose-400' : 'bg-gray-800 text-gray-400'"
                            class="py-2.5 px-3 rounded-xl text-xs transition">2ª Saída</button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="button" @click="punchTotem()" :disabled="!selectedUser || submitting"
                    class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-500 active:scale-[0.99] text-white font-bold text-sm sm:text-base shadow-xl shadow-indigo-900/50 transition disabled:opacity-40 disabled:cursor-not-allowed">
                <template x-if="!submitting">
                    <span x-text="selectedUser ? '📸 CONFIRMAR PONTO DE ' + selectedUser.name.split(' ')[0].toUpperCase() : 'SELECIONE SEU NOME ACIMA'"></span>
                </template>
                <template x-if="submitting">
                    <span>Validando biometria no Totem...</span>
                </template>
            </button>

        </div>
    </main>

    <!-- Footer Info -->
    <footer class="bg-gray-900/60 border-t border-gray-800/80 px-6 py-3 text-center text-xs text-gray-500">
        Etec SAM • Sistema de Ponto Eletrônico & Gestão Integrada • Totem Kiosk v2.0
    </footer>

    <!-- Success Modal Popup -->
    <div x-show="successModal.show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" style="display: none;">
        <div class="bg-gray-900 border border-gray-800 rounded-3xl p-8 max-w-sm w-full text-center space-y-4 shadow-2xl animate-bounce-short">
            <div class="w-16 h-16 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-3xl mx-auto border border-emerald-500/30">
                ✓
            </div>
            <h3 class="text-xl font-bold text-white">Ponto Confirmado!</h3>
            <p class="text-xs text-gray-300" x-text="successModal.message"></p>
            <div class="text-xs text-indigo-400 font-mono" x-text="'Horário: ' + successModal.time"></div>
            <button type="button" @click="successModal.show = false" class="w-full rounded-xl bg-gray-800 text-white py-2.5 text-xs font-bold hover:bg-gray-700 transition">
                Concluir (Próximo)
            </button>
        </div>
    </div>

    <script>
    function totemApp() {
        return {
            currentTime: '',
            currentDate: '',
            selectedUnitId: '{{ $selectedUnit->id ?? "" }}',
            selectedUnitName: '{{ $selectedUnit->name ?? "" }}',
            searchQuery: '',
            selectedUser: null,
            recordType: 'entry_1',
            submitting: false,
            employees: @json($employees),
            successModal: { show: false, message: '', time: '' },

            init() {
                this.updateClock();
                setInterval(() => this.updateClock(), 1000);
                this.startCamera();
            },

            updateClock() {
                const now = new Date();
                this.currentTime = now.toLocaleTimeString('pt-BR');
                this.currentDate = now.toLocaleDateString('pt-BR', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
            },

            updateUnitName() {
                const select = document.querySelector('select[x-model="selectedUnitId"]');
                if (select) this.selectedUnitName = select.selectedOptions[0].text;
            },

            get filteredEmployees() {
                if (!this.searchQuery) return this.employees.slice(0, 10);
                const q = this.searchQuery.toLowerCase();
                return this.employees.filter(e => e.name.toLowerCase().includes(q) || (e.registration_number && e.registration_number.includes(q))).slice(0, 10);
            },

            startCamera() {
                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
                    audio: false
                })
                .then(stream => { this.$refs.video.srcObject = stream; })
                .catch(err => { console.error('Erro ao acessar webcam do Totem:', err); });
            },

            punchTotem() {
                if (!this.selectedUser || this.submitting) return;

                const video = this.$refs.video;
                const canvas = this.$refs.canvas;
                canvas.width = video.videoWidth || 640;
                canvas.height = video.videoHeight || 480;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                const photoBase64 = canvas.toDataURL('image/jpeg', 0.85);

                this.submitting = true;

                fetch('{{ route('timeclock.totem.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        unit_id: this.selectedUnitId,
                        user_id: this.selectedUser.id,
                        record_type: this.recordType,
                        photo_base64: photoBase64,
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.submitting = false;
                    if (data.success) {
                        this.successModal.message = data.message;
                        this.successModal.time = data.record.time;
                        this.successModal.show = true;
                        this.selectedUser = null;
                        this.searchQuery = '';
                        setTimeout(() => { this.successModal.show = false; }, 3500);
                    } else {
                        alert(data.message || 'Erro ao registrar no Totem.');
                    }
                })
                .catch(err => {
                    this.submitting = false;
                    alert('Erro de conexão ao registrar no Totem.');
                });
            }
        }
    }
    </script>
</body>
</html>

