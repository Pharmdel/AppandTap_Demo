@php
    // reminder_screen/my_day_reminder.dart AppBar. toolbarHeight: 240 with the
    // dependent picker and the date strip open, 160 picker only, 200 strip
    // only, else 120. The strip is the latest page of 6 days, today on the right.
    $multiple = count($demo['dependents']) > 0;
    $today = now('Europe/London')->startOfDay();
    $days = collect(range(5, 0))->map(fn (int $back) => $today->copy()->subDays($back));
    [$closed, $open] = $multiple ? [160, 240] : [120, 200];
@endphp

<div class="shrink-0 bg-primaryColor shadow-[0_3px_6px_rgba(241,241,241,.9)] px-4 flex flex-col justify-center text-white leading-[1.3] relative z-[1]" style="height: {{ $closed }}px" data-myday-header data-height-closed="{{ $closed }}" data-height-open="{{ $open }}">
    <div class="flex items-center">
        <button type="button" onclick="history.back()" class="pr-5" aria-label="Back">
            <img src="/assets/app/images/back-arrow.svg" class="w-3 h-5 brightness-0 invert" alt="">
        </button>
        <p class="text-[20px] font-bold">My Day</p>
    </div>
    <div class="h-[5px]"></div>
    @if ($multiple)
        <div class="px-5">
            <x-app.dependent-picker :arrow="40" />
        </div>
    @endif
    <div class="h-[10px]"></div>
    <div class="px-5">
        <button type="button" data-calendar-toggle class="w-[100px] flex items-center text-[16px]">
            <span data-heading>Today</span>
            <svg class="w-[30px] h-[30px] transition-transform" data-chevron viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 10 5 5 5-5"/></svg>
        </button>
        <div class="hidden my-[5px] h-[70px] flex" data-calendar>
            @foreach ($days as $day)
                @php $selected = $day->isSameDay($today); @endphp
                <button type="button" data-day="{{ $day->isSameDay($today) ? 'Today' : $day->format('M') }}"
                    class="flex-1 mx-[2px] p-1 rounded-[5px] border flex flex-col items-center justify-evenly {{ $selected ? 'bg-primaryColor border-white text-white' : 'bg-white border-[#A4A4A4] text-black' }}">
                    <span class="text-[16px] font-bold">{{ $day->format('d') }}</span>
                    <span class="text-[11px] font-medium">{{ $day->format('D') }}</span>
                </button>
            @endforeach
        </div>
        <div class="h-[10px]"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('[data-myday-header]');
    const calendar = header.querySelector('[data-calendar]');
    // updateCalenderVisibility(): the bar grows to fit the date strip.
    header.querySelector('[data-calendar-toggle]').addEventListener('click', () => {
        const open = calendar.classList.toggle('hidden') === false;
        header.style.height = (open ? header.dataset.heightOpen : header.dataset.heightClosed) + 'px';
        header.querySelector('[data-chevron]').style.transform = open ? 'rotate(180deg)' : '';
    });
    // onTapDate(): "Today" for today, otherwise the month (DateFormat('MMM')).
    calendar.querySelectorAll('[data-day]').forEach((day) => day.addEventListener('click', () => {
        calendar.querySelectorAll('[data-day]').forEach((d) => {
            const on = d === day;
            d.classList.toggle('bg-primaryColor', on); d.classList.toggle('border-white', on); d.classList.toggle('text-white', on);
            d.classList.toggle('bg-white', !on); d.classList.toggle('border-[#A4A4A4]', !on); d.classList.toggle('text-black', !on);
        });
        header.querySelector('[data-heading]').textContent = day.dataset.day;
    }));
});
</script>
