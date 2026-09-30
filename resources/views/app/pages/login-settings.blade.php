@extends('layouts.app')

@section('content')
{{-- setting_screen/setting_screen.dart ("Login Settings") on white. --}}
<div class="min-h-full bg-white px-10 py-5 leading-[1.3]">
    <div class="flex items-center justify-between">
        <p class="text-[15px] text-[#737373]">Biometric Login</p>
        {{-- CupertinoSwitch at 0.8 scale. --}}
        <label class="relative inline-block w-[41px] h-[25px] cursor-pointer">
            <input type="checkbox" checked class="peer sr-only">
            <span class="absolute inset-0 rounded-full bg-[#E9E9EA] peer-checked:bg-[#34C759] transition-colors"></span>
            <span class="absolute top-[2px] left-[2px] w-[21px] h-[21px] rounded-full bg-white shadow-[0_2px_4px_rgba(0,0,0,.25)] transition-transform peer-checked:translate-x-4"></span>
        </label>
    </div>
    <div class="h-5"></div>
    <a href="{{ route('app.home') }}" class="block py-5 text-[15px] text-[#737373]">Change Pin</a>
    <div class="h-10"></div>
    <button type="button" class="w-full h-[45px] rounded-[45px] bg-primaryColor text-white text-[15px] font-medium tracking-[.4px]">Update</button>
</div>
@endsection
