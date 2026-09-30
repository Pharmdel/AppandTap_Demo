{{-- home_widgets.dart _buildReminderCard(): CupertinoIcons check_mark_circled / clear_circled. --}}
<div class="pt-[5px] flex items-center gap-[3px] {{ $status === 'Taken' ? 'text-greenColor' : 'text-[#B05030]' }}">
    <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="9.5" />
        @if ($status === 'Taken')
            <path d="m7.8 12.3 2.8 2.8 5.6-5.8" />
        @else
            <path d="m9 9 6 6m0-6-6 6" />
        @endif
    </svg>
    <span class="text-[13px] font-medium">{{ $status }}</span>
</div>
