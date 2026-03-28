<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('imagens/logo/etec.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etec Sebastiana Augusta de Moraes - Andradina</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-sans antialiased text-gray-700 bg-gray-50 flex flex-col min-h-screen">

    <div class="bg-etec-dark text-white text-xs py-2">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <span>Centro Paula Souza - Governo de SP</span>
            <span>(18) 3702-6850 | e028dir@cps.sp.gov.br</span>
        </div>
    </div>

    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('imagens/logo/etec.png') }}" alt="Logo Etec Sebastiana Augusta de Moraes"
                    class="h-20 md:h-20 w-auto transition transform group-hover:scale-105">

                <div class="hidden md:block leading-tight text-white">
                    <span class="block text-xs font-light tracking-widest uppercase opacity-80">Etec</span>
                    <span class="block font-serif font-bold text-lg">Sebastiana Augusta de Moraes</span>
                </div>
            </a>


            <nav class="hidden md:flex gap-6 font-medium text-sm">
                <a href="{{ route('home') }}" class="text-etec-dark hover:text-etec-main">Início</a>
                <a href="{{ route('institutional') }}" class="hover:text-etec-accent transition">A Escola</a>
                <a href="{{ route('library') }}" class="hover:text-etec-accent transition">Biblioteca</a>
                <a href="{{ route('home') }}#cursos" class="hover:text-etec-accent transition">Cursos</a>
                <a href="{{ route('home') }}#fazenda" class="hover:text-etec-accent transition">Escola Fazenda</a>
                <a href="{{ route('academic') }}" class="hover:text-etec-accent transition">Secretaria</a>
               <a href="{{ route('administrative') }}" class="hover:text-etec-accent transition">Diretoria de Serviços</a>
                <a href="{{ route('contact') }}" class="hover:text-etec-accent transition">Contato</a>
                <a href="{{ route('agenda') }}" class="hover:text-etec-accent transition">Agenda</a>

                <a href="#" class="px-4 py-2 bg-etec-main text-white rounded hover:bg-etec-dark transition">Vestibulinho</a>

                <div class="hidden md:block">
                    <img src="{{ asset('imagens/logo/logo-cps-2022.svg') }}" alt="Centro Paula Souza"
                        class="h-20 md:h-20 w-auto transition transform group-hover:scale-105">
                </div>

            </nav>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-etec-dark text-white py-10 mt-10">
        <div class="container mx-auto px-4 grid md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-4 border-b border-etec-main pb-2 inline-block">Sobre a Etec</h3>
                <p class="text-sm text-gray-300">
                    A Escola Agrícola de Andradina é referência em ensino técnico de qualidade,
                    formando profissionais competentes para o agronegócio e tecnologia.
                </p>
            </div>
            <div>
                <h3 class="text-lg font-bold mb-4 border-b border-etec-main pb-2 inline-block">Links Rápidos</h3>
                <ul class="text-sm space-y-2 text-gray-300">
                    <li><a href="#" class="hover:text-etec-accent">Portal do Aluno (NSA)</a></li>
                    <li><a href="#" class="hover:text-etec-accent">Calendário Escolar</a></li>
                    <li><a href="#" class="hover:text-etec-accent">Transparência</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-bold mb-4 border-b border-etec-main pb-2 inline-block">Localização</h3>
                <p class="text-sm text-gray-300 mb-2">Estrada Vicinal, Km 5 - Andradina/SP</p>
                <div class="h-24 bg-gray-600 rounded flex items-center justify-center">
                    [Mapa Google Aqui]
                </div>
            </div>
        </div>
        <div class="text-center text-xs text-gray-400 mt-10 border-t border-gray-700 pt-4">
            &copy; {{ date('Y') }} Etec Sebastiana Augusta de Moraes. Desenvolvido com Laravel.
        </div>
    </footer>

</body>

</html>
