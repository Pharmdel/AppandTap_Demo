@php
    // widgets/custom_text_field.dart TextFieldSimple: UnderlineInputBorder
    // (the borderRadius callers pass has no effect on an underline), the label
    // shown as a never-floating placeholder, and a 24px-high prefix icon with
    // 10px either side. Pass 'icon' (asset file) or 'svg' (inline markup).
    $type = $type ?? 'text';
    $line = $line ?? '#737373';
    $attrs = $attrs ?? '';
@endphp
<label class="relative flex items-end h-12 border-b cursor-text" style="border-color: {{ $line }}">
    <span class="shrink-0 px-[10px] pb-[10px]">
        @isset($icon)
            <span class="block w-6 h-6 bg-[#737373]" style="mask: url('/assets/app/images/{{ $icon }}') center/contain no-repeat; -webkit-mask: url('/assets/app/images/{{ $icon }}') center/contain no-repeat;"></span>
        @else
            {!! $svg !!}
        @endisset
    </span>
    @if ($type === 'date')
        <span class="flex-1 pb-[13px] text-[14px] text-[#6A6868]" data-date-label>{{ $placeholder }}</span>
        <input type="date" class="absolute inset-0 opacity-0 cursor-pointer" onclick="this.showPicker && this.showPicker()" {!! $attrs !!}
            onchange="const l = this.closest('label').querySelector('[data-date-label]'); l.textContent = this.value ? this.value.split('-').reverse().join('/') : @js($placeholder); l.classList.toggle('text-black', !!this.value);">
    @else
        <input type="{{ $type }}" placeholder="{{ $placeholder }}" value="{{ $value ?? '' }}" {!! $attrs !!}
            class="flex-1 min-w-0 pb-[13px] pr-5 bg-transparent outline-none text-[14px] text-black placeholder:text-[#6A6868]">
    @endif
</label>
