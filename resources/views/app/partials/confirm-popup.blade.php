{{-- PopupCustom.deleteConfirmation() -> DeleteConfirmationPopUp (widgets/popup_custom.dart). --}}
<div id="{{ $id }}" class="hidden absolute inset-0 z-50 bg-black/[.54] flex items-center justify-center px-10">
    <div class="w-full bg-white rounded-[5px] overflow-hidden leading-[1.3]">
        <p class="bg-primaryColor px-[10px] py-[10px] text-white text-[18px] font-medium text-center">{{ $title }}</p>
        <div class="px-6 pb-6 text-center">
            <p class="pt-5 text-[16px] text-black">{{ $body }}</p>
            <div class="pt-[30px] flex gap-[30px]">
                <button type="button" data-close class="flex-1 h-[45px] rounded-[45px] border-[1.5px] border-primaryColor text-primaryColor text-[15px] font-bold tracking-[.4px]">Cancel</button>
                <button type="button" data-close data-confirm class="flex-1 h-[45px] rounded-[45px] bg-primaryColor text-white text-[15px] font-medium tracking-[.4px]">Yes</button>
            </div>
        </div>
    </div>
</div>
