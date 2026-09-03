@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-4xl mx-auto space-y-5">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <a href="{{ route('admin.cooperative-dashboard') }}" class="hover:text-amber-700 transition">Cooperativa Escola</a>
                    <span>/</span>
                    <a href="{{ route('admin.cooperative-members.index') }}" class="hover:text-teal-700 transition">Cooperados</a>
                    <span>/</span>
                    <span class="text-teal-700 font-bold">Mensalidades</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-teal-600 text-white shadow-sm">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        Mensalidades: {{ $cooperativeMember->name }}
                    </span>
                </h1>
                <p class="text-xs text-gray-600 mt-0.5 font-normal">
                    Controle de quitação e histórico das taxas mensais de associação
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.cooperative-members.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-teal-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-teal-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Quadro de Cooperados</span>
                </a>

                <a href="{{ route('admin.cooperative-dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-amber-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-amber-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Painel Financeiro</span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-900 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Hub</span>
                </a>
            </div>
        </div>

        {{-- Banners de Feedback --}}
        @if(session('success'))
            <div class="rounded-xl bg-emerald-500 text-white px-4 py-3 text-xs font-semibold shadow-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-xl bg-red-600 text-white px-4 py-3 text-xs font-semibold shadow-sm flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        {{-- Card do Membro --}}
        <div class="rounded-2xl border border-teal-200 bg-white p-5 shadow-xs">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    @php
                        $photoSrc = $cooperativeMember->photo ? Storage::url($cooperativeMember->photo) : null;
                        $initials = strtoupper(substr($cooperativeMember->name, 0, 1));
                    @endphp
                    @if($photoSrc)
                        <img src="{{ $photoSrc }}" alt="{{ $cooperativeMember->name }}" class="h-12 w-12 rounded-full object-cover border-2 border-teal-200 shadow-2xs">
                    @else
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-teal-500 to-teal-700 text-white font-bold text-sm shadow-2xs">
                            {{ $initials }}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-base font-bold text-gray-900">{{ $cooperativeMember->name }}</h2>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                            @if($cooperativeMember->registration_number)
                                <span>RA: <strong>{{ $cooperativeMember->registration_number }}</strong></span>
                                <span>•</span>
                            @endif
                            <span>{{ $cooperativeMember->phone ?? 'Sem telefone' }}</span>
                            @if($cooperativeMember->email)
                                <span>•</span>
                                <span>{{ $cooperativeMember->email }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    @if(!$cooperativeMember->is_active)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                            Inativo — Sem cobrança
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $cooperativeMember->isUpToDate() ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-200' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $cooperativeMember->isUpToDate() ? 'bg-emerald-600' : 'bg-rose-600' }}"></span>
                            {{ $cooperativeMember->isUpToDate() ? 'Mensalidades em Dia' : 'Mensalidades Pendentes' }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tabela de Mensalidades --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700">Histórico de Cobranças Mensais</h3>
                <span class="text-xs text-gray-500">{{ count($monthlyFees) }} competências registradas</span>
            </div>

            @if($monthlyFees->isEmpty())
                <div class="py-12 text-center text-gray-400">
                    <p class="text-sm font-semibold text-gray-700">Nenhum mês de mensalidade configurado no sistema.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-xs text-gray-700">
                        <thead class="bg-gray-50 text-gray-600 font-semibold uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-4 py-3">Mês / Competência</th>
                                <th class="px-4 py-3">Valor da Mensalidade</th>
                                <th class="px-4 py-3 text-center">Situação</th>
                                <th class="px-4 py-3 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($monthlyFees as $fee)
                                @php $isPaid = (bool) ($dues[$fee->id] ?? false); @endphp
                                <tr class="hover:bg-teal-50/30 transition">
                                    <td class="px-4 py-3.5 font-bold text-gray-900 text-sm">
                                        {{ $fee->month->translatedFormat('F \d\e Y') }}
                                    </td>
                                    <td class="px-4 py-3.5 font-semibold text-gray-700 text-sm">
                                        R$ {{ number_format($fee->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        @if($isPaid)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Pago
                                            </span>
                                        @elseif(!$cooperativeMember->is_active)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                Isento (Inativo)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Pendente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-right">
                                        @if($cooperativeMember->is_active || $isPaid)
                                            <form action="{{ route('admin.cooperative-members.dues.toggle', [$cooperativeMember, $fee]) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-2xs
                                                        {{ $isPaid ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-emerald-600 text-white hover:bg-emerald-500' }}">
                                                    @if($isPaid)
                                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        <span>Marcar como Pendente</span>
                                                    @else
                                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        <span>Dar Baixa (Pago)</span>
                                                    @endif
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
