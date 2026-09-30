@extends('layouts.pharmacy')

@php
    $stats = $demo['staff_dashboard_stats'];
    $appointments = $demo['staff_appointments'];
    $ipConsultations = $demo['staff_ip_consultations'];
    $widgets = $demo['staff_dashboard_widgets'];
    $pharmacies = $demo['staff_pharmacies'];

    // notification.blade.php: a row opens that patient's Messages tab
    // (patient.show for a Pharmacy, pharmacy.show > patientDetail for a Group Owner).
    $messageUrl = fn (array $n) => route('pharmacy-portal.patient-detail', array_filter([
        'id' => $n['patient_id'],
        'pharmacy' => $isGroupOwner ? $n['pharmacy_id'] : null,
        'type' => 'message',
    ]));
    $periods = fn (string $selected = 'This Week', bool $withYesterday = false) => collect(array_merge($withYesterday ? ['Yesterday'] : [], ['This Week', 'Last Week', 'This Month', 'This Year', 'Custom']))
        ->map(fn ($o) => '<option'.($o === $selected ? ' selected' : '').'>'.$o.'</option>')->implode('');

    $th = 'py-3 pb-6 px-2 table-header-title text-grayish-blue text-xs leading-none font-medium';
@endphp

@section('content')
<div class="grid grid-cols-12 gap-x-7 gap-y-6">
    {{-- admin/includes/dashboard/group-dashboard.blade.php --}}
    @if ($isGroupOwner)
        <div class="px-8 py-5 bg-gradient-to-b from-[#317DFF] to-[#003A6F] rounded-lg col-span-12">
            <div class="flex justify-between items-center">
                <div class="select-type-1">
                    <select>
                        <option value="">All Pharmacies</option>
                        @foreach ($pharmacies as $ph)
                            <option value="{{ $ph['id'] }}">{{ $ph['name'] }} ({{ $ph['site_code'] }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif

    {{-- livewire/admin/dashboard/group-dashboard.blade.php: Widgets --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 {{ $isGroupOwner ? 'xl:grid-cols-5' : 'xl:grid-cols-3 2xl:grid-cols-6' }} gap-x-6 gap-y-5 col-span-12">
        <div class="p-4 bg-white rounded-3xl flex flex-col cursor-pointer" data-popup-open="{{ $isGroupOwner ? 'pharmacy-patients-popup' : 'awaiting-patients-popup' }}">
            <h3 class="text-grayish-blue font-medium text-sm mb-3 {{ $isGroupOwner ? '' : 'underline' }}">
                @if ($isGroupOwner)
                    No. of Patients
                @else
                    <a href="{{ route('pharmacy-portal.patients') }}">No. of Patients</a>
                @endif
            </h3>
            <p class="text-twilight-blue font-bold text-base leadigng-none mt-auto">{{ $stats['total_patients'] }}</p>
        </div>

        @unless ($isGroupOwner)
            <div class="p-4 bg-white rounded-3xl flex flex-col">
                <div class="flex items-center gap-2">
                    <a href="javascript:void(0)" class="flex gap-[1px]" data-popup-open="reorder-reminder-popup">
                        <h3 class="text-grayish-blue font-medium text-sm mb-3 text-pretty underline">Today's Re-order Reminder</h3>
                        <span class="shrink-0"><i data-lucide="arrow-up-right" class="stroke-1.5 w-5 h-5 text-[#368DDD]"></i></span>
                    </a>
                </div>
                <div class="flex items-center justify-between gap-2 mt-auto">
                    <p class="text-twilight-blue font-bold text-base leadigng-none">{{ $stats['today_reorder_reminders'] }}</p>
                    <p class="text-redclr font-semibold text-sm">Not Actioned : <span>{{ $stats['reorder_not_actioned'] }}</span></p>
                </div>
            </div>
        @endunless

        <div class="p-4 bg-white rounded-3xl flex flex-col">
            <h3 class="text-grayish-blue font-medium text-sm mb-3 {{ $isGroupOwner ? '' : 'underline' }}">
                @if ($isGroupOwner)
                    Active Patients
                @else
                    <a href="{{ route('pharmacy-portal.patients') }}">Active Patients</a>
                @endif
            </h3>
            <p class="text-twilight-blue font-bold text-base leadigng-none mt-auto">{{ $stats['active_patients'] }}</p>
        </div>

        <div class="p-4 bg-white rounded-3xl flex flex-col">
            <h3 class="text-grayish-blue font-medium text-sm mb-3 {{ $isGroupOwner ? '' : 'underline' }}">
                @if ($isGroupOwner)
                    Rx Orders
                @else
                    <a href="{{ route('pharmacy-portal.orders') }}">Rx Orders</a>
                @endif
            </h3>
            <p class="text-twilight-blue font-bold text-base leadigng-none mt-auto">{{ $stats['rx_orders'] }}</p>
        </div>

        <div class="p-4 bg-white rounded-3xl flex flex-col" data-popup-open="changed-pharmacy-popup">
            <div class="flex items-center gap-2">
                <a href="javascript:void(0)" class="flex gap-[1px]">
                    <h3 class="text-grayish-blue font-medium text-sm mb-3 text-pretty underline">Patients who changed their Pharmacy</h3>
                    <span class="shrink-0"><i data-lucide="arrow-up-right" class="stroke-1.5 w-5 h-5 text-[#368DDD]"></i></span>
                </a>
            </div>
            <p class="text-twilight-blue font-bold text-base leadigng-none mt-auto">{{ $stats['patients_changed_pharmacy'] }}</p>
        </div>

        <div class="p-4 bg-white rounded-3xl flex flex-col">
            <div class="flex items-start justify-between mb-3">
                <h3 class="text-grayish-blue font-medium text-sm {{ $isGroupOwner ? '' : 'underline' }}">Video call mins</h3>
                <div class="select-type-2 relative"><select>{!! $periods() !!}</select></div>
            </div>
            <p class="text-twilight-blue font-bold text-base leadigng-none mt-auto">{{ $stats['video_call_mins'] }}</p>
        </div>
    </div>

    {{-- livewire/admin/dashboard/appointment-detail.blade.php --}}
    <div class="col-span-12 xl:col-span-12 relative">
        <div class="p-6 bg-white rounded-3xl h-full min-h-[375px] max-h-[375px] flex flex-col">
            <div class="flex items-center justify-between gap-2 mb-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <a href="javascript:void(0)" class="flex gap-[1px]" data-popup-open="appointment-popup">
                        <h2 class="text-twilight-blue font-bold text-sm text-pretty underline">Appointment Scheduled</h2>
                        <span class="shrink-0"><i data-lucide="arrow-up-right" class="stroke-1.5 w-5 h-5 text-[#368DDD]"></i></span>
                    </a>
                </div>
                <div class="select-type-2 relative"><select>{!! $periods() !!}</select></div>
            </div>
            <div class="flex-1 relative overflow-auto">
                <div class="overflow-auto theme-scroll px-[52px] mb-5">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky bg-white top-[-1px]">
                            <tr>
                                <th class="{{ $th }}">Patient</th>
                                @if ($isGroupOwner)
                                    <th class="{{ $th }}">Pharmacy Name</th>
                                @endif
                                <th class="{{ $th }}">NHS No.-DOB</th>
                                <th class="{{ $th }}">Service</th>
                                <th class="{{ $th }}">Scheduled on</th>
                                <th class="{{ $th }}">Requested on</th>
                                <th class="{{ $th }} whitespace-nowrap">Status</th>
                                <th class="{{ $th }} whitespace-nowrap">Payment Status</th>
                                <th class="{{ $th }} text-center whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $appt)
                                @include('pharmacy.partials.dashboard.appointment-row', ['appt' => $appt])
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- livewire/admin/dashboard/ip-consultation-request.blade.php (Pharmacy role) --}}
    @unless ($isGroupOwner)
        <div class="col-span-12 xl:col-span-12 relative">
            <div class="p-6 bg-white rounded-3xl h-full min-h-[375px] max-h-[375px] flex flex-col">
                <div class="flex items-center justify-between gap-2 mb-4 flex-wrap">
                    <div class="flex items-center gap-2">
                        <a href="javascript:void(0)" class="flex gap-[1px]" data-popup-open="ip-consultation-popup">
                            <h2 class="text-twilight-blue font-bold text-sm text-pretty underline">IP Consultation Request</h2>
                            <span class="shrink-0"><i data-lucide="arrow-up-right" class="stroke-1.5 w-5 h-5 text-[#368DDD]"></i></span>
                        </a>
                    </div>
                    <div class="select-type-2 relative"><select>{!! $periods() !!}</select></div>
                </div>
                <div class="flex-1 relative overflow-auto">
                    <div class="overflow-auto theme-scroll px-[52px] mb-5">
                        @include('pharmacy.partials.dashboard.ip-consultation-table')
                    </div>
                </div>
            </div>
        </div>
    @endunless

    {{-- livewire/admin/dashboard/notification.blade.php --}}
    <div class="col-span-6 xl:col-span-4">
        <div class="p-6 bg-white rounded-3xl h-full max-h-[375px] flex flex-col relative">
            <div class="flex items-center justify-between flex-wrap gap-2 mb-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 p-2.5 bg-[#EFF4FB] rounded-full overflow-hidden flex items-center justify-center">
                        <img src="{{ asset('admin/images/notification-bell.svg') }}" alt="notification" class="max-w-3 h-auto">
                    </div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-twilight-blue font-bold text-sm"><a href="{{ route('pharmacy-portal.notifications') }}">Notifications</a></h2>
                    </div>
                </div>
                <div class="bg-[#F4F7FE] rounded-xl py-2 px-2 flex items-center justify-center" data-notification-filter>
                    <label class="selected [&.selected]:text-twilight-blue text-grayish-blue px-2 text-xs leading-none font-medium border-r border-black cursor-pointer inline-flex items-center">
                        <input type="radio" name="notificationType" value="all" class="sr-only" checked />
                        <span>All</span>
                    </label>
                    <label class="[&.selected]:text-twilight-blue text-grayish-blue px-2 text-xs leading-none font-medium border-r border-black cursor-pointer inline-flex items-center">
                        <input type="radio" name="notificationType" value="sent" class="sr-only" />
                        <span>Sent</span>
                    </label>
                    <label class="[&.selected]:text-twilight-blue text-grayish-blue px-2 text-xs leading-none font-medium cursor-pointer inline-flex items-center">
                        <input type="radio" name="notificationType" value="received" class="sr-only" />
                        <span>Recieved</span>
                    </label>
                </div>
            </div>
            <div class="overflow-auto theme-scroll">
                <table class="w-full text-left border-collapse">
                    <tbody>
                        @foreach ($demo['staff_notifications'] as $i => $n)
                            <tr onclick="window.location='{{ $messageUrl($n) }}'" class="cursor-pointer hover:bg-gray-100" data-notification-type="{{ $n['type'] }}">
                                <td class="py-3 px-2 pl-0 text-xs leading-none text-twilight-blue font-bold [&.read]:text-grayish-blue [&.read]:font-medium {{ $i === 0 ? 'pt-6' : '' }} {{ $n['is_read'] ? 'read' : '' }}">{{ mb_strimwidth($n['message'], 0, 40, '...') }}</td>
                                <td class="py-3 px-2 pl-0 text-xs leading-none text-grayish-blue font-regular pt-6">{{ strtok($n['patient'], ' ') }}</td>
                                <td class="py-3 px-2 pl-0 text-xs leading-none text-twilight-blue font-bold pt-6">
                                    <img src="{{ asset($n['type'] === 'received' ? 'admin/images/red-down-arrow.svg' : 'admin/images/green-up-arrow.svg') }}" alt="" class="w-3 min-w-3 h-3">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- livewire/admin/dashboard/no-of-signup.blade.php --}}
    <div class="col-span-6 xl:col-span-8 relative">
        <div class="p-6 bg-white rounded-3xl h-full flex flex-col max-h-[375px]">
            <div class="flex items-center justify-between gap-2 mb-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <h2 class="text-twilight-blue font-bold text-sm">No. of Sign Ups</h2>
                </div>
                <div class="select-type-2 relative"><select>{!! $periods('This Year') !!}</select></div>
            </div>
            <div class="flex-1 relative overflow-hidden mt-2 h-[200px]">
                <canvas id="deliveriesChart1" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    {{-- livewire/admin/dashboard/patient-not-order.blade.php --}}
    <div class="col-span-5 xl:col-span-4 relative">
        <div class="p-6 bg-white rounded-3xl h-full flex flex-col max-h-[375px]">
            <div class="flex items-center justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <a href="javascript:void(0)" class="flex gap-[1px]" data-popup-open="patient-not-order-popup">
                        <h2 class="text-twilight-blue font-bold text-sm text-pretty underline">Patients who did not order</h2>
                        <span class="shrink-0"><i data-lucide="arrow-up-right" class="stroke-1.5 w-5 h-5 text-[#368DDD]"></i></span>
                    </a>
                </div>
                <div class="select-type-2 shrink-0 relative"><select>{!! $periods('This Month', true) !!}</select></div>
            </div>
            <div class="overflow-auto theme-scroll">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky bg-white top-0">
                        <tr class="border-b border-[#E9EDF7]">
                            <th class="py-3 pb-6 px-2 pl-0 table-header-title text-grayish-blue text-xs leading-none font-medium w-full">Name</th>
                            <th class="py-3 pb-6 px-2 table-header-title text-center text-grayish-blue text-xs leading-none font-medium whitespace-nowrap">Days Since Last Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($widgets['patients_not_ordered'] as $p)
                            <tr>
                                <td class="py-3 px-2 pl-0 text-xs leading-none text-twilight-blue font-bold pt-6">{{ $p['name'] }}</td>
                                <td class="py-3 px-2 text-xs leading-none text-twilight-blue font-bold pt-6 text-center">{{ $p['days'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- livewire/admin/dashboard/order.blade.php --}}
    <div class="col-span-7 xl:col-span-8 relative">
        <div class="p-6 bg-white rounded-3xl flex flex-col h-full min-h-[375px] max-h-[375px]">
            <div class="flex items-center justify-between gap-2 mb-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <h2 class="text-twilight-blue font-bold text-sm text-pretty">Service Bookings and Rx Orders</h2>
                </div>
                @if ($isGroupOwner)
                    <div class="select-type-2 shrink-0 relative ml-auto">
                        <select>
                            <option selected>All Pharmacies</option>
                            @foreach ($pharmacies as $ph)
                                <option>{{ $ph['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="select-type-2 relative"><select>{!! $periods('This Year') !!}</select></div>
            </div>
            <div class="flex-1 relative overflow-hidden">
                <canvas id="serviceVsRxChart" class="w-full h-full"></canvas>
            </div>
            <div class="grid grid-cols-2 mt-10">
                <div class="flex items-center gap-3">
                    <div class="rounded-full w-3 h-3 bg-[#66B2FF]"></div>
                    <span class="text-xs font-bold text-grayish-blue">Service Bookings</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="rounded-full w-3 h-3 bg-[#20C997]"></div>
                    <span class="text-xs font-bold text-grayish-blue">Rx Orders</span>
                </div>
            </div>
        </div>
    </div>

    {{-- livewire/admin/dashboard/revenue-by-service.blade.php (Group Owner) --}}
    @if ($isGroupOwner)
        <div class="col-span-12 xl:col-span-6 relative">
            <div class="p-6 bg-white rounded-3xl h-full flex flex-col max-h-[375px] relative">
                <div class="flex items-center justify-between gap-2 mb-4 flex-wrap">
                    <div class="flex items-center gap-2">
                        <a href="javascript:void(0)" class="flex gap-[1px]" data-popup-open="revenue-service-popup">
                            <h2 class="text-twilight-blue font-bold text-sm text-pretty underline">Revenue by Service</h2>
                            <span class="shrink-0"><i data-lucide="arrow-up-right" class="stroke-1.5 w-5 h-5 text-[#368DDD]"></i></span>
                        </a>
                    </div>
                    <div class="select-type-2 shrink-0 relative ml-auto">
                        <select>
                            <option selected>All Pharmacies</option>
                            @foreach ($pharmacies as $ph)
                                <option>{{ $ph['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="select-type-2 relative"><select>{!! $periods('This Year') !!}</select></div>
                </div>
                <div class="flex justify-around items-start gap-6 mt-2">
                    <div class="shrink-0"><canvas id="deliveryChart" class="max-h-[275px] !w-full max-w-[205px] aspect-square"></canvas></div>
                    <div class="space-y-6">
                        <div class="flex items-baseline gap-3 justify-start">
                            <div class="flex items-center gap-3">
                                <div class="w-11 rounded-full">&nbsp;</div>
                                <div class="text-twilight-blue font-bold text-sm flex items-center w-14"><span>&#163;</span>{{ number_format(collect($widgets['revenue_by_service'])->sum('total')) }}</div>
                            </div>
                        </div>
                        @foreach ($widgets['revenue_by_service'] as $r)
                            <div class="flex items-baseline gap-3 justify-start">
                                <div class="flex items-center gap-3">
                                    <div class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $r['color'] }};"></div>
                                    <div class="text-sm font-bold text-twilight-blue">{{ $r['percentage'] }}%</div>
                                    <div class="text-sm font-bold text-twilight-blue"><span>&#163;</span>{{ number_format($r['total']) }}</div>
                                </div>
                                <div class="text-grayish-blue text-xs font-bold">{{ $r['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- livewire/admin/dashboard/top-service.blade.php --}}
    <div class="col-span-12 xl:col-span-6 relative">
        <div class="p-6 bg-white rounded-3xl h-full flex flex-col max-h-[375px] relative">
            <div class="flex items-center justify-between gap-2 mb-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <a href="javascript:void(0)" class="flex gap-[1px]" data-popup-open="top-service-popup">
                        <h2 class="text-twilight-blue font-bold text-sm text-pretty underline">Top Services</h2>
                        <span class="shrink-0"><i data-lucide="arrow-up-right" class="stroke-1.5 w-5 h-5 text-[#368DDD]"></i></span>
                    </a>
                </div>
                <div class="select-type-2 relative"><select>{!! $periods('This Year', true) !!}</select></div>
            </div>
            <div class="flex justify-around items-center flex-wrap gap-6 mt-2">
                <div class="shrink-0">
                    <canvas id="topServicesChart" class="max-h-[205px] aspect-square"></canvas>
                </div>
                <div class="space-y-6 flex-1">
                    @foreach ($widgets['top_services'] as $s)
                        <div class="flex items-baseline gap-3 w-full justify-between">
                            <div class="flex items center gap-3 w-4/5">
                                <div class="w-2.5 h-2.5 shrink-0 rounded-full" style="background-color: {{ $s['color'] }};"></div>
                                <div class="text-grayish-blue text-xs leading-none font-bold line-clamp-1">{{ $s['label'] }}</div>
                            </div>
                            <div class="text-base leading-none font-bold text-grayish-blue w-16 text-left">{{ number_format($s['total']) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- livewire/admin/dashboard/revenue-generated.blade.php (Group Owner) --}}
    @if ($isGroupOwner)
        <div class="col-span-12 xl:col-span-12 relative">
            <div class="p-6 bg-white rounded-3xl h-full min-h-[375px] max-h-[375px] flex flex-col">
                <div class="flex items-center justify-between gap-2 mb-4 flex-wrap">
                    <div class="flex items-center gap-2">
                        <a href="javascript:void(0)" class="flex gap-[1px]" data-popup-open="revenue-generated-popup">
                            <h2 class="text-twilight-blue font-bold text-sm text-pretty underline">Revenue Generated</h2>
                            <span class="shrink-0"><i data-lucide="arrow-up-right" class="stroke-1.5 w-5 h-5 text-[#368DDD]"></i></span>
                        </a>
                    </div>
                    <div class="select-type-2 relative"><select>{!! $periods('This Year') !!}</select></div>
                </div>
                <div class="flex-1 relative overflow-hidden">
                    <canvas id="deliveriesChart3" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
    @endif
</div>

@include('pharmacy.partials.dashboard.popups')
@endsection

@push('scripts')
<script>
    (function () {
        if (!window.Chart) { return; }

        const widgets = @json($widgets);
        const months = widgets.months;
        const barOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#68759A', font: { weight: 600 } } },
                y: { beginAtZero: true, grid: { color: '#F1F1F1' }, ticks: { color: '#68759A' } },
            },
        };
        const draw = (id, config) => {
            const el = document.getElementById(id);
            if (el) { new Chart(el, config); }
        };

        // no-of-signup.blade.php: bar, rounded, 30px bars.
        draw('deliveriesChart1', {
            type: 'bar',
            data: { labels: months, datasets: [{ label: 'Patient', data: widgets.signups, backgroundColor: '#0470D5', borderRadius: 60, barThickness: 30 }] },
            options: barOptions,
        });

        // order.blade.php: two straight lines, 5px, no points.
        draw('serviceVsRxChart', {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    { label: 'Rx Orders', data: widgets.rx_orders, borderColor: '#2ECC71', backgroundColor: '#20C997', fill: false, tension: 0, borderWidth: 5, pointStyle: false },
                    { label: 'Service Bookings', data: widgets.service_bookings, borderColor: '#66B2FF', backgroundColor: '#66B2FF', fill: false, tension: 0, borderWidth: 5, pointStyle: false },
                ],
            },
            options: barOptions,
        });

        const circle = (id, type, items, extra = {}) => draw(id, {
            type,
            data: { labels: items.map(i => i.label), datasets: [{ data: items.map(i => i.total), backgroundColor: items.map(i => i.color), borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false } }, ...extra },
        });
        // top-service.blade.php is a doughnut; revenue-by-service.blade.php a pie.
        circle('topServicesChart', 'doughnut', widgets.top_services, { cutout: '70%' });
        circle('deliveryChart', 'pie', widgets.revenue_by_service);

        // revenue-generated.blade.php: bar, same shape as sign-ups.
        draw('deliveriesChart3', {
            type: 'bar',
            data: { labels: months, datasets: [{ label: 'Revenue', data: widgets.revenue_generated, backgroundColor: '#0470D5', borderRadius: 60, barThickness: 30 }] },
            options: barOptions,
        });

        // The popup's chart (revenuePopupDeliveriesChart) covers the whole
        // year; it is drawn the first time the popup opens, once it has a size.
        let revenuePopupChart = null;
        document.addEventListener('popup:open', (event) => {
            if (event.detail.id !== 'revenue-generated-popup' || revenuePopupChart) { return; }
            const monthly = widgets.revenue_monthly;
            revenuePopupChart = new Chart(document.getElementById('revenuePopupDeliveriesChart'), {
                type: 'bar',
                data: { labels: Object.keys(monthly).map(m => m.slice(0, 3)), datasets: [{ label: 'Revenue', data: Object.values(monthly), backgroundColor: '#0470D5', borderRadius: 60, barThickness: 30 }] },
                options: barOptions,
            });
        });
    })();
</script>
@endpush
