@extends('layouts.app')

@php $patient = $demo['patient']; @endphp

@section('content')
{{-- dashboard_screen/search_pharmecy_screen.dart _buildApprovalWait(): shown
     while the nominated pharmacy hasn't approved the account yet. --}}
<div class="min-h-full bg-white px-5 flex flex-col items-center text-center leading-[1.3]">
    <p class="text-[20px] font-bold text-primaryColor">Hi, {{ $patient['first_name'] }} {{ $patient['last_name'] }}</p>
    <img src="/assets/app/images/pharmacy_service_image.svg" class="mt-[30px]" alt="">
    <p class="mt-5 text-[16px] font-semibold text-black">Please wait...</p>
    <p class="mt-[5px] text-[16px] text-[#737373]">Your request sent to pharmacy, waiting for Pharmacy Approval.</p>
    <a href="{{ route('app.awaiting-approval') }}" class="mt-5 w-[236px] h-[50px] p-2 rounded-[30px] bg-primaryColor flex items-center justify-center gap-[10px] text-white text-[16px] font-medium">
        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19.5 12a7.5 7.5 0 1 1-2.2-5.3"/><path d="M19.5 4.5v3.8h-3.8"/></svg>
        Refresh
    </a>
</div>
@endsection
