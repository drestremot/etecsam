@props([
    'title',
    'subtitle' => null,
    'label' => null,
    'variant' => 'solid', // solid | photo | plain
    'image' => null,
    'compact' => false,
])

@if($variant === 'plain')
    <div class="border-b border-gray-200 dark:border-white/10">
        <div class="container mx-auto px-4 py-16 text-center">
            @if($label)
                <span class="text-etec-medium dark:text-etec-accent font-bold uppercase tracking-widest text-xs">{{ $label }}</span>
            @endif
            <h1 class="text-4xl font-bold text-etec-dark dark:text-white mt-2 mb-4">{{ $title }}</h1>
            <div class="w-16 h-1 bg-etec-accent mx-auto"></div>
            @if($subtitle)
                <p class="mt-6 text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
@elseif($variant === 'photo')
    <div class="relative bg-etec-dark h-56 flex items-center justify-center overflow-hidden">
        <img src="{{ $image }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-30 grayscale">
        <div class="absolute inset-0 bg-gradient-to-b from-etec-dark/60 to-etec-dark/90"></div>
        <div class="relative z-10 text-center text-white px-4">
            @isset($icon)
                <div class="inline-flex items-center justify-center w-12 h-12 bg-white/10 rounded-xl mb-4 mx-auto">
                    {{ $icon }}
                </div>
            @endisset
            <h1 class="text-4xl font-bold mb-2">{{ $title }}</h1>
            @if($subtitle)
                <p class="text-gray-300">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
@else
    <div class="bg-etec-dark text-white {{ $compact ? 'py-12' : 'py-14' }} border-b-4 border-etec-accent">
        <div class="container mx-auto px-4 flex items-center gap-6">
            @isset($icon)
                <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                    {{ $icon }}
                </div>
            @endisset
            <div class="flex-grow">
                @if($label)
                    <p class="text-etec-accent text-xs font-bold uppercase tracking-widest mb-1">{{ $label }}</p>
                @endif
                <h1 class="text-3xl font-bold mb-1">{{ $title }}</h1>
                @if($subtitle)
                    <p class="text-gray-300{{ $label ? ' text-sm' : '' }}">{{ $subtitle }}</p>
                @endif
            </div>
            {{ $actions ?? '' }}
        </div>
        @isset($mobileActions)
            <div class="container mx-auto px-4 mt-4 sm:hidden">
                {{ $mobileActions }}
            </div>
        @endisset
    </div>
@endif
