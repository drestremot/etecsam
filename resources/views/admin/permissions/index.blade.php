@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10" x-data="permissionsMatrix()">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-extrabold">Políticas de Acesso</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>️ Matriz de Papéis & Permissões (RBAC)</span>
                    <span class="rounded-xl bg-indigo-100 border border-indigo-200 px-3 py-1 text-xs font-semibold text-indigo-700">
                        Acesso Granular por Grupo
                    </span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1 font-normal">
                    Defina com 1 clique exatamente quais botões, módulos e ações cada papel pode acessar no sistema
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <form action="{{ route('admin.permissions.reset-defaults') }}" method="POST" class="inline" onsubmit="return confirm('Deseja restaurar as permissões oficiais padrão para todos os papéis?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-white border border-gray-300 px-4 py-2.5 text-xs font-semibold text-gray-700 shadow-2xs transition hover:bg-gray-50 hover:text-gray-900">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Restaurar Padrões da Escola</span>
                    </button>
                </form>

                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Voltar ao Hub</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500 text-white p-4 text-xs sm:text-sm font-bold shadow-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Papéis Configurados</span>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 mt-1">{{ $stats['roles_count'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Permissões Granulares</span>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-indigo-700 mt-1">{{ $stats['permissions_count'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Módulos Cobertos</span>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-teal-700 mt-1">{{ $stats['modules_count'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Usuários Ativos</span>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-purple-700 mt-1">{{ $stats['users_count'] }}</div>
            </div>
        </div>

        <!-- Matrix Container -->
        <div class="space-y-6">
            @foreach($modules as $moduleName => $moduleData)
            <div class="rounded-2xl border border-gray-200 bg-white shadow-xs overflow-hidden">
                {{-- Module Header --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">{{ $moduleData['icon'] }}</span>
                        <div>
                            <h2 class="text-base font-bold text-gray-900">{{ $moduleName }}</h2>
                            <p class="text-xs text-gray-500 font-normal">{{ $moduleData['description'] }}</p>
                        </div>
                    </div>
                    <span class="rounded-xl bg-gray-200/80 px-2.5 py-1 text-xs font-semibold text-gray-700">
                        {{ count($moduleData['permissions']) }} permissões
                    </span>
                </div>

                {{-- Table for Module --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-gray-100/70 text-xs font-semibold uppercase text-gray-600 border-b border-gray-200 tracking-wider">
                            <tr>
                                <th class="px-5 py-2.5 w-1/3">Ação / Permissão</th>
                                @foreach($roles as $role)
                                <th class="px-3 py-2.5 text-center whitespace-nowrap">
                                    <span class="font-bold text-gray-800 text-xs">{{ $role->name }}</span>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($moduleData['permissions'] as $permName => $permInfo)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-5 py-2.5">
                                    <div class="font-bold text-gray-900 text-xs">{{ $permInfo['label'] }}</div>
                                    <div class="text-[10.5px] text-gray-500 font-normal leading-tight">{{ $permInfo['desc'] }}</div>
                                    <span class="inline-block font-mono text-[9.5px] text-gray-400">{{ $permName }}</span>
                                </td>

                                @foreach($roles as $role)
                                @php
                                    $hasPerm = $role->hasPermissionTo($permName);
                                    $isLocked = ($role->name === 'admin' && in_array($permName, ['permissions.manage', 'users.manage']));
                                @endphp
                                <td class="px-3 py-2.5 text-center">
                                    <div class="flex items-center justify-center">
                                        <button type="button"
                                                @click="togglePermission({{ $role->id }}, '{{ $permName }}', $event)"
                                                @if($isLocked) disabled title="Permissão obrigatória para o Administrador" @endif
                                                class="relative inline-flex h-4.5 w-8 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-1 focus:ring-indigo-400 {{ $isLocked ? 'opacity-70 cursor-not-allowed' : '' }}"
                                                :class="isGranted({{ $role->id }}, '{{ $permName }}', {{ $hasPerm ? 'true' : 'false' }}) ? 'bg-indigo-600' : 'bg-gray-300'"
                                                role="switch"
                                                :aria-checked="isGranted({{ $role->id }}, '{{ $permName }}', {{ $hasPerm ? 'true' : 'false' }})">
                                            <span class="sr-only">Permissão {{ $permName }} para {{ $role->name }}</span>
                                            <span aria-hidden="true"
                                                  class="pointer-events-none inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow-2xs ring-0 transition duration-200 ease-in-out"
                                                  :class="isGranted({{ $role->id }}, '{{ $permName }}', {{ $hasPerm ? 'true' : 'false' }}) ? 'translate-x-3.5' : 'translate-x-0'">
                                            </span>
                                        </button>
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Toast Notification Notification Popup -->
        <div x-show="toast.show" x-cloak
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed bottom-5 right-5 z-50 rounded-2xl bg-gray-900 text-white px-5 py-3.5 shadow-2xl flex items-center gap-3 border border-gray-700 text-xs font-semibold"
             style="display: none;">
            <span class="text-base" x-text="toast.icon"></span>
            <span x-text="toast.message"></span>
        </div>

    </div>
</div>

<script>
function permissionsMatrix() {
    return {
        matrix: {},
        toast: { show: false, message: '', icon: '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' },

        isGranted(roleId, permName, initialValue) {
            const key = roleId + '_' + permName;
            if (this.matrix[key] === undefined) {
                this.matrix[key] = initialValue;
            }
            return this.matrix[key];
        },

        togglePermission(roleId, permName, event) {
            const key = roleId + '_' + permName;
            const currentVal = this.matrix[key];
            const newVal = !currentVal;

            // Optimistic UI update
            this.matrix[key] = newVal;

            fetch('{{ route('admin.permissions.toggle') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    role_id: roleId,
                    permission_name: permName,
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.matrix[key] = data.has_permission;
                    this.showToast(data.message || 'Permissão atualizada!', data.has_permission ? '<span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500"></span>' : '<span class="inline-block w-2.5 h-2.5 rounded-full bg-gray-300"></span>');
                } else {
                    // Rollback
                    this.matrix[key] = currentVal;
                    alert(data.message || 'Erro ao alterar permissão.');
                }
            })
            .catch(err => {
                this.matrix[key] = currentVal;
                alert('Erro de conexão com o servidor.');
            });
        },

        showToast(msg, icon = '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>') {
            this.toast.message = msg;
            this.toast.icon = icon;
            this.toast.show = true;
            setTimeout(() => {
                this.toast.show = false;
            }, 2500);
        }
    }
}
</script>
@endsection

