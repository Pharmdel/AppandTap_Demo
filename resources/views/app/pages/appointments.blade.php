@extends('layouts.app')

@php
    // booking_appointment_screen/booking_appointment_list_screen.dart
    $appointments = collect($demo['appointments']);
    $tabs = ['upcoming' => 'Upcoming', 'previous' => 'Previous'];
    if ($appointments->where('tab', 'consultations')->isNotEmpty()) {
        $tabs['consultations'] = 'Consultations';
    }
    $active = array_key_exists(request('tab'), $tabs) ? request('tab') : 'upcoming';
    // getAppointmentStatusColor()
    $statusColor = fn (string $status) => match ($status) {
        'Cancelled', 'Declined' => '#B05030',
        'Approved', 'Dispensed' => '#1C4332',
        'Requested' => '#FF9800',
        'Under Review' => '#FFC107',
        'Awaiting Dispense' => '#2196F3',
        default => '#0676DD',
    };
    $videoSolid = '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h8A2.5 2.5 0 0 1 16 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-8A2.5 2.5 0 0 1 3 16.5v-9Zm14.5 2.3 3.1-2.2A.9.9 0 0 1 22 8.3v7.4a.9.9 0 0 1-1.4.7l-3.1-2.2V9.8Z"/></svg>';
@endphp

@section('content')
<div class="min-h-full bg-[#F1F1F1] leading-[1.3] pb-[90px]">
    {{-- Pinned SliverAppBar TabBar: white 15px labels, 3px secondaryColor indicator. --}}
    <div class="sticky top-0 z-10 bg-primaryColor flex h-[46px]" data-tabs>
        @foreach ($tabs as $key => $label)
            <button type="button" data-tab="{{ $key }}" class="flex-1 text-white text-[15px] border-b-[3px] {{ $key === $active ? 'border-secondaryColor' : 'border-transparent' }}">{{ $label }}</button>
        @endforeach
    </div>

    @foreach ($tabs as $key => $label)
        @php $list = $appointments->where('tab', $key); @endphp
        <div data-panel="{{ $key }}" class="{{ $key === $active ? '' : 'hidden' }}">
            @forelse ($list as $appt)
                <div class="mx-5 mt-[10px] bg-white rounded-[5px] border border-primaryColor/60 p-[10px] text-[14px]">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center min-w-0">
                            <span class="w-5 h-5 shrink-0 rounded-full bg-white overflow-hidden flex items-center justify-center">
                                <img src="/assets/app/images/service_image.png" class="max-h-5 object-contain" alt="">
                            </span>
                            <p class="ml-[10px] font-bold text-black line-clamp-3">{{ $appt['service'] }}</p>
                        </div>
                        @if ($appt['amount'] > 0)
                            <p class="pl-5 text-[15px] font-semibold text-[#B05030]">£{{ $appt['amount'] }}</p>
                        @endif
                    </div>

                    <div class="mt-[10px] flex items-center">
                        <div class="flex-1 flex items-center min-w-0">
                            <img src="/assets/app/images/calender_icon.svg" class="w-5 h-5 shrink-0" alt="">
                            <p class="ml-[10px] text-[#737373] line-clamp-3">{{ $appt['date'] }} | {{ $appt['slot'] }}</p>
                        </div>
                        @if ($appt['meeting_link'] && ($key === 'upcoming' || $appt['is_meeting_active']))
                            <a href="{{ route('app.video-conference') }}" class="pl-[5px] pr-[5px] pb-[2px] rounded-[5px] flex items-center text-white {{ $appt['is_meeting_active'] ? 'bg-primaryColor' : 'bg-gradient-to-b from-[#BDBDBD] to-[#9E9E9E] opacity-50 pointer-events-none' }}">
                                {!! $videoSolid !!}<span class="text-[14px] font-medium">Join&nbsp;</span>
                            </a>
                        @endif
                    </div>

                    @if ($key === 'upcoming')
                        <div class="mt-[10px] flex items-center {{ $appt['status'] === 'Cancelled' ? 'justify-center' : 'justify-between' }}" data-status-row>
                            <p class="font-medium" style="color: {{ $statusColor($appt['status']) }}" data-status>{{ $appt['status'] }}</p>
                            @if ($appt['status'] !== 'Cancelled')
                                <div class="flex items-center gap-[15px] font-semibold" data-actions>
                                    <a href="{{ route('app.book-appointment') }}?reschedule={{ $appt['booking_id'] }}" class="flex items-center gap-[3px] text-primaryColor">
                                        <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4"/><path d="M7.5 13.5h1m3 0h1m3 0h1m-9 3.5h1m3 0h1" stroke-linecap="round"/></svg>
                                        Reschedule
                                    </a>
                                    <button type="button" data-open="#cancelAppointmentPopup" data-cancel-appointment class="flex items-center gap-[3px] text-[#B05030] font-semibold">
                                        <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="m9 9 6 6m0-6-6 6" stroke-linecap="round"/></svg>
                                        Cancel
                                    </button>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="py-[10px] flex items-center {{ $appt['meeting_link'] ? 'justify-between' : 'justify-end' }}">
                            @if ($appt['meeting_link'])
                                <p class="flex items-center gap-[10px] text-[#737373]">
                                    <svg class="w-[30px] h-[30px] text-[#A4A4A4]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><rect x="3" y="6" width="12.5" height="12" rx="2.2"/><path d="m15.5 10.3 4.4-2.9a.7.7 0 0 1 1.1.6v8a.7.7 0 0 1-1.1.6l-4.4-2.9"/></svg>
                                    Video Call
                                </p>
                            @endif
                            <p class="font-medium" style="color: {{ $statusColor($appt['status']) }}">{{ $appt['status'] }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <p class="pt-[255px] text-center text-[28px] font-semibold text-[#9E9E9E]">No Record</p>
            @endforelse
        </div>
    @endforeach
</div>

{{-- bottomNavigationBar: SafeArea > Padding(h50, v20) > mainButton. --}}
<div class="absolute bottom-5 left-[50px] right-[50px]">
    <a href="{{ route('app.book-appointment') }}" class="h-[45px] rounded-[45px] bg-primaryColor flex items-center justify-center gap-[10px] text-white text-[15px] font-bold">
        <span class="w-[30px] h-[30px] bg-white [mask:url('/assets/app/images/appointment_calender_icon.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/appointment_calender_icon.svg')_center/contain_no-repeat]"></span>
        Book Appointment
    </a>
</div>

@push('overlays')
    @include('app.partials.confirm-popup', ['id' => 'cancelAppointmentPopup', 'title' => 'Cancel  Appointment', 'body' => 'Would you like to cancel this appointment ?'])
@endpush

<script>
document.addEventListener('DOMContentLoaded', () => {
    // BookAppointmentController.onTapCancel(): confirm, then the booking shows as Cancelled.
    let cancelling = null;
    document.querySelectorAll('[data-cancel-appointment]').forEach((b) => b.addEventListener('click', () => { cancelling = b.closest('[data-status-row]'); }));
    document.querySelector('#cancelAppointmentPopup [data-confirm]').addEventListener('click', () => {
        if (!cancelling) { return; }
        const status = cancelling.querySelector('[data-status]');
        status.textContent = 'Cancelled';
        status.style.color = '#B05030';
        cancelling.querySelector('[data-actions]').remove();
        cancelling.classList.replace('justify-between', 'justify-center');
    });

    document.querySelectorAll('[data-tab]').forEach((tab) => tab.addEventListener('click', () => {
        document.querySelectorAll('[data-tab]').forEach((t) => t.classList.toggle('border-secondaryColor', t === tab));
        document.querySelectorAll('[data-tab]').forEach((t) => t.classList.toggle('border-transparent', t !== tab));
        document.querySelectorAll('[data-panel]').forEach((p) => p.classList.toggle('hidden', p.dataset.panel !== tab.dataset.tab));
    }));
});
</script>
@endsection
