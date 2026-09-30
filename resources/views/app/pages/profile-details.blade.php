@extends('layouts.app')

@php
    // account_screen/patient_profile_details_screen.dart ("Patient Details") on
    // white. buildDetails() shows only the icon and value (its title is unused).
    $patient = $demo['patient'];
    $pharmacy = $demo['pharmacy'];
    $rows = [
        ['icon' => 'user_icon.svg', 'value' => $patient['first_name'].' '.$patient['last_name']],
        ['icon' => 'calender_icon.svg', 'value' => $patient['dob']],
        ['icon' => 'mobile_icon.svg', 'value' => $patient['contact_number'], 'editable' => true],
        ['icon' => 'email_address.svg', 'value' => $patient['email']],
    ];
@endphp

@section('content')
<div class="min-h-full bg-white leading-[1.3]">
    <div class="px-[30px] py-5">
        @foreach ($rows as $row)
            <div class="mt-[15px] h-[60px] py-2 bg-white border-b border-[#737373] flex items-center">
                <span class="w-[22px] h-[22px] shrink-0 bg-[#737373]" style="mask: url('/assets/app/images/{{ $row['icon'] }}') center/contain no-repeat; -webkit-mask: url('/assets/app/images/{{ $row['icon'] }}') center/contain no-repeat;"></span>
                <p class="ml-[15px] flex-1 text-[15px] font-medium text-[#737373] overflow-hidden" @if ($row['editable'] ?? false) data-mobile @endif>{{ $row['value'] }}</p>
                @if ($row['editable'] ?? false)
                    <button type="button" data-open="#addMobilePopup" class="ml-[15px] p-2 bg-white" aria-label="Edit">
                        <span class="block w-5 h-5 bg-primaryColor [mask:url('/assets/app/images/editp_icon.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/editp_icon.svg')_center/contain_no-repeat]"></span>
                    </button>
                @endif
            </div>
        @endforeach
    </div>

    {{-- myPharmacy() --}}
    <div class="mt-[10px] px-[30px] py-5">
        <p class="text-[18px] font-medium text-black">Nominated Pharmacy</p>
        <div class="mt-[10px] flex items-center">
            <span class="w-[60px] h-[60px] shrink-0 rounded-full bg-greenColor p-2 flex items-center justify-center">
                <span class="block w-full h-full bg-white [mask:url('/assets/app/images/pharmacy_image.png')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/pharmacy_image.png')_center/contain_no-repeat]"></span>
            </span>
            <div class="ml-[10px] flex-1 text-[15px]">
                <p class="font-medium text-black">{{ $pharmacy['name'] }}</p>
                <p class="font-light text-[#737373]">{{ $pharmacy['address'] }}</p>
            </div>
        </div>
        <div class="mt-5 flex justify-center">
            <button type="button" data-open="#changePharmacyPopup" class="p-2 text-[15px] font-medium text-[#B05030] underline">Change Pharmacy</button>
        </div>
    </div>
</div>

@push('overlays')
    @include('app.partials.change-pharmacy-popup')

    {{-- PopupCustom.showAddMobileNoPopup() -> AddMobileNoPopup. --}}
    <div id="addMobilePopup" class="hidden absolute inset-0 z-50 bg-black/[.54] flex items-center justify-center px-10">
        <form class="w-full bg-white rounded-[5px] overflow-hidden leading-[1.3]" data-mobile-form>
            <p class="bg-primaryColor px-[10px] py-[10px] text-white text-[20px] font-medium text-center">Update Mobile Number</p>
            <div class="px-5 pt-5">
                <p class="mt-[10px] text-[16px] text-black">Enter your mobile number.</p>
                <input type="tel" maxlength="13" value="{{ str_replace(' ', '', $patient['contact_number']) }}" placeholder="Enter mobile number" class="mt-[10px] w-full px-[10px] py-3 border border-[#737373] rounded-[5px] text-[14px] outline-none focus:border-primaryColor">
                <p class="hidden mt-1 text-[12px] text-[#CB2A2F]" data-mobile-error>Please enter a valid mobile number</p>
            </div>
            <div class="p-5 flex gap-5">
                <button type="button" data-close class="flex-1 h-[45px] rounded-[45px] border-[1.5px] border-primaryColor text-primaryColor text-[15px] font-bold tracking-[.4px]">Cancel</button>
                <button type="submit" class="flex-1 h-[45px] rounded-[45px] bg-primaryColor text-white text-[15px] font-medium tracking-[.4px]">Submit</button>
            </div>
        </form>
    </div>
@endpush

<script>
document.addEventListener('DOMContentLoaded', () => {
    // The popup's validator: ^\+?[0-9]{10,13}$; a valid number replaces the row's value.
    document.querySelector('[data-mobile-form]').addEventListener('submit', (event) => {
        event.preventDefault();
        const input = event.target.querySelector('input');
        const valid = /^\+?[0-9]{10,13}$/.test(input.value);
        event.target.querySelector('[data-mobile-error]').classList.toggle('hidden', valid);
        if (!valid) { return; }
        document.querySelector('[data-mobile]').textContent = input.value;
        event.target.closest('#addMobilePopup').classList.add('hidden');
    });
});
</script>
@endsection
