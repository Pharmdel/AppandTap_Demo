{{-- widgets/custom_radio_button.dart SelectCustomRadioBotton: an Expanded row with a
     1.3x Radio (primaryColor when on) and a 14px greySemiDark label. --}}
<label class="flex-1 min-w-0 flex items-center py-2 cursor-pointer">
    <input type="radio" name="{{ $name }}" @checked($checked) class="w-[26px] h-[26px] shrink-0 accent-primaryColor m-[5px]">
    <span class="ml-[5px] text-[14px] text-[#737373]">{{ $label }}</span>
</label>
