@extends('layouts.app')

@section('content')

<div class="relative bg-etec-dark h-64 flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 bg-black/40 z-10"></div>
    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1600&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-50 grayscale">

    <div class="relative z-20 text-center text-white">
        <h1 class="text-4xl font-serif font-bold mb-2">Fale Conosco</h1>
        <p class="text-gray-200 text-lg">Estamos prontos para atender você.</p>
    </div>
</div>

<div class="container mx-auto px-4 py-16">
    <div class="grid md:grid-cols-2 gap-12">

        <div class="space-y-8">
            <div>
                <h2 class="text-2xl font-bold text-etec-dark mb-6 border-l-4 border-etec-accent pl-3">
                    Canais de Atendimento
                </h2>
                <p class="text-gray-600 mb-6">
                    A secretaria acadêmica atende presencialmente de segunda a sexta-feira, das 08h às 21h.
                </p>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-etec-light text-etec-dark rounded-full flex items-center justify-center flex-shrink-0">
                            📍
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Endereço</h3>
                            <p class="text-sm text-gray-600">
                                Estrada Vicinal Sebastião Lourenço da Silva, Km 5<br>
                                Andradina - SP, CEP 16900-000
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-etec-light text-etec-dark rounded-full flex items-center justify-center flex-shrink-0">
                            📞
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Telefones</h3>
                            <p class="text-sm text-gray-600">
                                (18) 3702-6860 (Gelciane)<br>
                                (18) 3722-6861 (Héder)
                                (18) 3722-6862 (Tereza)
                                (18) 3722-6863 (Valeska)
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-etec-light text-etec-dark rounded-full flex items-center justify-center flex-shrink-0">
                            ✉️
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">E-mail Oficial</h3>
                            <p class="text-sm text-gray-600">e030acad@cps.sp.gov.br</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-200 h-64 rounded-lg overflow-hidden border border-gray-300 shadow-inner">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3720.896796684747!2d-51.36569662394622!3d-20.92723308070268!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94978bd2a6320593%3A0xf6573c79010260c8!2sETEC%20Sebastiana%20Augusta%20de%20Moraes!5e0!3m2!1spt-BR!2sbr!4v1709220000000!5m2!1spt-BR!2sbr"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
            <h2 class="text-2xl font-bold text-etec-dark mb-6">Envie uma mensagem</h2>

            <form action="#" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Seu Nome</label>
                    <input type="text" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-etec-medium focus:border-etec-medium block p-3 outline-none transition" placeholder="Ex: João da Silva">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Telefone / Whats</label>
                        <input type="text" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-etec-medium focus:border-etec-medium block p-3 outline-none transition" placeholder="(18) 99999-9999">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Assunto</label>
                        <select class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-etec-medium focus:border-etec-medium block p-3 outline-none transition">
                            <option>Secretaria / Documentos</option>
                            <option>Vestibulinho</option>
                            <option>Coordenação Pedagógica</option>
                            <option>Parcerias / Cooperativa</option>
                            <option>Outros</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Mensagem</label>
                    <textarea rows="5" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-etec-medium focus:border-etec-medium block p-3 outline-none transition" placeholder="Como podemos ajudar?"></textarea>
                </div>

                <button type="submit" class="w-full text-white bg-etec-medium hover:bg-etec-dark focus:ring-4 focus:ring-etec-light font-bold rounded-lg text-sm px-5 py-4 transition duration-300 shadow-md transform hover:-translate-y-1">
                    Enviar Mensagem
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
