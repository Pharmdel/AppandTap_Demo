@extends('layouts.pharmacy')

@php
    // admin/broadcast/pharmacy-appointment.blade.php -> admin/includes/appointment/appointment-info.blade.php
    $appointments = $demo['staff_calendar_appointments'];
    $services = $demo['staff_pharmacy_services'];
    $bookable = collect($services)->where('is_ip_clinic', false)->where('status', true);
    $colors = ['Approved' => '#B880E4', 'Attended' => '#67C882', 'Not Attended' => '#03C5F0', 'Cancelled' => '#FD4E3D', 'Pending Approval' => '#FF9153'];
    $events = collect($appointments)->map(fn ($a) => [
        'id' => (string) $a['id'],
        'title' => $a['patient'],
        'start' => $a['start'],
        'end' => $a['end'],
        'description' => $a['service'],
        'color' => $colors[$a['status']] ?? '#FF9153',
        'appointment_type' => $a['appointment_type'] === 'Video Call' ? '1' : '0',
        'status' => $a['status'],
    ])->values();
    $slideOver = 'w-[90%] ml-auto h-screen flex flex-col bg-white relative shadow-md transition-[margin-right] duration-[0.6s] -mr-[100%] group-[.show]:mr-0 sm:w-[800px]';
    $modal = 'modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s] overflow-y-auto';
    $field = 'transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 block';
    $slots = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'];
    $bookedSlots = ['09:30', '11:00', '14:00'];
    $printIcon = '<svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" class="w-4 h-4 fill-current" height="512" viewBox="0 0 24 24" width="512"><path d="m21.5 18h-3c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h3c.827 0 1.5-.673 1.5-1.5v-7c0-.827-.673-1.5-1.5-1.5h-19c-.827 0-1.5.673-1.5 1.5v7c0 .827.673 1.5 1.5 1.5h3c.276 0 .5.224.5.5s-.224.5-.5.5h-3c-1.379 0-2.5-1.122-2.5-2.5v-7c0-1.378 1.121-2.5 2.5-2.5h19c1.379 0 2.5 1.122 2.5 2.5v7c0 1.378-1.121 2.5-2.5 2.5z"/><path d="m14.5 21h-6c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h6c.276 0 .5.224.5.5s-.224.5-.5.5z"/><path d="m14.5 19h-6c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h6c.276 0 .5.224.5.5s-.224.5-.5.5z"/><path d="m10.5 17h-2c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h2c.276 0 .5.224.5.5s-.224.5-.5.5z"/><path d="m18.5 7c-.276 0-.5-.224-.5-.5v-4c0-.827-.673-1.5-1.5-1.5h-9c-.827 0-1.5.673-1.5 1.5v4c0 .276-.224.5-.5.5s-.5-.224-.5-.5v-4c0-1.378 1.121-2.5 2.5-2.5h9c1.379 0 2.5 1.122 2.5 2.5v4c0 .276-.224.5-.5.5z"/><path d="m16.5 24h-9c-1.379 0-2.5-1.122-2.5-2.5v-8c0-.276.224-.5.5-.5h13c.276 0 .5.224.5.5v8c0 1.378-1.121 2.5-2.5 2.5zm-10.5-10v7.5c0 .827.673 1.5 1.5 1.5h9c.827 0 1.5-.673 1.5-1.5v-7.5z"/></svg>';
@endphp

@section('content-class', 'pt-6')

@section('content')
<style>
    #calendar.fc .fc-toolbar.fc-header-toolbar { flex-wrap: wrap; row-gap: 1lh; column-gap: 16px; }
    #calendar.fc .fc-toolbar.fc-header-toolbar .fc-toolbar-chunk:has(.fc-toolbar-title) { order: 1; text-align: center; width: 100%; }
    .fc-print-button:hover svg { color: #fff !important; }
</style>

@if ($bookable->isNotEmpty())
    <div class="mb-5">
        <a data-tw-toggle="modal" data-tw-target="#addappp" href="javascript:void(0);"
            class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">
            <i data-lucide="plus" class="stroke-1.5 mr-2 h-4 w-4"></i>
            Book Appointment
        </a>
    </div>
@endif
<div class="col-span-12 xl:col-span-8 2xl:col-span-9">
    <div class="box p-5">
        <div class="pr-8 pb-4 col-span-12">
            <div class="flex justify-between items-center">
                <div class="select-type-1 service_filter">
                    <select class="w-full bg-transparent text-white border border-[#8FD689] rounded-lg" id="calendarServiceFilter">
                        <option value="">All Services</option>
                        @foreach ($services as $service)
                            <option value="{{ $service['title'] }}">{{ $service['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="flex space-x-5">
            <div class="w-full">
                <div id="calendar"></div>
            </div>
            <div class="mt-14 whitespace-nowrap">
                <ul>
                    @foreach (['Approved' => ['#B880E4', '#B880E4'], 'Cancelled' => ['#B880E4', '#FD4E3D'], 'Attended' => ['#67C882', '#67C882'], 'Not Attended' => ['#03C5F0', '#03C5F0'], 'Pending Approval' => ['#FF9153', '#FF9153']] as $label => [$border, $fill])
                        <li class="flex items-center space-x-2 mb-2">
                            <div class="border flex justify-center items-center" style="border-color: {{ $border }};">
                                <span class="w-6 h-4 border border-slate-200 block" style="background-color: {{ $fill }};"></span>
                            </div>
                            <span>{{ $label }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- #appointment_preview: the clicked event's details (book-services/{id}/show) --}}
<div aria-hidden="true" tabindex="-1" id="appointment_preview" class="{{ $modal }}">
    <div class="{{ $slideOver }}">
        <a data-tw-dismiss="modal" class="absolute top-0 left-0 right-auto mt-4 -ml-10 sm:-ml-12" href="javascript:void(0);">
            <i data-lucide="x" class="stroke-1.5 w-8 h-8 text-slate-400"></i>
        </a>
        <div id="appointment-container" class="overflow-y-auto h-full">
            @foreach ($appointments as $a)
                @include('pharmacy.partials.appointment-detail', ['a' => $a])
            @endforeach
        </div>
    </div>
</div>

{{-- #addappp: livewire/admin/service-booking/patient-booking.blade.php --}}
<div id="addappp" tabindex="-1" aria-hidden="true" class="{{ $modal }}">
    <div class="{{ $slideOver }}">
        <a class="absolute top-0 left-0 right-auto mt-4 -ml-10 sm:-ml-12" data-tw-dismiss="modal" href="javascript:void(0);">
            <i data-lucide="x" class="stroke-1.5 w-8 h-8 text-slate-400"></i>
        </a>
        <div class="overflow-y-auto h-full" id="patientBooking">
            <div class="bg-white">
                <div class="flex items-center justify-between border-b border-slate-200 py-3 px-4 min-h-[62px] h-auto font-semibold">
                    <p>Appointment info</p>
                </div>
                <div class="p-4 grid grid-cols-2 gap-x-3 gap-y-4">
                    <div class="w-full col-span-full">
                        <label class="inline-block mb-2 chlabel font-semibold">Choose patient *</label>
                        <div class="flex gap-4 items-start">
                            <div class="w-full" data-booking-choose>
                                <select class="{{ $field }} box" data-booking="patient">
                                    <option value="">Choose patient</option>
                                    @foreach ($demo['staff_patients'] as $p)
                                        @continue($p['status'] !== 'active')
                                        <option value="{{ $p['first_name'] }} {{ $p['last_name'] }}">{{ $p['first_name'] }} {{ $p['last_name'] }} ({{ $p['dob'] }}){{ $p['user_type'] === 'NHS' ? ' - NHS' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-2 items-center">
                                <button type="button" data-booking-mode="others" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-1.5 px-5 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">Others</button>
                                <button type="button" data-booking-mode="choose" class="bg-white text-lightBlue border-lightBlue transition duration-200 border shadow-sm inline-flex items-center justify-center py-1.5 px-5 rounded-md font-medium cursor-pointer">Choose Patient</button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-100 mb-4 p-5 rounded-lg overflow-hidden hidden col-span-full" data-booking-others>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="pb-3"><label class="inline-block mb-2 font-semibold">Patient name *</label><input type="text" placeholder="Patient name" class="{{ $field }} px-4 py-3" data-booking="other_name"></div>
                            <div class="pb-3"><label class="inline-block mb-2 font-semibold">Patient DOB *</label><input type="date" class="{{ $field }} px-4 py-3" data-booking="other_dob"></div>
                            <div class="pb-3"><label class="inline-block mb-2 font-semibold">Patient email *</label><input type="email" placeholder="Patient email" class="{{ $field }} px-4 py-3" data-booking="other_email"></div>
                            <div class="pb-0"><label class="inline-block mb-2 font-semibold">Contact number *</label><input type="text" placeholder="Contact number" maxlength="15" class="{{ $field }} px-4 py-3" data-booking="other_phone"></div>
                        </div>
                    </div>
                    <div>
                        <label class="inline-block mb-2 font-semibold">Choose service *</label>
                        <div class="w-full text-slate-500">
                            <select class="{{ $field }} box" data-booking="service">
                                <option value="">Select service</option>
                                @foreach ($bookable as $service)
                                    <option value="{{ $service['title'] }}" data-phone="{{ $service['book_phone'] ? 1 : 0 }}" data-video="{{ $service['video'] ? 1 : 0 }}">{!! $service['book_phone'] ? '&#9990;' : '&#128241;' !!} {{ $service['title'] }}{{ $service['amount'] > 0 ? ' -(£'.$service['amount'].')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="hidden" data-booking-type-wrap>
                        <label class="inline-block mb-2 font-semibold">Appointment type *</label>
                        <div class="w-full text-slate-500">
                            <select class="{{ $field }} box" data-booking="type">
                                <option value="" disabled>Select appointment type</option>
                                <option value="In Pharmacy" selected>In Pharmacy</option>
                                <option value="Video Call" data-video-option>Video Call</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="inline-block mb-2 font-semibold">Select appointment date *</label>
                        <div class="preview relative">
                            <input type="date" min="2026-09-28" class="{{ $field }} px-4 py-3" data-booking="date" disabled>
                        </div>
                    </div>
                    <div class="col-span-full hidden" data-booking-slots-wrap>
                        <h3 class="text-base text-black">Available Slots</h3>
                        <div class="flex flex-wrap md:gap-4 items-center md:justify-start justify-between sm:flex-row md:mt-4 mt-2 max-h-[200px] overflow-auto patient_book_appointment">
                            @foreach ($slots as $slot)
                                @if (in_array($slot, $bookedSlots, true))
                                    <div class="transition duration-200 border shadow-sm items-center justify-center py-2 px-3 rounded-md text-white mb-2 mr-1 inline-block w-20 text-center text-base" style="background-color:#959595;border-color:#959595;">{{ $slot }}</div>
                                @else
                                    <div class="slots transition duration-200 border shadow-sm items-center justify-center py-2 px-3 rounded-md font-regular cursor-pointer bg-white border-lightBlue text-lightBlue mb-2 mr-1 inline-block w-20 text-center text-base" data-slot="{{ $slot }}">{{ $slot }}</div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="p-4 grid grid-cols-2 gap-x-3 gap-y-4">
                    <div class="mt-6">
                        <label class="inline-block mb-2 font-semibold">Appointment Status</label>
                        <div class="w-full text-slate-500">
                            <select class="{{ $field }} box" data-booking="status">
                                <option value="Approved">Approved</option>
                                <option value="Attended">Attended</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="inline-block mb-2 font-semibold">Add notes</label>
                        <textarea placeholder="Add notes" maxlength="150" class="{{ $field }} px-4 py-3" data-booking="notes"></textarea>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden hidden" data-booking-summary>
                <div class="flex items-center justify-between border-b border-slate-200 py-3 px-4 min-h-[62px] h-auto font-semibold">
                    <p>Appointment detail</p>
                </div>
                <div class="p-4">
                    <ul class="mb-7">
                        <li class="border border-slate-200 bg-[#F6F6F6] p-3 flex space-x-3 hidden" data-summary="patient"><img src="{{ asset('admin/images/user-patient.svg') }}" alt=""><span></span></li>
                        <li class="border border-slate-200 bg-[#F6F6F6] p-3 flex space-x-3 hidden" data-summary="service"><img src="{{ asset('admin/images/services-name.svg') }}" alt=""><span></span></li>
                        <li class="border border-slate-200 bg-[#F6F6F6] p-3 flex space-x-3 hidden" data-summary="date"><img src="{{ asset('admin/images/reminder-black.svg') }}" alt=""><span></span></li>
                    </ul>
                    <span class="text-red-600 text-sm block mb-3" data-booking-error></span>
                    <button type="button" onclick="submitBooking()" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-5 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">
                        Book appointment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- #editPatientBooking: livewire/admin/service-booking/edit-patient-booking.blade.php --}}
<div id="editPatientBooking" tabindex="-1" aria-hidden="true" class="{{ $modal }}">
    <div class="{{ $slideOver }}">
        <a class="absolute top-0 left-0 right-auto mt-4 -ml-10 sm:-ml-12" data-tw-dismiss="modal" href="javascript:void(0);">
            <i data-lucide="x" class="stroke-1.5 w-8 h-8 text-slate-400"></i>
        </a>
        <div class="bg-white">
            <div class="flex items-center justify-between border-b border-slate-200 py-3 px-4 min-h-[62px] h-auto font-semibold">
                <p>Reschedule Appointment</p>
            </div>
            <div class="p-4 grid grid-cols-2 gap-x-3 gap-y-4">
                <input type="hidden" id="rescheduleAppointmentId">
                <div>
                    <label class="inline-block mb-2 font-semibold">Select appointment date *</label>
                    <input type="date" min="2026-09-28" class="{{ $field }} px-4 py-3" id="updateBookingDateInput">
                    <span id="update-service-date" class="text-red-600 text-sm"></span>
                </div>
                <div class="col-span-full">
                    <h3 class="text-base text-black">Available Slots</h3>
                    <div class="flex flex-wrap md:gap-4 items-center md:justify-start mt-2 patient_book_appointment" id="rescheduleSlots">
                        @foreach ($slots as $slot)
                            @if (in_array($slot, $bookedSlots, true))
                                <div class="transition duration-200 border shadow-sm items-center justify-center py-2 px-3 rounded-md text-white mb-2 mr-1 inline-block w-20 text-center text-base" style="background-color:#959595;border-color:#959595;">{{ $slot }}</div>
                            @else
                                <div class="slots transition duration-200 border shadow-sm items-center justify-center py-2 px-3 rounded-md font-regular cursor-pointer bg-white border-lightBlue text-lightBlue mb-2 mr-1 inline-block w-20 text-center text-base" data-slot="{{ $slot }}">{{ $slot }}</div>
                            @endif
                        @endforeach
                    </div>
                    <span id="update-slot" class="text-red-600 text-sm"></span>
                </div>
                <div>
                    <button type="button" onclick="validateAndUpdate()" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-5 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">Update appointment</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- #pdf_filter_poup: "Print Appointments" --}}
<div aria-hidden="false" tabindex="-1" id="pdf_filter_poup"
    class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed right-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s] flex justify-center items-center">
    <div class="w-[90vw] max-w-[450px] flex flex-col">
        <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] px-8 py-3 rounded-t flex items-center relative">
            <a class="bg-white text-darkBlue w-10 h-10 rounded-full absolute -right-4 -top-4 flex justify-center items-center cursor-pointer" href="javascript:void(0);" data-tw-dismiss="modal">
                <i data-lucide="x" class="stroke-2 w-6 h-6"></i>
            </a>
            <h2 class="text-white text-xl font-medium text-center grow">Print Appointments</h2>
        </div>
        <div class="bg-white">
            <div class="px-2.5 py-3 min-h-[230px] flex flex-col">
                <div class="mb-6">
                    <label class="inline-block font-semibold mb-2">select filter</label>
                    <div class="preview relative">
                        <div class="w-full text-slate-500">
                            <select class="{{ $field }} intro-x" id="pdf_filter_type">
                                <option value="today">Today</option>
                                <option value="this_week">This week</option>
                                <option value="this_month">This month</option>
                                <option value="this_year">This year</option>
                                <option value="Custom">Custom</option>
                            </select>
                        </div>
                    </div>
                    <div class="hidden grid grid-cols-2 gap-5 mt-4" id="custom_date_div">
                        <div>
                            <label class="inline-block mb-2 font-semibold">Start Date</label>
                            <input type="date" class="{{ $field }} px-4 py-2" id="start_date">
                        </div>
                        <div>
                            <label class="inline-block mb-2 font-semibold">End Date</label>
                            <input type="date" class="{{ $field }} px-4 py-2" id="end_date">
                        </div>
                        <div class="text-red-600 mt-1 col-span-2" id="dateError" role="alert" aria-live="polite"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="rounded-b-lg overflow-hidden" style="background: linear-gradient(180deg, white, transparent);">
            <div class="mt-auto text-right py-3 px-2 rounded-lg border border-blue flex justify-between bg-white">
                <button type="button" onclick="applyPdfFilter()" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">Print Appointments</button>
            </div>
        </div>
    </div>
</div>

@include('pharmacy.partials.resend-email-modals', ['modals' => ['book_confirm' => ['resendAppointmentId', 'resendEmails', false]]])
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    (function () {
        const statusColors = @json($colors);
        const calendarEl = document.getElementById('calendar');
        const videoIcon = @json(asset('admin/images/red_video_call.png'));
        const printIcon = @json($printIcon);

        const calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prevYear,prev,next,nextYear today',
                center: 'title',
                right: 'print dayGridMonth,timeGridWeek,timeGridDay,listWeek',
            },
            initialDate: new Date(),
            navLinks: true,
            editable: false,
            dayMaxEvents: 3,
            events: @json($events),
            eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
            eventClick: function (info) {
                showAppointment(info.event.id);
            },
            eventDidMount: function (info) {
                const isList = info.view.type.indexOf('list') === 0;
                let patient = '<b>' + info.event.title + '</b>';
                const service = info.event.extendedProps.description || '';
                if (info.event.extendedProps.appointment_type === '1') {
                    patient += ` <img src="${videoIcon}" style="width:14px;height:14px;margin-left:5px;display:inline-block;" />`;
                    patient += ' <span style="color:#E20000;font-weight:bold;">Join</span>';
                }
                const newText = isList ? [patient, service].filter(Boolean).join(' – ') : patient;
                const titleEl = isList
                    ? info.el.querySelector('td.fc-list-event-title a, td.fc-list-event-title')
                    : info.el.querySelector('.fc-event-title');
                if (titleEl) { titleEl.innerHTML = newText; }
            },
            customButtons: {
                print: { text: '', click: () => showModal('#pdf_filter_poup') },
            },
        });
        calendar.render();
        const printBtn = calendarEl.querySelector('.fc-print-button');
        if (printBtn) { printBtn.innerHTML = printIcon; }
        window.portalCalendar = calendar;

        // serviceFilter(): upstream reloads with ?service=; the demo filters in place.
        document.getElementById('calendarServiceFilter').addEventListener('change', (event) => {
            calendar.getEvents().forEach((e) => {
                e.setProp('display', event.target.value === '' || e.extendedProps.description === event.target.value ? 'auto' : 'none');
            });
        });

        window.showAppointment = function (id) {
            document.querySelectorAll('[data-appointment-detail]').forEach((el) => el.classList.toggle('hidden', el.dataset.appointmentDetail !== String(id)));
            showModal('#appointment_preview');
        };

        // A Confirm/Cancel/status change recolours the event, as the reload does upstream.
        document.addEventListener('appointment:updated', (event) => {
            const calEvent = calendar.getEventById(String(event.detail.id));
            if (calEvent) {
                calEvent.setProp('color', statusColors[event.detail.status] || '#FF9153');
                calEvent.setExtendedProp('status', event.detail.status);
            }
            const detail = document.querySelector(`[data-appointment-detail="${event.detail.id}"]`);
            if (detail) {
                const select = detail.querySelector('select[name="appointmentStatus"]');
                if (select) { select.value = event.detail.status; }
                detail.querySelectorAll('.pt-6').forEach((block) => block.classList.add('hidden'));
            }
            hideModal('#appointment_preview');
        });

        window.updateAppointmentNote = function (event, id) {
            event.preventDefault();
            const status = event.target.appointmentStatus.value;
            document.dispatchEvent(new CustomEvent('appointment:updated', { detail: { id, status } }));
            Swal.fire({ title: 'Appointment updated successfully.', icon: 'success', timer: 1800, showConfirmButton: false });
            return false;
        };

        // Slot pickers (Available Slots): one selected at a time, .mainbg like upstream.
        document.querySelectorAll('.patient_book_appointment').forEach((group) => {
            group.addEventListener('click', (event) => {
                const slot = event.target.closest('[data-slot]');
                if (!slot) { return; }
                group.querySelectorAll('[data-slot]').forEach((s) => s.classList.remove('mainbg', 'text-white'));
                slot.classList.add('mainbg', 'text-white');
                group.dataset.selected = slot.dataset.slot;
                refreshBookingSummary();
            });
        });

        // ----- Book Appointment (patient-booking.blade.php) -----
        const booking = (name) => document.querySelector(`[data-booking="${name}"]`);
        let bookingMode = 'choose';
        document.querySelectorAll('[data-booking-mode]').forEach((btn) => btn.addEventListener('click', () => {
            bookingMode = btn.dataset.bookingMode;
            document.querySelector('[data-booking-others]').classList.toggle('hidden', bookingMode !== 'others');
            document.querySelector('[data-booking-choose]').classList.toggle('opacity-50', bookingMode === 'others');
            booking('patient').disabled = bookingMode === 'others';
            refreshBookingSummary();
        }));
        ['patient', 'service', 'date', 'type', 'other_name', 'other_email'].forEach((name) => booking(name).addEventListener('input', refreshBookingSummary));
        booking('service').addEventListener('change', () => {
            const option = booking('service').selectedOptions[0];
            const chosen = booking('service').value !== '';
            booking('date').disabled = !chosen;
            document.querySelector('[data-booking-type-wrap]').classList.toggle('hidden', !chosen || option.dataset.phone === '1');
            document.querySelector('[data-video-option]').hidden = option.dataset.video !== '1';
        });
        booking('date').addEventListener('change', () => {
            document.querySelector('[data-booking-slots-wrap]').classList.toggle('hidden', !booking('date').value);
        });

        function bookingPatient() {
            return bookingMode === 'others' ? booking('other_name').value.trim() : booking('patient').value;
        }

        function refreshBookingSummary() {
            const slot = document.querySelector('#addappp .patient_book_appointment').dataset.selected || '';
            const date = booking('date').value;
            const items = {
                patient: bookingPatient() ? 'Patient Name : ' + bookingPatient() + (bookingMode === 'others' && booking('other_email').value ? ' / Patient Email : ' + booking('other_email').value : '') : '',
                service: booking('service').value ? 'Service Name : ' + booking('service').value : '',
                date: date ? 'Appointment Date / Time : ' + date.split('-').reverse().join('/') + ' / ' + slot : '',
            };
            let any = false;
            Object.entries(items).forEach(([key, text]) => {
                const li = document.querySelector(`[data-summary="${key}"]`);
                li.classList.toggle('hidden', !text);
                li.querySelector('span').textContent = text;
                any = any || Boolean(text);
            });
            document.querySelector('[data-booking-summary]').classList.toggle('hidden', !any);
        }

        window.submitBooking = function () {
            const slot = document.querySelector('#addappp .patient_book_appointment').dataset.selected;
            const error = document.querySelector('[data-booking-error]');
            const missing = !bookingPatient() ? 'Please choose patient.' : !booking('service').value ? 'Please select service.' : !booking('date').value ? 'Please select appointment date.' : !slot ? 'Please select a time slot.' : '';
            error.textContent = missing;
            if (missing) { return; }
            const start = `${booking('date').value}T${slot}:00`;
            const status = booking('status').value;
            calendar.addEvent({
                id: 'new-' + Date.now(), title: bookingPatient(), start, color: statusColors[status],
                description: booking('service').value, appointment_type: booking('type').value === 'Video Call' ? '1' : '0',
            });
            hideModal('#addappp');
            calendar.gotoDate(booking('date').value);
            Swal.fire({ title: 'Appointment booked successfully.', icon: 'success', timer: 1800, showConfirmButton: false });
        };

        // ----- Reschedule -----
        window.openReschedule = function (id) {
            document.getElementById('rescheduleAppointmentId').value = id;
            document.getElementById('updateBookingDateInput').value = '';
            document.getElementById('rescheduleSlots').dataset.selected = '';
            document.querySelectorAll('#rescheduleSlots [data-slot]').forEach((s) => s.classList.remove('mainbg', 'text-white'));
        };
        window.validateAndUpdate = function () {
            const date = document.getElementById('updateBookingDateInput').value;
            const slot = document.getElementById('rescheduleSlots').dataset.selected;
            document.getElementById('update-service-date').textContent = date ? '' : 'Please select appointment date.';
            document.getElementById('update-slot').textContent = slot ? '' : 'Please select a time slot.';
            if (!date || !slot) { return false; }
            const calEvent = calendar.getEventById(document.getElementById('rescheduleAppointmentId').value);
            if (calEvent) { calEvent.setStart(`${date}T${slot}:00`); calEvent.setEnd(null); }
            hideModal('#editPatientBooking');
            hideModal('#appointment_preview');
            calendar.gotoDate(date);
            Swal.fire({ title: 'Appointment rescheduled successfully.', icon: 'success', timer: 1800, showConfirmButton: false });
        };

        // ----- Print Appointments (appointment.list.pdf upstream) -----
        document.getElementById('pdf_filter_type').addEventListener('change', (event) => {
            document.getElementById('custom_date_div').classList.toggle('hidden', event.target.value !== 'Custom');
            document.getElementById('dateError').textContent = '';
        });
        window.applyPdfFilter = function () {
            const type = document.getElementById('pdf_filter_type').value;
            const now = calendar.getDate();
            let from; let to;
            const day = (d) => new Date(d.getFullYear(), d.getMonth(), d.getDate());
            if (type === 'today') { from = day(now); to = new Date(from.getTime() + 864e5); }
            if (type === 'this_week') { from = day(now); from.setDate(from.getDate() - from.getDay()); to = new Date(from.getTime() + 7 * 864e5); }
            if (type === 'this_month') { from = new Date(now.getFullYear(), now.getMonth(), 1); to = new Date(now.getFullYear(), now.getMonth() + 1, 1); }
            if (type === 'this_year') { from = new Date(now.getFullYear(), 0, 1); to = new Date(now.getFullYear() + 1, 0, 1); }
            if (type === 'Custom') {
                const s = document.getElementById('start_date').value; const e = document.getElementById('end_date').value;
                if (!s || !e) { document.getElementById('dateError').textContent = 'Both start and end dates are required'; return; }
                from = new Date(s); to = new Date(new Date(e).getTime() + 864e5);
            }
            const rows = calendar.getEvents().filter((e) => e.display !== 'none' && e.start >= from && e.start < to)
                .sort((a, b) => a.start - b.start)
                .map((e) => `<tr><td>${e.title}</td><td>${e.extendedProps.description}</td><td>${e.start.toLocaleDateString('en-GB')} ${e.start.toTimeString().slice(0, 5)}</td><td>${e.extendedProps.status || 'Approved'}</td></tr>`).join('');
            hideModal('#pdf_filter_poup');
            printHtml('Appointments', `<table><thead><tr><th>Patient</th><th>Service</th><th>Date &amp; Time</th><th>Status</th></tr></thead><tbody>${rows || '<tr><td colspan="4">No record found</td></tr>'}</tbody></table>`);
        };
    })();
</script>
@endpush
