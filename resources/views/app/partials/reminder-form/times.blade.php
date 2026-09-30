@php
    // remindTimeListWidget(): an underlined time (35% wide with the clock icon
    // on the alarm screen, 25% on the order screen), a red delete button once
    // there's more than one, and + after the last (the alarm screen caps at 6).
    $width = $withIcon ? 137.5 : 98.25;
    $max = $withIcon ? 6 : 99;
@endphp
<div data-times data-max="{{ $max }}">
    @foreach ($times as $time)
        <div class="flex items-center" data-time-row>
            <div class="py-[10px] border-b border-greyLightColor flex items-center justify-between" style="width: {{ $width }}px">
                <span class="text-[14px] font-medium text-[#737373]">{{ $time }}</span>
                @if ($withIcon)
                    <img src="/assets/app/images/time_icon.svg" class="w-[15px] h-[15px]" alt="">
                @endif
            </div>
            <button type="button" data-time-delete class="ml-5 w-[35px] h-[30px] px-[10px] py-[5px] rounded-[5px] bg-[#B05030] {{ count($times) > 1 ? '' : 'hidden' }}">
                <span class="block w-full h-full bg-white [mask:url('/assets/app/images/delete.png')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/delete.png')_center/contain_no-repeat]"></span>
            </button>
            <button type="button" data-time-add class="ml-[10px] w-[35px] h-[30px] rounded-[5px] bg-primaryColor flex items-center justify-center {{ $loop->last && count($times) < $max ? '' : 'hidden' }}">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            </button>
        </div>
    @endforeach
</div>

@once
<script>
document.addEventListener('DOMContentLoaded', () => {
    // + copies the last time; the delete buttons hide once one row is left.
    document.querySelectorAll('[data-times]').forEach((list) => {
        const max = Number(list.dataset.max);
        const sync = () => {
            const rows = [...list.querySelectorAll('[data-time-row]')];
            rows.forEach((row, i) => {
                row.querySelector('[data-time-delete]').classList.toggle('hidden', rows.length < 2);
                row.querySelector('[data-time-add]').classList.toggle('hidden', i !== rows.length - 1 || rows.length >= max);
            });
        };
        list.addEventListener('click', (event) => {
            const row = event.target.closest('[data-time-row]');
            if (event.target.closest('[data-time-add]')) { list.appendChild(row.cloneNode(true)); }
            if (event.target.closest('[data-time-delete]')) { row.remove(); }
            sync();
        });
    });
});
</script>
@endonce
