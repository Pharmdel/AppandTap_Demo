@php
    // my__account_screen.dart buildContainer(): textColor/imageColor tint the
    // label and icon; arrowColor/dividerColor default to primaryColor.
    $text = $text ?? '#000';
    $iconColor = $iconColor ?? '#0069c8';
    $accent = $accent ?? '#0069c8';
    $mask = "url('/assets/app/images/{$icon}') center/contain no-repeat";
@endphp
<div class="text-left">
    <a @if ($url) href="{{ $url }}" @endif class="px-[30px] py-[10px] flex items-center justify-between cursor-pointer">
        <span class="flex items-center gap-[10px]">
            @if ($iconColor === 'none')
                <img src="/assets/app/images/{{ $icon }}" style="width: {{ $size[0] }}px; height: {{ $size[1] }}px" alt="">
            @else
                <span class="shrink-0" style="width: {{ $size[0] }}px; height: {{ $size[1] }}px; background-color: {{ $iconColor }}; mask: {{ $mask }}; -webkit-mask: {{ $mask }};"></span>
            @endif
            <span class="text-[15px] font-medium" style="color: {{ $text }}">{{ $label }}</span>
        </span>
        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="{{ $accent }}" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 4 8 8-8 8" /></svg>
    </a>
    <div class="px-5 py-2"><hr style="border-color: {{ $accent }}"></div>
</div>
