@extends('layouts.pharmacy')

@php
    $patientIds = collect($demo['staff_patients'])->where('pharmacy_id', $pharmacyId)->pluck('id');
    $orders = collect($demo['staff_orders'])->whereIn('patient_id', $patientIds)->where('status', 'Requested')->values()->all();
@endphp

@section('content')
@unless ($showFrame)
    <a href="{{ route('pharmacy-portal.orders') }}" class="flex items-center gap-1 text-slate-500 hover:text-lightBlue text-sm mb-3">
        <i data-lucide="chevron-left" class="w-4 h-4"></i> Back to Orders
    </a>
@endunless
{{-- admin/pharmacy/index.blade.php: parentType=neworders --}}
@include('pharmacy.partials.repeat-medicine-orders', ['data' => $orders])
@endsection
