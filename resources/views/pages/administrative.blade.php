@extends('layouts.app')

@section('content')

{{-- Hero --}}
<div class="bg-etec-dark text-white py-14 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-8 h-8 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-etec-accent text-xs font-bold uppercase tracking-widest mb-1">Gestão Escolar</p>
            <h1 class="text-3xl font-bold mb-1">Diretoria de Serviços</h1>
            <p class="text-gray-300 text-sm">Gestão Administrativa, Financeira e de Infraestrutura</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">

    <div class="grid lg:grid-cols-3 gap-10">

        {{-- Diretora --}}
        <div class="lg:col-span-1">
            <h2 class="text-xl font-bold text-gray-800 mb-6 border-l-4 border-etec-accent pl-3">Responsável</h2>

            @if($director)
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 sticky top-24">
                <div class="h-24 bg-gradient-to-r from-etec-dark to-etec-medium"></div>
                <div class="px-6">
                    <div class="w-24 h-24 mx-auto -mt-12 bg-white rounded-full p-1 shadow-lg">
                        <img src="{{ photo_url($director->photo) }}"
                             onerror="this.src='{{ avatar_url($director->name, '1a3a6e', 'fff', ['bold' => 'true', 'size' => 256]) }}'"
                             class="w-full h-full object-cover rounded-full">
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-lg font-bold text-gray-800">{{ $director->name }}</h3>
                    <span class="text-sm font-bold text-etec-medium uppercase tracking-wide block mb-1">{{ $director->role }}</span>
                    @if($director->specialty)
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed italic">"{{ $director->specialty }}"</p>
                    @endif
                    <div class="bg-gray-50 rounded-xl p-4 text-left space-y-3">
                        @if($director->phone)
                        <div class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-etec-medium flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                            <a href="tel:{{ preg_replace('/\D/', '', $director->phone) }}" class="hover:text-etec-main">{{ $director->phone }}</a>
                        </div>
                        @endif
                        @if($director->email)
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-etec-medium flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $director->email }}" class="text-etec-main hover:underline text-sm truncate">{{ $director->email }}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="bg-gray-50 rounded-2xl p-8 text-center border border-dashed border-gray-300">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <p class="text-gray-500 text-sm">Informações em atualização.</p>
            </div>
            @endif
        </div>

        {{-- Equipe + Sistemas --}}
        <div class="lg:col-span-2 space-y-10">

            {{-- Equipe --}}
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-6 border-l-4 border-etec-medium pl-3">Equipe Administrativa</h2>

                @if($staff->isNotEmpty())
                <div class="grid md:grid-cols-2 gap-5">
                    @foreach($staff as $member)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex gap-4 hover:shadow-md transition items-start">
                        <img src="{{ photo_url($member->photo) }}"
                             onerror="this.src='{{ avatar_url($member->name, 'fef3c7', '92400e') }}'"
                             class="w-14 h-14 rounded-full object-cover border-2 border-gray-100 flex-shrink-0">
                        <div class="min-w-0 flex-grow">
                            <h4 class="font-bold text-gray-800 leading-tight">{{ $member->name }}</h4>
                            <span class="text-xs font-bold text-etec-medium uppercase tracking-wide block mb-1.5">{{ $member->role }}</span>
                            @if($member->specialty)
                            <p class="text-xs text-gray-500 mb-2 line-clamp-2 leading-relaxed">{{ $member->specialty }}</p>
                            @endif
                            <div class="space-y-1">
                                @if($member->phone)
                                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                                    {{ $member->phone }}
                                </div>
                                @endif
                                @if($member->email)
                                <a href="mailto:{{ $member->email }}" class="inline-flex items-center gap-1 text-xs text-etec-main hover:underline">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    {{ $member->email }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-gray-50 rounded-xl p-8 text-center border border-dashed border-gray-200">
                    <p class="text-gray-500 text-sm">Equipe em atualização.</p>
                </div>
                @endif
            </div>

            {{-- Acesso Rápido --}}
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-5 border-l-4 border-etec-accent pl-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Acesso Rápido aos Sistemas
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($links as $link)
                    <a href="{{ $link['url'] }}" target="_blank"
                       class="bg-white border border-gray-200 p-4 rounded-xl hover:shadow-lg hover:border-etec-accent hover:-translate-y-1 transition group text-center flex flex-col items-center gap-2">
                        <div class="text-2xl bg-gray-50 w-14 h-14 rounded-xl flex items-center justify-center group-hover:bg-etec-light transition font-bold">
                            {{ $link['icon'] }}
                        </div>
                        <div>
                            <strong class="block text-sm text-etec-dark group-hover:text-etec-accent transition leading-tight">{{ $link['name'] }}</strong>
                            <span class="text-xs text-gray-500">{{ $link['desc'] }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Documentos --}}
    @if($downloads->isNotEmpty())
    <div class="mt-12 bg-gray-50 rounded-2xl p-8 border border-gray-200">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2.5">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            Arquivos e Formulários
        </h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($downloads as $file)
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-start gap-4 hover:border-etec-medium transition group">
                <div class="w-10 h-10 bg-red-50 text-red-400 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-red-500 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="flex-grow min-w-0">
                    <h4 class="font-bold text-gray-800 text-sm mb-1 leading-tight">{{ $file->title }}</h4>
                    @if($file->published_at)
                    <span class="text-xs text-gray-400 block mb-2">
                        {{ \Carbon\Carbon::parse($file->published_at)->format('d/m/Y') }}
                    </span>
                    @endif
                    <a href="{{ $file->file_path }}" class="inline-flex items-center gap-1 text-xs font-bold text-etec-accent hover:underline">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Baixar Arquivo
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
