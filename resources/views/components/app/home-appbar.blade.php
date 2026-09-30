@php
    // dashboard_screen.dart AppBar (toolbarHeight 70): the time-of-day greeting
    // over "Hello, {name}", and the chat icon with its unread count.
    $patient = $demo['patient'];
    $hour = now('Europe/London')->hour;
    $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
@endphp

{{-- The 1px same-colour shadow hides the sub-pixel seam above the body's blue strip when the preview scales the app down. --}}
<div class="h-[70px] shrink-0 bg-primaryColor flex items-center justify-between pl-4 pr-5 shadow-[0_1px_0_#0069c8]">
    <div>
        <p class="text-[12px] font-medium leading-[1.3] text-[#7BAF9E] uppercase" data-greeting>{{ $greeting }}</p>
        <p class="mt-[2px] font-DMSerif text-[22px] font-bold leading-[1.3] text-white">Hello, {{ $patient['first_name'] }}</p>
    </div>
    <a href="{{ route('app.chat') }}" class="relative w-10 h-10 flex items-center justify-center">
        <span class="w-[30px] h-[30px] bg-secondaryColor [mask:url('/assets/app/images/chat.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/chat.svg')_center/contain_no-repeat]"></span>
        <span class="absolute top-0 right-0 w-5 h-5 rounded-full bg-[#F44336] text-white text-[10px] leading-none flex items-center justify-center">2</span>
    </a>
</div>
<script>
    // The app greets by the device's own clock (DateTime.now()).
    (function () {
        const hour = new Date().getHours();
        document.querySelector('[data-greeting]').textContent = hour < 12 ? 'Good Morning' : (hour < 17 ? 'Good Afternoon' : 'Good Evening');
    })();
</script>
