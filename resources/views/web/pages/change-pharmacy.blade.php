@extends('layouts.web')

@php
    $pharmacy = $demo['pharmacy'];
    $services = collect($demo['services']);
@endphp

@section('content')
<div class="bg-white rounded-xl shadow p-5 mb-6 flex items-center justify-between">
    <h2 class="text-lg font-semibold text-lightBlue">Pharmacy Detail</h2>
    <button onclick="document.getElementById('changePharmacyModal').showModal()"
            class="flex items-center gap-2 bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white text-sm px-4 py-2 rounded-full font-semibold">
        <i data-lucide="repeat" class="w-4 h-4"></i> Change Pharmacy
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="border border-slate-100 rounded-xl p-5 bg-white">
        <h3 class="font-semibold text-lightBlue text-sm mb-3">Pharmacy Info</h3>
        <img src="/assets/web/images/appntap-logo.svg" class="w-10 h-10 mb-3" alt="">
        <p class="font-medium text-lightBlue">{{ $pharmacy['name'] }}</p>
        <div class="flex items-center gap-2 text-sm text-slate-500 mt-2">
            <i data-lucide="phone-call" class="w-4 h-4"></i> {{ $pharmacy['phone'] }}
        </div>
        <div class="flex items-center gap-2 text-sm text-slate-500 mt-2">
            <i data-lucide="map-pin" class="w-4 h-4"></i> {{ $pharmacy['address'] }}, {{ $pharmacy['postcode'] }}
        </div>
    </div>

    <div class="border border-slate-100 rounded-xl p-5 bg-white">
        <h3 class="font-semibold text-lightBlue text-sm mb-3">Services</h3>
        <div class="flex flex-col gap-2 max-h-60 overflow-y-auto">
            @foreach ($services as $service)
                <button onclick="document.getElementById('service-{{ $service['id'] }}').showModal()"
                        class="text-left text-sm text-lightBlue border border-slate-100 rounded-lg px-3 py-2 hover:bg-slate-50">
                    {{ $service['title'] }}
                </button>
                <dialog id="service-{{ $service['id'] }}" class="rounded-xl p-0 w-[380px] backdrop:bg-black/40">
                    <img src="{{ $service['image'] }}" class="w-full h-32 object-contain bg-slate-50" alt="">
                    <div class="p-4">
                        <h4 class="font-semibold text-lightBlue">{{ $service['title'] }}</h4>
                        <p class="text-xs text-slate-500 mt-2">{{ $service['description'] }}</p>
                        <div class="flex gap-2 mt-4">
                            <button onclick="document.getElementById('service-{{ $service['id'] }}').close()" class="flex-1 border border-slate-200 rounded-full py-2 text-sm">Close</button>
                            <a href="{{ route('web.book-appointment') }}?service={{ $service['id'] }}" class="flex-1 text-center bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white rounded-full py-2 text-sm font-semibold">Book Now</a>
                        </div>
                    </div>
                </dialog>
            @endforeach
        </div>
    </div>

    <div class="border border-slate-100 rounded-xl p-5 bg-white">
        <h3 class="font-semibold text-lightBlue text-sm mb-3">Working Hours</h3>
        <table class="w-full text-sm">
            @foreach ($pharmacy['hours'] as $h)
                <tr class="border-b border-slate-50">
                    <td class="py-1.5 text-slate-500">{{ $h['day'] }}</td>
                    <td class="py-1.5 text-right">
                        @if ($h['open'])
                            {{ $h['open'] }} - {{ $h['close'] }}
                        @else
                            <span class="text-redclr">Closed</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>

<dialog id="changePharmacyModal" class="rounded-xl p-0 w-[420px] backdrop:bg-black/40">
    <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white px-5 py-4">
        <h3 class="font-semibold">Change Pharmacy</h3>
    </div>
    <div class="p-5">
        <label class="text-xs text-slate-400">Current Pharmacy</label>
        <select disabled class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mb-3 bg-slate-50">
            <option>{{ $pharmacy['name'] }}</option>
        </select>
        <label class="text-xs text-slate-400">Search for a new pharmacy</label>
        <input type="text" placeholder="Search by name or postcode" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm mb-4">
        <div class="flex gap-2">
            <button onclick="document.getElementById('changePharmacyModal').close()" class="flex-1 border border-slate-200 rounded-full py-2 text-sm">Cancel</button>
            <button onclick="document.getElementById('changePharmacyModal').close()" class="flex-1 bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white rounded-full py-2 text-sm font-semibold">Submit</button>
        </div>
    </div>
</dialog>
@endsection
