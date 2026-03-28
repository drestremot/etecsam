@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="text-6xl">💼</div>
        <div>
            <h1 class="text-4xl font-serif font-bold mb-2">Diretoria de Serviços</h1>
            <p class="text-gray-300 text-lg">Gestão Administrativa, Acadêmica e Financeira</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">

    @if($superintendent)
    <div class="mb-16">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 flex flex-col md:flex-row">

            <div class="md:w-1/3 h-64 md:h-auto relative bg-etec-dark">
                <img src="{{ asset($superintendent->photo) }}"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($superintendent->name) }}&size=512&background=random'"
                     class="w-full h-full object-cover opacity-90 hover:opacity-100 transition duration-500">
            </div>

            <div class="p-8 md:p-12 flex flex-col justify-center md:w-2/3">
                <span class="text-etec-accent font-bold tracking-widest uppercase text-sm mb-2">
                    Superintendência
                </span>

                <h2 class="text-3xl md:text-4xl font-serif font-bold text-gray-800 mb-4">
                    {{ $superintendent->name }}
                </h2>

                <p class="text-gray-600 text-lg mb-6 leading-relaxed">
                    {{ $superintendent->specialty }}
                </p>

                <div class="flex flex-wrap gap-4 mt-auto">
                    @if($superintendent->lattes_url)
                    <a href="{{ $superintendent->lattes_url }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-full font-bold hover:bg-etec-medium hover:text-white transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        Currículo Lattes
                    </a>
                    @endif

                    <a href="mailto:{{ $superintendent->email }}" class="inline-flex items-center gap-2 px-6 py-3 border border-gray-300 text-gray-600 rounded-full font-bold hover:border-etec-accent hover:text-etec-accent transition">
                        ✉️ {{ $superintendent->email }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
<div class="container mx-auto px-4 py-12">

    <div class="mb-16">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-etec-medium pl-3 flex items-center gap-2">
            🚀 Acesso Rápido aos Sistemas
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($links as $link)
            <a href="{{ $link['url'] }}" target="_blank" class="bg-white border border-gray-200 p-4 rounded-xl hover:shadow-lg hover:border-etec-accent hover:-translate-y-1 transition group text-center flex flex-col items-center gap-2 h-full">
                <div class="text-4xl bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center group-hover:bg-etec-light transition">
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
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-etec-accent pl-3">
                Diretoria
            </h2>

            @if($director)
            <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-100 sticky top-6">
                <div class="h-32 bg-etec-medium"></div>
                <div class="px-6 relative">
                    <div class="w-32 h-32 mx-auto -mt-16 bg-white rounded-full p-1 shadow-lg">
                        <img src="{{ asset($director->photo) }}"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($director->name) }}&background=0D5E34&color=fff&size=256'"
                             class="w-full h-full object-cover rounded-full">
                    </div>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-800">{{ $director->name }}</h3>
                    <span class="text-sm font-bold text-etec-accent uppercase tracking-widest block mb-4">
                        {{ $director->role }}
                    </span>

                    <p class="text-gray-600 italic mb-6 text-sm">
                        "{{ $director->specialty ?? 'Gestão focada na excelência e no bem-estar dos servidores.' }}"
                    </p>

                    <div class="space-y-3 text-left bg-gray-50 p-4 rounded-lg text-sm">
                        <div class="flex items-center gap-3">
                            <span class="bg-white p-2 rounded text-etec-dark">📞</span>
                            <div>
                                <strong class="block text-xs text-gray-500">Telefone</strong>
                                <span class="text-gray-800">{{ $director->phone }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="bg-white p-2 rounded text-etec-dark">✉️</span>
                            <div>
                                <strong class="block text-xs text-gray-500">Email</strong>
                                <a href="mailto:{{ $director->email }}" class="text-blue-600 hover:underline">
                                    {{ $director->email }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-l-4 border-etec-medium pl-3">
                Equipe Administrativa
            </h2>

            <div class="grid md:grid-cols-2 gap-6">
                @foreach($staff as $member)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 flex gap-4 hover:shadow-md transition items-start">

                    <div class="flex-shrink-0">
                        <img src="{{ asset($member->photo) }}"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=eee&color=333'"
                             class="w-16 h-16 rounded-full object-cover border-2 border-gray-100">
                    </div>

                    <div class="flex-grow">
                        <h4 class="font-bold text-gray-800 text-lg leading-tight">{{ $member->name }}</h4>
                        <span class="text-xs font-bold text-etec-medium uppercase mb-2 block">{{ $member->role }}</span>

                        <p class="text-xs text-gray-500 mb-3 line-clamp-2">
                            {{ $member->specialty }}
                        </p>

                        <div class="text-sm space-y-1">
                            <div class="flex items-center gap-2 text-gray-600">
                                <span class="text-xs">📞</span> {{ $member->phone }}
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs">✉️</span>
                                <a href="mailto:{{ $member->email }}" class="text-blue-600 hover:underline text-xs">
                                    {{ $member->email }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        </div> <div class="mb-16 bg-gray-50 rounded-xl p-8 border border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            📂 Arquivos e Formulários Úteis
        </h2>

        @if($downloads->isEmpty())
            <p class="text-gray-500">Nenhum arquivo disponível no momento.</p>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($downloads as $file)
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex items-start gap-4 hover:border-etec-medium transition group">
                    <div class="text-3xl text-red-500 group-hover:scale-110 transition">
                        @if(Str::contains($file->title, 'PDF') || Str::contains($file->title, 'Manual')) 📕
                        @elseif(Str::contains($file->title, 'Excel') || Str::contains($file->title, 'Planilha')) 📗
                        @else 📄 @endif
                    </div>

                    <div class="flex-grow">
                        <h4 class="font-bold text-gray-800 text-sm mb-1 leading-tight">
                            {{ $file->title }}
                        </h4>
                        <span class="text-xs text-gray-400 block mb-2">
                            Atualizado em {{ \Carbon\Carbon::parse($file->published_at)->format('d/m/Y') }}
                        </span>

                        <a href="{{ $file->file_path }}" class="text-xs font-bold text-etec-accent uppercase tracking-wide hover:underline flex items-center gap-1">
                            ⬇️ Baixar Arquivo
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="grid lg:grid-cols-3 gap-10">

    </div>
</div>
@endsection
