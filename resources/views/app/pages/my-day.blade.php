@extends('layouts.app')

@section('content')
{{-- reminder_screen/my_day_reminder.dart reminderList() on greyVeryLightColor. --}}
<div class="min-h-full bg-[#F1F1F1] px-5 py-[10px] leading-[1.3]">
    @forelse ($demo['reminders'] as $reminder)
        @php $status = ['taken' => 'Taken', 'missed' => 'Missed'][$reminder['status_today']] ?? null; @endphp
        <div class="relative {{ $loop->first ? '' : 'mt-[10px]' }} p-[10px] bg-white rounded-[10px] flex items-center">
            <span class="shrink-0 p-2 rounded-full bg-white">
                <img src="/assets/app/images/medicine_b_icon.svg" class="w-[35px]" alt="">
            </span>
            <div class="ml-[10px] min-w-0 pr-16">
                <p class="text-[15px] font-bold text-black">{{ $reminder['medicine'] }}</p>
                <p class="mt-[10px] text-[12px] text-black underline">{{ $reminder['dose'] }}</p>
                <span class="mt-[10px] inline-block px-2 py-[2px] rounded-[30px] border border-black text-[15px] font-medium text-black">{{ implode(', ', $reminder['times']) }}</span>
            </div>
            <div class="absolute bottom-[10px] right-[10px]" data-myday-actions>
                @if ($status === null)
                    <div class="flex items-start gap-[10px]">
                        <button type="button" data-myday-status="Taken"><img src="/assets/app/images/check_home_icon.svg" alt="Taken"></button>
                        <button type="button" data-myday-status="Missed"><img src="/assets/app/images/cross_icon.svg" alt="Missed"></button>
                    </div>
                @else
                    @include('app.partials.reminder-status', ['status' => $status])
                @endif
            </div>
        </div>
    @empty
        <p class="h-[510px] flex items-center justify-center text-[16px] text-[#737373]">No reminder added yet.</p>
    @endforelse
</div>

<template id="myday-taken">@include('app.partials.reminder-status', ['status' => 'Taken'])</template>
<template id="myday-missed">@include('app.partials.reminder-status', ['status' => 'Missed'])</template>
<script>
    // MyDayReminderController.onTapTakenMissed()
    document.querySelectorAll('[data-myday-status]').forEach((b) => b.addEventListener('click', () => {
        const t = document.getElementById('myday-' + b.dataset.mydayStatus.toLowerCase());
        b.closest('[data-myday-actions]').replaceChildren(t.content.cloneNode(true));
    }));
</script>
@endsection
