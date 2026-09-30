@extends('layouts.app')

@section('content')
{{-- NotificationSettings/notification_settings_screen.dart --}}
<div class="px-[30px] py-5 leading-[1.3]">
    <p class="text-[18px] font-medium text-black underline">Set Notification preferences</p>

    <div class="mt-5">
        @foreach ($demo['notification_preferences'] as $pref)
            <div class="{{ $loop->first ? '' : 'mt-[30px]' }}">
                <p class="text-[15px] font-medium text-[#737373]">{{ $pref['category'] }}</p>
                {{-- One 45x40 button per channel, 'assets/images/$type.svg', dimmed to .4 when off. --}}
                <div class="mt-[15px] flex justify-center gap-[30px]">
                    @foreach (['chat', 'email', 'notification'] as $type)
                        <button type="button" data-toggle-channel class="w-[45px] h-10 px-[10px] py-2 rounded-[5px] bg-primaryColor {{ $pref[$type] ? '' : 'opacity-40' }}">
                            <span class="block w-full h-full bg-white" style="mask: url('/assets/app/images/{{ $type }}.svg') center/contain no-repeat; -webkit-mask: url('/assets/app/images/{{ $type }}.svg') center/contain no-repeat;"></span>
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    // NotificationSettingsController.onTapButton(): flips the channel on/off.
    document.querySelectorAll('[data-toggle-channel]').forEach((b) => b.addEventListener('click', () => b.classList.toggle('opacity-40')));
</script>
@endsection
