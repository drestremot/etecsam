@extends('layouts.app')

@section('content')

<div class="relative bg-etec-dark h-56 flex items-center justify-center overflow-hidden">
    <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1600&auto=format&fit=crop"
         class="absolute inset-0 w-full h-full object-cover opacity-30 grayscale">
    <div class="absolute inset-0 bg-gradient-to-b from-etec-dark/60 to-etec-dark/90"></div>
    <div class="relative z-10 text-center text-white px-4">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-white/10 rounded-xl mb-4 mx-auto">
            <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h1 class="text-4xl font-bold mb-2">Fale Conosco</h1>
        <p class="text-gray-300">Estamos prontos para atender você.</p>
    </div>
</div>

<div class="container mx-auto px-4 py-16">
    <div class="grid md:grid-cols-2 gap-12">

        <div class="space-y-8">
            <div>
                <h2 class="text-2xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-accent pl-4">
                    Canais de Atendimento
                </h2>
                <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                    A secretaria acadêmica atende presencialmente de segunda a sexta-feira, das 08h às 21h.
                </p>

                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 bg-etec-light dark:bg-white/10 text-etec-dark dark:text-etec-accent rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white mb-0.5">Endereço</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                Estrada Vicinal Sebastião Lourenço da Silva, Km 11, Bairro: Planalto.<br>
                                Andradina — SP, CEP 16900-530
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 bg-etec-light dark:bg-white/10 text-etec-dark dark:text-etec-accent rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white mb-0.5">Telefones</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-loose">
                                <a href="tel:1837026860" class="hover:text-etec-main dark:hover:text-etec-accent transition">(18) 3702-6860</a> — Gelciane<br>
                                <a href="tel:1837226861" class="hover:text-etec-main dark:hover:text-etec-accent transition">(18) 3722-6861</a> — Héder<br>
                                <a href="tel:1837226862" class="hover:text-etec-main dark:hover:text-etec-accent transition">(18) 3722-6862</a> — Tereza<br>
                                <a href="tel:1837226863" class="hover:text-etec-main dark:hover:text-etec-accent transition">(18) 3722-6863</a> — Valeska
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 bg-etec-light dark:bg-white/10 text-etec-dark dark:text-etec-accent rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white mb-0.5">E-mail Oficial</h3>
                            <a href="mailto:e028acad@cps.sp.gov.br" class="text-sm text-etec-main dark:text-etec-light hover:text-etec-dark dark:hover:text-etec-accent transition">e028acad@cps.sp.gov.br</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-white/10 shadow-sm" style="height: 260px;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3720.896796684747!2d-51.36569662394622!3d-20.92723308070268!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94978bd2a6320593%3A0xf6573c79010260c8!2sETEC%20Sebastiana%20Augusta%20de%20Moraes!5e0!3m2!1spt-BR!2sbr!4v1709220000000!5m2!1spt-BR!2sbr"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>

        <div class="bg-etec-main p-8 rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10">
            <h2 class="text-2xl font-bold text-white mb-2">Envie uma mensagem</h2>
            <p class="text-green-100 text-sm mb-6">Responderemos em até 2 dias úteis.</p>

            {{-- Feedback de sucesso/erro --}}
            @if(session('success'))
                <div class="flex items-center gap-3 bg-green-600/30 border border-green-400/40 text-white text-sm rounded-xl px-4 py-3 mb-5">
                    <svg class="w-5 h-5 text-green-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 bg-red-600/30 border border-red-400/40 text-white text-sm rounded-xl px-4 py-3 mb-5">
                    <svg class="w-5 h-5 text-red-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('contact.send') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-green-100 mb-1.5">Seu Nome *</label>
                    <input type="text" name="nome" value="{{ old('nome') }}"
                           class="w-full bg-white/90 border @error('nome') border-red-400 @else border-white/20 @enderror text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-etec-accent focus:border-etec-accent block px-3.5 py-2.5 outline-none transition"
                           placeholder="Ex: João da Silva">
                    @error('nome')<p class="text-red-300 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-green-100 mb-1.5">Telefone / WhatsApp</label>
                        <input type="tel" name="telefone" value="{{ old('telefone') }}"
                               class="w-full bg-white/90 border border-white/20 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-etec-accent focus:border-etec-accent block px-3.5 py-2.5 outline-none transition"
                               placeholder="(18) 99999-9999">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-green-100 mb-1.5">Assunto *</label>
                        <select name="assunto" class="w-full bg-white/90 border @error('assunto') border-red-400 @else border-white/20 @enderror text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-etec-accent focus:border-etec-accent block px-3.5 py-2.5 outline-none transition">
                            <option value="Secretaria / Documentos"  {{ old('assunto') === 'Secretaria / Documentos'  ? 'selected' : '' }}>Secretaria / Documentos</option>
                            <option value="Vestibulinho"             {{ old('assunto') === 'Vestibulinho'             ? 'selected' : '' }}>Vestibulinho</option>
                            <option value="Coordenação Pedagógica"   {{ old('assunto') === 'Coordenação Pedagógica'   ? 'selected' : '' }}>Coordenação Pedagógica</option>
                            <option value="Parcerias / Cooperativa"  {{ old('assunto') === 'Parcerias / Cooperativa'  ? 'selected' : '' }}>Parcerias / Cooperativa</option>
                            <option value="Diretoria de Serviços"    {{ old('assunto') === 'Diretoria de Serviços'    ? 'selected' : '' }}>Diretoria de Serviços</option>
                            <option value="Outros"                   {{ old('assunto') === 'Outros'                   ? 'selected' : '' }}>Outros</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-100 mb-1.5">E-mail para resposta *</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full bg-white/90 border @error('email') border-red-400 @else border-white/20 @enderror text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-etec-accent focus:border-etec-accent block px-3.5 py-2.5 outline-none transition"
                           placeholder="seu@email.com">
                    @error('email')<p class="text-red-300 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-100 mb-1.5">Mensagem *</label>
                    <textarea rows="5" name="mensagem"
                              class="w-full bg-white/90 border @error('mensagem') border-red-400 @else border-white/20 @enderror text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-etec-accent focus:border-etec-accent block px-3.5 py-2.5 outline-none transition resize-none"
                              placeholder="Como podemos ajudar?">{{ old('mensagem') }}</textarea>
                    @error('mensagem')<p class="text-red-300 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 text-etec-dark bg-etec-accent hover:bg-yellow-400 font-bold rounded-lg text-sm px-5 py-3.5 transition duration-300 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Enviar Mensagem
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
