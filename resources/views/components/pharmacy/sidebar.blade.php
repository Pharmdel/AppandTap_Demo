@props(['activeTab' => null, 'portal' => 'group-owner', 'logo' => null])

@php
    // Same items, icons and order as admin/includes/sidebar.blade.php, for a
    // group with the IP clinic service switched on (as on the live pharmacy
    // login): a Pharmacy then loses "Orders" and gains "Categorise Options"
    // and "Dispensed Consultations". 'portal' mirrors each item's @role gate;
    // a Group Owner reaches patients, orders and the calendar through
    // Pharmacies (pharmacy.show).
    $categoriseIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="none" stroke="currentColor" stroke-width="32" class="w-5 h-5"><rect x="96" y="48" width="320" height="416" rx="48" ry="48" stroke-linejoin="round" /><path d="M192 48v416M192 176h90c39.77 0 72 32.23 72 72s-32.23 72-72 72H192" stroke-linecap="round" stroke-linejoin="round" /></svg>';
    $dispensedIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="none" stroke="currentColor" stroke-width="32" class="w-5 h-5"><path d="M336 64h32a48 48 0 0148 48v320a48 48 0 01-48 48H144a48 48 0 01-48-48V112a48 48 0 0148-48h32" stroke-linecap="round" stroke-linejoin="round" /><rect x="176" y="32" width="160" height="64" rx="16" ry="16" stroke-linecap="round" stroke-linejoin="round" /><path d="M172 288l52 52 116-116" stroke-linecap="round" stroke-linejoin="round" /></svg>';

    $navItems = [
        ['id' => 'dashboard', 'route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'home', 'portal' => 'both'],
        ['id' => 'pharmacies', 'route' => 'patients', 'label' => 'Pharmacies', 'icon' => 'plus-square', 'portal' => 'group-owner'],
        ['id' => 'patients', 'route' => 'patients', 'label' => 'Patients', 'icon' => 'user', 'portal' => 'pharmacy'],
        ['id' => 'appointments', 'route' => 'appointments', 'label' => 'Calendar', 'icon' => 'calendar', 'portal' => 'pharmacy'],
        ['id' => 'services', 'route' => 'services', 'label' => 'Services', 'icon' => 'list', 'iconSize' => 'w-6 h-6', 'portal' => 'both'],
        ['id' => 'broadcast', 'route' => 'broadcast', 'label' => 'Broadcast', 'icon' => 'message-circle', 'portal' => 'pharmacy'],
        ['id' => 'repeat-orders', 'route' => 'repeat-orders', 'label' => 'Repeat Orders', 'icon' => 'refresh-ccw', 'portal' => 'pharmacy'],
        ['id' => 'categorise-options', 'route' => 'categorise-options', 'label' => 'Categorise Options', 'svg' => $categoriseIcon, 'portal' => 'pharmacy'],
        ['id' => 'dispensed-consultations', 'route' => 'dispensed-consultations', 'label' => 'Dispensed Consultations', 'svg' => $dispensedIcon, 'portal' => 'pharmacy'],
        ['id' => 'notifications', 'route' => 'notifications', 'label' => 'Notifications', 'icon' => 'bell', 'portal' => 'both'],
        ['id' => 'broadcast', 'route' => 'broadcast', 'label' => 'Broadcast', 'icon' => 'message-circle', 'portal' => 'group-owner'],
        ['id' => 'pharmacy-first-queries', 'route' => 'pharmacy-first-queries', 'label' => 'Pharmacy First Query', 'icon' => 'list', 'iconSize' => 'w-6 h-6', 'portal' => 'both'],
    ];

    $navItems = array_filter($navItems, fn ($item) => in_array($item['portal'], ['both', $portal], true));
@endphp

<div class="h-[90px]">
    <div class="mt-4 w-fit mx-auto relative before:absolute before:inset-[-4px] before:bg-white before:-z-10 before:rounded-lg">
        <a href="{{ route('pharmacy-portal.dashboard') }}" class="-intro-x hidden md:flex max-w-[120px] w-full mx-auto">
            <img src="{{ $logo }}" alt="logo" class="w-auto max-h-10 sm:max-h-[60px] lg:max-h-[80px] xl:max-h-[100px]">
        </a>
    </div>
</div>
<ul class="font-medium pt-10">
    @foreach ($navItems as $item)
        <li>
            <a href="{{ route('pharmacy-portal.'.$item['route']) }}"
               class="side-menu {{ $activeTab === $item['id'] ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    @isset($item['svg'])
                        <i class="stroke-1.5 w-5 h-5">{!! $item['svg'] !!}</i>
                    @else
                        <i data-lucide="{{ $item['icon'] }}" class="stroke-1.5 {{ $item['iconSize'] ?? 'w-5 h-5' }} mx-auto block"></i>
                    @endisset
                </div>
                <div class="side-menu__title">
                    {{ $item['label'] }}
                </div>
            </a>
        </li>
    @endforeach
</ul>
