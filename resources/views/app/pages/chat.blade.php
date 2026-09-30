@extends('layouts.app')

@php
    // chat_screen/chat_screen.dart on a white Scaffold.
    $chat = $demo['chat'];
    $today = now('Europe/London')->startOfDay();
    // _buildChatItem(): "Today", "Yesterday", else dd-MM-yyyy.
    $dateLabel = function (string $date) use ($today) {
        $d = \Illuminate\Support\Carbon::parse($date)->startOfDay();

        return $d->equalTo($today) ? 'Today' : ($d->equalTo($today->copy()->subDay()) ? 'Yesterday' : $d->format('d-m-Y'));
    };
    $videoSolid = '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h8A2.5 2.5 0 0 1 16 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-8A2.5 2.5 0 0 1 3 16.5v-9Zm14.5 2.3 3.1-2.2A.9.9 0 0 1 22 8.3v7.4a.9.9 0 0 1-1.4.7l-3.1-2.2V9.8Z"/></svg>';
@endphp

@section('content')
<div class="h-full bg-white flex flex-col leading-[1.3]">
    <div class="flex-1 overflow-y-auto px-5 pt-[10px] pb-20" data-chat-list>
        @php $lastDate = null; @endphp
        @foreach ($chat['messages'] as $msg)
            @if ($msg['date'] !== $lastDate)
                <div class="flex items-center">
                    <hr class="flex-1 border-black/10">
                    <span class="px-[10px] py-[10px] text-[14px] text-black">{{ $dateLabel($msg['date']) }}</span>
                    <hr class="flex-1 border-black/10">
                </div>
                @php $lastDate = $msg['date']; @endphp
            @endif

            @php $mine = $msg['from'] === 'patient'; @endphp
            <div class="py-[5px] flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                <div class="min-w-0 flex flex-col {{ $mine ? 'items-end' : 'items-start' }}">
                    <div class="px-5 py-[10px] rounded-[20px] {{ $mine ? 'rounded-br-none bg-primaryColor text-white' : 'rounded-bl-none bg-greyLightColor text-black' }}">
                        <p class="text-[14px]">{{ $msg['text'] }}</p>
                        @if (! $mine && ! empty($msg['meeting_link']))
                            <div class="mt-2 flex justify-end">
                                <a href="{{ route('app.video-conference') }}" class="px-2 py-1 rounded-[5px] bg-primaryColor flex items-center gap-1 text-white text-[14px] font-medium">{!! $videoSolid !!} Join</a>
                            </div>
                        @endif
                    </div>
                    <p class="mt-[2px] text-[12px] text-[#A4A4A4]">{{ $msg['time'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- _buildInputArea(): TextFieldSimpleChat + the round send button. --}}
    <div class="shrink-0 px-[10px] pt-[10px]">
        @if ($chat['enabled'])
            <form class="flex items-center" data-chat-form>
                <input type="text" maxlength="140" placeholder="Type your message here..." class="flex-1 min-w-0 h-[49px] px-5 bg-white border border-primaryColor rounded-[30px] text-[14px] text-[#393837] placeholder:text-[#6A6868] outline-none">
                <button type="submit" class="ml-[10px] p-[10px] rounded-[30px] bg-primaryColor text-white shrink-0" aria-label="Send">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </form>
        @else
            <p class="py-3 text-[14px] text-center">You can respond only once.</p>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.querySelector('[data-chat-list]');
    list.scrollTop = list.scrollHeight;
    // validateAndSendMessage(): the message joins the list as a sent bubble.
    document.querySelector('[data-chat-form]')?.addEventListener('submit', (event) => {
        event.preventDefault();
        const input = event.target.querySelector('input');
        const text = input.value.trim();
        if (!text) { return; }
        const row = document.createElement('div');
        row.className = 'py-[5px] flex justify-end';
        row.innerHTML = '<div class="min-w-0 flex flex-col items-end"><div class="px-5 py-[10px] rounded-[20px] rounded-br-none bg-primaryColor text-white"><p class="text-[14px]"></p></div><p class="mt-[2px] text-[12px] text-[#A4A4A4]"></p></div>';
        row.querySelector('.text-\\[14px\\]').textContent = text;
        row.querySelector('.text-\\[12px\\]').textContent = new Date().toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: true }).toUpperCase();
        list.appendChild(row);
        input.value = '';
        list.scrollTop = list.scrollHeight;
    });
});
</script>
@endsection
