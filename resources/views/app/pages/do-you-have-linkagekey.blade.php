@extends('layouts.app')

@php
    // gp_linkage_screen/do_you_have_linkagekey_screen.dart: an untitled AppBar on
    // white; the answer to the registration-letter question changes the body.
    $semi = 'text-[#4B4B4B]';
    $tick = fn (string $color) => '<svg class="w-6 h-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="'.$color.'" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>';
@endphp

@section('content')
<div class="min-h-full bg-white px-[30px] pt-[10px] pb-10 leading-[1.3]">
    <p class="text-[18px] font-semibold {{ $semi }}">Do You have a GP linkage key?</p>
    <p class="mt-[10px] text-[14px] font-medium text-[#737373]">Have you received a registration letter from your GP practice?</p>
    <div class="mt-[10px] w-1/2 flex">
        @include('app.partials.radio', ['name' => 'linkage', 'label' => 'Yes', 'checked' => false])
        @include('app.partials.radio', ['name' => 'linkage', 'label' => 'No', 'checked' => false])
    </div>

    <div class="mt-5">
        {{-- linkageReceive == "": what a linkage key is. --}}
        <div data-answer="" class="text-center {{ $semi }}">
            <p class="text-[16px] font-semibold">What is a GP linkage key and how do you get one?</p>
            <p class="mt-[10px] text-[14px] font-medium">A GP linkage key is a unique code created by NHS digital that allows us to connect with your GP records to obtain your repeat prescriptions.</p>
            <p class="mt-[10px] text-[14px] font-medium">You will need to connect your GP Surgery and tell the staff you would like your unique GP linkage key.</p>
            <p class="mt-[10px] text-[14px] font-medium">The practice may need to verify a form of ID in order to give you this information, or choose to send you this via e-mail or to your address with the details you have previously provided them.</p>
            <p class="mt-[10px] text-[16px] font-semibold">Due to Coronavirus (COVID-19) please do not visit your GP surgery unless request to.</p>
            <p class="mt-5 text-[14px] font-medium">The specific code you need is - <span class="text-[15px] font-semibold">Linkage Key or sometimes referred to as a Passphrase.</span></p>
            <p class="mt-5 text-[16px] font-semibold">If you have any concerns or questions about this process you can find all relevant information on <span class="text-[#B05030]">https://digital.rhs.uk</span></p>
        </div>

        {{-- "Yes": the linkage key and account ID. --}}
        <div data-answer="Yes" class="hidden">
            <p class="text-[16px] font-semibold {{ $semi }}">Link to practice</p>
            <div class="mt-[10px]">@include('app.partials.field', ['icon' => 'attachment.svg', 'placeholder' => 'Linkage key', 'line' => '#4B4B4B'])</div>
            <div class="mt-[10px]">@include('app.partials.field', ['icon' => 'account_id.svg', 'placeholder' => 'Account ID', 'line' => '#4B4B4B'])</div>
        </div>

        {{-- "No": how to get one. --}}
        <div data-answer="No" class="hidden {{ $semi }}">
            <p class="text-[16px] font-semibold">Head down to your GP and they’ll provide you with a linkage key.</p>
            <p class="mt-[10px] text-[14px] font-semibold">You may be asked to :</p>
            <p class="mt-[10px] flex items-center gap-[5px] text-[14px]">{!! $tick('#4B4B4B') !!} Provide photo ID  and proof of address.</p>
            <p class="mt-[10px] flex items-center gap-[5px] text-[14px]">{!! $tick('#0069c8') !!} Fill in a short registration form.</p>
            <p class="mt-5 text-[14px]">your GP will give you a letter with the information you need to link your Rxday account. Once you’ve connected online, you will be able to see and instantly order and GP-authorised repeatprescription items. it’s that easy!</p>
        </div>
    </div>

    <a href="{{ route('app.prescriptions-gp-appointments') }}" class="hidden mt-10 h-[45px] rounded-[45px] bg-primaryColor items-center justify-center text-white text-[15px] font-medium tracking-[.4px]" data-linkage-button></a>
</div>

<script>
    document.querySelectorAll('input[name="linkage"]').forEach((radio) => radio.addEventListener('change', () => {
        const answer = radio.closest('label').textContent.trim();
        document.querySelectorAll('[data-answer]').forEach((a) => a.classList.toggle('hidden', a.dataset.answer !== answer));
        const button = document.querySelector('[data-linkage-button]');
        button.textContent = answer === 'Yes' ? 'Confirm' : 'Okay';
        button.classList.replace('hidden', 'flex');
    }));
</script>
@endsection
