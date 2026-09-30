@php
    // admin/includes/patient/patient-info.blade.php, Patient Info tab, when the
    // group runs PGD / the IP clinic: the additional-info tag boxes, then
    // livewire/admin/patient/patient-vital-history.blade.php.
    $clinical = $demo['staff_patient_clinical'][$patient['id']] ?? null;
    $tagBoxes = [
        'allergies' => ['Allergies', 'No allergies recorded', 'e.g. Nut Allergy, Penicillin…'],
        'medicines' => ['Current Medicines', 'No Current Medication', 'e.g. Metformin 500mg, Aspirin…'],
        'history' => ['Relevant medical history', 'No medical history recorded', 'e.g. Type 2 Diabetes, Hypertension…'],
        'family' => ['Family history', 'No family history recorded', 'e.g. Type 2 Diabetes, Hypertension…'],
        'lifestyle' => ['Lifestyle', 'No lifestyle recorded', 'e.g. Type 2 Diabetes, Hypertension…'],
        'examination' => ['Examination', 'No examination history recorded', 'e.g. Type 2 Diabetes, Hypertension…'],
    ];
    $vitals = $clinical['vitals'] ?? [];
    $updated = $clinical['updated'] ?? '14 Sep 2026, 01:11 PM';
    $updatedDay = \Illuminate\Support\Str::before($updated, ',');
    $icons = [
        'height' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="{STROKE}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="2" x2="8" y2="22" /><line x1="8" y1="2" x2="12" y2="2" /><line x1="8" y1="22" x2="12" y2="22" /><line x1="8" y1="6" x2="11" y2="6" /><line x1="8" y1="10" x2="12" y2="10" /><line x1="8" y1="14" x2="11" y2="14" /><line x1="8" y1="18" x2="12" y2="18" /><line x1="8" y1="4" x2="10" y2="4" /><line x1="8" y1="8" x2="10" y2="8" /><line x1="8" y1="12" x2="10" y2="12" /><line x1="8" y1="16" x2="10" y2="16" /><line x1="8" y1="20" x2="10" y2="20" /></svg>',
        'weight' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#22c55e" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="3" x2="12" y2="20" /><line x1="4" y1="20" x2="20" y2="20" /><line x1="5" y1="7" x2="19" y2="7" /><path d="M5 7 C3 9 3 12 5 12 C7 12 7 9 5 7" /><path d="M19 7 C17 9 17 12 19 12 C21 12 21 9 19 7" /><circle cx="12" cy="3" r="1" fill="#22c55e" stroke="none" /></svg>',
        'bmi' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#f97316" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" /><rect x="7" y="5" width="10" height="4" rx="1" /><circle cx="8" cy="13" r="0.8" fill="#f97316" /><circle cx="12" cy="13" r="0.8" fill="#f97316" /><circle cx="16" cy="13" r="0.8" fill="#f97316" /><circle cx="8" cy="17" r="0.8" fill="#f97316" /><circle cx="12" cy="17" r="0.8" fill="#f97316" /><circle cx="16" cy="17" r="0.8" fill="#f97316" /></svg>',
        'respiratory_rate' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#14b8a6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="3" x2="12" y2="10" /><path d="M12 10 C12 10 8 10 6 13 C4 16 4 19 6 20 C8 21 10 20 10 18 L10 14" /><path d="M12 10 C12 10 16 10 18 13 C20 16 20 19 18 20 C16 21 14 20 14 18 L14 14" /></svg>',
        'temperature' => '<svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z" /></svg>',
        'blood_pressure' => '<svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12" /></svg>',
        'heart_rate' => '<svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" /></svg>',
    ];
    $band = function (?float $value, array $bands) {
        if ($value === null) {
            return ['N/A', 'text-gray-400', 'bg-gray-300'];
        }
        foreach ($bands as [$max, $label, $text, $dot]) {
            if ($value <= $max) {
                return [$label, $text, $dot];
            }
        }

        return ['N/A', 'text-gray-400', 'bg-gray-300'];
    };
    $bmiBands = [[18.4, 'Low', 'text-blue-500', 'bg-blue-400'], [24.9, 'Normal', 'text-green-500', 'bg-green-400'], [29.9, 'Overweight', 'text-orange-400', 'bg-orange-400'], [INF, 'Obese', 'text-red-500', 'bg-red-500']];
    $cards = [
        ['key' => 'height', 'label' => 'Height', 'unit' => 'ft/in', 'bg' => ($vitals['height'] ?? null) ? 'bg-blue-50' : 'bg-gray-100', 'status' => ($vitals['height'] ?? null) ? ['Recorded', 'text-[#3b82f6]', 'bg-[#3b82f6]'] : ['N/A', 'text-gray-400', 'bg-gray-300'], 'history' => false],
        ['key' => 'weight', 'label' => 'Weight', 'unit' => 'kg', 'bg' => 'bg-green-50', 'status' => $band($vitals['bmi'] ?? null, $bmiBands), 'history' => true],
        ['key' => 'bmi', 'label' => 'BMI', 'unit' => 'kg/m²', 'bg' => 'bg-orange-50', 'status' => $band($vitals['bmi'] ?? null, $bmiBands), 'history' => true],
        ['key' => 'respiratory_rate', 'label' => 'Respiratory Rate', 'unit' => 'breaths/min', 'bg' => 'bg-teal-50', 'status' => $band($vitals['respiratory_rate'] ?? null, [[11, 'Low', 'text-blue-500', 'bg-blue-400'], [20, 'Normal', 'text-green-500', 'bg-green-400'], [INF, 'High', 'text-red-500', 'bg-red-500']]), 'history' => true],
        ['key' => 'temperature', 'label' => 'Body Temperature', 'unit' => '°C', 'bg' => 'bg-red-50', 'status' => $band($vitals['temperature'] ?? null, [[36.0, 'Low', 'text-blue-500', 'bg-blue-400'], [37.2, 'Normal', 'text-green-500', 'bg-green-400'], [INF, 'High', 'text-red-500', 'bg-red-500']]), 'history' => true],
        ['key' => 'blood_pressure', 'label' => 'Blood Pressure', 'unit' => 'mmHg', 'bg' => 'bg-purple-50', 'status' => isset($vitals['blood_pressure']) ? ['Normal', 'text-green-500', 'bg-green-400'] : ['N/A', 'text-gray-400', 'bg-gray-300'], 'history' => true],
        ['key' => 'heart_rate', 'label' => 'Resting Heart Rate', 'unit' => 'BPM', 'bg' => 'bg-red-50', 'status' => $band($vitals['heart_rate'] ?? null, [[59, 'Low', 'text-blue-500', 'bg-blue-400'], [100, 'Normal', 'text-green-500', 'bg-green-400'], [INF, 'High', 'text-red-500', 'bg-red-500']]), 'history' => true],
    ];
@endphp
<template id="tag-template">
    <span class="tag-item inline-flex items-center gap-1.5 bg-blue-50 border border-blue-200 text-[#0070D5] text-xs font-medium px-2.5 py-1 rounded-full transition group">
        <span class="[overflow-wrap:anywhere]"></span>
        <button type="button" data-tag-remove class="btn-remove-tag w-3.5 h-3.5 flex items-center justify-center rounded-full text-blue-300 hover:bg-red-100 hover:text-red-500 transition focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </span>
</template>
<!-- BEGIN: Addi Info -->
<div class="intro-y box p-5 mt-3" data-additional-info>
    <div class="grid gap-6 grid-cols-3">
        @foreach ($tagBoxes as $key => [$label, $empty, $placeholder])
            <div class="flex flex-col gap-2" data-tag-box="{{ $key }}">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-[#454646]">{{ $label }}</span>
                    <a href="javascript:void(0)" data-tag-add class="inline-flex items-center gap-1 text-xs font-semibold text-darkBlue transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14" /></svg>
                        Add
                    </a>
                </div>
                <div class="border border-slate-200 rounded-md px-3 py-2 flex flex-wrap gap-2 min-h-[40px] items-center bg-white" data-tag-list>
                    @foreach ($clinical[$key] ?? [] as $tag)
                        <span class="tag-item inline-flex items-center gap-1.5 bg-blue-50 border border-blue-200 text-[#0070D5] text-xs font-medium px-2.5 py-1 rounded-full transition group">
                            <span class="[overflow-wrap:anywhere]">{{ $tag }}</span>
                            <button type="button" data-tag-remove class="btn-remove-tag w-3.5 h-3.5 flex items-center justify-center rounded-full text-blue-300 hover:bg-red-100 hover:text-red-500 transition focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                            </button>
                        </span>
                    @endforeach
                    <span class="empty-text text-xs text-slate-400 italic {{ empty($clinical[$key]) ? '' : 'hidden' }}" data-tag-empty>{{ $empty }}</span>
                </div>
                <div class="hidden flex items-center gap-2" data-tag-input-wrapper>
                    <div class="relative flex-1">
                        <input type="text" placeholder="{{ $placeholder }}" data-tag-input
                            class="w-full border border-[#0070D5] rounded-md px-3 py-1.5 text-[13px] text-[#454646] placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-100 transition pr-20">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none">↵ Enter</span>
                    </div>
                    <button type="button" data-tag-cancel class="btn-cancel-add text-xs text-slate-400 hover:text-slate-600 transition whitespace-nowrap">Cancel</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
<!-- End: Addi Info -->

<!-- BEGIN: Vitals info -->
<div class="intro-y box p-5 mt-3">
    <div class="bg-white rounded-2xl">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12" /></svg>
                <h2 class="text-sm font-bold text-[#454646]">Patient Vitals</h2>
            </div>
            <div class="flex items-right justify-between pt-3">
                <span class="text-xs text-gray-400">Last updated:</span>
                <span class="text-xs text-gray-400 ml-1">{{ $updated }}</span>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-3 mb-3">
            @foreach ($cards as $card)
                @php
                    [$statusText, $statusColor, $dot] = $card['status'];
                    $value = $vitals[$card['key']] ?? null;
                    $icon = str_replace('{STROKE}', $value ? '#3b82f6' : '#9ca3af', $icons[$card['key']]);
                @endphp
                <a href="javascript:void(0)" @if ($card['history']) data-popup-open="vital-history-{{ $card['key'] }}" @endif>
                    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 {{ $card['bg'] }} rounded-md flex items-center justify-center">{!! $icon !!}</div>
                                <span class="text-xs text-gray-500 font-medium">{{ $card['label'] }}</span>
                            </div>
                            <span class="w-2 h-2 rounded-full {{ $dot }} inline-block"></span>
                        </div>
                        <div class="mb-1">
                            <span class="text-2xl font-bold {{ $value ? 'text-gray-800' : 'text-gray-400' }}">{{ $value ?? ' ' }}</span>
                            <span class="text-xs text-gray-400 ml-1">{{ $card['unit'] }}</span>
                        </div>
                        <p class="text-xs {{ $statusColor }} font-medium mb-4">{{ $statusText }}</p>
                        <p class="text-xs text-gray-300 mt-3">{{ $updatedDay }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
<!-- End Vitals info -->

{{-- patient-vital-history-chart.blade.php: a reading's history --}}
@foreach ($cards as $card)
    @continue(! $card['history'])
    @php
        $current = $vitals[$card['key']] ?? null;
        $numeric = is_numeric($current) ? (float) $current : (is_string($current) ? (float) \Illuminate\Support\Str::before($current, '/') : null);
        $series = $numeric === null ? [] : array_map(fn ($offset) => round($numeric * (1 + $offset), 1), [0.04, 0.03, -0.01, 0.02, 0]);
        $dates = ['14 May', '12 Jun', '10 Jul', '14 Aug', $updatedDay];
    @endphp
    <x-pharmacy.popup id="vital-history-{{ $card['key'] }}" box="max-w-[750px] p-7 rounded-[36px]">
        <div class="p-6 bg-white rounded-3xl h-full flex flex-col max-h-[80vh] min-h-[400px] pt-0">
            <div class="flex items-center justify-between gap-2 mb-3">
                <h2 class="text-twilight-blue font-bold text-sm">{{ $card['label'] }} History</h2>
            </div>
            @if ($series)
                <div class="relative" style="height: 260px;"><canvas data-vital-chart='@json(['labels' => $dates, 'data' => $series, 'label' => $card['label']])'></canvas></div>
            @else
                <div class="py-6 text-center text-xs text-twilight-blue font-semibold">No record found</div>
            @endif
        </div>
    </x-pharmacy.popup>
@endforeach

@push('scripts')
<script>
    (function () {
        // Tag boxes: "+ Add" opens the input, Enter adds, × removes (upstream renderTags()).
        document.querySelectorAll('[data-tag-box]').forEach((box) => {
            const wrapper = box.querySelector('[data-tag-input-wrapper]');
            const input = box.querySelector('[data-tag-input]');
            const list = box.querySelector('[data-tag-list]');
            const empty = box.querySelector('[data-tag-empty]');
            const syncEmpty = () => empty.classList.toggle('hidden', list.querySelectorAll('.tag-item').length > 0);
            box.querySelector('[data-tag-add]').addEventListener('click', () => { wrapper.classList.remove('hidden'); input.focus(); });
            box.querySelector('[data-tag-cancel]').addEventListener('click', () => { wrapper.classList.add('hidden'); input.value = ''; });
            input.addEventListener('keydown', (event) => {
                if (event.key !== 'Enter' || !input.value.trim()) { return; }
                event.preventDefault();
                const tag = document.getElementById('tag-template').content.firstElementChild.cloneNode(true);
                tag.querySelector('span').textContent = input.value.trim();
                list.insertBefore(tag, empty);
                input.value = '';
                syncEmpty();
            });
            list.addEventListener('click', (event) => {
                const remove = event.target.closest('[data-tag-remove]');
                if (remove) { remove.closest('.tag-item').remove(); syncEmpty(); }
            });
        });

        // History charts are drawn once their popup has a size.
        document.addEventListener('popup:open', (event) => {
            const canvas = document.querySelector(`#${event.detail.id} [data-vital-chart]`);
            if (!canvas || canvas.dataset.drawn || !window.Chart) { return; }
            canvas.dataset.drawn = '1';
            const cfg = JSON.parse(canvas.dataset.vitalChart);
            new Chart(canvas, {
                type: 'line',
                data: { labels: cfg.labels, datasets: [{ label: cfg.label, data: cfg.data, borderColor: '#0070D5', backgroundColor: '#0070D5', tension: 0.3, borderWidth: 3 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } } } },
            });
        });
    })();
</script>
@endpush
