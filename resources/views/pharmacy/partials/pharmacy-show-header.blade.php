@php
    $pharmacy = $framePharmacy;
    $in = ['pharmacy' => $framePharmacy['id']];
    $patients = collect($demo['staff_patients'])->where('pharmacy_id', $pharmacyId);
    $orders = collect($demo['staff_orders'])->whereIn('patient_id', $patients->pluck('id'));
    $activeCount = $patients->where('status', 'active')->count();
    $pendingCount = $patients->where('status', 'pending')->count();
    $newOrderCount = $orders->where('status', 'Requested')->count();

    // Tab order and the conditional "New ..." tabs follow admin/pharmacy/index.blade.php.
    $tabs = [
        ['id' => 'patients', 'label' => "Patients ({$activeCount})", 'url' => route('pharmacy-portal.patients', $in)],
        ['id' => 'new-patients', 'label' => "New Patients ({$pendingCount})", 'url' => route('pharmacy-portal.new-patients', $in), 'dot' => true, 'show' => $pendingCount > 0],
        ['id' => 'appointments', 'label' => 'Appointments', 'url' => route('pharmacy-portal.appointments', $in)],
        ['id' => 'orders', 'label' => 'Orders ('.$orders->where('status', '!=', 'Requested')->count().')', 'url' => route('pharmacy-portal.orders', $in)],
        ['id' => 'new-orders', 'label' => "New Orders ({$newOrderCount})", 'url' => route('pharmacy-portal.new-orders', $in), 'dot' => true, 'show' => $newOrderCount > 0],
        ['id' => 'broadcast', 'label' => 'Broadcast', 'url' => route('pharmacy-portal.broadcast', ['tab' => 1] + $in)],
        ['id' => 'info', 'label' => 'Pharmacy Info', 'url' => route('pharmacy-portal.info', ['tab' => 1] + $in)],
    ];

    $tabClass = "cursor-pointer block appearance-none py-2.5 border border-transparent text-slate-700 dark:text-white [&.active]:text-darkBlue [&.active]:dark:text-blue border-b-2 border-transparent dark:border-transparent [&.active]:border-b-darkBlue hover:border-b-blue [&.active]:font-medium [&.active]:dark:border-b-blue w-full";
@endphp

<div class="flex items-center">
    <h2 class="">{{ mb_strimwidth($pharmacy['name'], 0, 20, '...') }}</h2>
    <div class="flex items-center space-x-2 pl-6">
        <input type="checkbox" id="statusCheckbox"
            class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&[type='radio']]:checked:bg-green [&[type='radio']]:checked:border-primary [&[type='radio']]:checked:border-opacity-10 [&[type='checkbox']]:checked:bg-green [&[type='checkbox']]:checked:border-primary [&[type='checkbox']]:checked:border-opacity-10 [&:disabled:not(:checked)]:bg-slate-100 [&:disabled:not(:checked)]:cursor-not-allowed [&:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&:disabled:checked]:opacity-70 [&:disabled:checked]:cursor-not-allowed [&:disabled:checked]:dark:bg-darkmode-800/50 w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 before:bg-white checked:bg-redclr checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white bg-redclr"
            name="status" value="1" checked>
        <span id="statusText"></span>
    </div>
</div>
<div class="mt-4">
    <ul class="w-full flex gap-8 mb-2">
        @foreach ($tabs as $tab)
            @continue(! ($tab['show'] ?? true))
            <li class="focus-visible:outline-none">
                <a class="{{ ($tab['dot'] ?? false) ? 'relative ' : '' }}{{ $tabClass }} {{ $frameTab === $tab['id'] ? 'active' : '' }}" href="{{ $tab['url'] }}">
                    {{ $tab['label'] }}
                    @if ($tab['dot'] ?? false)
                        <span class="absolute top-1 -right-1 flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-danger"></span>
                        </span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>
