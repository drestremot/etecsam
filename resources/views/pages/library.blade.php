@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="text-6xl">📚</div>
        <div>
            <h1 class="text-4xl font-serif font-bold mb-2">Biblioteca Ativa</h1>
            <p class="text-gray-300 text-lg">Centro de Memória e Pesquisa da Etec Sebastiana Augusta de Moraes</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12 grid lg:grid-cols-3 gap-10">

    <aside class="space-y-8">

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden text-center p-6">
            <div class="w-32 h-32 mx-auto bg-gray-200 rounded-full mb-4 overflow-hidden border-4 border-etec-light">
                <img src="{{ asset('imagens/equipe/estremote.jpg') }}" onerror="this.src='https://ui-avatars.com/api/?name=B&background=0D5E34&color=fff'" class="w-full h-full object-cover">
            </div>
            <h3 class="text-xl font-bold text-gray-800">Esther do Nascimento Martins</h3>
            <span class="text-sm text-etec-medium font-bold uppercase tracking-wider">Bibliotecário(a)</span>

            <div class="mt-6 text-left space-y-3 text-sm text-gray-600 bg-gray-50 p-4 rounded-lg">
                <p><strong>📞 Contato:</strong> (18) 3722-XXXX</p>
                <p><strong>✉️ Email:</strong> biblioteca@etec.sp.gov.br</p>
            </div>
        </div>

        <div class="bg-etec-dark text-white rounded-xl shadow-lg p-6">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                ⏰ Horário de Atendimento
            </h3>
            <ul class="space-y-3 text-sm">
                <li class="flex justify-between border-b border-white/10 pb-2">
                    <span>Segunda a Sexta</span>
                    <span class="font-bold text-etec-accent">08h às 22h</span>
                </li>
                <li class="flex justify-between border-b border-white/10 pb-2">
                    <span>Intervalos</span>
                    <span class="text-gray-400">Aberto</span>
                </li>
                <li class="text-xs text-gray-400 mt-2">
                    *Não há atendimento aos sábados, domingos e feriados.
                </li>
            </ul>
        </div>
    </aside>

    <div class="lg:col-span-2 space-y-10">

        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-etec-medium pl-3">
                🔍 Bases de Dados para Pesquisa
            </h2>
            <div class="grid md:grid-cols-2 gap-4">
                <a href="https://www.periodicos.capes.gov.br/" target="_blank" class="flex items-center gap-4 p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md hover:border-etec-medium transition group">
                    <div class="bg-blue-100 text-blue-600 w-12 h-12 rounded flex items-center justify-center text-xl">🎓</div>
                    <div>
                        <strong class="block text-gray-800 group-hover:text-blue-700">Portal CAPES</strong>
                        <span class="text-xs text-gray-500">Periódicos científicos</span>
                    </div>
                </a>

                <a href="https://scielo.org/" target="_blank" class="flex items-center gap-4 p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md hover:border-etec-medium transition group">
                    <div class="bg-orange-100 text-orange-600 w-12 h-12 rounded flex items-center justify-center text-xl">🔬</div>
                    <div>
                        <strong class="block text-gray-800 group-hover:text-orange-700">SciELO</strong>
                        <span class="text-xs text-gray-500">Biblioteca Eletrônica</span>
                    </div>
                </a>

                <a href="https://scholar.google.com.br/" target="_blank" class="flex items-center gap-4 p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md hover:border-etec-medium transition group">
                    <div class="bg-blue-50 text-blue-500 w-12 h-12 rounded flex items-center justify-center text-xl">G</div>
                    <div>
                        <strong class="block text-gray-800 group-hover:text-blue-600">Google Acadêmico</strong>
                        <span class="text-xs text-gray-500">Pesquisa ampla</span>
                    </div>
                </a>

                <a href="http://www.dominiopublico.gov.br/" target="_blank" class="flex items-center gap-4 p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md hover:border-etec-medium transition group">
                    <div class="bg-green-100 text-green-600 w-12 h-12 rounded flex items-center justify-center text-xl">🏛️</div>
                    <div>
                        <strong class="block text-gray-800 group-hover:text-green-700">Domínio Público</strong>
                        <span class="text-xs text-gray-500">Obras literárias gratuitas</span>
                    </div>
                </a>
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-etec-accent pl-3">
                📂 Normas, Manuais e TCC
            </h2>

            @if($documents->isEmpty())
                <p class="text-gray-500">Nenhum documento disponível no momento.</p>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 divide-y divide-gray-100">
                    @foreach($documents as $doc)
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50 transition">
                        <div class="text-3xl text-etec-dark">
                            @if(Str::contains($doc->title, 'TCC')) 📘
                            @elseif(Str::contains($doc->title, 'ABNT')) 📏
                            @else 📄 @endif
                        </div>
                        <div class="flex-grow">
                            <h4 class="font-bold text-gray-800">{{ $doc->title }}</h4>
                            <span class="text-xs text-gray-500">Atualizado em {{ \Carbon\Carbon::parse($doc->published_at)->format('d/m/Y') }}</span>
                        </div>
                        <a href="{{ $doc->file_path }}" class="bg-etec-light text-etec-dark px-4 py-2 rounded text-sm font-bold hover:bg-etec-medium hover:text-white transition">
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
