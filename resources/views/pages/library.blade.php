@extends('layouts.app')

@section('content')

<x-page-header compact title="Biblioteca Ativa" subtitle="Centro de Memória e Pesquisa — Etec Sebastiana Augusta de Moraes">
    <x-slot:icon>
        <svg class="w-8 h-8 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
    </x-slot:icon>
</x-page-header>

<div class="bg-[#0b172a] text-white py-14" style="background-color: #0b172a; color: #ffffff;">
    <div class="container mx-auto px-4 grid lg:grid-cols-3 gap-10">

        <aside class="space-y-6">

            {{-- Bibliotecário --}}
            <div class="bg-[#14284b] rounded-3xl shadow-sm border border-white/10 overflow-hidden text-center">
                <div class="h-16 bg-gradient-to-r from-[#0c1b33] to-[#16325c]"></div>
                <div class="px-6 pt-0 pb-6 relative">
                    <div class="relative hover:z-20 w-[92px] h-[92px] mx-auto -mt-[46px] bg-[#0c1b33] rounded-full p-1 shadow-lg border-2 border-amber-400">
                        <img src="{{ asset('imagens/equipe/estremote.jpg') }}"
                             onerror="this.src='https://ui-avatars.com/api/?name=Esther+Martins&background=0f223f&color=fff'"
                             class="w-full h-full object-cover rounded-full scale-[1.15] hover:scale-[1.4375] transition duration-700 ease-in-out">
                    </div>
                    <h3 class="text-lg font-bold text-white mt-3">Esther do Nascimento Martins</h3>
                    <span class="text-xs text-amber-300 font-bold uppercase tracking-wider">Bibliotecária</span>

                    <div class="mt-5 text-left space-y-3 text-sm bg-[#0f223f] border border-white/10 p-4 rounded-2xl">
                        <div class="flex items-center gap-2.5 text-slate-200">
                            <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                            <span>(18) 3702-6850</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:biblioteca@etec.sp.gov.br" class="text-slate-200 hover:text-amber-300 hover:underline text-xs">biblioteca@etec.sp.gov.br</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Horários --}}
            <div class="bg-[#14284b] text-white rounded-3xl shadow-sm border border-white/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 bg-[#0f223f] flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="font-bold text-base text-white">Horário de Atendimento</h3>
                </div>
                <div class="p-6">
                    <ul class="space-y-3 text-sm">
                        <li class="flex justify-between items-center border-b border-white/10 pb-3">
                            <span class="text-slate-300">Segunda a Sexta</span>
                            <span class="font-bold text-amber-300">08h às 22h</span>
                        </li>
                        <li class="flex justify-between items-center border-b border-white/10 pb-3">
                            <span class="text-slate-300">Intervalos</span>
                            <span class="font-semibold text-emerald-400">Aberto</span>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-slate-300">Sábados / Feriados</span>
                            <span class="font-semibold text-rose-400">Fechado</span>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <div class="lg:col-span-2 space-y-10">

            {{-- Bases de pesquisa --}}
            <div>
                <h2 class="text-xl font-extrabold text-white mb-6 flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <span>Bases de Dados para Pesquisa</span>
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <a href="https://www.periodicos.capes.gov.br/" target="_blank"
                       class="flex items-center gap-4 p-4 bg-[#14284b] hover:bg-[#1a335f] border border-white/10 rounded-2xl hover:border-amber-400/50 transition group">
                        <div class="w-12 h-12 bg-amber-400/15 text-amber-300 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        </div>
                        <div>
                            <strong class="block text-white group-hover:text-amber-300 transition text-sm">Portal CAPES</strong>
                            <span class="text-xs text-slate-300">Periódicos científicos</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 ml-auto group-hover:text-amber-300 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>

                    <a href="https://scielo.org/" target="_blank"
                       class="flex items-center gap-4 p-4 bg-[#14284b] hover:bg-[#1a335f] border border-white/10 rounded-2xl hover:border-amber-400/50 transition group">
                        <div class="w-12 h-12 bg-amber-400/15 text-amber-300 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <div>
                            <strong class="block text-white group-hover:text-amber-300 transition text-sm">SciELO</strong>
                            <span class="text-xs text-slate-300">Biblioteca Eletrônica</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 ml-auto group-hover:text-amber-300 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>

                    <a href="https://scholar.google.com.br/" target="_blank"
                       class="flex items-center gap-4 p-4 bg-[#14284b] hover:bg-[#1a335f] border border-white/10 rounded-2xl hover:border-amber-400/50 transition group">
                        <div class="w-12 h-12 bg-amber-400/15 text-amber-300 rounded-xl flex items-center justify-center flex-shrink-0 font-bold text-lg">
                            G
                        </div>
                        <div>
                            <strong class="block text-white group-hover:text-amber-300 transition text-sm">Google Acadêmico</strong>
                            <span class="text-xs text-slate-300">Pesquisa ampla e gratuita</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 ml-auto group-hover:text-amber-300 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>

                    <a href="http://www.dominiopublico.gov.br/" target="_blank"
                       class="flex items-center gap-4 p-4 bg-[#14284b] hover:bg-[#1a335f] border border-white/10 rounded-2xl hover:border-amber-400/50 transition group">
                        <div class="w-12 h-12 bg-amber-400/15 text-amber-300 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <strong class="block text-white group-hover:text-amber-300 transition text-sm">Domínio Público</strong>
                            <span class="text-xs text-slate-300">Obras literárias gratuitas</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 ml-auto group-hover:text-amber-300 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>

            {{-- Documentos --}}
            <div>
                <h2 class="text-xl font-extrabold text-white mb-6 flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <span>Normas, Manuais e TCC</span>
                </h2>

                @if($documents->isEmpty())
                    <div class="py-12 text-center bg-[#14284b] rounded-2xl border border-dashed border-white/15 space-y-3">
                        <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-slate-300 text-sm font-medium">Nenhum documento publicado ainda.</p>
                        <p class="text-slate-400 text-xs max-w-xs mx-auto leading-relaxed">
                            Normas de TCC, manuais de pesquisa e regulamentos serão disponibilizados aqui em breve.
                        </p>
                    </div>
                @else
                    <div class="bg-[#14284b] rounded-3xl shadow-sm border border-white/10 divide-y divide-white/10">
                        @foreach($documents as $doc)
                        <div class="p-4 flex items-center gap-4 hover:bg-white/5 transition group">
                            <div class="w-10 h-10 bg-amber-400/15 text-amber-300 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="flex-grow min-w-0">
                                <h4 class="font-bold text-white text-sm leading-tight">{{ $doc->title }}</h4>
                                <span class="text-xs text-slate-300">Atualizado em {{ \Carbon\Carbon::parse($doc->published_at)->format('d/m/Y') }}</span>
                            </div>
                            <a href="{{ $doc->file_path }}"
                               class="flex-shrink-0 inline-flex items-center gap-1.5 bg-amber-400/15 text-amber-300 px-4 py-2 rounded-xl text-xs font-bold hover:bg-amber-400 hover:text-slate-950 transition border border-amber-400/25">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>Baixar</span>
                            </a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
