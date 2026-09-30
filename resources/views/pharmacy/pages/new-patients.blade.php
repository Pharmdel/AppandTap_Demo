@extends('layouts.pharmacy')

@php $patients = collect($demo['staff_patients'])->where('status', 'pending')->where('pharmacy_id', $pharmacyId); @endphp

@section('content')
@unless ($showFrame)
    <a href="{{ route('pharmacy-portal.patients') }}" class="flex items-center gap-1 text-slate-500 hover:text-lightBlue text-sm mb-3">
        <i data-lucide="chevron-left" class="w-4 h-4"></i> Back to Patients
    </a>
@endunless
<div class="flex flex-col gap-2.5">
    @forelse ($patients as $patient)
        <div class="bg-white rounded-lg shadow-sm px-5 py-3 flex items-center gap-4">
            <div class="flex-1 min-w-0">
                <p class="font-medium text-lightBlue">{{ $patient['first_name'] }} {{ $patient['last_name'] }}</p>
                <div class="text-xs text-slate-400 mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                    <span class="flex items-center gap-1"><i data-lucide="phone" class="w-3 h-3"></i>{{ $patient['contact_number'] }}</span>
                    <span class="flex items-center gap-1"><i data-lucide="mail" class="w-3 h-3"></i>{{ $patient['email'] }}</span>
                </div>
            </div>
            <div class="w-48 text-sm text-slate-500 shrink-0">
                {{ $patient['address_line_1'] }}<br>{{ $patient['postcode'] }}
            </div>
            <div class="flex flex-col shrink-0 w-40">
                <button class="approve-btn max-w-52 w-full mx-auto border border-green bg-green hover:opacity-80 text-redclr rounded-full px-5 py-2 text-center text-white">Approve</button>
                <button class="reject-btn max-w-52 w-full mx-auto border mt-2 border-redclr bg-redclr hover:opacity-80 text-redclr rounded-full px-5 py-2 text-center text-white">Reject</button>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-sm p-10 text-center text-slate-400 text-sm">No new patient requests.</div>
    @endforelse
</div>
@endsection
