@extends('layouts.app')

@section('content')
{{-- account_screen/nhs_web_view.dart: a plain Material 3 AppBar (surface colour,
     adaptive back arrow, centred title on iOS) over a WebViewWidget. --}}
<div class="h-full flex flex-col bg-white">
    <div class="h-14 shrink-0 bg-[#FEF7FF] flex items-center px-1">
        <button type="button" onclick="history.back()" class="w-12 h-12 shrink-0 flex items-center justify-center" aria-label="Back">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#1D1B20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 4 7 12l8 8"/></svg>
        </button>
        <p class="flex-1 min-w-0 truncate text-center text-[22px] text-[#1D1B20] pr-12">Back to {{ $demo['pharmacy']['name'] }}</p>
    </div>
    <div class="flex-1 flex items-center justify-center">
        <p class="text-xs text-greyColor">External website content would load here.</p>
    </div>
</div>
@endsection
