@php
    // admin/includes/patient/patient-info.blade.php. $patient is the target
    // patient; ?type= picks the tab, defaulting to Booking when absent (the
    // bare Patients page, which auto-selects a patient but sets no type).
    // Upstream both roles see Prescription and Messages for an NHS patient
    // (a Pharmacy only for its own patients); tabs other than Patient Info
    // need an approved account.
    $type = request('type') ?? 'booking';
    $approved = $patient['status'] === 'active';
    // Upstream's tab links also keep the list's search and page.
    $baseParams = array_filter([
        'id' => $patient['id'],
        'pharmacy' => $isGroupOwner ? request('pharmacy') : null,
        'search' => request('search'),
        'page' => request('page'),
    ]);
    $tabUrl = fn (string $tabType) => route('pharmacy-portal.patient-detail', ['id' => $patient['id'], 'type' => $tabType] + $baseParams);
    $unread = collect($demo['staff_notifications'])->where('patient_id', $patient['id'])->where('type', 'received')->where('is_read', false)->count();

    $tabs = [
        ['label' => 'Notes', 'type' => 'notes', 'visible' => $approved],
        ['label' => 'Booking', 'type' => 'booking', 'visible' => $approved],
        ['label' => 'Patient Info', 'type' => 'patientinfo', 'visible' => true],
    ];
    if ($isGroupOwner || $patient['user_type'] === 'NHS') {
        array_unshift($tabs,
            ['label' => 'Prescription', 'type' => 'prescription', 'visible' => $approved],
            ['label' => 'Messages', 'type' => 'message', 'visible' => $approved],
        );
    }
    // The group runs the IP clinic, so both roles get the Consultation tab.
    $tabs[] = ['label' => 'Consultation', 'type' => 'consultation', 'visible' => true];
    $consultations = collect($demo['staff_ip_consultations'])->where('patient_id', $patient['id']);

    $orders = collect($demo['staff_orders'])->where('patient_id', $patient['id'])->values()->all();
    // staff_calendar_appointments is the fuller set (it also has the
    // Calendar's past/historical bookings), so a patient's Booking tab shows
    // everything the Calendar does, not just the dashboard's upcoming rows.
    $bookings = collect($demo['staff_calendar_appointments'])->where('patient_id', $patient['id']);
    $readonlyInput = '[&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 intro-x block min-w-full px-4 py-3 w-full';
    $formLabel = 'inline-block mb-2 font-semibold';
    $avatar = asset('images/no-images-avtaar.jpg');
@endphp
<div class="mt-4">
    <ul class="w-full flex gap-8">
        @foreach ($tabs as $tab)
            @if ($tab['visible'])
                <li class="focus-visible:outline-none">
                    <a class="cursor-pointer block appearance-none py-2.5 border-transparent text-slate-700 [&.active]:text-darkBlue border-b-2 border-transparent [&.active]:border-b-darkBlue hover:border-b-darkBlue [&.active]:font-medium w-full {{ $type === $tab['type'] ? 'active' : '' }}" href="{{ $tabUrl($tab['type']) }}">
                        {{ $tab['label'] }}
                        @if ($tab['type'] === 'message' && $unread > 0)
                            ({{ $unread }})
                        @endif
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
    <div class="tab-content mt-5">
        @if ($approved)
            @if ($type === 'booking')
                {{-- livewire/admin/service-booking/service-booking-data.blade.php --}}
                <div class="col-span-12 xl:col-span-8 2xl:col-span-9">
                    <div class="h-full">
                        <div class="flex items-center justify-between mb-4 flex-wrap md:flex-nowrap gap-3 md:gap-0 mt-4">
                            <div>
                                <div class="mb-5">
                                    <a href="{{ route('pharmacy-portal.appointments') }}" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">
                                        <i data-lucide="plus" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                        Book Appointment
                                    </a>
                                </div>
                            </div>
                            <div class="relative w-56 items-center text-slate-500">
                                <input type="search" placeholder="Search..." data-list-search="[data-booking-row]" class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 box w-56 pr-10">
                                <i data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-6">
                            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                                @if ($bookings->isNotEmpty())
                                    <div class="sm:w-full w-max text-left -mt-2 mb-5">
                                        <div class="flex">
                                            <div class="w-[20%] font-semibold px-5 py-3 border-b-0">Booking ID</div>
                                            <div class="w-[15%] font-semibold px-5 py-3 border-b-0">Pharmacy</div>
                                            <div class="w-[15%] font-semibold px-5 py-3 border-b-0">Service</div>
                                            <div class="w-[15%] font-semibold px-5 py-3 border-b-0">Appointment Type</div>
                                            <div class="w-[15%] font-semibold px-5 py-3 border-b-0">Date & Slot</div>
                                            <div class="w-[10%] font-semibold px-5 py-3 border-b-0 text-center">Amount</div>
                                            <div class="w-[10%] font-semibold px-5 py-3 border-b-0 text-center">Notes</div>
                                            <div class="w-[15%] font-semibold px-5 py-3 border-b-0 text-center">Status</div>
                                            <div class="w-[15%] font-semibold px-5 py-3 border-b-0 text-center">Payment Status</div>
                                            <div class="w-[15%] font-semibold px-5 py-3 border-b-0 text-center">Actions</div>
                                        </div>
                                        <div>
                                            @foreach ($bookings as $b)
                                                <div class="flex transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded bg-white text-slate-800" data-booking-row data-appointment="{{ $b['id'] }}">
                                                    <div class="w-[20%] px-5 py-3 flex items-center break-all">#{{ $b['appointment_no'] }}</div>
                                                    <div class="w-[15%] px-5 py-3 flex items-center"><div>{{ $b['pharmacy'] }}</div></div>
                                                    <div class="w-[15%] px-5 py-3 flex items-center"><div>{{ $b['service'] }}</div></div>
                                                    <div class="w-[15%] px-5 py-3 flex items-center"><div>{{ $b['appointment_type'] }}</div></div>
                                                    <div class="w-[15%] px-5 py-3 flex items-center"><div><span class="block text-left mt-1">{{ $b['date'] }} {{ $b['time'] }}</span></div></div>
                                                    <div class="w-[10%] px-5 py-3 flex items-center justify-center"><div class="text-danger">{{ $b['amount'] > 0 ? '£'.$b['amount'] : '' }}</div></div>
                                                    <div class="w-[10%] px-5 py-3 flex items-center justify-center"><div></div></div>
                                                    <div class="w-[15%] px-5 py-3 flex items-center justify-center">
                                                        @php
                                                            $statusClass = match ($b['status']) {
                                                                'Approved' => 'text-orange text-success',
                                                                'Attended' => 'text-[#67C882]',
                                                                'Not Attended' => 'text-[#03C5F0]',
                                                                default => 'text-orange text-danger',
                                                            };
                                                        @endphp
                                                        <div class="max-w-52 mx-auto rounded-full px-5 py-2 text-center font-medium {{ $statusClass }}" data-appointment-status>{{ $b['status'] === 'Pending Approval' ? 'Pending' : $b['status'] }}</div>
                                                    </div>
                                                    <div class="w-[15%] px-5 py-3 flex items-center justify-center">{{ $b['payment_status'] }}</div>
                                                    <div class="w-[15%] px-5 py-3 flex items-center justify-center">
                                                        <div class="flex gap-2 flex-col items-center justify-start">
                                                            @if ($b['is_video'] && $b['status'] === 'Approved')
                                                                <a href="javascript:void(0)" data-join-button
                                                                    onclick="{{ $b['is_meeting_active'] ? "window.open('".route('app.video-conference')."', '_blank')" : 'joinTimeWarning()' }}"
                                                                    class="max-w-24 w-full flex items-center justify-center border px-2 py-1 rounded-md group {{ $b['is_meeting_active'] ? 'bg-gradient-to-t from-[#002B56] to-[#0070D5] border-darkBlue text-white isMeetingActive' : 'opacity-60 bg-grey border-grey cursor-not-allowed text-black' }}">
                                                                    <img src="{{ asset('admin/images/video_call.png') }}" width="19" class="mr-1 group-[.isMeetingActive]:invert" alt="Video">
                                                                    <span class="mt-[1px]">Join</span>
                                                                </a>
                                                            @endif
                                                            <div class="{{ $b['status'] === 'Pending Approval' ? '' : 'hidden' }} flex flex-col gap-2" data-status-actions="Pending Approval">
                                                                <div class="max-w-24 w-full mx-auto border bg-gradient-to-t from-[#002B56] to-[#0070D5] hover:opacity-80 rounded-md px-5 py-1 text-center text-white" onclick="appointmentUpdate('booked', '{{ $b['id'] }}')"><span class="mt-[1px]">Accept</span></div>
                                                                <div class="max-w-24 w-full flex items-center justify-center text-lightBlue border border-lightBlue px-1.5 py-1 rounded-md" onclick="appointmentUpdate('cancel', '{{ $b['id'] }}')"><span class="mt-[1px]">Cancel</span></div>
                                                            </div>
                                                            <div class="{{ $b['status'] === 'Approved' ? '' : 'hidden' }}" data-status-actions="Approved">
                                                                <div class="max-w-24 w-full flex items-center justify-center text-lightBlue border border-lightBlue px-1.5 py-1 rounded-md" onclick="appointmentUpdate('cancel', '{{ $b['id'] }}')"><span class="mt-[1px]">Cancel</span></div>
                                                            </div>
                                                            <div class="hidden" data-status-actions="Cancelled"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @include('pharmacy.partials.pagination-footer', ['count' => $bookings->count()])
                                @else
                                    <div class="p-6 transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 bg-white text-slate-800">
                                        <img class="mx-auto" src="{{ asset('admin/images/no-record-found-new.png') }}" alt="">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($type === 'message')
                {{-- admin/includes/chatinterface.blade.php --}}
                <div class="col-span-12 xl:col-span-8 2xl:col-span-9">
                    <div class="box p-5 patient_chat">
                        <div>
                            <div class="chat_screen bg-white rounded-lg overflow-hidden">
                                <div class="p-4 md:p-10 overflow-auto h-[80vh] md:h-[68vh] change_height" id="output" data-sender-image="{{ $avatar }}">
                                    @foreach ($patient['chat'] as $message)
                                        @if ($message['from'] === 'pharmacy')
                                            <div class="flex space-x-3 justify-end mb-2">
                                                <div>
                                                    <div class="massage text-white text-base bg-gradient-to-t from-[#002B56] to-[#0070D5] px-7 py-1 rounded-3xl rounded-br-none max-w-sm py-3">{{ $message['text'] }}</div>
                                                    <span class="block text-right mt-1">{{ $message['time'] }}</span>
                                                </div>
                                                <div class="w-10 h-10 overflow-hidden rounded-full"><img src="{{ $avatar }}" alt="" class="w-full h-full object-cover"></div>
                                            </div>
                                        @else
                                            <div class="flex space-x-3 justify-left mb-2">
                                                <div class="w-10 h-10 overflow-hidden rounded-full"><img src="{{ $avatar }}" alt="" class="w-full h-full object-cover"></div>
                                                <div>
                                                    <div class="massage text-black text-base bg-[#DBE5F3] px-7 py-1 rounded-3xl rounded-bl-none max-w-sm py-3">{{ $message['text'] }}</div>
                                                    <span class="block text-left mt-1">{{ $message['time'] }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="send_field">
                                    <div class="flex items-center border-t border-slate-200/60 bg-slate-100 border border-white p-5 sm:p-10 sm:py-4">
                                        <input type="text" id="message" placeholder="Type your message..." class="rounded-full transition duration-200 ease-in-out w-full text-sm placeholder:text-slate-400/90 h-[46px] resize-none border-transparent px-5 py-3 shadow-none">
                                        <a class="ml-5 flex h-8 w-8 flex-none items-center justify-center rounded-full bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white sm:h-10 sm:w-10 hover:bg-blue/80 duration-200 send-button" href="javascript:void(0);" onclick="sendMessage()">
                                            <i data-lucide="send" class="stroke-1.5 h-4 w-4"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($type === 'notes')
                {{-- admin/includes/patient-note.blade.php --}}
                <form autocomplete="off" onsubmit="event.preventDefault(); Swal.fire({ title: 'Notes updated successfully.', icon: 'success', timer: 1800, showConfirmButton: false });">
                    <div class="col-span-12 xl:col-span-8 2xl:col-span-9">
                        <div class="box p-5">
                            <textarea placeholder="Notes" class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 intro-x block min-h-[200px] px-4 py-3 w-full" name="notes" id="notes">{{ $patient['notes'] }}</textarea>
                            <button type="submit" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white mt-4">Save</button>
                        </div>
                    </div>
                </form>
            @endif

            @if (in_array($type, ['prescription', 'repeat-medications'], true))
                @php
                    $subTabs = [['label' => 'Rx Orders', 'type' => 'prescription', 'id' => 'prescription']];
                    if (! empty($patient['repeat_meds'])) {
                        $subTabs[] = ['label' => 'Repeat Meds', 'type' => 'repeat-medications', 'id' => 'medication'];
                    }
                @endphp
                <div class="col-span-12 xl:col-span-8 2xl:col-span-9">
                    <div>
                        <ul role="tablist" class="w-full flex gap-6">
                            @foreach ($subTabs as $sub)
                                <li id="{{ $sub['id'] }}" class="focus-visible:outline-none">
                                    <a class="bg-white text-lightBlue border-lightBlue cursor-pointer block appearance-none px-5 border rounded-md [&.active]:text-white [&.active]:bg-gradient-to-t [&.active]:from-[#002B56] [&.active]:to-[#0070D5] [&.active]:font-medium w-full py-2 {{ $type === $sub['type'] ? 'active' : '' }}"
                                        href="{{ $tabUrl($sub['type']) }}"
                                        @if ($sub['id'] === 'medication') onclick="event.preventDefault(); showNote('{{ $tabUrl($sub['type']) }}')" @endif>
                                        {{ $sub['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content mt-5">
                            @if ($type === 'prescription')
                                <div class="leading-relaxed visible opacity-100">
                                    <div class="patient_prescription_search">
                                        @include('pharmacy.partials.repeat-medicine-orders', ['data' => $orders, 'withPatient' => false])
                                    </div>
                                </div>
                            @else
                                <div class="leading-relaxed visible opacity-100">
                                    <div class="mt-3">
                                        <label class="inline-block mb-2 p-3 rounded-md bg-red-50 text-red-700 border border-red-200">
                                            <b>Note :</b> Due to NHS guidelines, pharmacies can no longer order medicine on a patient's behalf. This option has been disabled. Patients must place orders themselves via the customer panel or app.
                                        </label>
                                    </div>
                                    <div class="patient_prescription_search">
                                        {{-- admin/includes/prescription/repeat-medicine.blade.php --}}
                                        <table class="w-full text-left border-separate border-spacing-y-[10px]">
                                            <thead>
                                                <tr>
                                                    <th class="font-medium p-3 whitespace-nowrap border-b-0">Medicine</th>
                                                    <th class="font-medium p-3 whitespace-nowrap border-b-0 text-center">Last Issue Date</th>
                                                    <th class="font-medium p-3 whitespace-nowrap border-b-0 text-left">Dose</th>
                                                    <th class="font-medium p-3 whitespace-nowrap border-b-0 text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($patient['repeat_meds'] as $med)
                                                    <tr class="intro-x transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded text-slate-800 box hover:bg-[#DEDEDE]">
                                                        <td class="p-3 border-x-0 shadow-[5px_3px_5px_#00000005]">
                                                            <div class="flex space-x-1 items-center justify-start">
                                                                <img src="{{ asset('admin/images/medicine.svg') }}" alt="">
                                                                <span class="whitespace-break-spaces">{{ $med['medicine'] }} ({{ $med['quantity'] }})</span>
                                                            </div>
                                                        </td>
                                                        <td class="p-3 w-36 border-x-0 shadow-[5px_3px_5px_#00000005]">
                                                            <div class="flex space-x-1 items-center justify-center whitespace-break-spaces"><span>{{ $med['last_issued'] }}</span></div>
                                                        </td>
                                                        <td class="p-3 w-48 border-x-0 shadow-[5px_3px_5px_#00000005]">
                                                            <div class="flex space-x-1 items-center justify-start">
                                                                <img src="{{ asset('admin/images/stop-watch.svg') }}" alt="">
                                                                <span class="whitespace-break-spaces">{{ $med['dose'] }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="p-3 w-52 border-x-0 shadow-[5px_3px_5px_#00000005] relative before:absolute before:inset-y-0 before:left-0 before:my-auto before:block before:h-8 before:w-px before:bg-slate-200">
                                                            @if ($med['requested'])
                                                                <div class="text-center">
                                                                    <div class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-1.5 px-4 rounded-full font-medium cursor-pointer border-orange text-orange">
                                                                        Requested<br /> {{ $med['requested'] }}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endif

        @if ($type === 'patientinfo')
            <div class="col-span-12 lg:col-span-9 2xl:col-span-9">
                <div>
                    <div class="grid grid-cols-12 gap-6">
                        <div class="intro-y col-span-12 lg:col-span-12">
                            <div class="intro-y box p-5">
                                <div class="grid gap-6 grid-cols-3">
                                    @foreach ([
                                        'First name' => $patient['first_name'],
                                        'Last name' => $patient['last_name'],
                                        'NHS number' => $patient['nhs_number'],
                                        'Date of Birth' => $patient['dob'],
                                        'Email address' => $patient['email'],
                                        'Contact number' => $patient['contact_number'],
                                    ] as $fieldLabel => $value)
                                        <div>
                                            <label class="{{ $formLabel }}">{{ $fieldLabel }}</label>
                                            <input type="text" placeholder="{{ $fieldLabel }}" class="{{ $readonlyInput }}" value="{{ $value }}" readonly>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @unless ($isGroupOwner)
                                @include('pharmacy.partials.patient-clinical')
                            @endunless
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($type === 'consultation')
            {{-- livewire/admin/pgd-screening/pgd-consultation-data.blade.php --}}
            @php
                $badge = fn (string $status) => match ($status) {
                    'Failed', 'Declined', 'Dispense Declined' => 'px-2 py-1 rounded-full text-[11px] font-bold bg-red-100 text-red-700 uppercase tracking-wide',
                    'Requested' => 'px-2 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700 uppercase tracking-wide',
                    'Under Review' => 'inline-flex items-center px-2 py-1 rounded-full text-[11px] font-bold bg-yellow-100 text-yellow-700 uppercase tracking-wide whitespace-nowrap',
                    'Awaiting Dispense' => 'inline-flex items-center px-2 py-1 rounded-full text-[11px] font-bold bg-orange-100 text-orange-700 uppercase tracking-wide whitespace-nowrap',
                    'Dispensed' => 'inline-flex items-center px-2 py-1 rounded-full text-[11px] font-bold bg-green-100 text-green-700 uppercase tracking-wide whitespace-nowrap',
                    default => 'px-2 py-1 rounded-full text-[11px] font-bold bg-green-100 text-green-700 uppercase tracking-wide',
                };
                $action = 'transition duration-200 border shadow-sm inline-flex items-center justify-center py-1.5 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] whitespace-nowrap text-white text-[13px]';
                $head = 'font-semibold px-5 py-3 border-b-0';
            @endphp
            <div class="col-span-12 xl:col-span-8 2xl:col-span-9">
                <div class="h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div></div>
                        <div class="w-full sm:ml-auto sm:mt-0 sm:w-auto md:ml-0 flex justify-end">
                            <div class="relative w-56 items-center text-slate-500">
                                <input type="search" placeholder="Search..." autocomplete="off" data-list-search="[data-consultation-row]"
                                    class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 box w-56 pr-10">
                                <i data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                            </div>
                            <select class="transition duration-200 ease-in-out w-32 text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 box ml-2" data-consultation-type>
                                <option value="">All</option>
                                <option value="PGD">PGD</option>
                                <option value="IP">IP</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-6">
                        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                            @if ($consultations->isNotEmpty())
                                <div>
                                    <div class="flex">
                                        <div class="w-[10%] {{ $head }}">Date</div>
                                        <div class="w-[25%] {{ $head }}">Condition / Service</div>
                                        <div class="w-[15%] {{ $head }}">Status</div>
                                        <div class="w-[10%] {{ $head }}">Payment Status</div>
                                        <div class="w-[25%] {{ $head }}">Pharmacy</div>
                                        <div class="w-[20%] {{ $head }} text-center">Actions</div>
                                    </div>
                                </div>
                                <div>
                                    @foreach ($consultations as $c)
                                        <div class="flex transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded bg-white text-slate-800" data-consultation-row data-consultation-kind="IP" data-ip-consultation="{{ $c['id'] }}">
                                            <div class="w-[10%] px-5 py-3 flex items-center"><div><span class="block text-left mt-1">{{ $c['request_date'] }}</span></div></div>
                                            <div class="w-[25%] px-5 py-3 flex items-center"><div class="font-medium text-slate-700">{{ $c['service'] }} <span>(IP)</span> <br></div></div>
                                            <div class="w-[15%] px-5 py-3 flex items-center"><div class="flex items-center"><span class="{{ $badge($c['status']) }}" data-ip-status>{{ $c['status'] }}</span></div></div>
                                            <div class="w-[10%] px-5 py-3 flex items-center"><div class="font-medium text-slate-700">{{ $c['payment_status'] }}</div></div>
                                            <div class="w-[25%] px-5 py-3 flex items-center"><div class="font-medium text-slate-700">{{ $currentPharmacy['name'] }}</div></div>
                                            <div class="w-[20%] px-5 py-3 flex items-center justify-end gap-2">
                                                <a class="{{ $action }}" data-popup-open="ip-view-{{ $c['id'] }}">Summary</a>
                                                @if ($c['status'] === 'Dispensed')
                                                    <a class="{{ $action }}" data-popup-open="gp-letter-{{ $c['id'] }}">GP Letter</a>
                                                @endif
                                                @if ($c['status'] === 'Dispensed' && ($c['follow_up_available'] ?? false))
                                                    <button type="button" class="{{ $action }}" onclick="Swal.fire({ title: 'Follow Up', text: 'A follow-up consultation would open here.', icon: 'info', confirmButtonText: 'Ok' })">
                                                        <span class="translate-y-[-1px]">Follow Up</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @include('pharmacy.partials.pagination-footer', ['count' => $consultations->count()])
                            @else
                                <div>
                                    <div class="p-6 transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded bg-white text-slate-800">
                                        <img class="mx-auto" src="{{ asset('admin/images/no-record-found-new.png') }}" alt="">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @foreach ($consultations as $c)
                @include('pharmacy.partials.dashboard.ip-consultation-view', ['c' => $c])
                @if ($c['status'] === 'Dispensed')
                    @include('pharmacy.partials.gp-letter-panel', ['c' => $c, 'pharmacy' => $currentPharmacy])
                @endif
            @endforeach
            @include('pharmacy.partials.dashboard.ip-consultation-modals')
            @push('scripts')
            <script>
                // consultationType filter (All / PGD / IP) upstream re-queries Livewire.
                document.querySelector('[data-consultation-type]').addEventListener('change', (event) => {
                    document.querySelectorAll('[data-consultation-row]').forEach((row) => {
                        row.classList.toggle('hidden', event.target.value !== '' && row.dataset.consultationKind !== event.target.value);
                    });
                });
            </script>
            @endpush
        @endif
    </div>
</div>
