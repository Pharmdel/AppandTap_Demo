@extends('layouts.app')

@php
    // pharmecy_screens/pharmacy_screen.dart body: up to six services on the
    // page background, with PopupCustom.servicesPopup() behind "See all".
    $services = collect($demo['services']);
@endphp

@section('content')
<div class="px-[15px] py-[10px] leading-[1.3]">
    <div class="px-[15px] py-[10px] flex items-center justify-between">
        <p class="text-[20px] font-bold text-black">Pharmacy&nbsp; Services</p>
        <button type="button" data-open="#servicesPopup" class="px-2 py-[2px] rounded-[5px] bg-primaryColor text-white text-[14px]">See all</button>
    </div>
    @foreach ($services->take(6) as $service)
        @unless ($loop->first)
            <hr class="border-[#8A9A95]/30">
        @endunless
        <a href="{{ route('app.service-details') }}?service={{ $service['id'] }}" class="px-[15px] py-[15px] flex items-center">
            <span class="w-[35px] h-[35px] shrink-0 rounded-full overflow-hidden border border-[#F1F1F1] bg-[#F1F1F1]">
                <img src="{{ $service['image'] }}" class="w-full h-full object-cover" alt="">
            </span>
            <span class="ml-[10px] flex-1 text-[14px] font-medium text-black">{{ $service['title'] }}</span>
            @include('app.partials.forward-arrow', ['size' => 20, 'color' => '#0069c8'])
        </a>
    @endforeach
</div>

@push('overlays')
    @include('app.partials.services-popup')
@endpush
@endsection
