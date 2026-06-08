@extends('admin.layouts.app')
@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Parceiros</h1>
            <p class="text-sm text-gray-500 mt-1">Gerencie os parceiros exibidos no rodape do site</p>
        </div>
        <a href="{{ route('admin.partners.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Parceiro
        </a>
    </div>
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
    @endif
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Logo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Site</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($partners as $partner)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        @if($partner->logo)
                            <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" class="h-10 w-auto object-contain">
                        @else
                            <div class="h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-sm">{{ strtoupper(substr($partner->name,0,2)) }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $partner->name }}</td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        @if($partner->website)
                            <a href="{{ $partner->website }}" target="_blank" class="text-blue-600 hover:underline text-xs">{{ $partner->website }}</a>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $partner->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $partner->active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2 justify-end">
                            <a href="{{ route('admin.partners.edit', $partner) }}" class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-xs hover:bg-blue-100">Editar</a>
                            <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" onsubmit="return confirm('Remover parceiro?')">
                                @csrf @method('DELETE')
                                <button class="bg-red-50 text-red-700 px-3 py-1.5 rounded-lg text-xs hover:bg-red-100">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                        Nenhum parceiro cadastrado.
                        <a href="{{ route('admin.partners.create') }}" class="text-indigo-600 hover:underline ml-1">Adicionar parceiro</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
