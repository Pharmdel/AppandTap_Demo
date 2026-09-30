@extends('layouts.app')

@php
    $patient = $demo['patient'];
    // Back from the search with a pharmacy chosen (selectedPharmacyId).
    $chosen = collect($demo['pharmacy_directory'])->firstWhere('id', request()->integer('selected'));
    $detailIcon = fn (string $file) => "<span class=\"w-5 h-5 shrink-0 bg-[#737373]\" style=\"mask:url('/assets/app/images/{$file}') center/contain no-repeat;-webkit-mask:url('/assets/app/images/{$file}') center/contain no-repeat\"></span>";
@endphp

@section('content')
{{-- dashboard_screen/search_pharmecy_screen.dart _buildSearchView(): shown while
     the account has no pharmacy (profileData.pharmacyUuid == null). --}}
<div class="min-h-full bg-white px-[30px] pb-5 flex flex-col items-center leading-[1.3]">
    <div class="mt-10 px-5 py-[25px] rounded-[10px] bg-primaryColor">
        <img src="/assets/app/images/app_logo.png" class="w-[255px]" alt="AppAndTap">
    </div>
    <p class="mt-[30px] text-center text-[20px] font-bold text-primaryColor">{{ $patient['first_name'] }} {{ $patient['last_name'] }}</p>
    <p class="mt-7 text-center text-[14px] text-[#737373]">To view pharmacy services and order your repeat prescriptions please nominate your community pharmacy</p>
    <a href="{{ route('app.choose-pharmacy-search') }}" class="mt-[14px] w-full h-[55px] px-[10px] py-[10px] rounded-[5px] border border-[#737373] flex items-center gap-[10px]">
        <span class="w-5 h-5 shrink-0 bg-[#737373] [mask:url('/assets/app/images/location_icon.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/location_icon.svg')_center/contain_no-repeat]"></span>
        <span class="flex-1 text-[14px] text-[#737373]">Search Pharmacy Name/Postcode</span>
        <span class="w-5 h-5 shrink-0 bg-[#737373] [mask:url('/assets/app/images/search_icon_new.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/search_icon_new.svg')_center/contain_no-repeat]"></span>
    </a>

    @if ($chosen)
        <p class="mt-5 text-center text-[18px] font-semibold text-black underline">Nominated Pharmacy</p>
        <div class="mt-[10px] w-full rounded-[5px] border border-[#737373] text-[14px] text-black">
            @foreach ([['pharmacy.svg', $chosen['name']], ['telephone_icon.svg', $chosen['phone']], ['location_icon.svg', $chosen['location'].' - '.$chosen['postcode']]] as [$icon, $text])
                <div class="px-[10px] py-3 flex items-center gap-[10px] {{ $loop->last ? '' : 'border-b border-[#737373]' }}">{!! $detailIcon($icon) !!}<span>{{ $text }}</span></div>
            @endforeach
        </div>
        <a href="{{ route('app.awaiting-approval') }}" class="mt-5 w-full h-[45px] rounded-[45px] bg-primaryColor flex items-center justify-center text-white text-[15px] font-medium tracking-[.4px]">Nominate this pharmacy</a>
    @endif
</div>
@endsection
