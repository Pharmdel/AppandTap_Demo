@props(['active'])

@php
    $tabs = [
        ['id' => 'prescriptions-rx-orders', 'label' => 'Rx Orders'],
        ['id' => 'prescriptions-repeat-meds', 'label' => 'Repeat Meds'],
        ['id' => 'prescriptions-gp-appointments', 'label' => 'GP Appointments'],
    ];
    $dependents = $demo['dependents'];
@endphp

<div class="w-56 shrink-0 flex flex-col gap-1">
    @foreach ($tabs as $tab)
        <a href="{{ route('web.'.$tab['id']) }}"
           class="px-4 py-2.5 rounded-lg text-sm font-medium {{ $active === $tab['id'] ? 'bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white' : 'text-lightBlue bg-white hover:bg-slate-50' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach

    @if (count($dependents))
        <div class="mt-4 bg-white rounded-lg p-3">
            <p class="text-xs text-slate-400 mb-2">Dependents</p>
            <label class="flex items-center gap-2 text-sm text-lightBlue mb-1">
                <input type="radio" name="dependent" checked> Myself
            </label>
            @foreach ($dependents as $dep)
                <label class="flex items-center gap-2 text-sm text-lightBlue">
                    <input type="radio" name="dependent"> {{ $dep['name'] }}
                </label>
            @endforeach
        </div>
    @endif
</div>
