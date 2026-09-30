@extends('layouts.pharmacy')

@php $pharmacy = $demo['pharmacy']; @endphp

@section('content')
@if ($showFrame)
    <x-pharmacy.info-subtabs active="settings" />
@endif

<div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl">
    <label class="text-sm font-semibold text-slate-600 block mb-1.5">Welcome Message</label>
    <textarea rows="3" maxlength="500" class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm mb-5">{{ $pharmacy['welcome_message'] }}</textarea>

    <div class="grid grid-cols-2 gap-6">
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">Chat text limit</label>
            <input type="text" value="{{ $pharmacy['chat_text_limit'] }}" class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">Chat status</label>
            <div class="flex flex-col gap-2 text-sm">
                @foreach (['no_response' => 'No response', 'one_response' => 'One Response', 'real_time_response' => 'Real time response'] as $value => $label)
                    <label class="flex items-center gap-2">
                        <input type="radio" name="chat_status" {{ $pharmacy['chat_status'] === $value ? 'checked' : '' }}>
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    <button class="mt-6 bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white rounded-md px-5 py-2.5 text-sm font-medium">Save</button>
</div>
@endsection
