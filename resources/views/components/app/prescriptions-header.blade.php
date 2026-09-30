@php
    // prescription_screen.dart AppBar: back arrow + "Prescriptions", the
    // dependent picker (only when the account has more than one person, which
    // makes the bar 160 tall instead of 128), then the three tabs.
    $patient = $demo['patient'];
    $people = [$patient['first_name'].' '.$patient['last_name'], ...array_column($demo['dependents'], 'name')];
    $tabs = ['rx-orders' => 'Rx Orders', 'repeat-meds' => 'Repeat Meds', 'gp-appointments' => 'GP Appointments'];
    $active = array_key_exists(request('tab'), $tabs) ? request('tab') : 'rx-orders';
@endphp

<div class="{{ count($people) > 1 ? 'h-[160px]' : 'h-[128px]' }} shrink-0 bg-primaryColor shadow-[0_4px_10px_rgba(241,241,241,.9)] px-4 flex flex-col justify-center text-white leading-[1.3] relative z-[1]">
    <div class="flex items-center">
        <button type="button" onclick="history.back()" class="pr-5" aria-label="Back">
            <img src="/assets/app/images/back-arrow.svg" class="w-3 h-5 brightness-0 invert" alt="">
        </button>
        <p class="text-[20px] font-bold">Prescriptions</p>
    </div>
    @if (count($people) > 1)
        <div class="px-[30px]">
            <x-app.dependent-picker :arrow="30" />
        </div>
    @endif
    <div class="mt-[10px] h-10 flex">
        @foreach ($tabs as $key => $label)
            <button type="button" data-rx-tab="{{ $key }}" class="flex-1 min-w-0 truncate text-[14px] border-b-2 {{ $key === $active ? 'border-white font-medium' : 'border-transparent' }}">{{ $label }}</button>
        @endforeach
    </div>
</div>
