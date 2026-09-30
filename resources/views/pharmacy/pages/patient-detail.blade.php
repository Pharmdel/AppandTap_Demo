@extends('layouts.pharmacy')

@unless ($showFrame)
    @section('content-class', 'patient-page-offset')
@endunless

@section('content')
@if ($showFrame)
    {{-- admin/pharmacy/index.blade.php: parentType=patients, childType=patientDetail --}}
    <div class="back_btn">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('pharmacy-portal.patients', ['pharmacy' => $framePharmacy['id']]) }}" class="flex items-center space-x-1">
            <i data-lucide="chevron-left" class="stroke-1.5 h-6 w-6 -ml-2"></i>
            <span class="font-bold">Go Back</span>
        </a>
    </div>
    <div class="bg-white box flex items-center mt-3">
        <div class="flex items-center space-x-2 p-3 border-r border-slate-200">
            <span class="w-10 h-10 overflow-hidden rounded-full flex justify-center items-center"><img class="w-full h-full object-cover" src="{{ asset('admin/images/no-record-found-new.png') }}" alt=""></span>
            <p>{{ $patient['first_name'] }} {{ $patient['last_name'] }}</p>
        </div>
        <div class="h-full">
            <a href="mailto:{{ $patient['email'] }}" class="flex items-center space-x-2 px-3 py-5 h-full">
                <i data-lucide="mail" class="storke-1.5 w-6 h-6"></i>
                <span>{{ $patient['email'] }}</span>
            </a>
        </div>
    </div>
    @include('pharmacy.partials.patient-info')
@else
    {{-- admin/patient/index.blade.php with a patient chosen (patient.show) --}}
    <div class="h-full">
        <div class="grid grid-cols-12 gap-6 h-full">
            <div class="col-span-12 lg:col-span-3 2xl:col-span-3 border-r border-slate-300 sticky top-[100px] max-h-[calc(100vh-100px)] z-[1]">
                @include('pharmacy.partials.patient-list', ['selectedId' => $patient['id']])
            </div>
            <div class="col-span-12 lg:col-span-9 2xl:col-span-9">
                <div class="flex space-x-3">
                    <div class="flex items-center space-x-5">
                        <h2 class="">{{ $patient['first_name'] }} {{ $patient['last_name'] }}</h2>
                        @if ($patient['status'] !== 'active')
                            <div class="flex space-x-3 items-center" data-patient-status-actions>
                                <button type="button" onclick="updatePatientStatus(this, true)"
                                    class="max-w-52 w-full mx-auto border border-green bg-green hover:opacity-80 text-redclr rounded-full px-5 py-2 text-center text-white">Approve</button>
                                <button type="button" onclick="updatePatientStatus(this, false)"
                                    class="max-w-52 w-full mx-auto border border-redclr bg-redclr hover:opacity-80 text-redclr rounded-full px-5 py-2 text-center text-white">Reject</button>
                            </div>
                        @endif
                    </div>
                </div>
                @include('pharmacy.partials.patient-info')
            </div>
        </div>
    </div>
    @include('pharmacy.partials.patient-export-modal')
@endif
@endsection
