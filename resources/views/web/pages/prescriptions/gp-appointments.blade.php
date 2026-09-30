@extends('layouts.web')

@php
    $linked = $demo['patient']['is_gp_linked'];
    $appointments = $demo['gp_appointments'];
@endphp

@section('content')
<div class="flex gap-6">
    <x-web.prescriptions-tabs active="prescriptions-gp-appointments" />

    <div class="flex-1">
        @if (!$linked)
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm rounded-lg px-5 py-4">
                <strong>Enter Your GP Linkage Key</strong>
                <p class="mt-1 text-xs">Speak to your GP and ask for your "Linkage Key" so we can connect your NHS GP record to AppAndTap.</p>
            </div>
        @else
            <div class="flex justify-end mb-3">
                <button onclick="document.getElementById('bookGpModal').showModal()" class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white text-sm px-4 py-2 rounded-full font-semibold">
                    Book appointment
                </button>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-400 text-xs">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium">Booking Info</th>
                            <th class="text-left px-4 py-3 font-medium">Start Time</th>
                            <th class="text-left px-4 py-3 font-medium">End Time</th>
                            <th class="text-left px-4 py-3 font-medium">Location</th>
                            <th class="text-left px-4 py-3 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appt)
                            <tr class="border-t border-slate-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="stethoscope" class="w-4 h-4 text-blue"></i>
                                        <div>
                                            <p class="text-lightBlue font-medium">{{ $appt['doctor'] }}</p>
                                            <p class="text-xs text-slate-400">{{ $appt['staff_role'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-500">{{ $appt['start'] }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $appt['end'] }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $appt['location'] }}</td>
                                <td class="px-4 py-3">
                                    @if ($appt['can_cancel'])
                                        <span class="text-xs px-2 py-1 rounded-full bg-orange-100 text-orange-700 cursor-pointer">Cancel</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<dialog id="bookGpModal" class="rounded-xl p-0 w-[380px] backdrop:bg-black/40">
    <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white px-5 py-4">
        <h3 class="font-semibold">Book GP Appointment</h3>
    </div>
    <div class="p-5 flex flex-col gap-3">
        <div>
            <label class="text-xs text-slate-400">Booking Date</label>
            <input type="date" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="text-xs text-slate-400">Choose Appointment Slot</label>
            <select class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm">
                <option>09:20 AM - 09:35 AM</option>
                <option>10:00 AM - 10:15 AM</option>
            </select>
        </div>
        <div>
            <label class="text-xs text-slate-400">Booking Reason</label>
            <textarea rows="3" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm"></textarea>
        </div>
        <div class="flex gap-2">
            <button onclick="document.getElementById('bookGpModal').close()" class="flex-1 border border-slate-200 rounded-full py-2 text-sm">Cancel</button>
            <button onclick="document.getElementById('bookGpModal').close()" class="flex-1 bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white rounded-full py-2 text-sm font-semibold">Book</button>
        </div>
    </div>
</dialog>
@endsection
