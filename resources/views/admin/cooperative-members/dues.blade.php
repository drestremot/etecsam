@extends('admin.layouts.app')
@section('page-title', 'Mensalidades de ' . $cooperativeMember->name)

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('admin.cooperative-members.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition">
            ← Voltar para lista de Cooperados
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">{{ $cooperativeMember->name }}</h2>
                @if($cooperativeMember->registration_number)
                <p class="text-xs text-gray-400 mt-0.5">Matrícula: {{ $cooperativeMember->registration_number }}</p>
                @endif
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $cooperativeMember->isUpToDate() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $cooperativeMember->isUpToDate() ? 'Em dia' : 'Pendente' }}
            </span>
        </div>

        @if($monthlyFees->isEmpty())
        <div class="p-8 text-center text-gray-400 text-sm">
            Nenhum mês de mensalidade cadastrado ainda.
            <a href="{{ route('admin.cooperative-monthly-fees.create') }}" class="text-indigo-600 hover:underline">Cadastrar valores</a>.
        </div>
        @else
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Mês</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Valor</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Situação</th>
                    <th class="px-4 py-3 w-32"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($monthlyFees as $fee)
                @php $isPaid = (bool) ($dues[$fee->id] ?? false); @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $fee->month->translatedFormat('F \d\e Y') }}</td>
                    <td class="px-4 py-3 text-gray-600">R$ {{ number_format($fee->amount, 2, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $isPaid ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $isPaid ? 'Pago' : 'Pendente' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <form action="{{ route('admin.cooperative-members.dues.toggle', [$cooperativeMember, $fee]) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $isPaid ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'bg-green-600 text-white hover:bg-green-700' }}">
                                {{ $isPaid ? 'Marcar pendente' : 'Marcar pago' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
