@php
    // admin/includes/appointment/appointment-detail.blade.php (book-services/{id}/show),
    // loaded into #appointment_preview when a calendar event is clicked.
    $isPast = \Illuminate\Support\Carbon::parse($a['start'])->lt(\Illuminate\Support\Carbon::parse('2026-09-28 12:00'));
    $service = collect($demo['staff_pharmacy_services'])->firstWhere('title', $a['service']);
    $bookByPhone = (bool) ($service['book_phone'] ?? false);
    $canReschedule = ! $bookByPhone && ($a['appointment_type'] === 'In Pharmacy' || ($service['video'] ?? false));
    $patient = collect($demo['staff_patients'])->firstWhere('id', $a['patient_id']);
    $primary = 'transition duration-200 border shadow-sm inline-flex items-center justify-center py-1 px-5 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] border-darkBlue text-white text-base';
    $secondary = 'bg-white text-lightBlue border-lightBlue transition duration-200 border shadow-sm inline-flex items-center justify-center py-1 px-5 rounded-md font-medium cursor-pointer text-base';
    $rowLabel = 'mb-2';
@endphp
<div class="hidden" data-appointment-detail="{{ $a['id'] }}">
    <div class="flex items-center p-4 border-b border-slate-200/60">
        <h2 class="mr-auto text-base font-medium flex items-center space-x-2">
            <span class="w-8 h-8 rounded-full overflow-hidden block"><img src="{{ asset('admin/images/user-patient.svg') }}" alt="" class="object-cover h-full w-full"></span>
            <span>Patient Info</span>
        </h2>
    </div>
    <div class="tab-content bg-white">
        <div class="tab-pane leading-relaxed active visible opacity-100">
            <div class="bg-[#F6F6F6] p-6 flex flex-wrap justify-center">
                <div class="bg-white grid grid-cols-3 w-full">
                    <div class="border border-slate-200">
                        <p class="border-b border-slate-200 p-4">Patient :</p>
                        <div class="p-4 break-all hyphens-auto">
                            <p class="mb-2 text-black font-bold">{{ $a['patient'] }}</p>
                        </div>
                    </div>
                    <div class="border border-slate-200 text-center">
                        <p class="border-b border-slate-200 p-4 ">Service Name :</p>
                        <div class="p-4">
                            <p class="mb-2 text-black font-bold">{{ $a['service'] }}</p>
                            <p class="text-redclr text-sm font-bold">{{ $a['amount'] > 0 ? '£'.$a['amount'] : '' }}</p>
                            <p class="text-black text-sm font-bold">Delivery Preference: <span class="font-normal">{{ $a['appointment_type'] }}</span></p>
                        </div>
                    </div>
                    <div class="border border-slate-200 text-center">
                        <p class="border-b border-slate-200 p-4">Appointment Time :</p>
                        <div class="p-4">
                            <p class="mb-2 text-black font-bold">{{ $a['date'] }} {{ $a['time'] }}</p>
                        </div>
                    </div>
                </div>
                @if ($a['notes'])
                    <div class="bg-white grid grid-cols-1 w-full">
                        <div class="border border-slate-200">
                            <p class="border-b border-slate-200 p-4"><span class="font-bold">Notes :</span> <span class="long-text-break">{{ $a['notes'] }}</span></p>
                        </div>
                    </div>
                @endif
                @if (in_array($a['status'], ['Pending Approval', 'Approved'], true))
                    @if (! $isPast && $bookByPhone)
                        <div class="bg-white grid grid-cols-1 w-full">
                            <div class="border border-slate-200">
                                <p class="border-b border-slate-200 p-4"><span class="text-red-600 font-medium">This service is set to be booked by phone, so you cannot reschedule it.</span></p>
                            </div>
                        </div>
                    @endif
                    @if ($a['amount'] > 0)
                        <div class="bg-white grid grid-cols-1 w-full">
                            <div class="border border-slate-200">
                                <div class="flex flex-wrap items-start gap-4 p-4 border-b border-slate-200">
                                    <div class="flex items-center gap-2 shrink-0">
                                        <div class="w-2 h-2 bg-green-600 rounded-full"></div>
                                        <p class="text-green-600 font-medium whitespace-nowrap"><span class="text-black font-bold">Payment Status :</span> Approved</p>
                                    </div>
                                    <div class="flex-1 min-w-[200px]">
                                        <p class="text-red-600 font-medium"><span class="text-black font-bold">Payment Detail :</span> £{{ $a['amount'] }} deposit paid, take rest of payment in branch.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($a['status'] === 'Approved' && $a['is_video'])
                        <div class="flex justify-center gap-4 mt-6">
                            <button type="button"
                                onclick="{{ $a['is_meeting_active'] ? "window.open('".route('app.video-conference')."', '_blank')" : 'joinTimeWarning()' }}"
                                class="mr-4 transition duration-200 shadow-sm inline-flex items-center justify-center py-1 px-5 rounded-md font-medium cursor-pointer group {{ $a['is_meeting_active'] ? 'bg-gradient-to-t from-[#002B56] to-[#0070D5] border-darkBlue text-white isMeetingActive' : 'opacity-60 bg-grey border-grey cursor-not-allowed text-black' }}">
                                <img src="{{ asset('admin/images/video_call.png') }}" width="18" class="mr-2 group-[.isMeetingActive]:invert" alt="Video">
                                <span class="mt-[1px]"> Join</span>
                            </button>
                        </div>
                    @endif
                    @if ($isPast)
                        <div class="flex justify-center gap-4 mt-6">
                            <button type="button" data-tw-dismiss="modal" class="bg-white text-[#002c57] transition duration-200 shadow-sm inline-flex items-center justify-center py-1 px-5 rounded-md font-medium cursor-pointer">
                                <span class="mt-[1px]"> Cancel</span>
                            </button>
                        </div>
                    @else
                        @if ($canReschedule)
                            <div class="pt-6">
                                <div class="flex justify-center pr-4">
                                    <a data-tw-toggle="modal" data-tw-target="#editPatientBooking" href="javascript:void(0);" onclick="openReschedule({{ $a['id'] }})" class="{{ $primary }}">Reschedule</a>
                                </div>
                            </div>
                        @endif
                        <div class="pt-6">
                            <div class="flex justify-center gap-4">
                                @if ($a['status'] === 'Pending Approval')
                                    <button type="button" onclick="appointmentUpdate('booked', '{{ $a['id'] }}')" class="{{ $primary }}"><span class="mt-[1px]"> Confirm</span></button>
                                    <button type="button" onclick="appointmentUpdate('cancel', '{{ $a['id'] }}')" class="{{ $secondary }}"><span class="mt-[1px]"> Cancel</span></button>
                                @else
                                    <button type="button" data-tw-toggle="modal" data-tw-target="#book_confirm" onclick="openResendEmail('{{ $a['email'] }}', '{{ $a['id'] }}')" class="{{ $primary }}">Resend booking confirmation email</button>
                                    <button type="button" onclick="appointmentUpdate('cancel', '{{ $a['id'] }}')" class="{{ $secondary }}"><span class="mt-[1px]"> Cancel</span></button>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            </div>
            <div>
                <div class="border-b border-slate-200 py-3 px-10 grid grid-cols-3">
                    <div class="col-span-2">
                        <p class="{{ $rowLabel }}">Email</p>
                        <div class="flex space-x-2 items-center"><i data-lucide="mail" class="stroke-1.5 w-5 h-5 text-[#475569]"></i><span>{{ $a['email'] }}</span></div>
                    </div>
                    <div>
                        <p class="{{ $rowLabel }}">Telephone</p>
                        <div class="flex space-x-2 items-center"><i data-lucide="smartphone" class="stroke-1.5 w-5 h-5 text-[#475569]"></i><span>{{ $patient['contact_number'] ?? '' }}</span></div>
                    </div>
                </div>
                <div class="border-b border-slate-200 py-3 px-10 grid grid-cols-3">
                    <div class="col-span-2">
                        <p class="{{ $rowLabel }}">Date of Birth</p>
                        <div class="flex space-x-2 items-center"><i data-lucide="calendar" class="stroke-1.5 w-5 h-5 text-[#475569]"></i><span>{{ $a['dob'] }}</span></div>
                    </div>
                </div>
                @if ($patient)
                    <div class="border-b border-slate-200 py-3 px-10 grid grid-cols-3">
                        <div class="col-span-2">
                            <p class="{{ $rowLabel }}">Postcode</p>
                            <div class="flex space-x-2 items-center"><i data-lucide="home" class="stroke-1.5 w-5 h-5 text-[#475569]"></i><span>{{ $patient['postcode'] }}</span></div>
                        </div>
                        <div>
                            <p class="{{ $rowLabel }}">Address</p>
                            <div class="flex space-x-2 items-center"><i data-lucide="home" class="stroke-1.5 w-5 h-5 text-[#475569]"></i><span>{{ $patient['address_line_1'] }}</span></div>
                        </div>
                    </div>
                @endif
                <div class="border-b border-slate-200 py-3 px-10 grid grid-cols-3">
                    <div class="col-span-2">
                        <p class="{{ $rowLabel }}">Pharmacy Name</p>
                        <div class="flex space-x-2 items-center"><img src="{{ asset('admin/images/pharmacy-image.svg') }}" alt=""><span>{{ $a['pharmacy'] }}</span></div>
                    </div>
                </div>
            </div>
            @if ($a['status'] !== 'Cancelled')
                <div>
                    <form class="flex flex-col grow" onsubmit="return updateAppointmentNote(event, {{ $a['id'] }})">
                        <div class="p-4 grid grid-cols-2 gap-x-3 gap-y-4 bg-white">
                            <div>
                                <label class="inline-block mb-2 font-semibold">Appointment Status</label>
                                <div class="w-full text-slate-500">
                                    <select class="w-full box" name="appointmentStatus">
                                        @foreach (['Approved', 'Pending Approval', 'Attended', 'Not Attended'] as $status)
                                            <option value="{{ $status }}" @selected($a['status'] === $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="inline-block mb-2 font-semibold">Add notes</label>
                                <textarea placeholder="Add notes" maxlength="150" name="notes" class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 block">{{ $a['notes'] }}</textarea>
                            </div>
                            <div class="flex items-center gap-4">
                                <button type="submit" class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white px-4 py-2 rounded booking_update_form_submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
