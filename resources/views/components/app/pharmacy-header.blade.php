@php
    // pharmecy_screens/pharmacy_screen.dart AppBar: toolbarHeight Get.height * .30
    // with rounded bottom corners; the title column is centred in it.
    $pharmacy = $demo['pharmacy'];
    $now = now('Europe/London');
    $today = collect($pharmacy['hours'])->firstWhere('day', $now->format('l'));
    $isOpen = $today && $today['open'] && $now->format('H:i') >= $today['open'] && $now->format('H:i') < $today['close'];
    $icon = fn (string $file, int $w, int $h) => "<span class=\"shrink-0 bg-white\" style=\"width:{$w}px;height:{$h}px;mask:url('/assets/app/images/{$file}') center/contain no-repeat;-webkit-mask:url('/assets/app/images/{$file}') center/contain no-repeat\"></span>";
    $chip = 'shrink-0 px-[10px] py-[3px] rounded-[5px] bg-white text-black text-[12px] leading-[1.3]';
@endphp

<div class="h-[255.6px] shrink-0 bg-primaryColor rounded-b-[20px] shadow-[0_3px_6px_rgba(241,241,241,.8)] px-4 flex items-center text-white text-[14px] leading-[1.3] relative z-[1]">
    <div class="w-full py-5">
        <p class="text-center text-[25px] font-medium">Pharmacy</p>
        <hr class="my-2 border-white">
        <div class="py-[5px] flex items-center">
            {!! $icon('pharmacy.svg', 20, 20) !!}
            <span class="ml-[10px] min-w-0">{{ $pharmacy['name'] }}</span>
            <button type="button" data-open="#changePharmacyPopup" class="ml-[5px] {{ $chip }}">Change Pharmacy</button>
        </div>
        <div class="py-[10px] flex items-start">
            {!! $icon('phone_call_icon.svg', 20, 20) !!}
            <span class="ml-[10px] flex-1">{{ $pharmacy['phone'] }}</span>
        </div>
        <div class="py-[10px] flex items-start">
            {!! $icon('location_icon.svg', 20, 20) !!}
            <span class="ml-[10px] flex-1">{{ $pharmacy['address'] }}, {{ $pharmacy['postcode'] }}</span>
        </div>
        <div class="py-[5px] flex items-center">
            {!! $icon('hour_icon.svg', 20, 17) !!}
            <span class="ml-[10px]">Hours: {{ $isOpen ? 'Open Now' : 'Closed Now' }}</span>
            <button type="button" data-open="#openingHoursPopup" class="ml-[5px] {{ $chip }}">Opening Hours</button>
        </div>
    </div>
</div>

{{-- Dialogs are absolutely positioned inside the phone screen (not native
     <dialog>) so they stay within the mockup. Barrier: greyLightColor at 70%. --}}
<div id="openingHoursPopup" class="hidden absolute inset-0 z-50 bg-[#D8D8D8]/70 flex items-center justify-center px-[30px]">
    <div class="w-full bg-white rounded-[5px] overflow-hidden leading-[1.3]">
        <div class="bg-primaryColor px-5 py-[10px] flex flex-col items-center text-white">
            <span class="w-[115px] h-[73px] bg-white [mask:url('/assets/app/images/watch_icon.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/watch_icon.svg')_center/contain_no-repeat]"></span>
            <p class="mt-[10px] text-[20px] font-semibold">Opening Hours</p>
        </div>
        <div class="pb-[10px]">
            @foreach ($pharmacy['hours'] as $i => $h)
                <div class="px-[15px] py-[15px] flex justify-between text-[15px] {{ $i % 2 === 0 ? 'bg-white' : 'bg-[#F1F1F1]' }}">
                    <span>{{ $h['day'] }}</span>
                    @if ($h['open'])
                        <span class="text-primaryColor">({{ $h['open'] }} - {{ $h['close'] }})</span>
                    @else
                        <span class="text-[#B05030]">(Closed)</span>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="pt-[30px] pb-5 flex justify-center">
            <button type="button" data-close class="w-[118px] h-10 rounded-[40px] bg-primaryColor text-white text-[15px] font-bold tracking-[.4px]">OK</button>
        </div>
    </div>
</div>

@include('app.partials.change-pharmacy-popup')
