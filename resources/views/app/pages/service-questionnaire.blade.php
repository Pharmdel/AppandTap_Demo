@extends('layouts.app')

@php
    // booking_appointment_screen/service_questionnaire_screen.dart: the sheet
    // PopupCustom.showServiceQuestionary() opens for a service's own form.
    $service = collect($demo['services'])->firstWhere('id', request()->integer('service')) ?? $demo['services'][0];
    $questions = [
        ['q' => 'Have you had this vaccination before?', 'type' => 'single', 'options' => ['Yes', 'No'], 'required' => true],
        ['q' => 'Are you currently pregnant?', 'type' => 'single', 'options' => ['Yes', 'No'], 'required' => true],
        ['q' => 'Do you have any of the following conditions?', 'type' => 'multiple', 'options' => ['Asthma', 'Diabetes', 'Heart disease', 'None'], 'required' => true],
        ['q' => 'Any other relevant medical history?', 'type' => 'textarea', 'required' => false],
    ];
    $chip = 'px-[14px] py-[10px] rounded-[10px] bg-[#F1F1F1] flex items-center gap-2 cursor-pointer';
@endphp

@section('content')
<div class="h-full bg-white flex flex-col leading-[1.3]">
    <div class="relative shrink-0 pt-[50px] pl-5 pr-[60px] pb-5 bg-primaryColor rounded-t-[5px]">
        <p class="text-[18px] font-semibold text-white">{{ $service['title'] }} Questionnaire</p>
        <a href="{{ route('app.book-appointment') }}?service={{ $service['id'] }}" class="absolute top-[50px] right-[10px] p-2 rounded-[30px] bg-white" aria-label="Close">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
        </a>
    </div>
    <p class="shrink-0 px-5 py-5 text-[16px] text-black">Please answer the questions below before your appointment.</p>

    <div class="flex-1 overflow-y-auto">
        @foreach ($questions as $i => $question)
            <div class="px-5 pt-5 pb-[10px]" data-question data-type="{{ $question['type'] }}" @if ($question['required']) data-required @endif>
                <div class="flex text-[16px] text-black">
                    <span>{{ $i + 1 }}.&nbsp;</span>
                    <p class="flex-1">{{ $question['q'] }}@if ($question['required'])<span class="text-[#F44336]"> *</span>@endif</p>
                </div>
                <div class="mt-[10px] rounded-[10px] border border-transparent" data-field>
                    @if (in_array($question['type'], ['single', 'multiple'], true))
                        <div class="p-[2px] flex flex-wrap gap-3">
                            @foreach ($question['options'] as $option)
                                <div class="{{ $chip }}" data-option>
                                    <span class="w-[22px] h-[22px] bg-white flex items-center justify-center {{ $question['type'] === 'single' ? 'rounded-full' : 'rounded-[4px]' }}" data-mark></span>
                                    <span class="text-[15px] text-black">{{ $option }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="h-[100px] px-3 pt-1 pb-2 rounded-[10px] bg-[#F1F1F1]">
                            <textarea rows="3" placeholder="Type your answer" class="w-full h-full bg-transparent resize-none outline-none text-[14px] text-black placeholder:text-[#6A6868]"></textarea>
                        </div>
                    @endif
                    <p class="hidden px-3 pt-[6px] pb-1 text-[12px] text-[#F44336]" data-error>This field is required</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="shrink-0 px-5 py-5 flex gap-[30px]">
        <a href="{{ route('app.book-appointment') }}?service={{ $service['id'] }}" class="flex-1 h-[45px] rounded-[45px] border-[1.5px] border-primaryColor flex items-center justify-center text-primaryColor text-[15px] font-bold tracking-[.4px]">Cancel</a>
        <button type="button" class="flex-1 h-[45px] rounded-[45px] bg-primaryColor text-white text-[15px] font-bold tracking-[.4px]" data-submit>Submit</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tick = '<img src="/assets/app/images/check-icon-f.svg" class="w-[18px]" alt="">';
    document.querySelectorAll('[data-question]').forEach((q) => q.querySelectorAll('[data-option]').forEach((opt) => opt.addEventListener('click', () => {
        if (q.dataset.type === 'single') {
            q.querySelectorAll('[data-option]').forEach((o) => { if (o !== opt) { o.classList.remove('is-on'); o.querySelector('[data-mark]').innerHTML = ''; } });
        }
        const on = !opt.classList.contains('is-on');
        opt.classList.toggle('is-on', on);
        opt.querySelector('[data-mark]').innerHTML = on ? tick : '';
    })));
    // Submit: required questions without an answer get a red border and message;
    // the first is scrolled into view. Otherwise the sheet closes back to booking.
    document.querySelector('[data-submit]').addEventListener('click', () => {
        let first = null;
        document.querySelectorAll('[data-question][data-required]').forEach((q) => {
            const ok = q.querySelector('.is-on') || (q.querySelector('textarea')?.value.trim());
            q.querySelector('[data-field]').classList.toggle('border-[#F44336]', !ok);
            q.querySelector('[data-field]').classList.toggle('border-transparent', !!ok);
            q.querySelector('[data-error]').classList.toggle('hidden', !!ok);
            if (!ok && !first) { first = q; }
        });
        if (first) { const list = first.parentElement; list.scrollTo({ top: first.offsetTop - list.offsetTop, behavior: 'smooth' }); return; }
        location.href = @json(route('app.book-appointment').'?service='.$service['id']);
    });
});
</script>
@endsection
