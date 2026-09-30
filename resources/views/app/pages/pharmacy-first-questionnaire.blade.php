@extends('layouts.app')

@php
    // pharmacy_first_service/pharmacy_first_questionary.dart: the full-screen
    // sheet PopupCustom.showPharmacyFirstQuestionary() opens. QuestionaryWidget
    // pages through the sections; QuestionarySummaryWidget reviews the answers.
    $service = collect($demo['services'])->firstWhere('is_pharmacy_first', true);
    $sections = [
        ['section' => 1, 'questions' => [
            ['q' => 'Do you have a sore throat?', 'type' => 'single', 'options' => ['Yes', 'No']],
            ['q' => 'Have you had these symptoms for more than 7 days?', 'type' => 'single', 'options' => ['Yes', 'No']],
            ['q' => 'Do you have any of the following?', 'type' => 'multiple', 'options' => ['Fever above 38°C', 'Swollen glands in your neck', 'White spots on your tonsils', 'None of the above']],
        ]],
        ['section' => 2, 'questions' => [
            ['q' => 'Are you pregnant or breastfeeding?', 'type' => 'single', 'options' => ['Yes', 'No']],
            ['q' => 'Are you allergic to penicillin?', 'type' => 'single', 'options' => ['Yes', 'No']],
        ]],
    ];
    $count = count($sections);
    $chip = 'px-[15px] py-2 mt-[10px] rounded-[10px] bg-greyLightColor flex items-center cursor-pointer';
@endphp

@section('content')
<div class="h-full bg-white flex flex-col leading-[1.3]">
    <div class="relative shrink-0 pt-[50px] pl-5 pr-[60px] pb-5 bg-primaryColor rounded-t-[5px]">
        <p class="mt-[5px] text-[18px] font-semibold text-white">{{ $service['title'] }}</p>
        <a href="{{ route('app.book-appointment') }}?service={{ $service['id'] }}" class="absolute top-[50px] right-[10px] p-2 rounded-[30px] bg-white" aria-label="Close">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg>
        </a>
    </div>

    {{-- QuestionaryWidget --}}
    <div class="flex-1 min-h-0 flex flex-col" data-questions>
        <div class="shrink-0 p-[30px] flex flex-col items-center">
            <p class="text-[18px] font-semibold text-black">Section <span data-section-no>1</span>/{{ $count }}</p>
            <div class="mt-[10px] w-[85%] h-[5px] rounded-[5px] bg-[#EEEEEE] overflow-hidden">
                <div class="h-full rounded-[5px] bg-primaryColor transition-all" style="width: {{ 100 / $count }}%" data-progress></div>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto px-[30px]">
            @foreach ($sections as $s => $section)
                <div class="{{ $s === 0 ? '' : 'hidden' }}" data-section="{{ $s }}">
                    @foreach ($section['questions'] as $i => $question)
                        <div class="pb-5" data-question data-type="{{ $question['type'] }}">
                            <div class="flex text-[16px] text-black"><span>{{ $i + 1 }}.&nbsp;</span><p class="flex-1">{{ $question['q'] }}</p></div>
                            @if ($question['type'] === 'single')
                                <div class="flex">
                                    @foreach ($question['options'] as $option)
                                        <div class="flex-1 min-w-0 mr-5 {{ $chip }}" data-option>
                                            <span class="w-6 h-6 shrink-0 p-[3px] rounded-full bg-white" data-mark></span>
                                            <span class="ml-[10px] text-[16px] text-black truncate">{{ $option }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                @foreach ($question['options'] as $option)
                                    <div class="{{ $chip }}" data-option>
                                        <span class="w-6 h-6 shrink-0 p-[3px] rounded-[4px] bg-white" data-mark></span>
                                        <span class="ml-[10px] flex-1 text-[16px] text-black line-clamp-3">{{ $option }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
        <div class="shrink-0 px-5 py-5">
            <div class="hidden p-[10px] rounded-[10px] bg-[#4CAF50]/20 border border-greenColor flex items-center gap-[10px] text-greenColor text-[16px]" data-eligible>
                <svg class="w-6 h-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
                You are eligible for this service.
            </div>
            <div class="mt-5 flex gap-10">
                <div class="flex-1">
                    <button type="button" class="hidden w-full h-[45px] rounded-[45px] border-[1.5px] border-primaryColor text-primaryColor text-[15px] font-bold tracking-[.4px]" data-prev>Previous</button>
                </div>
                <button type="button" class="flex-1 h-[45px] rounded-[45px] bg-primaryColor text-white text-[15px] font-bold tracking-[.4px]" data-next>Next</button>
            </div>
        </div>
    </div>

    {{-- QuestionarySummaryWidget --}}
    <div class="hidden flex-1 min-h-0 flex flex-col" data-summary>
        <p class="shrink-0 px-[10px] py-[10px] text-[18px] font-semibold text-primaryColor">Summary</p>
        <div class="flex-1 overflow-y-auto" data-summary-list></div>
        <div class="shrink-0 px-5 py-5 flex gap-5">
            <button type="button" class="w-[100px] h-[45px] rounded-[45px] border-[1.5px] border-primaryColor text-primaryColor text-[15px] font-bold tracking-[.4px]" data-edit>Edit</button>
            <a href="{{ route('app.book-appointment') }}?service={{ $service['id'] }}" class="flex-1 h-[45px] rounded-[45px] bg-primaryColor flex items-center justify-center text-white text-[15px] font-bold tracking-[.4px]">Save &amp; Book Appointment</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tick = '<img src="/assets/app/images/check-icon-f.svg" class="w-full h-full" alt="">';
    const sections = [...document.querySelectorAll('[data-section]')];
    let current = 0;

    // Single answers replace each other; multiple answers toggle.
    document.querySelectorAll('[data-question]').forEach((q) => q.querySelectorAll('[data-option]').forEach((opt) => opt.addEventListener('click', () => {
        if (q.dataset.type === 'single') {
            q.querySelectorAll('[data-option]').forEach((o) => { o.classList.remove('is-on'); o.querySelector('[data-mark]').innerHTML = ''; });
        }
        const on = !opt.classList.contains('is-on');
        opt.classList.toggle('is-on', on);
        opt.querySelector('[data-mark]').innerHTML = on ? tick : '';
        sync();
    })));

    const answered = (section) => [...section.querySelectorAll('[data-question]')].every((q) => q.querySelector('.is-on'));
    const sync = () => {
        document.querySelector('[data-section-no]').textContent = current + 1;
        document.querySelector('[data-progress]').style.width = ((current + 1) / sections.length * 100) + '%';
        document.querySelector('[data-prev]').classList.toggle('hidden', current === 0);
        document.querySelector('[data-eligible]').classList.toggle('hidden', !(current === sections.length - 1 && answered(sections[current])));
        sections.forEach((s, i) => s.classList.toggle('hidden', i !== current));
    };

    document.querySelector('[data-prev]').addEventListener('click', () => { current--; sync(); });
    document.querySelector('[data-next]').addEventListener('click', () => {
        const missing = [...sections[current].querySelectorAll('[data-question]')].find((q) => !q.querySelector('.is-on'));
        if (missing) { const list = missing.closest('.overflow-y-auto'); list.scrollTo({ top: missing.offsetTop - list.offsetTop - list.clientHeight * 0.2, behavior: 'smooth' }); return; }
        if (current < sections.length - 1) { current++; sync(); return; }
        // Last section: show the summary of every answer.
        const list = document.querySelector('[data-summary-list]');
        list.innerHTML = '';
        sections.forEach((s, i) => {
            const block = s.cloneNode(true);
            block.classList.remove('hidden');
            block.querySelectorAll('[data-option]:not(.is-on)').forEach((o) => o.remove());
            const wrap = document.createElement('div');
            wrap.className = 'px-5';
            wrap.innerHTML = '<p class="text-[18px] font-semibold text-primaryColor">Section ' + (i + 1) + '</p><hr class="my-[7px] border-[#F1F1F1]">';
            wrap.appendChild(block);
            list.appendChild(wrap);
        });
        document.querySelector('[data-questions]').classList.add('hidden');
        document.querySelector('[data-summary]').classList.remove('hidden');
    });
    document.querySelector('[data-edit]').addEventListener('click', () => {
        current = 0; sync();
        document.querySelector('[data-summary]').classList.add('hidden');
        document.querySelector('[data-questions]').classList.remove('hidden');
    });
});
</script>
@endsection
