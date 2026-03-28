@extends('layouts.app')

@section('content')

    <div class="bg-white py-16">
        <div class="container mx-auto px-4">

            <div class="text-center mb-16">
                <h1 class="text-4xl font-serif font-bold text-etec-dark mb-4">Nossa História</h1>
                <div class="w-20 h-1 bg-etec-accent mx-auto"></div>
                <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
                    Conheça a trajetória da Etec Sebastiana Augusta de Moraes (Andradina-SP) e nosso compromisso com o
                    ensino agrícola de qualidade em Andradina.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
                <div>
                    <img src="https://images.unsplash.com/photo-1560493676-04071c5f467b?q=80&w=1000&auto=format&fit=crop"
                        alt="Fachada da Escola" class="rounded-lg shadow-xl shadow-etec-medium/20">
                </div>
                <div class="space-y-6 text-gray-700 leading-relaxed">

                    <p><strong>Nossa História: Tradição e Inovação no Campo</strong></p>
                    <p>
                        Desde a sua fundação, a Etec Sebastiana Augusta de Moraes tem sido um pilar fundamental no
                        desenvolvimento da região de Andradina e do Oeste Paulista. Mais do que uma instituição de ensino,
                        somos um solo fértil onde germinam o conhecimento, a técnica e o futuro do agronegócio.
                    </p>

                    <p><strong>Raízes Fortes (Fundação e Integração)</strong></p>

                    <p> A nossa trajetória deu um passo decisivo em 1994, quando a escola foi oficialmente integrada ao
                        Centro Paula Souza (CPS). A partir deste marco, passamos a alinhar a vocação agrícola regional com a
                        excelência educacional de uma das maiores instituições de ensino técnico da América Latina. O nome
                        da escola homenageia Sebastiana Augusta de Moraes, figura que simboliza o respeito à terra e o
                        compromisso com a comunidade local. </p>

                    <p><strong>O Diferencial "Escola-Fazenda"</strong></p>

                    <p>
                        O coração da nossa unidade pulsa em nossa infraestrutura única de "Escola-Fazenda". Aqui, a teoria
                        da sala de aula encontra a prática imediata no campo. Nossos alunos não apenas estudam o
                        agronegócio; eles o vivenciam.
                    </p>

                    <p>
                        <strong>
                    Vivência Real: </strong> manejo do solo à colheita, passando pela pecuária e gestão, o estudante está imerso na
                    rotina produtiva.
                    </p>

                    Pedagogia da Alternância: Destacamo-nos no estado de São Paulo pela aplicação de metodologias que
                    permitem ao aluno intercalar períodos de estudo na escola com a aplicação prática em suas propriedades
                    ou comunidades, fortalecendo a agricultura familiar e a sucessão no campo.

                    Inovação e Sustentabilidade
                    Ao longo das décadas, a Etec Sebastiana Augusta de Moraes evoluiu para atender às demandas do
                    agronegócio moderno. Não formamos apenas técnicos; formamos gestores conscientes.

                    Tecnologia: Integramos ferramentas modernas de gestão e produção para maximizar resultados.

                    Sustentabilidade: Somos referência em projetos de agroecologia e preservação, com destaque para o
                    cultivo e resgate de sementes crioulas e uso de energias renováveis.

                    Olhando para o Futuro
                    Hoje, oferecemos Ensino Médio Integrado e Cursos Técnicos que preparam o profissional para os desafios
                    do século XXI. Nosso compromisso continua o mesmo de nossa fundação: impulsionar o desenvolvimento
                    regional, transformando a vida de nossos alunos através de uma educação pública, gratuita e de
                    qualidade.

                    Etec Sebastiana Augusta de Moraes: Onde o futuro do campo começa.

                    <p>
                        Oferecemos ensino médio integrado e cursos técnicos que atendem às demandas do agronegócio moderno,
                        focando em sustentabilidade, tecnologia e gestão.
                    </p>
                </div>
            </div>

            <div class="bg-gray-50 py-16 mt-12 -mx-4 px-4 md:-mx-0 md:px-0 rounded-xl">
                <div class="container mx-auto">
                    <h2 class="text-3xl font-bold text-etec-dark mb-12 text-center font-serif">
                        Estrutura Administrativa
                    </h2>

                    @if ($direcaoGeral)
                        <div class="flex justify-center mb-16">
                            <div
                                class="bg-white p-8 rounded-xl shadow-lg border-t-4 border-etec-accent text-center max-w-md transform hover:-translate-y-2 transition duration-300">
                                <div
                                    class="w-32 h-32 mx-auto bg-etec-dark text-white rounded-full flex items-center justify-center text-4xl mb-6 shadow-md">
                                    @if ($direcaoGeral->photo)
                                        <img src="{{ asset($direcaoGeral->photo) }}"
                                            class="w-full h-full rounded-full object-cover">
                                    @else
                                        {{ substr($direcaoGeral->name, 0, 1) }}
                                    @endif
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800">{{ $direcaoGeral->name }}</h3>
                                <span
                                    class="inline-block bg-etec-medium text-white text-xs px-3 py-1 rounded-full uppercase tracking-wider mt-2 mb-4">
                                    {{ $direcaoGeral->role }}
                                </span>
                                <p class="text-gray-600 italic">"{{ $direcaoGeral->bio }}"</p>
                                <div class="mt-6 pt-6 border-t border-gray-100">
                                    <a href="mailto:{{ $direcaoGeral->email }}"
                                        class="text-etec-dark font-bold hover:text-etec-accent flex items-center justify-center gap-2">
                                        ✉ Fale com a Direção
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="hidden md:block w-px h-12 bg-gray-300 mx-auto -mt-16 mb-8"></div>
                    <div class="hidden md:block w-3/4 h-px bg-gray-300 mx-auto mb-12"></div>

                    <div class="grid md:grid-cols-3 gap-8">
                        @foreach ($departamentos as $dept)
                            <div
                                class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition border border-gray-100 group">
                                <div class="flex items-center gap-4 mb-4">
                                    <div
                                        class="w-12 h-12 bg-etec-light text-etec-dark rounded-lg flex items-center justify-center text-xl group-hover:bg-etec-dark group-hover:text-white transition">
                                        @if (Str::contains($dept->role, 'Administrativo'))
                                            💼
                                        @elseif(Str::contains($dept->role, 'Acadêmico'))
                                            📚
                                        @elseif(Str::contains($dept->role, 'Pedagógico'))
                                            🎓
                                        @else
                                            👤
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-lg leading-tight">{{ $dept->role }}</h4>
                                        <span class="text-sm text-etec-medium font-medium">{{ $dept->name }}</span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mb-4 h-10 overflow-hidden">{{ $dept->bio }}</p>
                                <a href="mailto:{{ $dept->email }}"
                                    class="text-xs text-gray-400 hover:text-etec-dark transition flex items-center gap-1">
                                    📧 {{ $dept->email }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    @endsection
