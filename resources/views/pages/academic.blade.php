@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="text-6xl">🎓</div>
        <div>
            <h1 class="text-4xl font-serif font-bold mb-2">Secretaria Acadêmica</h1>
            <p class="text-gray-300 text-lg">Vida Escolar, Matrículas e Documentação</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">

    <div class="mb-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-etec-medium pl-3 flex items-center gap-2">
            🚀 Área do Aluno
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($links as $link)
            <a href="{{ $link['url'] }}" target="_blank" class="bg-white border border-gray-200 p-4 rounded-xl hover:shadow-lg hover:border-etec-accent hover:-translate-y-1 transition group text-center flex flex-col items-center gap-2 h-full">
                <div class="text-4xl bg-blue-50 text-blue-600 w-16 h-16 rounded-full flex items-center justify-center group-hover:bg-etec-light group-hover:text-etec-dark transition">
                    {{ $link['icon'] }}
                </div>
                <div>
                    <strong class="block text-etec-dark group-hover:text-etec-accent transition">{{ $link['name'] }}</strong>
                    <span class="text-xs text-gray-500">{{ $link['desc'] }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>


    <div class="grid lg:grid-cols-3 gap-10">

        <div class="lg:col-span-1">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-etec-accent pl-3">Responsável</h2>

            @if($director)
            <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-100">
                <div class="h-24 bg-etec-dark"></div>
                <div class="px-6 relative">
                    <div class="w-24 h-24 mx-auto -mt-12 bg-white rounded-full p-1 shadow-lg">
                        <img src="{{ asset($director->photo) }}"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($director->name) }}&background=0D5E34&color=fff'"
                             class="w-full h-full object-cover rounded-full">
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-lg font-bold text-gray-800">{{ $director->name }}</h3>
                    <span class="text-xs font-bold text-etec-medium uppercase block mb-4">{{ $director->role }}</span>

                    <div class="bg-gray-50 p-3 rounded text-sm text-left space-y-2">
                        <p><strong>📞 Tel:</strong> {{ $director->phone }}</p>
                        <p><strong>✉️ Email:</strong> <a href="mailto:{{ $director->email }}" class="text-blue-600">{{ $director->email }}</a></p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-etec-medium pl-3">Equipe de Atendimento</h2>

            <div class="grid md:grid-cols-2 gap-6">
                @foreach($staff as $member)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 flex gap-4 hover:shadow-md transition">
                    <div class="flex-shrink-0">
                        <img src="{{ asset($member->photo) }}"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=eee&color=333'"
                             class="w-14 h-14 rounded-full object-cover border border-gray-200">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $member->name }}</h4>
                        <span class="text-xs font-bold text-gray-400 uppercase block mb-1">{{ $member->role }}</span>
                        <p class="text-xs text-gray-500 mb-2">{{ $member->specialty }}</p>
                        <a href="mailto:{{ $member->email }}" class="text-xs text-blue-600 hover:underline">✉️ {{ $member->email }}</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mb-16 bg-blue-50 rounded-xl p-8 border border-blue-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            📂 Requerimentos e Documentos
        </h2>

        @if($downloads->isEmpty())
            <p class="text-gray-500">Nenhum arquivo disponível.</p>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($downloads as $file)
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex items-start gap-4 hover:border-etec-medium transition group">
                    <div class="text-3xl text-etec-medium group-hover:scale-110 transition">📄</div>
                    <div class="flex-grow">
                        <h4 class="font-bold text-gray-800 text-sm mb-1 leading-tight">{{ $file->title }}</h4>
                        <a href="{{ $file->file_path }}" class="text-xs font-bold text-blue-600 uppercase tracking-wide hover:underline flex items-center gap-1">
                            ⬇️ Baixar PDF
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
