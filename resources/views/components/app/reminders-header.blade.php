@php
    // reminder_screen/all_reminder_screen.dart AppBar: 160 tall with the
    // dependent picker (more than one person on the account), else 80.
    $multiple = count($demo['dependents']) > 0;
@endphp

<div class="{{ $multiple ? 'h-[160px]' : 'h-20' }} shrink-0 bg-primaryColor shadow-[0_3px_6px_rgba(241,241,241,.9)] px-4 flex flex-col justify-center text-white leading-[1.3] relative z-[1]">
    <div class="flex items-center">
        <button type="button" onclick="history.back()" class="pr-5" aria-label="Back">
            <img src="/assets/app/images/back-arrow.svg" class="w-3 h-5 brightness-0 invert" alt="">
        </button>
        <p class="text-[20px] font-bold">Reminders</p>
    </div>
    <div class="h-[10px]"></div>
    @if ($multiple)
        <div class="px-[30px] pb-5 mb-[15px]">
            <x-app.dependent-picker :arrow="40" />
        </div>
    @endif
</div>
