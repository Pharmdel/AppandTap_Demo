@extends('layouts.app')

@php
    // reminder_screen/order_medicine_reminder_screen.dart ("Reminder"): the
    // re-order reminder for one repeat medicine (?medicine= its id). A medicine
    // that already has one shows its take-medicine alarm and "Delete reminder".
    $meds = collect($demo['prescriptions']['repeat_meds']);
    $med = $meds->firstWhere('id', request()->integer('medicine')) ?? $meds->first();
    $hasReminder = ! empty($med['reminder_times']);
    $name = preg_replace('/\s*\(\d+\)$/', '', $med['medicine']);
    $alarm = collect($demo['reminders'])->firstWhere('medicine', $name);
    $label = 'text-[14px] font-medium text-[#737373]';
@endphp

@section('content')
<div class="min-h-full bg-white px-[30px] py-5 leading-[1.3]">
    @include('app.partials.reminder-form.medicine', ['name' => $med['medicine']])

    <p class="mt-[30px] {{ $label }}">How often do you need to re-order your medication?</p>
    <label class="mt-[10px] relative block h-10 px-[5px] border-b border-[#737373]">
        <select class="w-full h-full appearance-none bg-transparent text-[16px] font-semibold text-black outline-none pr-10">
            @foreach ([28, 56, 84, 0] as $days)
                <option>{{ $days === 0 ? 'Custom' : "Repeat- {$days} Days" }}</option>
            @endforeach
        </select>
        <svg class="absolute right-0 top-1/2 -translate-y-1/2 w-10 h-10 pointer-events-none" viewBox="0 0 24 24" fill="#737373"><path d="M7 10l5 5 5-5z"/></svg>
    </label>

    <p class="mt-[30px] {{ $label }}">Time to remind</p>
    @include('app.partials.reminder-form.times', ['times' => ['09:00 AM'], 'withIcon' => false])

    <p class="mt-[30px] text-[20px] font-bold text-black">End Date</p>
    @include('app.partials.reminder-form.date', ['placeholder' => 'Select Date'])

    <div class="mt-[30px]">
        @if ($hasReminder && $alarm)
            <div class="p-[10px] rounded-[5px] bg-[#F1F1F1]">
                <div class="flex items-center justify-between">
                    <p class="text-[18px] font-medium text-black">Take Medicine Alarm</p>
                    <a href="{{ route('app.reminders-add') }}?id={{ $alarm['id'] }}" class="w-[30px] py-[10px]"><img src="/assets/app/images/editp_icon.svg" alt="Edit"></a>
                </div>
                <p class="text-[12px] font-medium text-[#737373]">{{ $alarm['dose'] }}</p>
                <div class="mt-[10px] h-[30px] flex gap-[25px] text-[12px] font-medium text-skyColor">
                    @foreach ($alarm['times'] as $time)
                        <span>{{ $time }}</span>
                    @endforeach
                </div>
                @if ($alarm['end_date'])
                    <p class="text-right text-[12px] font-medium text-[#B05030]">End date : - {{ $alarm['end_date'] }}</p>
                @endif
            </div>
        @else
            <a href="{{ route('app.reminders-add') }}" class="h-[55px] rounded-[55px] bg-primaryColor flex items-center justify-center text-white text-[15px] font-bold tracking-[.4px]">Setup take medicine alarm</a>
        @endif
    </div>

    @if ($hasReminder)
        <button type="button" data-open="#deleteOrderReminderPopup" class="mt-5 w-full h-[55px] rounded-[55px] border-[1.5px] border-primaryColor flex items-center justify-center gap-[10px] text-primaryColor text-[15px] font-semibold">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M10 11v6M14 11v6M5.5 7l1 12a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2l1-12M9 7V4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5V7"/></svg>
            Delete reminder
        </button>
        @push('overlays')
            @include('app.partials.confirm-popup', ['id' => 'deleteOrderReminderPopup', 'title' => 'Delete Reminder', 'body' => 'Are you sure, you want to delete this reminder'])
        @endpush
    @endif
</div>
@endsection
