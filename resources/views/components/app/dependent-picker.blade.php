@props(['arrow' => 30])

@php
    // The DropdownButton the prescription / reminder / My Day AppBars show when
    // the account has more than one person: selected name centred in white over
    // a white underline, arrow_drop_down on the right.
    $patient = $demo['patient'];
    $people = [$patient['first_name'].' '.$patient['last_name'], ...array_column($demo['dependents'], 'name')];
@endphp

<label {{ $attributes->merge(['class' => 'relative block border-b border-white']) }}>
    <select class="w-full appearance-none bg-transparent text-white text-[16px] text-center py-3 outline-none" style="padding-right: {{ $arrow }}px">
        @foreach ($people as $name)
            <option class="text-black">{{ $name }}</option>
        @endforeach
    </select>
    <svg class="absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none" style="width: {{ $arrow }}px; height: {{ $arrow }}px" viewBox="0 0 24 24" fill="#fff"><path d="M7 10l5 5 5-5z"/></svg>
</label>
