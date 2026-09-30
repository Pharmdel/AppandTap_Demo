@props(['active'])

@php
    $tabs = [
        ['id' => 'info', 'label' => 'Pharmacy Info'],
        ['id' => 'services', 'label' => 'Services'],
        ['id' => 'settings', 'label' => 'Settings'],
    ];
@endphp

<ul class="flex gap-6 mt-4 mb-5">
    @foreach ($tabs as $tab)
        <li>
            <a href="{{ route('pharmacy-portal.'.$tab['id'], ['tab' => 1]) }}"
               class="inline-block px-5 py-2.5 rounded-md text-sm border {{ $active === $tab['id'] ? 'bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white border-transparent font-medium' : 'bg-white text-lightBlue border-lightBlue' }}">
                {{ $tab['label'] }}
            </a>
        </li>
    @endforeach
</ul>
