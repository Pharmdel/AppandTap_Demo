@extends('layouts.app')

@php
    // lib/screens/dashboard_screen/home_screen.dart, built from
    // lib/widgets/home_widgets.dart. Widths use Get.width = 393 (iPhone 15 Pro).
    $services = collect($demo['services'])->take(5);
    $reminders = collect($demo['reminders']);
    $appointments = collect($demo['appointments'])->where('tab', 'upcoming')->values();
    $heading = 'text-[15px] font-bold text-black';
    $seeAll = 'text-[13px] font-bold text-secondaryColor';
    $cardBorder = 'border border-black/[.06]';
    $appointmentWidth = match ($appointments->count()) { 1 => 353, 2 => 171.5, default => 149.34 };
    // Each booking shows its own service's icon rather than one generic image.
    // (Looked up from the full catalog, not $services, which is only the first 5.)
    $serviceImages = collect($demo['services'])->pluck('image', 'title');
    $apptImage = fn (array $a) => $serviceImages[$a['service']] ?? '/assets/app/images/service_image.png';
@endphp

@section('content')
<div class="leading-[1.3]">

    {{-- buildTopButtons(): a 13px strip carries the AppBar colour down, and
         the two Cards (4px default margin) straddle it. --}}
    <div class="relative">
        <div class="absolute inset-x-0 top-0 h-[13px] bg-primaryColor"></div>
        <div class="relative flex gap-[10px] px-[15px] pb-[10px]">
            @foreach ([
                ['title' => 'Prescriptions', 'image' => 'home_nhs_icon.jpg', 'url' => route('app.prescriptions-rx-orders')],
                ['title' => 'Appointments', 'image' => 'appointment-icon.png', 'url' => route('app.appointments-list')],
            ] as $box)
                <a href="{{ $box['url'] }}" class="flex-1 min-w-0 m-1 bg-white rounded-[15px] overflow-hidden">
                    <div class="p-[15px] {{ $cardBorder }}">
                        <div class="w-[50px] h-[50px] px-[5px] py-[10px] rounded-[12px] bg-secondaryColor/[.12] flex items-center justify-center">
                            <img src="/assets/app/images/{{ $box['image'] }}" class="max-h-[28px] max-w-full object-contain" alt="">
                        </div>
                        <p class="mt-[10px] text-[13px] font-bold text-black truncate">{{ $box['title'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- buildPharmacyServices(): autoplaying carousel, viewportFraction 0.7, padEnds false. --}}
    <div class="pl-5 pb-5">
        <div class="flex items-center justify-between pr-5">
            <p class="{{ $heading }}">Pharmacy&nbsp; Services</p>
            <button type="button" data-open="#servicesPopup" class="{{ $seeAll }}">See all</button>
        </div>
        <div class="mt-[10px] h-[130px] flex overflow-x-auto snap-x snap-mandatory [scrollbar-width:none] [&::-webkit-scrollbar]:hidden" data-carousel>
            @foreach ($services as $service)
                <div class="w-[70%] shrink-0 snap-start pr-[10px]">
                    <div class="h-full bg-white rounded-[15px] {{ $cardBorder }} p-[15px] flex items-center justify-between overflow-hidden">
                        <div class="flex-1 min-w-0 h-full flex flex-col justify-between items-start">
                            <p class="text-[14px] font-bold text-black leading-[1.4] line-clamp-2">{{ $service['title'] }}</p>
                            <a href="{{ route('app.service-details') }}?service={{ $service['id'] }}" class="mt-[10px] w-[100px] h-[30px] rounded-[30px] bg-primaryColor text-white text-[12px] font-bold flex items-center justify-center">Book</a>
                        </div>
                        <div class="ml-[10px] w-[100px] h-[100px] shrink-0 rounded-[10px] bg-white overflow-hidden">
                            <img src="{{ $service['image'] }}" class="w-full h-full object-cover" alt="">
                        </div>
                    </div>
                </div>
            @endforeach
            {{-- padEnds: false still lets the last page settle at the left edge. --}}
            <div class="w-[30%] shrink-0"></div>
        </div>
    </div>

    {{-- buildTodayReminders(): at most two cards; the first is the red one. --}}
    <div>
        <div class="flex items-center justify-between px-5">
            <div class="flex items-center">
                <p class="{{ $heading }}">Reminders</p>
                @if ($reminders->isNotEmpty())
                    <span class="ml-[6px] px-2 py-[2px] rounded-[15px] bg-secondaryColor text-white text-[12px] font-semibold">{{ $reminders->count() === 1 ? 1 : 2 }}/{{ $reminders->count() }}</span>
                @endif
            </div>
            <a href="{{ route('app.my-day') }}" class="{{ $seeAll }}">See all</a>
        </div>
        <div class="mt-[10px] flex items-stretch overflow-x-auto pl-5 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            @foreach ($reminders->take(2) as $i => $reminder)
                @php
                    $isLast = $i === min(2, $reminders->count()) - 1;
                    $status = ['taken' => 'Taken', 'missed' => 'Missed'][$reminder['status_today']] ?? null;
                @endphp
                <div class="shrink-0 {{ $isLast ? 'pr-5' : 'pr-[10px]' }}" style="width: {{ ($reminders->count() < 2 ? 393 * 0.9 : 393 * 0.67) + ($isLast ? 20 : 10) }}px">
                    <div class="h-full rounded-[15px] p-[15px] border {{ $i === 0 ? 'bg-[#FFF8F4] border-[#B05030]/[.05]' : 'bg-[#F6FAF8] border-secondaryColor/[.12]' }}" data-reminder>
                        <p class="text-[14px] font-bold text-secondaryColor leading-[1.2] truncate">{{ $reminder['medicine'] }}</p>
                        <p class="text-[20px] font-bold text-black leading-none truncate">{{ $reminder['times'][0] }}</p>
                        <p class="mt-[5px] text-[12px] text-[#8A9A95]">Today</p>
                        <p class="mt-[5px] text-[12px] text-[#8A9A95] truncate">{{ $reminder['dose'] }}</p>
                        <div class="mt-[6px]" data-reminder-actions>
                            @if ($status === null)
                                <div class="pt-2 flex flex-wrap gap-2">
                                    <button type="button" data-reminder-status="Taken" class="px-5 py-2 rounded-[5px] bg-greenLightColor text-greenColor text-[13px] font-semibold">Taken</button>
                                    <button type="button" data-reminder-status="Missed" class="px-5 py-2 rounded-[5px] bg-[#B05030]/10 text-[#B05030] text-[13px] font-semibold">Skip</button>
                                </div>
                            @else
                                @include('app.partials.reminder-status', ['status' => $status])
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- buildUpcomingAppointments() --}}
    <div class="pl-5 pt-5 pb-[15px]">
        <div class="flex items-center justify-between pr-5">
            <p class="{{ $heading }} truncate">Upcoming appointment</p>
            <a href="{{ route('app.appointments-list') }}" class="{{ $seeAll }}">See all</a>
        </div>
        <div class="mt-[15px] flex items-stretch overflow-x-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            @foreach ($appointments as $appt)
                <div class="shrink-0 pr-2">
                    <div class="h-full p-[15px] rounded-[15px] {{ $cardBorder }} bg-white flex flex-col justify-between" style="width: {{ $appointmentWidth }}px">
                        <div>
                            <span class="w-[50px] h-[50px] rounded-full bg-white overflow-hidden flex items-center justify-center">
                                <img src="{{ $apptImage($appt) }}" class="w-full h-full object-cover" alt="">
                            </span>
                            <p class="mt-[6px] text-[14px] font-bold text-black line-clamp-2">{{ $appt['service'] }}</p>
                            <div class="mt-[5px] flex">
                                <p class="flex-1 text-[15px] font-medium text-[#8A9A95]">{{ $appt['slot'] }}</p>
                                @if ($appt['status'] === 'Cancelled')
                                    <p class="flex-1 text-right text-[10px] font-medium text-[#B05030]">Cancelled</p>
                                @endif
                            </div>
                            <p class="text-[13px] font-medium text-[#8A9A95]">{{ $appt['date'] }}</p>
                        </div>
                        @if (! empty($appt['meeting_link']))
                            <a href="{{ route('app.video-conference') }}" class="mt-[5px] self-start inline-flex items-center gap-[2px] px-[5px] py-[2px] rounded-[5px] text-white text-[14px] font-medium {{ $appt['is_meeting_active'] ? 'bg-primaryColor' : 'bg-gradient-to-b from-[#BDBDBD] to-[#9E9E9E] opacity-50 pointer-events-none' }}">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h8A2.5 2.5 0 0 1 16 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-8A2.5 2.5 0 0 1 3 16.5v-9Zm14.5 2.3 3.1-2.2A.9.9 0 0 1 22 8.3v7.4a.9.9 0 0 1-1.4.7l-3.1-2.2V9.8Z"/></svg>
                                Join
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- buildReminderDayRow() --}}
    <div class="px-5 py-[10px] flex gap-[10px]">
        @foreach ([
            ['title' => 'Reminder', 'icon' => 'reminder-icon.svg', 'bg' => 'bg-greenColor', 'url' => route('app.reminders-all')],
            ['title' => 'My Day', 'icon' => 'myday-icon.svg', 'bg' => 'bg-primaryColor', 'url' => route('app.my-day')],
        ] as $tile)
            <a href="{{ $tile['url'] }}" class="flex-1 min-w-0 h-[70px] pt-[10px] pb-[10px] pl-[15px] pr-[10px] rounded-[15px] {{ $tile['bg'] }} flex items-center justify-between">
                <span class="text-[18px] font-medium text-white truncate">{{ $tile['title'] }}</span>
                <img src="/assets/app/images/{{ $tile['icon'] }}" class="ml-[10px] mt-[10px] w-[25px] h-[25px] shrink-0" alt="">
            </a>
        @endforeach
    </div>
</div>

@push('overlays')
    @include('app.partials.services-popup')
@endpush

<template id="reminder-status-taken">@include('app.partials.reminder-status', ['status' => 'Taken'])</template>
<template id="reminder-status-missed">@include('app.partials.reminder-status', ['status' => 'Missed'])</template>

<script>
    (function () {
        // HomeController.onTapTakenMissed: the buttons give way to the status row.
        document.querySelectorAll('[data-reminder-status]').forEach((button) => button.addEventListener('click', () => {
            const template = document.getElementById('reminder-status-' + button.dataset.reminderStatus.toLowerCase());
            button.closest('[data-reminder-actions]').replaceChildren(template.content.cloneNode(true));
        }));

        // CarouselOptions(autoPlay: true): 4s interval, back to the start after the last page.
        const carousel = document.querySelector('[data-carousel]');
        let paused = false;
        ['pointerdown', 'mouseenter'].forEach((e) => carousel.addEventListener(e, () => { paused = true; }));
        carousel.addEventListener('mouseleave', () => { paused = false; });
        setInterval(() => {
            if (paused) { return; }
            const page = carousel.firstElementChild.offsetWidth;
            const pages = carousel.querySelectorAll('.snap-start').length;
            const next = Math.round(carousel.scrollLeft / page) + 1;
            carousel.scrollTo({ left: (next >= pages ? 0 : next) * page, behavior: 'smooth' });
        }, 4000);
    })();
</script>
@endsection
