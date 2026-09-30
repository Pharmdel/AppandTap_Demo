{{-- PopupCustom.servicesPopup() (widgets/pop_up.dart -> ServicesPopup), behind both "See all"s. --}}
@php $services = collect($demo['services']); @endphp
<div id="servicesPopup" class="hidden absolute inset-0 z-50 bg-[#D8D8D8]/70 flex items-center justify-center px-[30px] py-[60px]">
    <div class="w-full max-h-full bg-white rounded-[5px] overflow-hidden flex flex-col leading-[1.3]">
        <div class="shrink-0 bg-primaryColor px-5 py-[10px] flex flex-col items-center text-white">
            <span class="w-[60px] h-[60px] bg-white [mask:url('/assets/app/images/services_header_icon.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/services_header_icon.svg')_center/contain_no-repeat]"></span>
            <p class="mt-[5px] text-[22px] font-semibold">Services</p>
        </div>
        <div class="flex-1 overflow-y-auto">
            @foreach ($services as $i => $service)
                <a href="{{ route('app.service-details') }}?service={{ $service['id'] }}" class="px-[15px] py-[15px] flex items-center {{ $i % 2 === 0 ? 'bg-white' : 'bg-[#F1F1F1]' }}">
                    <span class="w-[35px] h-[35px] shrink-0 rounded-full overflow-hidden border border-[#F1F1F1]">
                        <img src="{{ $service['image'] }}" class="w-full h-full object-cover" alt="">
                    </span>
                    <span class="ml-[10px] flex-1 text-[15px] text-[#737373]">{{ $service['title'] }}</span>
                    @include('app.partials.forward-arrow', ['size' => 15, 'color' => '#737373'])
                </a>
            @endforeach
        </div>
        <div class="shrink-0 pt-[30px] pb-5 flex justify-center">
            <button type="button" data-close class="w-[118px] h-10 rounded-[40px] bg-primaryColor text-white text-[15px] font-bold tracking-[.4px]">OK</button>
        </div>
    </div>
</div>
