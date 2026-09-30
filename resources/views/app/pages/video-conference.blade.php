@extends('layouts.app')

@section('content')
{{-- video_conference/video_conference_screen.dart: the meeting room in a full-bleed
     InAppWebView on #006654, with a round back button over its top-left. --}}
<div class="h-full w-full bg-[#006654] relative flex items-center justify-center">
    <p class="text-white/60 text-sm">Video call in progress&hellip;</p>

    <button type="button" onclick="history.back()" class="absolute top-[3px] left-[7px] p-[15px] rounded-full bg-[#006654]" aria-label="Back">
        <img src="/assets/app/images/back-arrow.svg" class="w-3 h-5 brightness-0 invert" alt="">
    </button>
</div>
@endsection
