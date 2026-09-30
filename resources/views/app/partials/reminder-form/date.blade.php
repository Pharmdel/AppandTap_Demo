{{-- A 45px white date field with a greySemiDark underline and the calendar icon; tapping opens the picker. --}}
<label class="relative h-[45px] px-5 bg-white border-b border-[#737373] flex items-center cursor-pointer">
    <span class="text-[14px] text-[#737373]" data-date-label>{{ $placeholder }}</span>
    <span class="ml-auto w-5 h-5 bg-[#737373] [mask:url('/assets/app/images/calender_icon.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/calender_icon.svg')_center/contain_no-repeat]"></span>
    <input type="date" class="absolute inset-0 opacity-0 cursor-pointer"
        onclick="this.showPicker && this.showPicker()"
        onchange="this.closest('label').querySelector('[data-date-label]').textContent = this.value ? this.value.split('-').reverse().join('-') : '{{ $placeholder }}'">
</label>
