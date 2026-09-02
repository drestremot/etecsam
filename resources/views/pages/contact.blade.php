@extends('layouts.app')

@section('content')

<x-page-header variant="photo" image="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1600&auto=format&fit=crop"
    title="Fale Conosco" subtitle="Estamos prontos para atender você com agilidade e eficiência.">
    <x-slot:icon>
        <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
    </x-slot:icon>
</x-page-header>

<div class="bg-[#0b172a] text-white py-16" style="background-color: #0b172a; color: #ffffff;">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-10">

            {{-- Canais de Atendimento & Mapa --}}
            <div class="space-y-8">
                <div class="bg-[#14284b] p-8 rounded-3xl border border-white/10 shadow-sm">
                    <h2 class="text-2xl font-extrabold text-white mb-4 flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span>Canais de Atendimento</span>
                    </h2>
                    <p class="text-slate-300 mb-6 text-sm leading-relaxed">
                        A secretaria acadêmica atende presencialmente de segunda a sexta-feira, das 08h às 21h.
                    </p>

                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 bg-amber-400/15 text-amber-300 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-sm mb-0.5">Endereço</h3>
                                <p class="text-xs text-slate-300 leading-relaxed">
                                    Estrada Vicinal Sebastião Lourenço da Silva, Km 11, Bairro: Planalto.<br>
                                    Andradina — SP, CEP 16900-530
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 bg-amber-400/15 text-amber-300 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-sm mb-0.5">Telefones</h3>
                                <p class="text-xs text-slate-300 leading-relaxed space-y-1">
                                    <span class="block"><a href="tel:1837026860" class="text-amber-400 font-semibold hover:underline">(18) 3702-6860</a> — Gelciane</span>
                                    <span class="block"><a href="tel:1837226861" class="text-amber-400 font-semibold hover:underline">(18) 3722-6861</a> — Héder</span>
                                    <span class="block"><a href="tel:1837226862" class="text-amber-400 font-semibold hover:underline">(18) 3722-6862</a> — Tereza</span>
                                    <span class="block"><a href="tel:1837226863" class="text-amber-400 font-semibold hover:underline">(18) 3722-6863</a> — Valeska</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 bg-amber-400/15 text-amber-300 rounded-2xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-sm mb-0.5">E-mail Oficial</h3>
                                <a href="mailto:e028acad@cps.sp.gov.br" class="text-xs text-amber-400 font-semibold hover:underline">e028acad@cps.sp.gov.br</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl overflow-hidden border border-white/10 shadow-sm">
                    <div class="bg-[#0f223f] flex items-center justify-between px-4 py-3 gap-3 border-b border-white/10">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-7 h-7 bg-amber-400 text-slate-950 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-white text-xs font-bold leading-tight truncate">Etec Sebastiana Augusta de Moraes</p>
                                <p class="text-slate-300 text-[11px] leading-tight truncate">Est. Vicinal S. Lourenço da Silva, Km 11 — Andradina/SP</p>
                            </div>
                        </div>
                        <a href="https://maps.google.com/?q=ETEC+Sebastiana+Augusta+de+Moraes,+Andradina+SP"
                           target="_blank" rel="noopener noreferrer"
                           class="flex-shrink-0 inline-flex items-center gap-1.5 bg-amber-400 text-slate-950 text-xs font-bold px-3 py-1.5 rounded-xl hover:bg-amber-300 transition whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span>Abrir no Maps</span>
                        </a>
                    </div>
                    <div style="height: 280px;">
                        <iframe
                            src="https://maps.google.com/maps?q=-20.9738224,-51.3207176&z=17&output=embed&hl=pt-BR"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            title="Localização da Etec Sebastiana Augusta de Moraes">
                        </iframe>
                    </div>
                </div>
            </div>

            {{-- Formulário de Contato --}}
            <div class="bg-[#14284b] p-8 rounded-3xl shadow-sm border border-white/10">
                <h2 class="text-2xl font-extrabold text-white mb-2">Envie uma Mensagem</h2>
                <p class="text-slate-300 text-xs sm:text-sm mb-6">Responderemos em até 2 dias úteis diretamente em seu e-mail.</p>

                {{-- Feedback de sucesso/erro --}}
                @if(session('success'))
                    <div class="flex items-center gap-3 bg-emerald-950/60 border border-emerald-500 text-emerald-200 text-xs rounded-2xl px-4 py-3 mb-5">
                        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="flex items-center gap-3 bg-rose-950/60 border border-rose-500 text-rose-200 text-xs rounded-2xl px-4 py-3 mb-5">
                        <svg class="w-5 h-5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-1.5">Seu Nome Completo *</label>
                        <input type="text" name="nome" value="{{ old('nome') }}"
                               class="w-full bg-[#0b172a] border @error('nome') border-red-400 @else border-white/20 @enderror text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl focus:ring-2 focus:ring-amber-400 focus:border-amber-400 block px-4 py-3 outline-none transition"
                               placeholder="Ex: João da Silva">
                        @error('nome')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-1.5">Telefone / WhatsApp</label>
                            <input type="tel" name="telefone" value="{{ old('telefone') }}"
                                   class="w-full bg-[#0b172a] border border-white/20 text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl focus:ring-2 focus:ring-amber-400 focus:border-amber-400 block px-4 py-3 outline-none transition"
                                   placeholder="(18) 99999-9999">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-1.5">Assunto *</label>
                            <select name="assunto" class="w-full bg-[#0b172a] border @error('assunto') border-red-400 @else border-white/20 @enderror text-white text-xs sm:text-sm rounded-xl focus:ring-2 focus:ring-amber-400 focus:border-amber-400 block px-4 py-3 outline-none transition">
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
                        <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-1.5">E-mail para Resposta *</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full bg-[#0b172a] border @error('email') border-red-400 @else border-white/20 @enderror text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl focus:ring-2 focus:ring-amber-400 focus:border-amber-400 block px-4 py-3 outline-none transition"
                               placeholder="seu@email.com">
                        @error('email')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-1.5">Mensagem *</label>
                        <textarea rows="4" name="mensagem"
                                  class="w-full bg-[#0b172a] border @error('mensagem') border-red-400 @else border-white/20 @enderror text-white placeholder-slate-400 text-xs sm:text-sm rounded-xl focus:ring-2 focus:ring-amber-400 focus:border-amber-400 block px-4 py-3 outline-none transition resize-none"
                                  placeholder="Descreva detalhadamente sua dúvida ou solicitação...">{{ old('mensagem') }}</textarea>
                        @error('mensagem')<p class="text-rose-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 text-slate-950 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 font-extrabold rounded-2xl text-xs sm:text-sm px-6 py-3.5 transition duration-200 shadow-md">
                        <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Enviar Mensagem</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
