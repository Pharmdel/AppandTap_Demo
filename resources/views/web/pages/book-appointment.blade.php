@extends('layouts.web')

@php
    $services = collect($demo['services']);
    $selectedId = (int) request('service', $services->first()['id']);
    $selected = $services->firstWhere('id', $selectedId) ?? $services->first();
    $slots = ['09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM', '11:00 AM', '02:00 PM', '02:30 PM', '03:00 PM'];
@endphp

@section('content')
<div class="max-w-2xl mx-auto bg-gradient-to-t from-[#002B56] to-[#0070D5] rounded-2xl p-8 text-white">
    <h2 class="text-lg font-semibold mb-6">Book Appointment</h2>

    <label class="text-xs text-white/70">Choose service</label>
    <select onchange="window.location = '{{ route('web.book-appointment') }}?service=' + this.value" class="w-full rounded-lg px-3 py-2 text-sm mb-4 text-lightBlue">
        @foreach ($services as $service)
            <option value="{{ $service['id'] }}" {{ $service['id'] === $selected['id'] ? 'selected' : '' }}>
                {{ $service['title'] }} &mdash; {{ $service['price'] > 0 ? '£'.$service['price'] : 'Free' }}
            </option>
        @endforeach
    </select>

    <div class="bg-white/10 rounded-lg p-4 mb-4">
        <p class="text-sm">{{ $selected['description'] }}</p>
        @if ($selected['is_pharmacy_first'])
            <button onclick="document.getElementById('pgdModal').showModal()" class="mt-3 text-xs underline">Fill Eligibility Form</button>
        @endif
    </div>

    <label class="text-xs text-white/70">Appointment Type</label>
    <select class="w-full rounded-lg px-3 py-2 text-sm mb-4 text-lightBlue">
        @foreach ($selected['appointment_types'] as $type)
            <option>{{ $type }}</option>
        @endforeach
    </select>

    @if (count($selected['delivery_types']))
        <label class="text-xs text-white/70">Delivery Type</label>
        <select class="w-full rounded-lg px-3 py-2 text-sm mb-4 text-lightBlue">
            @foreach ($selected['delivery_types'] as $type)
                <option>{{ $type }}</option>
            @endforeach
        </select>
    @endif

    <label class="text-xs text-white/70">Choose date</label>
    <input type="date" class="w-full rounded-lg px-3 py-2 text-sm mb-4 text-lightBlue">

    <label class="text-xs text-white/70">Available Slots</label>
    <div class="grid grid-cols-4 gap-2 mb-4 mt-1">
        @foreach ($slots as $i => $slot)
            <label class="text-xs text-center rounded-lg py-2 cursor-pointer {{ $i === 0 ? 'bg-white text-lightBlue font-semibold' : 'bg-white/10' }}">
                <input type="radio" name="slot" class="hidden" {{ $i === 0 ? 'checked' : '' }}> {{ $slot }}
            </label>
        @endforeach
    </div>

    <label class="text-xs text-white/70">Add note (optional)</label>
    <textarea rows="3" class="w-full rounded-lg px-3 py-2 text-sm mb-6 text-lightBlue"></textarea>

    <button class="w-full border-2 border-white rounded-full py-3 font-semibold">BOOK AN APPOINTMENT</button>
</div>

<dialog id="pgdModal" class="rounded-xl p-0 w-[90%] max-w-lg backdrop:bg-black/40">
    @include('web.partials.pharmacy-first-questionnaire')
</dialog>
@endsection
