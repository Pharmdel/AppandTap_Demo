@extends('layouts.pharmacy')

@php
    // Scoped to whichever pharmacy is open, via each order's patient.
    $patientIds = collect($demo['staff_patients'])->where('pharmacy_id', $pharmacyId)->pluck('id');
    $orders = collect($demo['staff_orders'])->whereIn('patient_id', $patientIds);
@endphp

@unless ($showFrame)
    @section('content-class', 'orders-page-offset')
@endunless

@section('content')
@if ($showFrame)
    {{-- admin/pharmacy/index.blade.php, parentType=orders: Pharmacy::repeatMedicineOrders,
         i.e. everything but the still-Requested ones (those are New Orders). --}}
    @include('pharmacy.partials.repeat-medicine-orders', ['data' => $orders->where('status', '!=', 'Requested')->values()->all()])
@else
    {{-- admin/prescription/patient-prescription-order.blade.php (patient.orders):
         Pharmacy::repeatMedicineOrdersAll. --}}
    <div class="mt-12">
        <div class="grid grid-cols-4 gap-6">
            <div class="col-span-4 order_patient">
                @include('pharmacy.partials.repeat-medicine-orders', ['data' => $orders->values()->all()])
            </div>
        </div>
    </div>
@endif
@endsection
