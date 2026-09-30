@props(['title' => '', 'back' => true])

{{-- widgets/back_appBar.dart CustomAppBar.appBarWithoutAction(): 60px, the
     back-arrow.svg (12x20, 20px padding) in the 56px leading slot, and a
     20px bold white title with titleSpacing 0. --}}
<div class="h-[60px] shrink-0 bg-primaryColor flex items-center">
    @if ($back)
        <button type="button" onclick="history.back()" class="w-14 h-full shrink-0 flex items-center justify-center" aria-label="Back">
            <img src="/assets/app/images/back-arrow.svg" class="w-3 h-5 brightness-0 invert" alt="">
        </button>
    @else
        <span class="w-[30px] shrink-0"></span>
    @endif
    <p class="text-white font-bold text-[20px] leading-[1.3] truncate pr-4">{{ $title }}</p>
</div>
