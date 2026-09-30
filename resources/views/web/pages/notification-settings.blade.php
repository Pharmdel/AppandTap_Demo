@extends('layouts.web')

@section('content')
<div class="max-w-xl bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold text-lightBlue mb-1">Set Notification Preferences</h2>
    <p class="text-sm text-slate-400 underline mb-5">Choose how you'd like to be notified for each category</p>

    <div class="flex flex-col divide-y divide-slate-50">
        @foreach ($demo['notification_preferences'] as $pref)
            <div class="flex items-center justify-between py-4">
                <div class="flex items-center gap-3">
                    <img src="{{ $pref['icon'] }}" class="w-6 h-6" alt="">
                    <span class="text-sm text-lightBlue">{{ $pref['category'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    @foreach (['chat' => 'message-circle', 'email' => 'mail', 'notification' => 'bell'] as $key => $icon)
                        <button class="w-9 h-9 rounded-lg flex items-center justify-center {{ $pref[$key] ? 'bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white' : 'border border-slate-200 text-slate-300' }}">
                            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
