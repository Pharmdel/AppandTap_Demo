@extends('layouts.app')

@php
    // prescription_screen.dart: rxOrders(), repeatMedications() and
    // GPAppointmentListScreen on greyVeryLightColor, one per tab.
    $rx = $demo['prescriptions'];
    $active = in_array(request('tab'), ['rx-orders', 'repeat-meds', 'gp-appointments'], true) ? request('tab') : 'rx-orders';
    $card = 'mt-[10px] bg-white rounded-[5px]';
    // GP times come back as 24h without a leading zero ("9:20", "14:00").
    $gpTime = fn (string $t) => ltrim(\Illuminate\Support\Carbon::createFromFormat('h:i A', $t)->format('G:i'), '0') ?: '0';
    // Rx Orders status: Issued green, Requested yellow, Rejected blood red.
    $orderStatusColor = fn (string $status) => match ($status) {
        'Issued' => 'text-greenColor',
        'Requested' => 'text-yellow-600',
        'Rejected' => 'text-[#8B0000]',
        default => 'text-[#B05030]',
    };
@endphp

@section('content')
<div class="min-h-full bg-[#F1F1F1] px-5 pb-20 leading-[1.3]">
    <div data-rx-panel="rx-orders" class="{{ $active === 'rx-orders' ? '' : 'hidden' }}">
        @foreach ($rx['rx_orders'] as $order)
            <div class="{{ $card }} p-[15px]">
                <div class="flex items-start">
                    <img src="{{ $order['icon'] }}" class="w-5 h-5 shrink-0" alt="">
                    <p class="ml-[10px] flex-1 text-[13px] font-bold text-black">{{ $order['medicine'] }}</p>
                </div>
                <div class="mt-[6px] pl-[25px] text-[11px]">
                    <p class="text-[#737373]">{{ $order['quantity'] }}</p>
                    <div class="mt-[6px] flex justify-between">
                        <p class="font-bold text-greenColor underline truncate">{{ $order['date_requested'] }}</p>
                        <p class="{{ $orderStatusColor($order['status']) }} underline text-right">{{ $order['status'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div data-rx-panel="repeat-meds" class="{{ $active === 'repeat-meds' ? '' : 'hidden' }}">
        @foreach ($rx['repeat_meds'] as $med)
            @php $hasReminder = ! empty($med['reminder_times']); @endphp
            <div class="{{ $card }} pl-[15px] pt-[15px] pb-[15px] pr-[10px]">
                <div class="flex items-start">
                    <img src="{{ $med['icon'] }}" class="w-5 h-5 shrink-0" alt="">
                    <div class="ml-[10px] flex-1 min-w-0">
                        <p class="text-[13px] font-bold text-black">{{ $med['medicine'] }}</p>
                        @if ($hasReminder)
                            <p class="text-[11px] text-[#737373]">{{ $med['dose'] }}</p>
                        @else
                            <a href="{{ route('app.reminders-order-medicine') }}?medicine={{ $med['id'] }}" class="block text-[12px] text-primaryColor underline truncate">Setup a reminder</a>
                        @endif
                        <div class="mt-[6px] flex text-[11px]">
                            @if ($hasReminder)
                                <a href="{{ route('app.reminders-order-medicine') }}?medicine={{ $med['id'] }}" class="font-bold text-greenColor underline truncate">{{ $med['days_to_go'] }}</a>
                            @endif
                            <p class="ml-[5px] flex-1 text-right text-[#B05030] underline">{{ $med['last_issue'] }}</p>
                        </div>
                    </div>
                    @if ($med['status'] === null)
                        <button type="button" data-select-medicine class="ml-[10px] w-5 h-5 shrink-0 rounded-full border border-black bg-white"></button>
                    @endif
                </div>
                <div class="h-[6px]"></div>
            </div>
        @endforeach
    </div>

    <div data-rx-panel="gp-appointments" class="{{ $active === 'gp-appointments' ? '' : 'hidden' }}">
        @foreach ($demo['gp_appointments'] as $gp)
            <div class="{{ $card }} p-[10px] flex items-start text-primaryColor">
                <span class="w-[15px] h-[17px] shrink-0 bg-primaryColor [mask:url('/assets/app/images/doctor%20_icon.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/doctor%20_icon.svg')_center/contain_no-repeat]"></span>
                <div class="ml-[10px] flex-1 min-w-0">
                    <p class="text-[14px] font-bold leading-none">{{ $gp['doctor'] }}</p>
                    <p class="mt-[10px] text-[12px]">Appointment Date: {{ \Illuminate\Support\Carbon::parse($gp['date'])->format('m/d/Y') }} ({{ $gpTime($gp['start']) }} To {{ $gpTime($gp['end']) }})</p>
                    <div class="mt-[10px] flex items-end justify-between text-[12px]">
                        <p class="flex-1">{{ $gp['location'] }}</p>
                        @if ($gp['can_cancel'])
                            <button type="button" class="pl-2 flex items-center gap-[5px] text-[#B05030]">
                                <span class="w-[14px] h-[14px] rounded-full border border-[#B05030] flex items-center justify-center">
                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="m7 7 10 10M17 7 7 17"/></svg>
                                </span>
                                Cancel
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- floatingActionButton (centerFloat): Request Medications on Repeat Meds, Book Appointment on GP Appointments. --}}
<div class="absolute bottom-4 left-5 right-5">
    <a href="{{ route('app.request-medication') }}" data-rx-fab="repeat-meds" class="{{ $active === 'repeat-meds' ? 'flex' : 'hidden' }} h-[45px] rounded-[45px] bg-primaryColor items-center justify-center text-white text-[15px] font-bold tracking-[.4px]">Request Medications</a>
    <a href="{{ route('app.find-your-gp') }}" data-rx-fab="gp-appointments" class="{{ $active === 'gp-appointments' ? 'flex' : 'hidden' }} h-[45px] rounded-[45px] bg-primaryColor items-center justify-center text-white text-[15px] font-bold tracking-[.4px]">Book Appointment</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-rx-tab]').forEach((tab) => tab.addEventListener('click', () => {
        const key = tab.dataset.rxTab;
        document.querySelectorAll('[data-rx-tab]').forEach((t) => {
            t.classList.toggle('border-white', t === tab);
            t.classList.toggle('font-medium', t === tab);
            t.classList.toggle('border-transparent', t !== tab);
        });
        document.querySelectorAll('[data-rx-panel]').forEach((p) => p.classList.toggle('hidden', p.dataset.rxPanel !== key));
        document.querySelectorAll('[data-rx-fab]').forEach((f) => { f.classList.toggle('hidden', f.dataset.rxFab !== key); f.classList.toggle('flex', f.dataset.rxFab === key); });
    }));
    // Tapping the circle selects a medicine (border widens to 5).
    document.querySelectorAll('[data-select-medicine]').forEach((c) => c.addEventListener('click', () => c.classList.toggle('border-[5px]')));
});
</script>
@endsection
