@props([
    'title',
    'subtitle' => null,
    'label' => null,
    'variant' => 'solid', // solid | photo | plain
    'image' => null,
    'compact' => false,
])

@if($variant === 'plain')
    <div class="border-b border-white/10 bg-[#0f223f] text-white transition-colors" style="background-color: #0f223f; color: #ffffff;">
        <div class="container mx-auto px-4 py-14 text-center">
            @if($label)
                <span class="text-xs font-extrabold uppercase tracking-widest text-amber-300 bg-amber-400/15 px-3.5 py-1 rounded-full border border-amber-400/25 inline-block mb-3">{{ $label }}</span>
            @endif
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">{{ $title }}</h1>
            <div class="w-16 h-1 bg-amber-400 mx-auto mt-4 rounded-full"></div>
            @if($subtitle)
                <p class="mt-4 text-sm sm:text-base text-slate-200 max-w-2xl mx-auto leading-relaxed font-normal">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
@elseif($variant === 'photo')
    <div class="relative bg-[#0c1b33] h-60 sm:h-64 flex items-center justify-center overflow-hidden border-b border-white/10 text-white" style="background-color: #0c1b33; color: #ffffff;">
        @if($image)
            <img src="{{ $image }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-35">
        @endif
        <div class="absolute inset-0 bg-gradient-to-r from-[#0c1b33]/90 via-[#0c1b33]/70 to-[#0c1b33]/90"></div>
        <div class="relative z-10 text-center text-white px-4 max-w-3xl mx-auto space-y-3">
            @isset($icon)
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-400/20 text-amber-300 border border-amber-400/30 backdrop-blur-md mb-1 shadow-sm">
                    {{ $icon }}
                </div>
            @endisset
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white drop-shadow-md">{{ $title }}</h1>
            @if($subtitle)
                <p class="text-sm sm:text-base text-slate-200 font-normal leading-relaxed">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
@else
    <div class="bg-gradient-to-r from-[#0c1b33] via-[#14284b] to-[#0c1b33] text-white {{ $compact ? 'py-10' : 'py-12' }} border-b border-white/10 shadow-md" style="background-color: #0c1b33; color: #ffffff;">
        <div class="container mx-auto px-4 flex flex-wrap items-center justify-between gap-6">
            <div class="flex items-center gap-5 min-w-0">
                @isset($icon)
                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center justify-center flex-shrink-0 text-amber-300 shadow-sm">
                        {{ $icon }}
                    </div>
                @endisset
                <div class="min-w-0">
                    @if($label)
                        <span class="inline-block text-[11px] font-extrabold uppercase tracking-widest text-amber-300 bg-amber-400/15 px-2.5 py-0.5 rounded-full border border-amber-400/25 mb-1.5">{{ $label }}</span>
                    @endif
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white truncate">{{ $title }}</h1>
                    @if($subtitle)
                        <p class="text-xs sm:text-sm text-slate-200 mt-0.5 leading-relaxed truncate">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            @isset($actions)
                <div class="flex items-center gap-3">
                    {{ $actions }}
                </div>
            @endisset
        </div>
        @isset($mobileActions)
            <div class="container mx-auto px-4 mt-4 sm:hidden">
                {{ $mobileActions }}
            </div>
        @endisset
    </div>
@endif
