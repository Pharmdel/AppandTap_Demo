@extends('layouts.web')

@php $chat = $demo['chat']; @endphp

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow flex flex-col h-[70vh]">
    <div class="flex-1 overflow-y-auto p-5 flex flex-col gap-3">
        @php $lastDate = null; @endphp
        @foreach ($chat['messages'] as $msg)
            @if ($msg['date'] !== $lastDate)
                <div class="flex items-center gap-3 my-2">
                    <div class="flex-1 h-px bg-slate-100"></div>
                    <span class="text-xs text-slate-400">{{ $msg['date'] }}</span>
                    <div class="flex-1 h-px bg-slate-100"></div>
                </div>
                @php $lastDate = $msg['date']; @endphp
            @endif

            @if ($msg['from'] === 'patient')
                <div class="self-end max-w-[70%]">
                    <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white rounded-3xl rounded-br-none px-4 py-2 text-sm">
                        {{ $msg['text'] }}
                    </div>
                    <p class="text-[11px] text-slate-400 text-right mt-1">{{ $msg['time'] }}</p>
                </div>
            @else
                <div class="self-start max-w-[70%]">
                    <div class="bg-[#DBE5F3] text-lightBlue rounded-3xl rounded-bl-none px-4 py-2 text-sm">
                        {{ $msg['text'] }}
                        @if (!empty($msg['meeting_link']))
                            <a href="{{ $msg['meeting_link'] }}" class="mt-2 inline-flex items-center gap-1 bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white text-xs px-3 py-1 rounded-full">
                                <i data-lucide="video" class="w-3 h-3"></i> Join
                            </a>
                        @endif
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">{{ $msg['time'] }}</p>
                </div>
            @endif
        @endforeach
    </div>

    <div class="border-t border-slate-100 p-4 flex items-center gap-3">
        @if ($chat['enabled'])
            <input type="text" maxlength="140" placeholder="Type a message..." class="flex-1 border border-slate-200 rounded-full px-4 py-2.5 text-sm">
            <button class="w-10 h-10 rounded-full bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white flex items-center justify-center">
                <i data-lucide="send" class="w-4 h-4"></i>
            </button>
        @else
            <p class="text-sm text-slate-400 text-center w-full">You can respond only once.</p>
        @endif
    </div>
</div>
@endsection
