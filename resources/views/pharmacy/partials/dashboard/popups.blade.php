@php
    // Shared cell classes of the widget popups (livewire/admin/dashboard/*).
    $td = 'py-3 px-2 text-xs leading-none text-twilight-blue font-semibold';
    $popupBody = 'p-6 bg-white rounded-3xl h-full flex flex-col max-h-[80vh] min-h-[500px] pt-0';
    $awaitingPatients = collect($demo['staff_patients'])->where('status', 'pending');
    $reminders = $demo['staff_reorder_reminders'];
@endphp

@if ($isGroupOwner)
    {{-- group-dashboard.blade.php: "Show pharmacy Patient list popup" (No. of Patients card) --}}
    <x-pharmacy.popup id="pharmacy-patients-popup" box="max-w-[750px] w-[90%] p-7 rounded-[36px]">
        <div class="">
            <div class="{{ $popupBody }}">
                <div class="flex items-center justify-between gap-2 flex-wrap-- mb-3">
                    <div class="flex items-center gap-2">
                        <a href="javascript:void(0)"><h2 class="text-twilight-blue font-bold text-sm">Pharmacy Patient's List</h2></a>
                    </div>
                </div>
                <div class="overflow-auto theme-scroll">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky bg-white top-[-1px]">
                            <tr>
                                <th class="{{ $th }}">Pharmacy Name</th>
                                <th class="{{ $th }} text-left">No of Patients</th>
                                <th class="{{ $th }} text-left">Awaiting approval Patients</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach ($pharmacies as $ph)
                                <tr class="odd:bg-[#FAFAFA]">
                                    <td class="{{ $td }}">{{ $ph['name'] }} ({{ $ph['site_code'] }})</td>
                                    <td class="{{ $td }}">{{ $ph['patients'] }}</td>
                                    <td class="{{ $td }}">{{ $ph['awaiting'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-pharmacy.popup>
@else
    {{-- group-dashboard.blade.php: awaiting-approval popup (Pharmacy's No. of Patients card) --}}
    <x-pharmacy.popup id="awaiting-patients-popup" box="max-w-[750px] w-[90%] p-7 rounded-[36px]">
        <div class="">
            <div class="{{ $popupBody }}">
                <div class="flex items-center justify-between gap-2 flex-wrap-- mb-3">
                    <div class="flex items-center gap-2">
                        <a href="javascript:void(0)"><h2 class="text-twilight-blue font-bold text-sm">Pharmacy Patient's List</h2></a>
                    </div>
                </div>
                <div class="overflow-auto theme-scroll">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-[-1px] bg-white">
                            <tr class="border-b border-[#E9EDF7]">
                                <th class="py-3 pb-4 px-2 text-grayish-blue text-xs font-medium">Patient Name</th>
                                <th class="py-3 pb-4 px-2 text-grayish-blue text-xs font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($awaitingPatients as $patient)
                                <tr class="odd:bg-[#FAFAFA]">
                                    <td class="py-3 px-2 text-xs text-twilight-blue font-semibold">{{ $patient['first_name'] }} {{ $patient['last_name'] }}</td>
                                    <td class="py-3 px-2 text-xs text-twilight-blue font-semibold">Awaiting Approval</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-6 text-center text-xs text-twilight-blue font-semibold">No records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-pharmacy.popup>

    {{-- group-dashboard.blade.php: "openRepeatOrderData" (Today's Re-order Reminder) --}}
    <x-pharmacy.popup id="reorder-reminder-popup" box="max-w-[90%] p-7-- pt-7 rounded-[36px]">
        <form class="h-full flex flex-col" id="udpate_medicine_reminder_order" autocomplete="off" onsubmit="return markReorderAsOrdered(event)">
            <div class="">
                <div class="p-6-- bg-white rounded-3xl h-full flex flex-col max-h-[80vh] h-[1000px] min-h-[500px] pt-0">
                    <div class="flex items-center justify-between gap-2 flex-wrap-- mb-3 px-[52px]">
                        <div class="flex items-center gap-2">
                            <a href="javascript:void(0)"><h2 class="text-twilight-blue font-bold text-sm">Today’s Re-order medicine reminders</h2></a>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="bg-[#F4F7FE] rounded-xl py-2 px-2 flex items-center justify-center" data-reorder-filter>
                                <label class="selected [&.selected]:text-twilight-blue text-grayish-blue px-2 text-xs leading-none font-medium [&:not(:last-child)]:border-r border-black cursor-pointer inline-flex items-center">
                                    <input type="radio" name="repeatOrderDate" value="today" class="sr-only" checked />
                                    <span>Today</span>
                                </label>
                                <label class="[&.selected]:text-twilight-blue text-grayish-blue px-2 text-xs leading-none font-medium [&:not(:last-child)]:border-r border-black cursor-pointer inline-flex items-center">
                                    <input type="radio" name="repeatOrderDate" value="all" class="sr-only" />
                                    <span>Not actioned</span>
                                </label>
                            </div>
                            <label class="[&.selected]:text-twilight-blue text-grayish-blue text-xs leading-none font-medium [&:not(:last-child)]:border-r border-black cursor-pointer inline-flex items-center" onclick="printPopupTable(this)">
                                @include('pharmacy.partials.print-icon', ['uid' => 'Reorder'])
                            </label>
                        </div>
                    </div>
                    <div class="overflow-auto theme-scroll px-[52px] mb-5">
                        <table class="w-full text-left border-collapse">
                            <thead class="sticky bg-white top-[-1px]">
                                <tr>
                                    <th class="{{ $th }}">Patient</th>
                                    <th class="{{ $th }} text-center">NHS No.-DOB</th>
                                    <th class="{{ $th }} text-center">Medicine</th>
                                    <th class="{{ $th }} text-center">Next order date & Repeat cycle</th>
                                    <th class="{{ $th }} text-center w-1 whitespace-nowrap">Mark As ordered</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach ($reminders as $r)
                                    <tr class="odd:bg-[#FAFAFA] {{ $r['today'] ? '' : 'hidden' }}" data-reorder-row data-today="{{ $r['today'] ? 1 : 0 }}" data-ordered="{{ $r['is_ordered'] ? 1 : 0 }}">
                                        <td class="{{ $td }}">{{ $r['patient'] }}</td>
                                        <td class="{{ $td }} text-center">{{ $r['nhs_number'] }} - {{ $r['dob'] }}</td>
                                        <td class="{{ $td }} text-center">{{ $r['medicine'] }}</td>
                                        <td class="{{ $td }} text-center">{{ $r['order_date'] }} & {{ $r['repeat_type'] }}</td>
                                        <td class="{{ $td }} text-center">
                                            @if ($r['is_ordered'])
                                                <label class="">Ordered</label>
                                            @else
                                                <label class="">
                                                    <input type="checkbox" name="ordered_medicine[]" value="{{ $r['id'] }}" class="whitelabel-checked-background checked:ring-0 focus:ring-0" />
                                                </label>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="odd:bg-[#FAFAFA] hidden" data-reorder-empty>
                                    <td class="{{ $td }} text-center" colspan="6">No record found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-[52px] py-2 rounded-xl border-2 mt-auto text-right flex justify-end items-center gap-10 -ml-[2px]" data-reorder-save>
                    <span class="order-error text-redclr text-sm"></span>
                    <button type="submit" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white min-w-[138px]">Save</button>
                </div>
            </div>
        </form>
    </x-pharmacy.popup>
@endif

{{-- group-dashboard.blade.php: "Patients who changed their Pharmacy popup" --}}
<x-pharmacy.popup id="changed-pharmacy-popup" box="max-w-[90%] p-7 rounded-[36px]">
    <div class="">
        <div class="{{ $popupBody }}">
            <div class="flex items-center justify-between gap-2 flex-wrap-- mb-3">
                <div class="flex items-center gap-2">
                    <a href="javascript:void(0)"><h2 class="text-twilight-blue font-bold text-sm">Patients who changed their Pharmacy</h2></a>
                </div>
                <div class="select-type-2 shrink-0 relative"><select>{!! $periods('This Month', true) !!}</select></div>
            </div>
            <div class="overflow-auto theme-scroll">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky bg-white top-[-1px]">
                        <tr>
                            <th class="{{ $th }}">Name</th>
                            <th class="{{ $th }} text-left">NHS No.</th>
                            <th class="{{ $th }} text-left">DOB</th>
                            <th class="{{ $th }} text-left">Address</th>
                            <th class="{{ $th }} text-left">Date of change</th>
                            <th class="{{ $th }} text-left w-1 whitespace-nowrap">No of Days since</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        @foreach ($demo['staff_changed_pharmacy'] as $c)
                            <tr class="odd:bg-[#FAFAFA]">
                                <td class="{{ $td }}">{{ $c['name'] }}</td>
                                <td class="{{ $td }}">{{ $c['nhs_number'] }}</td>
                                <td class="{{ $td }}">{{ $c['dob'] }}</td>
                                <td class="{{ $td }}">{{ $c['address'] }}</td>
                                <td class="{{ $td }}">{{ $c['date_of_change'] }}</td>
                                <td class="{{ $td }}">{{ $c['days'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-pharmacy.popup>

{{-- appointment-detail.blade.php: "popup table" --}}
<x-pharmacy.popup id="appointment-popup" box="max-w-[90%] p-7-- pt-7 rounded-[36px]">
    <div class="">
        <div class="p-6 -- bg-white rounded-3xl h-full flex flex-col max-h-[80vh] h-[1000px] min-h-[500px] pt-0">
            <div class="flex items-center justify-between gap-2 flex-wrap-- mb-3 px-[52px]">
                <div class="flex items-center gap-2">
                    <a href="javascript:void(0)"><h2 class="text-twilight-blue font-bold text-sm">Appointment Scheduled</h2></a>
                </div>
                <div class="select-type-2 shrink-0 relative flex items-center">
                    <select>{!! $periods() !!}</select>
                    <label class="[&.selected]:text-twilight-blue text-grayish-blue text-xs leading-none font-medium cursor-pointer inline-flex items-center ps-2" onclick="printPopupTable(this)">
                        @include('pharmacy.partials.print-icon', ['uid' => 'Appointment'])
                    </label>
                </div>
            </div>
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
                    <tbody class="">
                        @foreach ($appointments as $appt)
                            @include('pharmacy.partials.dashboard.appointment-row', ['appt' => $appt, 'isPopup' => true])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-pharmacy.popup>

@unless ($isGroupOwner)
    {{-- ip-consultation-request.blade.php: "IP Clinic Appointment Popup" --}}
    <x-pharmacy.popup id="ip-consultation-popup" box="max-w-[90%] p-7-- pt-7 rounded-[36px]">
        <div class="">
            <div class="p-6 bg-white rounded-3xl h-full flex flex-col max-h-[80vh] h-[1000px] min-h-[500px] pt-0">
                <div class="flex items-center justify-between gap-2 flex-wrap-- mb-3 px-[52px]">
                    <div class="flex items-center gap-2">
                        <a href="javascript:void(0)"><h2 class="text-twilight-blue font-bold text-sm">IP Consultation Request</h2></a>
                    </div>
                    <div class="select-type-2 shrink-0 relative"><select>{!! $periods() !!}</select></div>
                </div>
                <div class="overflow-auto theme-scroll px-[52px] mb-5">
                    @include('pharmacy.partials.dashboard.ip-consultation-table')
                </div>
            </div>
        </div>
    </x-pharmacy.popup>

    @foreach ($ipConsultations as $c)
        @include('pharmacy.partials.dashboard.ip-consultation-view', ['c' => $c])
    @endforeach
    @include('pharmacy.partials.dashboard.ip-consultation-modals')
@endunless

{{-- patient-not-order.blade.php: "Patients who did not order popup" --}}
<x-pharmacy.popup id="patient-not-order-popup" box="max-w-[90%] p-7 rounded-[36px]">
    <div class="">
        <div class="{{ $popupBody }}">
            <div class="flex items-center justify-between gap-2 flex-wrap-- mb-3">
                <div class="flex items-center gap-2">
                    <h2 class="text-twilight-blue font-bold text-sm">Patients who did not order</h2>
                </div>
                <div class="select-type-2 shrink-0 relative"><select>{!! $periods('This Month', true) !!}</select></div>
            </div>
            <div class="overflow-auto theme-scroll">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky bg-white top-[-1px]">
                        <tr>
                            <th class="{{ $th }}">Name</th>
                            <th class="{{ $th }} text-left">NHS No.</th>
                            <th class="{{ $th }} text-left">DOB</th>
                            <th class="{{ $th }} text-left">Address</th>
                            <th class="{{ $th }} text-left">Date of Last Order</th>
                            <th class="{{ $th }} text-left w-1 whitespace-nowrap">No of Days since</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        @foreach ($widgets['patients_not_ordered'] as $p)
                            <tr class="odd:bg-[#FAFAFA]">
                                <td class="{{ $td }}">{{ $p['name'] }}</td>
                                <td class="{{ $td }}">{{ $p['nhs_number'] }}</td>
                                <td class="{{ $td }}">{{ $p['dob'] }}</td>
                                <td class="{{ $td }}">{{ $p['address'] }}</td>
                                <td class="{{ $td }}">{{ $p['last_order'] }}</td>
                                <td class="{{ $td }}">{{ $p['days'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-pharmacy.popup>

{{-- top-service.blade.php: popup --}}
<x-pharmacy.popup id="top-service-popup" box="max-w-[750px] p-7 rounded- [36px]">
    <div class="">
        <div class="{{ $popupBody }}">
            <div class="flex items-center justify-between gap-2 flex-wrap-- mb-3">
                <div class="flex items-center gap-2">
                    <h2 class="text-twilight-blue font-bold text-sm"><a href="javascript:void(0)">Top Service</a></h2>
                </div>
                <div class="select-type-2 shrink-0 relative"><select>{!! $periods('This Year', true) !!}</select></div>
            </div>
            <div class="overflow-auto theme-scroll">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky bg-white top-[-1px]">
                        <tr>
                            <th class="py-3 pb-6 px-2 pl-0 table-header-title text-grayish-blue text-xs leading-none font-medium w-full">Name</th>
                            <th class="py-3 pb-6 px-2 table-header-title text-center text-grayish-blue text-xs leading-none font-medium whitespace-nowrap">No of Bookings</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        @foreach ($widgets['top_services'] as $s)
                            <tr class="">
                                <td class="py-3 px-2 pl-0 text-xs leading-none text-twilight-blue font-semibold pt-6">{{ $s['label'] }}</td>
                                <td class="py-3 px-2 text-xs leading-none text-twilight-blue font-semibold pt-6 text-center">{{ number_format($s['total']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-pharmacy.popup>

@if ($isGroupOwner)
    {{-- revenue-by-service.blade.php: popup --}}
    <x-pharmacy.popup id="revenue-service-popup" box="max-w-[585px] p-7 rounded- [36px]">
        <div class="">
            <div class="{{ $popupBody }}">
                <div class="flex items-center justify-between gap-2 flex-wrap-- mb-3">
                    <div class="flex items-center gap-2">
                        <h2 class="text-twilight-blue font-bold text-sm">Revenue By Service</h2>
                    </div>
                    <div class="select-type-2 shrink-0 relative"><select>{!! $periods('This Year') !!}</select></div>
                </div>
                <div class="overflow-auto theme-scroll">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky bg-white top-[-1px]">
                            <tr>
                                <th class="{{ $th }}">Service</th>
                                <th class="{{ $th }} text-center whitespace-nowrap">Revenue Generated</th>
                                <th class="{{ $th }} text-center whitespace-nowrap">Percentage</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach ($widgets['revenue_by_service'] as $r)
                                <tr class="odd:bg-[#FAFAFA]">
                                    <td class="{{ $td }}">{{ $r['label'] }}</td>
                                    <td class="{{ $td }}">£ {{ number_format($r['total']) }}</td>
                                    <td class="{{ $td }}">{{ $r['percentage'] }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-pharmacy.popup>

    {{-- revenue-generated.blade.php: "openAddService" popup --}}
    @php $monthly = $widgets['revenue_monthly']; @endphp
    <x-pharmacy.popup id="revenue-generated-popup" box="max-w-[90%] w-[1082px] p-7 rounded-[36px]">
        <div>
            <div class="{{ $popupBody }}">
                <div class="flex items-center gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        <h2 class="text-twilight-blue font-bold text-sm"><a href="javascript:void(0)">Revenue Generated</a></h2>
                    </div>
                    <div class="select-type-2 shrink-0 ml-auto relative">
                        <select>
                            <option value="" selected>All</option>
                            @foreach ($widgets['revenue_by_service'] as $r)
                                <option>{{ $r['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="select-type-2 shrink-0 relative ml-auto--">
                        <select>
                            @foreach ($widgets['revenue_years'] as $year)
                                <option>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex-1 relative overflow-hidden">
                    <canvas id="revenuePopupDeliveriesChart" class="w-full !h-[240px]"></canvas>
                </div>
                <div class="overflow-auto theme-scroll grid-- grid-cols-2-- flex justify-between items-stretch gap-20">
                    @foreach ([array_slice($monthly, 0, 6, true), array_slice($monthly, 6, 6, true)] as $half)
                        @if (! $loop->first)
                            <div class="w-[1px] min-h-full bg-[#E5E5E5] shrink-0"></div>
                        @endif
                        <table class="w-full text-left border-collapse">
                            <thead class="sticky bg-white top-0">
                                <tr>
                                    <th class="py-3 pb-6 px-2 pl-0 table-header-title text-grayish-blue text-xs leading-none font-medium">Service</th>
                                    <th class="{{ $th }} text-left w-1 whitespace-nowrap">Revenue Generated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($half as $month => $value)
                                    <tr>
                                        <td class="py-3 px-2 pl-0 text-xs leading-none text-twilight-blue font-semibold">{{ $month }}</td>
                                        <td class="{{ $td }}">£ {{ number_format($value) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
    </x-pharmacy.popup>
@endif

@include('pharmacy.partials.resend-email-modals', ['modals' => ['book_confirm' => ['resendAppointmentId', 'resendEmails', false], 'book_confirm_popup' => ['resendAppointmentIdPopup', 'resendEmailPopup', true]]])
