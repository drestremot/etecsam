@props([
    'name',
    'photo' => null,
    'size' => 64,
    'bg' => 'bg-etec-dark',
    'textSize' => 'text-xl',
])

@php
    $clean = trim(preg_replace('/\s*\([^)]*\)/u', '', (string) $name));
    if ($clean === '') {
        $clean = (string) $name;
    }
    $words = preg_split('/\s+/', $clean);
    $initials = strtoupper(mb_substr($words[0] ?? '', 0, 1));
    if (count($words) > 1) {
        $initials .= strtoupper(mb_substr(end($words), 0, 1));
    }
@endphp

<div {{ $attributes->merge(['class' => "relative rounded-full {$bg} text-white flex items-center justify-center flex-shrink-0"]) }}
     style="width: {{ $size }}px; height: {{ $size }}px;">
    @if($photo)
        <img src="{{ photo_url($photo) }}" alt="{{ $name }}"
             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
             class="w-full h-full object-cover rounded-full scale-[1.15] hover:scale-[1.4375] transition duration-700 ease-in-out">
        <div style="display:none" class="w-full h-full flex items-center justify-center font-bold {{ $textSize }}">{{ $initials }}</div>
    @elseif(isset($fallbackIcon))
        {{ $fallbackIcon }}
    @else
        <div class="w-full h-full flex items-center justify-center font-bold {{ $textSize }}">{{ $initials }}</div>
    @endif
</div>
