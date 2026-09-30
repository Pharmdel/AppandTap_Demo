@extends('layouts.web')

@php
    $tab = request('tab', 'upcoming');
    $appointments = collect($demo['appointments'])->where('tab', $tab);
    $statusColor = fn ($s) => match ($s) {
        'Cancelled', 'Declined' => 'text-redclr',
        'Approved', 'Dispensed' => 'text-green',
        'Requested' => 'text-orange',
        'Attended' => 'text-green',
        default => 'text-sky',
    };
@endphp

@section('content')
<div class="flex items-center justify-between mb-4">
    <div class="flex gap-2">
        @foreach (['upcoming' => 'Upcoming', 'previous' => 'Previous', 'consultations' => 'Consultations'] as $key => $label)
            <a href="{{ route('web.appointments-list') }}?tab={{ $key }}"
               class="px-4 py-2 rounded-full text-sm font-medium {{ $tab === $key ? 'bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white' : 'bg-white text-lightBlue' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
    <a href="{{ route('web.book-appointment') }}" class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white text-sm px-4 py-2 rounded-full font-semibold">Book appointment</a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-400 text-xs">
            <tr>
                <th class="text-left px-4 py-3 font-medium">Booking ID</th>
                <th class="text-left px-4 py-3 font-medium">Service</th>
                <th class="text-left px-4 py-3 font-medium">Type</th>
                <th class="text-left px-4 py-3 font-medium">Date &amp; Slot</th>
                <th class="text-left px-4 py-3 font-medium">Amount</th>
                <th class="text-left px-4 py-3 font-medium">Status</th>
                <th class="text-left px-4 py-3 font-medium">Payment</th>
                <th class="text-left px-4 py-3 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($appointments as $appt)
                <tr class="border-t border-slate-50">
                    <td class="px-4 py-3 text-lightBlue font-medium">{{ $appt['booking_id'] }}</td>
                    <td class="px-4 py-3">
                        {{ $appt['service'] }}
                        @if ($appt['notes'])
                            <button onclick="document.getElementById('notes-{{ $appt['booking_id'] }}').showModal()" class="ml-1 text-slate-400"><i data-lucide="message-square" class="w-3.5 h-3.5 inline"></i></button>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-slate-500">{{ $appt['type'] }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $appt['date'] }} &middot; {{ $appt['slot'] }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $appt['amount'] > 0 ? '£'.$appt['amount'] : '-' }}</td>
                    <td class="px-4 py-3 font-medium {{ $statusColor($appt['status']) }}">{{ $appt['status'] }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $appt['payment_status'] }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            @if ($appt['type'] === 'Video Call')
                                @if ($appt['is_meeting_active'])
                                    <a href="{{ $appt['meeting_link'] }}" target="_blank" class="flex items-center gap-1 bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white text-xs px-3 py-1 rounded-full">
                                        <i data-lucide="video" class="w-3 h-3"></i> Join
                                    </a>
                                @else
                                    <button onclick="alert('You\'re a little early - the room opens 10 minutes before the appointment.')" class="flex items-center gap-1 bg-slate-200 text-slate-500 text-xs px-3 py-1 rounded-full">
                                        <i data-lucide="video" class="w-3 h-3"></i> Join
                                    </button>
                                @endif
                            @endif
                            @if ($appt['can_cancel'])
                                <button onclick="confirm('Cancel this appointment?')" class="text-redclr text-xs">Cancel</button>
                            @endif
                        </div>
                    </td>
                </tr>
                <dialog id="notes-{{ $appt['booking_id'] }}" class="rounded-xl p-5 w-[320px] backdrop:bg-black/40">
                    <p class="text-sm text-slate-600">{{ $appt['notes'] }}</p>
                    <button onclick="document.getElementById('notes-{{ $appt['booking_id'] }}').close()" class="mt-3 w-full border border-slate-200 rounded-full py-2 text-sm">Close</button>
                </dialog>
            @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-slate-400">No appointments in this tab.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
