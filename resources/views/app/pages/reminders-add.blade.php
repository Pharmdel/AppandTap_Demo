@extends('layouts.app')

@php
    // reminder_screen/add_reminder_screen.dart ("Alarm Times"), on a white Scaffold.
    // Opened with ?id= to edit an existing medicine reminder.
    $reminder = collect($demo['reminders'])->firstWhere('id', request()->integer('id'));
    $heading = 'text-[20px] font-bold text-black';
@endphp

@section('content')
<div class="min-h-full bg-white px-[30px] py-5 leading-[1.3]">
    @include('app.partials.reminder-form.medicine', ['name' => $reminder['medicine'] ?? $demo['reminders'][0]['medicine']])

    <p class="mt-[30px] {{ $heading }}">Set alarm times</p>
    @include('app.partials.reminder-form.times', ['times' => $reminder['times'] ?? ['08:00 AM'], 'withIcon' => true])

    <p class="mt-[30px] {{ $heading }}">Start Date</p>
    @include('app.partials.reminder-form.date', ['placeholder' => 'Start Date'])

    {{-- buildRepeatDaysType(): repeatTypesList, "Every Day" by default. --}}
    <p class="mt-[30px] {{ $heading }}">Repeat</p>
    <label class="mt-[10px] relative block h-10 px-[5px] border-b border-[#737373]">
        <select class="w-full h-full appearance-none bg-transparent text-[14px] text-[#737373] outline-none pr-10">
            <option>Every Day</option>
            <option>Specific days of week</option>
            <option>Remind Every</option>
            <option>Birth Control Cycle</option>
        </select>
        <svg class="absolute right-0 top-1/2 -translate-y-1/2 w-10 h-10 pointer-events-none" viewBox="0 0 24 24" fill="#737373"><path d="M7 10l5 5 5-5z"/></svg>
    </label>

    <p class="mt-[30px] {{ $heading }}">End Date (Optional)</p>
    @include('app.partials.reminder-form.date', ['placeholder' => 'End Date'])

    <p class="mt-[30px] text-[14px] font-medium text-[#737373]">By clicking the button below, you confirm that you understand the need to accurately add the remaining quantity and timings.</p>

    <div class="mt-[30px] flex {{ $reminder ? 'justify-between' : 'justify-center' }}">
        @if ($reminder)
            <button type="button" data-open="#deleteReminderPopup" class="w-[157px] h-[45px] rounded-[45px] border-[1.5px] border-primaryColor text-primaryColor text-[15px] font-bold tracking-[.4px]">Delete</button>
        @endif
        <a href="{{ route('app.reminders-all') }}" class="w-[157px] h-[45px] rounded-[45px] bg-primaryColor flex items-center justify-center text-white text-[15px] font-bold tracking-[.4px]">{{ $reminder ? 'Update' : 'Confirm' }}</a>
    </div>
</div>

@if ($reminder)
    @push('overlays')
        @include('app.partials.confirm-popup', ['id' => 'deleteReminderPopup', 'title' => 'Delete Reminder', 'body' => 'Are you sure, you want to delete this reminder'])
    @endpush
@endif
@endsection
