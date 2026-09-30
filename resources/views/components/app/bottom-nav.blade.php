@props(['active' => 'home'])

@php
    // dashboard_screen.dart buildNavBar(): 65px white bar inside a SafeArea;
    // each item is its 30px SVG tinted primary (selected) or secondary, over a
    // bold 12px label.
    $tabs = [
        ['id' => 'home', 'label' => 'Home', 'icon' => 'home-menu-icon.svg', 'screen' => 'home'],
        ['id' => 'pharmacy', 'label' => 'Pharmacy', 'icon' => 'pharmacy-menu-icon.svg', 'screen' => 'change-pharmacy'],
        ['id' => 'profile', 'label' => 'Profile', 'icon' => 'profile-menu-icon.svg', 'screen' => 'account-menu'],
    ];
@endphp

<div class="app-bottom-safe shrink-0 bg-white">
    <div class="h-[65px] flex">
        @foreach ($tabs as $tab)
            @php
                $isActive = $active === $tab['id'];
                $mask = "url('/assets/app/images/{$tab['icon']}') center/contain no-repeat";
            @endphp
            <a href="{{ route('app.'.$tab['screen']) }}" class="flex-1 flex flex-col items-center pt-2">
                <span class="block w-[30px] h-[30px] {{ $isActive ? 'bg-primaryColor' : 'bg-secondaryColor' }}" style="mask: {{ $mask }}; -webkit-mask: {{ $mask }};"></span>
                <span class="mt-[5px] text-[12px] font-bold leading-[1.3] {{ $isActive ? 'text-primaryColor' : 'text-secondaryColor' }}">{{ $tab['label'] }}</span>
            </a>
        @endforeach
    </div>
</div>
