@extends('layouts.app')

@php
    // prescription_screen/request_medication_screen.dart on white. The item and
    // prices are hard-coded in the app itself ("Candes tan 16mg Tablets", $9.00).
    $box = 'rounded-[20px] border border-black';
    $label = 'text-[14px] font-medium text-black';
    $row = 'text-[14px] font-medium text-primaryColor';
@endphp

@section('content')
<div class="min-h-full bg-white px-5 py-[10px] leading-[1.3]">
    <p class="text-center text-[30px] font-bold text-primaryColor">Request Medication</p>

    <p class="mt-[10px] {{ $label }}">Review your order</p>
    <div class="mt-[10px] {{ $box }} p-[15px] text-primaryColor text-[14px]">
        <p class="font-medium">Candes tan 16mg Tablets</p>
        <p class="mt-[5px]">56 tablets</p>
    </div>

    <p class="mt-[10px] {{ $label }}">Order Summary</p>
    <div class="mt-[10px] {{ $box }} overflow-hidden">
        <div class="p-[15px] flex justify-between {{ $row }}"><span>Items</span><span>1</span></div>
        <hr class="my-2 border-black">
        <div class="px-[15px] flex justify-between {{ $row }}"><span>Delivery Charge</span><span>$9.00</span></div>
        <div class="mt-[10px] p-[15px] bg-black rounded-b-[15px] flex justify-between text-[14px] font-medium text-white"><span>Total</span><span>$9.00</span></div>
    </div>

    {{-- SelectCustomRadioBotton with a black border: a bordered pill. --}}
    <p class="mt-[10px] {{ $label }}">Choose delivery option</p>
    <div class="mt-[10px] flex rounded-[30px] border border-black">@include('app.partials.radio', ['name' => 'delivery', 'label' => 'Collect at pharmacy', 'checked' => true])</div>

    <p class="mt-[10px] {{ $label }}">Do you have an exemption?</p>
    <div class="mt-[10px] flex rounded-[30px] border border-black">@include('app.partials.radio', ['name' => 'exemption', 'label' => 'Yes, I have exemption', 'checked' => false])</div>
    <div class="mt-[10px] flex rounded-[30px] border border-black">@include('app.partials.radio', ['name' => 'exemption', 'label' => 'I don’t have exemption', 'checked' => true])</div>

    <p class="mt-[10px] {{ $label }}">Payment Type</p>
    <div class="mt-[10px] flex rounded-[30px] border border-black">@include('app.partials.radio', ['name' => 'payment', 'label' => 'Online Payment', 'checked' => true])</div>
    <div class="mt-[10px] flex rounded-[30px] border border-black">@include('app.partials.radio', ['name' => 'payment', 'label' => 'Payment In Pharmacy', 'checked' => false])</div>

    <div class="mt-5 flex justify-center">
        <a href="{{ route('app.prescriptions-repeat-meds') }}" class="w-[80%] h-[45px] rounded-[45px] bg-primaryColor flex items-center justify-center text-white text-[20px] font-medium tracking-[.4px]">Save</a>
    </div>
</div>
@endsection
