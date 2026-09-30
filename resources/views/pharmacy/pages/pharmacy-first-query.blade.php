@extends('layouts.pharmacy')

@php
    // admin/pharmacy-first-query/show.blade.php: the patient's questionnaire, one column per section.
    $sections = $query['answers'];
    $sectionCount = count($sections);
    $columnWidth = 'calc(1 / '.$sectionCount.' * (100% - (80px * ('.$sectionCount.' - 1))))';
@endphp

@section('content-class', 'pt-6')

@section('content')
<div class="h-full">
    <div class="grid grid-cols-1 gap-5 rounded-3xl">
        <div class="">
            <div class="bg-white rounded-3xl flex flex-col">
                <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] px-8 py-3 rounded-t-3xl flex items-center">
                    <a class="bg-white text-lightBlue border border-lightBlue font-medium px-4 py-2 rounded" href="{{ route('pharmacy-portal.pharmacy-first-queries') }}"> Back</a>
                    <h2 class="text-white text-xl font-medium text-center grow pr-[67px]">Patient Response</h2>
                </div>
                <div class="px-8 py-4 relative pt-0">
                    <div class="overflow-auto" style="height:calc(100vh - 180px)">
                        <div class="gap-y-6 gap-x-20 flex">
                            @foreach ($sections as $section => $questions)
                                <div class="min-w-[300px]" style="width: {{ $columnWidth }}">
                                    <h3 class="text-lg font-bold py-3 text-center">Section {{ $section }}</h3>
                                </div>
                            @endforeach
                        </div>
                        <div class="gap-y-6 gap-x-20 flex ">
                            @foreach ($sections as $section => $questions)
                                <div class="relative min-w-[300px]" style="width: {{ $columnWidth }}">
                                    @unless ($loop->last)
                                        <div class="bg-[#f1efef] shrink-0 absolute top-0 w-1 rounded-[63px] -right-10 h-full"></div>
                                    @endunless
                                    @foreach ($questions as $qIndex => $question)
                                        <div class="text-gray-700 mb-4">
                                            <label class="block font-medium text-sm">{{ $qIndex + 1 }}. {{ $question['question'] }}</label>
                                            <div class="flex flex-col gap-2 mt-2">
                                                @foreach ($question['options'] as $option)
                                                    <label class="text-xs rounded-md bg-[#0470D533] p-2 pr-5 w-fit">
                                                        <input type="checkbox" disabled @checked($option['checked']) class="mr-1 section1-option">
                                                        {{ $option['name'] }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
