@extends('layouts.app')

@section('content')
{{-- dashboard_screen/search_pharmecy_screen_new.dart: a 100px primary AppBar
     holding the search box; results once 3+ characters are typed (350ms
     debounce). Choosing one returns to the pharmacy gate with it selected. --}}
<div class="h-full bg-white flex flex-col leading-[1.3]">
    <div class="h-[100px] shrink-0 bg-primaryColor rounded-t-[5px] pl-[30px] pr-[30px] flex items-center">
        <label class="w-full h-12 rounded-[30px] border border-white flex items-center">
            <input type="search" autofocus placeholder="Search Pharmacy Name/Postcode" class="flex-1 min-w-0 h-full bg-transparent outline-none px-5 text-[14px] text-white placeholder:text-[#6A6868] caret-white [&::-webkit-search-cancel-button]:hidden" data-pharmacy-search>
            <span class="mr-[15px] w-[18px] h-[18px] shrink-0 bg-white [mask:url('/assets/app/images/search_icon_new.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/search_icon_new.svg')_center/contain_no-repeat]"></span>
        </label>
    </div>

    <div class="flex-1 overflow-y-auto px-[30px] pt-[30px]">
        <p class="h-full flex items-center justify-center px-3 text-center text-[16px] text-black" data-search-hint>Enter your pharmacy name, postal code or address to search pharmacies.</p>
        <div class="hidden flex-col gap-[10px] pb-5" data-search-results>
            @foreach ($demo['pharmacy_directory'] as $ph)
                <a href="{{ route('app.pharmacy-gate') }}?selected={{ $ph['id'] }}" data-result="{{ strtolower($ph['name'].' '.$ph['location'].' '.$ph['postcode']) }}"
                    class="px-[10px] py-[10px] rounded-[8px] border border-[#A4A4A4] flex items-center gap-3 text-[#393837]">
                    <span class="flex-1 min-w-0">
                        <span class="block text-[16px] font-semibold">{{ $ph['name'] }}</span>
                        <span class="block text-[14px]">{{ $ph['location'] }}</span>
                        <span class="block text-[14px]">{{ $ph['postcode'] }}</span>
                    </span>
                    {{-- _CustomRadio --}}
                    <span class="w-[22px] h-[22px] shrink-0 rounded-full border-2 border-[#737373]"></span>
                </a>
            @endforeach
        </div>
    </div>
</div>

<script>
    (function () {
        const input = document.querySelector('[data-pharmacy-search]');
        const hint = document.querySelector('[data-search-hint]');
        const results = document.querySelector('[data-search-results]');
        let timer;
        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                const q = input.value.trim().toLowerCase();
                let shown = 0;
                results.querySelectorAll('[data-result]').forEach((r) => {
                    const match = q.length >= 3 && r.dataset.result.includes(q);
                    r.classList.toggle('hidden', !match);
                    shown += match ? 1 : 0;
                });
                const searched = q.length >= 3;
                results.classList.toggle('hidden', !(searched && shown));
                results.classList.toggle('flex', searched && shown > 0);
                hint.classList.toggle('hidden', searched && shown > 0);
                hint.textContent = searched && !shown ? 'No pharmacies found.' : 'Enter your pharmacy name, postal code or address to search pharmacies.';
            }, 350);
        });
    })();
</script>
@endsection
