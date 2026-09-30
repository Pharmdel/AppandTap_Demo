@extends('layouts.app')

@php
    $dateIcon = '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="#737373"><path d="M7 11h2v2H7v-2zm14-5v14c0 1.1-.9 2-2 2H5a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h1V2h2v2h8V2h2v2h1c1.1 0 2 .9 2 2zM5 8h14V6H5v2zm14 12V10H5v10h14zm-4-7h2v-2h-2v2zm-4 0h2v-2h-2v2z"/></svg>';
@endphp

@section('content')
{{-- prescription_screen/add_dependent_screen.dart ("Add Dependent") on white. --}}
{{-- The Scaffold has no colour of its own: Material 3 surface shows below the white form. --}}
<div class="min-h-full bg-[#FEF7FF] leading-[1.3]">
<div class="bg-white px-[30px] py-[10px]">
    <div class="flex flex-col gap-[10px]">
        @include('app.partials.field', ['icon' => 'user.png', 'placeholder' => 'First Name'])
        @include('app.partials.field', ['icon' => 'user.png', 'placeholder' => 'Last Name'])
        @include('app.partials.field', ['svg' => $dateIcon, 'placeholder' => 'DOB', 'type' => 'date'])

        {{-- widgets/custom_dropdown.dart: gender icon, "Select Gender" hint, 40px arrow_drop_down. --}}
        <label class="relative flex items-end h-12 border-b border-[#737373]">
            <span class="shrink-0 px-[10px] pb-[10px]"><span class="block w-6 h-6 bg-[#737373] [mask:url('/assets/app/images/gender_icon.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/gender_icon.svg')_center/contain_no-repeat]"></span></span>
            <select class="flex-1 pb-[10px] appearance-none bg-transparent outline-none text-[16px] text-black invalid:text-[#6A6868] invalid:text-[14px]" required>
                <option value="" selected disabled>Select Gender</option>
                @foreach (['male', 'female', 'indeterminate', 'not known'] as $gender)
                    <option>{{ $gender }}</option>
                @endforeach
            </select>
            <svg class="absolute right-0 bottom-0 w-10 h-10 pointer-events-none" viewBox="0 0 24 24" fill="#737373"><path d="M7 10l5 5 5-5z"/></svg>
        </label>

        @include('app.partials.field', ['icon' => 'email_address.svg', 'placeholder' => 'Email', 'type' => 'email'])
        @include('app.partials.field', ['icon' => 'mobile_icon.svg', 'placeholder' => 'Contact Number', 'type' => 'tel'])
    </div>

    <p class="mt-5 text-[16px] font-medium text-[#737373]">Verified by</p>
    <div class="mt-[10px] w-1/2 flex">
        @include('app.partials.radio', ['name' => 'verified_by', 'label' => 'Email', 'checked' => true])
        @include('app.partials.radio', ['name' => 'verified_by', 'label' => 'Verbally', 'checked' => false])
    </div>

    {{-- Save, and a Cancel that is also a primary mainButton, only with a red border. --}}
    <div class="mt-5 mb-5 flex gap-5">
        <a href="{{ route('app.prescriptions-rx-orders') }}" class="flex-1 h-[45px] rounded-[45px] bg-primaryColor flex items-center justify-center text-white text-[16px] font-medium tracking-[.4px]">Save</a>
        <button type="button" onclick="history.back()" class="flex-1 h-[45px] rounded-[45px] bg-primaryColor border border-[#B05030] text-white text-[16px] font-medium tracking-[.4px]">Cancel</button>
    </div>
</div>
</div>
@endsection
