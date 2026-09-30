@extends('layouts.app')

@php $patient = $demo['patient']; @endphp

@section('content')
{{-- gp_linkage_screen/find_your_gp_screen.dart ("Find your GP") on white. --}}
<div class="min-h-full bg-white px-[30px] pt-[30px] leading-[1.3]">
    <p class="text-center text-[22px] font-bold text-primaryColor">Hi, {{ $patient['first_name'] }}</p>
    <p class="mt-[50px] text-[14px] font-semibold text-[#4B4B4B]">Enter your GP practice postcode</p>
    <div class="mt-[10px]">
        @include('app.partials.field', ['icon' => 'gp_linkage_icon.svg', 'placeholder' => 'Enter your GP practice postcode', 'line' => '#4B4B4B', 'attrs' => 'data-gp-postcode'])
    </div>
    {{-- Confirm goes on to the linkage-key question once a postcode is entered. --}}
    <button type="button" class="mt-[30px] w-full h-[45px] rounded-[45px] bg-primaryColor text-white text-[15px] font-medium tracking-[.4px]" data-gp-confirm>Confirm</button>
</div>

<script>
    document.querySelector('[data-gp-confirm]').addEventListener('click', (event) => {
        if (document.querySelector('[data-gp-postcode]').value.trim()) {
            location.href = @json(route('app.do-you-have-linkage-key'));
        } else {
            event.currentTarget.dataset.toast = 'Enter your GP practice postcode.';
        }
    });
</script>
@endsection
