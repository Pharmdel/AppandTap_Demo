@extends('layouts.app')

@php
    // Services_screen/service_details_screen.dart ("Services") on white.
    $services = collect($demo['services']);
    $service = $services->firstWhere('id', request()->integer('service')) ?? $services->first();
    $bookable = in_array($service['book_by'], ['app', 'video'], true);
@endphp

@section('content')
<div class="min-h-full bg-white px-[30px] pt-[30px] leading-[1.3] {{ $bookable ? 'pb-20' : 'pb-[50px]' }}">
    <img src="{{ $service['image'] }}" class="mx-auto h-[150px] w-auto object-cover" alt="">
    <div class="mt-[30px] flex items-start">
        <p class="flex-1 text-[22px] font-medium text-black">{{ $service['title'] }}</p>
        @if ($service['price'] > 0)
            <p class="ml-[15px] text-[20px] text-[#B05030]">£{{ $service['price'] }}</p>
        @endif
    </div>
    <div class="mt-[10px] text-[14px] text-black">{{ $service['description'] }}</div>
</div>

@if ($bookable)
    {{-- floatingActionButton (centerDocked): 150 x Get.height * .05, 20px off the bottom. --}}
    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 w-[150px] h-[42.6px] rounded-[5px] shadow-[0_5px_15px_1px_rgba(28,67,50,.2)]">
        <a href="{{ route('app.book-appointment') }}?service={{ $service['id'] }}" class="h-full rounded-[42.6px] bg-primaryColor flex items-center justify-center text-white text-[20px] font-medium tracking-[.4px]">Book Now</a>
    </div>
@else
    <div class="absolute bottom-0 inset-x-0 bg-white px-[30px] py-[10px] flex items-center text-[16px] font-medium">
        <span class="text-black">Booking Availability: </span>
        <span class="ml-[5px] text-primaryColor">By Phone</span>
    </div>
@endif
@endsection
