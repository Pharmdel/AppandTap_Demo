@extends('layouts.app')

@section('content')
{{-- reminder_screen/all_reminder_screen.dart reminderList() on greyVeryLightColor. --}}
<div class="min-h-full bg-[#F1F1F1] pt-[5px] pb-[60px] leading-[1.3]">
    @forelse ($demo['reminders'] as $reminder)
        <div class="mx-5 my-[5px] px-[15px] py-[10px] bg-white rounded-[5px] flex items-start">
            <img src="{{ $reminder['icon'] }}" class="w-5 mr-2 mt-[3px] shrink-0" alt="">
            <div class="flex-1 min-w-0">
                <p class="text-[15px] font-bold text-black leading-[1.5] line-clamp-2">{{ $reminder['medicine'] }}</p>
                <a href="{{ route('app.reminders-add') }}?id={{ $reminder['id'] }}" class="text-[11px] text-[#737373] underline">{{ $reminder['dose'] }}</a>
                <div class="mt-[5px] h-5 flex gap-[25px] overflow-x-auto [scrollbar-width:none]">
                    @foreach ($reminder['times'] as $time)
                        <span class="shrink-0 text-[14px] font-medium text-skyColor">{{ $time }}</span>
                    @endforeach
                </div>
                @if ($reminder['end_date'])
                    <p class="text-right text-[12px] font-medium text-[#B05030]">End date : - {{ $reminder['end_date'] }}</p>
                @endif
            </div>
        </div>
    @empty
        <p class="h-[680px] flex items-center justify-center text-[16px] text-[#737373]">No reminder added yet.</p>
    @endforelse
</div>
@endsection
