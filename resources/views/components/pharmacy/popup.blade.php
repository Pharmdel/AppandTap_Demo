@props(['id', 'box' => 'max-w-[90%] p-7 rounded-[36px]'])

{{-- The dashboard widgets' Livewire popups (<section x-show> with the
     gradient close circle). public/js/pharmacy-portal.js opens it for any
     [data-popup-open="{{ $id }}"] and closes it from [data-popup-close]. --}}
<section id="{{ $id }}" class="hidden" data-popup>
    <div class="fixed inset-0 bg-black bg-opacity-25 z-[99]"></div>
    <div class="fixed transition duration-300 left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 transform w-full custom_height border border-[#989898] bg-white z-[99] rounded-xl m-5 {{ $box }}">
        {{ $slot }}

        <!-- close button -->
        <a href="javascript:;" data-popup-close
            class="fixed z-[999] top-0 right-0 w-9 h-9 rounded-full bg-gradient-to-t from-[#002B56] to-[#0070D5] flex justify-center items-center -translate-y-1/2 translate-x-1/2"><svg
                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke="#fff"
                class="fill-current text-white" stroke-width="1.5" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </a>
    </div>
</section>
