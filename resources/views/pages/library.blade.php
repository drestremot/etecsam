@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-8 h-8 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div>
            <h1 class="text-3xl font-bold mb-1">Biblioteca Ativa</h1>
            <p class="text-gray-300">Centro de Memória e Pesquisa — Etec Sebastiana Augusta de Moraes</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12 grid lg:grid-cols-3 gap-10">

    <aside class="space-y-6">

        {{-- Bibliotecário --}}
        <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 overflow-hidden text-center">
            <div class="h-16 bg-gradient-to-r from-etec-dark to-etec-medium"></div>
            <div class="px-6 pt-0 pb-6 relative">
                <div class="relative hover:z-20 w-[92px] h-[92px] mx-auto -mt-[46px] bg-white rounded-full p-1 shadow-lg border-2 border-etec-light">
                    <img src="{{ asset('imagens/equipe/estremote.jpg') }}"
                         onerror="this.src='https://ui-avatars.com/api/?name=Esther+Martins&background=2d5a27&color=fff'"
                         class="w-full h-full object-cover rounded-full scale-[1.15] hover:scale-[1.4375] transition duration-700 ease-in-out">
                </div>
                <h3 class="text-lg font-bold text-white mt-3">Esther do Nascimento Martins</h3>
                <span class="text-xs text-etec-light font-bold uppercase tracking-wider">Bibliotecária</span>

                <div class="mt-5 text-left space-y-3 text-sm bg-white/10 p-4 rounded-xl">
                    <div class="flex items-center gap-2.5 text-blue-100">
                        <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                        <span>(18) 3722-XXXX</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:biblioteca@etec.sp.gov.br" class="text-white hover:text-etec-accent hover:underline text-xs">biblioteca@etec.sp.gov.br</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Horários --}}
        <div class="bg-etec-dark text-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 flex items-center gap-2">
                <svg class="w-5 h-5 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="font-bold text-lg">Horário de Atendimento</h3>
            </div>
            <div class="p-6">
                <ul class="space-y-3 text-sm">
                    <li class="flex justify-between items-center border-b border-white/10 pb-3">
                        <span class="text-gray-300">Segunda a Sexta</span>
                        <span class="font-bold text-etec-accent">08h às 22h</span>
                    </li>
                    <li class="flex justify-between items-center border-b border-white/10 pb-3">
                        <span class="text-gray-300">Intervalos</span>
                        <span class="font-semibold text-green-400">Aberto</span>
                    </li>
                    <li class="flex justify-between items-center">
                        <span class="text-gray-300">Sábados / Feriados</span>
                        <span class="font-semibold text-red-400">Fechado</span>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <div class="lg:col-span-2 space-y-10">

        {{-- Bases de pesquisa --}}
        <div>
            <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-medium pl-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-etec-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Bases de Dados para Pesquisa
            </h2>
            <div class="grid md:grid-cols-2 gap-4">
                <a href="https://www.periodicos.capes.gov.br/" target="_blank"
                   class="flex items-center gap-4 p-4 bg-white/50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl hover:shadow-md hover:border-blue-400 transition group">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <div>
                        <strong class="block text-etec-dark dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-400 transition">Portal CAPES</strong>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Periódicos científicos</span>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-500 ml-auto group-hover:text-blue-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <a href="https://scielo.org/" target="_blank"
                   class="flex items-center gap-4 p-4 bg-white/50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl hover:shadow-md hover:border-orange-400 transition group">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <div>
                        <strong class="block text-etec-dark dark:text-white group-hover:text-orange-700 dark:group-hover:text-orange-400 transition">SciELO</strong>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Biblioteca Eletrônica</span>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-500 ml-auto group-hover:text-orange-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <a href="https://scholar.google.com.br/" target="_blank"
                   class="flex items-center gap-4 p-4 bg-white/50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl hover:shadow-md hover:border-blue-300 transition group">
                    <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center flex-shrink-0 font-bold text-lg">
                        G
                    </div>
                    <div>
                        <strong class="block text-etec-dark dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">Google Acadêmico</strong>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Pesquisa ampla e gratuita</span>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-500 ml-auto group-hover:text-blue-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <a href="http://www.dominiopublico.gov.br/" target="_blank"
                   class="flex items-center gap-4 p-4 bg-white/50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl hover:shadow-md hover:border-green-400 transition group">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <strong class="block text-etec-dark dark:text-white group-hover:text-green-700 dark:group-hover:text-green-400 transition">Domínio Público</strong>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Obras literárias gratuitas</span>
                    </div>
                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-500 ml-auto group-hover:text-green-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
        </div>

        {{-- Documentos --}}
        <div>
            <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-accent pl-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                Normas, Manuais e TCC
            </h2>

            @if($documents->isEmpty())
                <div class="py-10 text-center bg-white/50 dark:bg-white/5 rounded-xl border border-dashed border-gray-200 dark:border-white/10">
                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhum documento disponível no momento.</p>
                </div>
            @else
                <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 divide-y divide-white/10">
                    @foreach($documents as $doc)
                    <div class="p-4 flex items-center gap-4 hover:bg-white/10 transition group">
                        <div class="w-10 h-10 bg-white/10 text-etec-accent rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-etec-accent/30 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="flex-grow min-w-0">
                            <h4 class="font-bold text-white text-sm leading-tight">{{ $doc->title }}</h4>
                            <span class="text-xs text-blue-200/70">Atualizado em {{ \Carbon\Carbon::parse($doc->published_at)->format('d/m/Y') }}</span>
                        </div>
                        <a href="{{ $doc->file_path }}"
                           class="flex-shrink-0 inline-flex items-center gap-1.5 bg-white/10 text-etec-accent px-4 py-2 rounded-lg text-xs font-bold hover:bg-etec-accent hover:text-etec-dark transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Baixar
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
