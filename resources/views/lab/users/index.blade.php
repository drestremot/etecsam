@extends('admin.layouts.app')
@section('title', 'Usuários do Laboratório')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Usuários</h1>
    </div>

    @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">{{ session('error') }}</div>@endif

    {{-- Formulário de cadastro --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6"
         x-data="{
             teacher: '',
             teachers: {{ \Illuminate\Support\Js::from($teachers->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'email' => $t->email ?? '', 'reg' => $t->registration_number ?? ''])) }},
             fillFromTeacher() {
                 const t = this.teachers.find(t => t.id == this.teacher);
                 if (t) {
                     this.$refs.name.value  = t.name;
                     this.$refs.email.value = t.email;
                     this.$refs.reg.value   = t.reg;
                 }
             }
         }">
        <h2 class="font-bold text-gray-900 dark:text-white mb-4">Cadastrar usuário</h2>

        <form action="{{ route('lab.users.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Vincular a professor/funcionário existente --}}
            @if($teachers->count())
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                    Vincular a professor/funcionário já cadastrado (opcional)
                </label>
                <select x-model="teacher" @change="fillFromTeacher()"
                        class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                    <option value="">— Preencher manualmente —</option>
                    @foreach($teachers as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}{{ $t->role ? ' ('.$t->role.')' : '' }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Selecionar preenche nome e e-mail automaticamente</p>
            </div>
            <div class="border-t border-gray-100 dark:border-gray-700 pt-4"></div>
            @endif

            {{-- Campos principais --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Nome *</label>
                    <input x-ref="name" type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Nome completo"
                           class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">E-mail *</label>
                    <input x-ref="email" type="email" name="email" value="{{ old('email') }}" required
                           placeholder="email@escola.com"
                           class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Matrícula</label>
                    <input x-ref="reg" type="text" name="registration_number" value="{{ old('registration_number') }}"
                           placeholder="Ex: 12345"
                           class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Papel *</label>
                    <select name="role" required class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                        <option value="">Selecione...</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Senha --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">
                        Senha <span class="text-gray-400 font-normal">(deixe em branco para usar etec1234)</span>
                    </label>
                    <input type="password" name="password"
                           placeholder="Mínimo 6 caracteres"
                           class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Confirmar senha</label>
                    <input type="password" name="password_confirmation"
                           placeholder="Repita a senha"
                           class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-5 py-2.5 bg-etec-dark text-white text-sm font-semibold rounded-lg hover:bg-etec-main transition">
                    Cadastrar usuário
                </button>
            </div>
        </form>
    </div>

    {{-- Tabela de usuários --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-bold text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Nome</th>
                    <th class="px-6 py-3 text-left">E-mail</th>
                    <th class="px-6 py-3 text-left">Papel</th>
                    <th class="px-6 py-3 text-left">Vínculos</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($users as $u)
                @php $teacher = \App\Models\Teacher::where('email', $u->email)->first(); @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2.5">
                            @if($teacher?->photo)
                                <img src="{{ photo_url($teacher->photo) }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="w-8 h-8 rounded-full bg-etec-dark text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white truncate">{{ $u->name }}</p>
                                @if($teacher)
                                    <p class="text-xs text-etec-main dark:text-etec-accent truncate">{{ $teacher->role }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-gray-500 dark:text-gray-400 text-xs">{{ $u->email }}</td>
                    <td class="px-6 py-3">
                        <form action="{{ route('lab.users.role', $u) }}" method="POST" class="flex gap-2 items-center">
                            @csrf @method('PATCH')
                            <select name="role" class="border border-gray-200 dark:border-gray-600 rounded px-2 py-1 text-xs dark:bg-gray-700 dark:text-white focus:ring-1 focus:ring-etec-dark outline-none">
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $u->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <button class="text-xs text-etec-main dark:text-etec-accent hover:underline font-semibold">OK</button>
                        </form>
                    </td>
                    <td class="px-6 py-3">
                        @if($u->hasRole('Auxiliar'))
                        <form action="{{ route('lab.users.vinculos', $u) }}" method="POST" class="flex gap-2 items-center">
                            @csrf @method('PATCH')
                            <select name="coordenador_ids[]" multiple size="3" class="border border-gray-200 dark:border-gray-600 rounded px-2 py-1 text-xs dark:bg-gray-700 dark:text-white focus:ring-1 focus:ring-etec-dark outline-none min-w-[10rem]">
                                @foreach($coordenadoresList as $c)
                                <option value="{{ $c->id }}" {{ $u->coordenadoresVinculados->contains('id', $c->id) ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            <button class="text-xs text-etec-main dark:text-etec-accent hover:underline font-semibold">OK</button>
                        </form>
                        @else
                        <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-center">
                        <form action="{{ route('lab.users.status', $u) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="px-2.5 py-1 rounded-full text-xs font-bold {{ $u->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $u->is_active ? 'Ativo' : 'Inativo' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex justify-end gap-3 items-center">
                            {{-- Enviar link de redefinição --}}
                            <form action="{{ route('lab.users.reset-link', $u) }}" method="POST"
                                  title="Enviar e-mail de redefinição de senha">
                                @csrf
                                <button class="text-xs text-gray-400 hover:text-etec-main dark:hover:text-etec-accent transition" title="Enviar link de senha">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </button>
                            </form>
                            @if($u->id !== auth()->id())
                            <form action="{{ route('lab.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Excluir {{ addslashes($u->name) }}?')">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-600 text-xs font-semibold">Excluir</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Nenhum usuário encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
