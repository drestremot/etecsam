@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-16 md:py-24 flex justify-center">
    <div class="max-w-3xl w-full bg-white dark:bg-white/95 rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12 flex justify-center">
        <img src="{{ asset('imagens/mensagem.jpg') }}" alt="Aviso — Em atendimento à legislação eleitoral, os conteúdos desta seção de notícias ficarão indisponíveis de 4 de julho de 2026 até o final da eleição estadual em São Paulo. No período, as notícias relacionadas aos serviços públicos estarão disponíveis na Agência SP."
             class="max-w-full h-auto">
    </div>
</div>

@endsection
