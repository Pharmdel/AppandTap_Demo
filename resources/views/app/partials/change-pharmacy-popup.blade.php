{{-- PopupCustom.changePharmacyPopUp() -> ChangePharmacyPopUp (widgets/popup_custom.dart). --}}
@php $pharmacy = $demo['pharmacy']; @endphp
<div id="changePharmacyPopup" class="hidden absolute inset-0 z-50 bg-[#D8D8D8]/70 flex items-center justify-center px-5">
    <div class="w-full bg-white rounded-[5px] overflow-hidden leading-[1.3]">
        <p class="bg-primaryColor px-5 py-[15px] text-white text-[20px] font-semibold text-center">Change Pharmacy</p>
        <div class="p-[30px]">
            <p class="text-[14px] font-medium text-[#4B4B4B]">Nominated Pharmacy</p>
            <div class="mt-[10px] px-[10px] py-[15px] bg-[#F1F1F1] border-b border-[#737373] flex items-center gap-[10px] text-[14px]">
                <span class="w-5 h-5 shrink-0 bg-[#737373] [mask:url('/assets/app/images/pharmacy.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/pharmacy.svg')_center/contain_no-repeat]"></span>
                {{ $pharmacy['name'] }}
            </div>
            <p class="mt-[30px] text-[14px] font-medium">Choose New Pharmacy</p>
            <label class="border-b border-[#737373] flex items-center gap-[10px] py-3">
                <span class="w-5 h-5 shrink-0 bg-[#737373] [mask:url('/assets/app/images/pharmacy.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/pharmacy.svg')_center/contain_no-repeat]"></span>
                <input type="text" placeholder="Search Pharmacy Name/Postcode" class="flex-1 min-w-0 bg-transparent outline-none text-[14px] placeholder:text-[#737373]">
            </label>
            <div class="pt-5 flex gap-5">
                <button type="button" data-close class="flex-1 h-[45px] rounded-[45px] border-[1.5px] border-primaryColor text-primaryColor text-[15px] font-bold tracking-[.4px]">Cancel</button>
                <button type="button" data-toast="Please select pharmacy" class="flex-1 h-[45px] rounded-[45px] bg-primaryColor text-white text-[15px] font-medium tracking-[.4px]">Done</button>
            </div>
        </div>
    </div>
</div>
