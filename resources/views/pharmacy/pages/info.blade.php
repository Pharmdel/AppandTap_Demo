@extends('layouts.pharmacy')

@php $pharmacy = $demo['pharmacy']; @endphp

@section('content')
@if ($showFrame)
    <x-pharmacy.info-subtabs active="info" />
@endif

<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="grid gap-5 xl:grid-cols-3 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">Pharmacy name</label>
            <input type="text" value="{{ $pharmacy['name'] }}" class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">ODS code</label>
            <input type="text" value="{{ $pharmacy['ods_code'] }}" readonly class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm bg-slate-50">
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">Manager name</label>
            <input type="text" value="{{ $pharmacy['manager_name'] }}" class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">Contact number</label>
            <input type="text" value="{{ $pharmacy['phone'] }}" class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">PMR type</label>
            <select class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm">
                <option selected>{{ $pharmacy['pmr_type'] }}</option>
            </select>
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">Address</label>
            <input type="text" value="{{ $pharmacy['address'] }}" class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">City</label>
            <input type="text" value="{{ $pharmacy['city'] }}" class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">Postcode</label>
            <input type="text" value="{{ $pharmacy['postcode'] }}" class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">Country</label>
            <select class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm">
                <option selected>{{ $pharmacy['country'] }}</option>
            </select>
        </div>
        <div class="xl:col-span-2 sm:col-span-2">
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">Review Link</label>
            <input type="text" value="{{ $pharmacy['review_link'] }}" class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">Allow video call appointment</label>
            <select class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm">
                <option value="1" {{ $pharmacy['allow_video_appointment'] ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ ! $pharmacy['allow_video_appointment'] ? 'selected' : '' }}>No</option>
            </select>
        </div>
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">Show On Website</label>
            <select class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm">
                <option value="1" {{ $pharmacy['show_on_website'] ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ ! $pharmacy['show_on_website'] ? 'selected' : '' }}>No</option>
            </select>
        </div>
    </div>

    <h3 class="font-semibold text-slate-700 mt-6 mb-3">Login Details</h3>
    <div class="grid gap-5 xl:grid-cols-3 sm:grid-cols-2">
        <div>
            <label class="text-sm font-semibold text-slate-600 block mb-1.5">Email address</label>
            <input type="email" value="pharmacy@wellcare.example" disabled class="w-full border border-slate-200 rounded-md px-3 py-2.5 text-sm bg-slate-50">
        </div>
    </div>
    <label class="flex items-center gap-2 mt-4 text-sm text-slate-600">
        <input type="checkbox"> Send reset password link
    </label>

    <h3 class="font-semibold text-slate-700 mt-6 mb-3">Working Hours</h3>
    <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-slate-500 mb-2">
        <div class="col-span-4">Week day</div>
        <div class="col-span-4">Open time</div>
        <div class="col-span-4">Close time</div>
    </div>
    @foreach ($pharmacy['hours'] as $h)
        <div class="grid grid-cols-12 gap-4 items-center py-1.5 border-t border-slate-50 text-sm">
            <div class="col-span-4 text-slate-600">{{ $h['day'] }}</div>
            <div class="col-span-4">
                <input type="time" value="{{ $h['open'] }}" class="border border-slate-200 rounded-md px-2 py-1.5 text-sm w-full" {{ $h['open'] ? '' : 'disabled' }}>
            </div>
            <div class="col-span-4">
                <input type="time" value="{{ $h['close'] }}" class="border border-slate-200 rounded-md px-2 py-1.5 text-sm w-full" {{ $h['close'] ? '' : 'disabled' }}>
            </div>
        </div>
    @endforeach

    <button class="mt-6 bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white rounded-md px-5 py-2.5 text-sm font-medium">Save</button>
</div>
@endsection
