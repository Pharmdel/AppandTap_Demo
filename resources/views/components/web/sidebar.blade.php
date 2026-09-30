@php
    $navItems = [
        ['id' => 'home', 'label' => 'Dashboard', 'icon' => 'home'],
        ['id' => 'appointments-list', 'label' => 'Book Appointment', 'icon' => 'calendar'],
        ['id' => 'consultation', 'label' => 'Consultation', 'icon' => 'stethoscope'],
        ['id' => 'prescriptions-rx-orders', 'label' => 'NHS Prescriptions', 'icon' => 'pill'],
        ['id' => 'chat', 'label' => 'Notification', 'icon' => 'bell'],
        ['id' => 'reminders-all', 'label' => 'Reminder', 'icon' => 'clock'],
    ];
    $current = $screen ?? null;
@endphp

<nav class="hidden lg:flex flex-col w-64 shrink-0 min-h-screen bg-white border-r border-slate-100 py-6 px-4">
    <div class="flex items-center gap-2 px-2 mb-8">
        <img src="/assets/web/images/appntap-logo-big-coloured.svg" alt="AppAndTap" class="h-9">
    </div>

    <div class="flex flex-col gap-1">
        @foreach ($navItems as $item)
            @php
                $isActive = $current === $item['id']
                    || ($item['id'] === 'prescriptions-rx-orders' && str_starts_with((string) $current, 'prescriptions-'));
            @endphp
            <a href="{{ route('web.'.$item['id']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
                      {{ $isActive ? 'bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white shadow' : 'text-lightBlue hover:bg-slate-50' }}">
                <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>
</nav>
