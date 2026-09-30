@extends('layouts.pharmacy')

@php
    // admin/pharmacy-first-query/index.blade.php -> livewire/admin/pharmacy-first-query/index.blade.php
    $queries = collect($demo['staff_pharmacy_first_queries']);
    if (! $isGroupOwner) {
        $queries = $queries->where('pharmacy_id', $currentPharmacy['id']);
    }
    $statusColor = fn (string $status) => $status === 'Incomplete' ? 'danger' : 'success';
    $head = 'font-semibold px-5 py-3 border-b-0';
@endphp

@section('content-class', 'pt-6')

@section('content')
<div>
    <div class="h-full">
        <div class="flex items-center gap-5 mb-4">
            @if ($isGroupOwner)
                <div class="relative items-center text-slate-500 select-type-3">
                    <select class="transition duration-200 ease-in-out w-[200px] text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 box" data-query-pharmacy>
                        <option value="">All Pharmacies</option>
                        @foreach ($demo['staff_pharmacies'] as $ph)
                            <option value="{{ $ph['id'] }}">{{ $ph['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="preview relative" id="pre-calendar">
                <input type="date" placeholder="Select date" data-query-date
                    class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 block w-56 px-4 py-2">
            </div>
            <div class="relative w-56 items-center text-slate-500">
                <input type="search" placeholder="Search..." data-list-search="[data-query-row]"
                    class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 box w-56 pr-10">
                <div>
                    <i data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                </div>
            </div>
            <button type="button" class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white px-4 py-2 rounded text-center" onclick="resetQueryFilters()"> Reset </button>
        </div>
        <div class="grid grid-cols-12 gap-6">
            <!-- BEGIN: Data List -->
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                @if ($queries->isNotEmpty())
                    <div class="sm:w-full w-max text-left -mt-2 mb-5">
                        <div>
                            <div class="flex">
                                <div class="w-[25%] {{ $head }}">Patient</div>
                                <div class="w-[25%] {{ $head }} ">NHS</div>
                                <div class="w-[25%] {{ $head }} ">DOB</div>
                                <div class="w-[25%] {{ $head }} text-center">Service</div>
                                <div class="w-[15%] {{ $head }} text-center">Date & time</div>
                                <div class="w-[10%] {{ $head }} text-center">Status</div>
                                <div class="w-[15%] {{ $head }} text-center">View Form</div>
                            </div>
                        </div>
                        <div>
                            @foreach ($queries as $q)
                                <div class="flex transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded bg-white text-slate-800"
                                    data-query-row data-pharmacy="{{ $q['pharmacy_id'] }}" data-date="{{ \Illuminate\Support\Carbon::createFromFormat('d/m/Y h:i A', $q['datetime'])->format('Y-m-d') }}">
                                    <div class="w-[25%] px-5 py-3 flex flex-col items-start justify-center break-all">
                                        <span class="font-bold block">{{ $q['patient'] }}</span>
                                        <div class="flex items-center gap-1 ml-1">
                                            <span class="shrink-0">@include('pharmacy.partials.icon', ['name' => 'phone'])</span>
                                            {{ $q['contact'] }}
                                        </div>
                                    </div>
                                    <div class="w-[25%] px-5 py-3 flex items-center break-all">{{ $q['nhs'] ?: '-' }}</div>
                                    <div class="w-[25%] px-5 py-3 flex items-center break-all">{{ $q['dob'] }}</div>
                                    <div class="w-[25%] px-5 py-3 flex items-center">
                                        <div class="flex gap-2">{{ $q['service'] }}<p class="text-redclr text-sm font-bold">{{ $q['amount'] > 0 ? '£'.$q['amount'] : '' }}</p></div>
                                    </div>
                                    <div class="w-[15%] px-5 py-3 flex items-center justify-center">
                                        <div><span class="block text-left mt-1">{{ $q['datetime'] }}</span></div>
                                    </div>
                                    <div class="w-[10%] px-5 py-3 flex items-center justify-center">
                                        <div class="text-{{ $statusColor($q['status']) }}">{{ $q['status'] }}</div>
                                    </div>
                                    <div class="w-[15%] px-5 py-3 flex items-center justify-center">
                                        @if ($q['answers'])
                                            <div>
                                                <div class="w-6 h-6 rounded-md">
                                                    <a href="{{ route('pharmacy-portal.pharmacy-first-query', $q['id']) }}"><img src="{{ asset('admin/images/eye_second.svg') }}" alt=""></a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div>
                        <div class="p-6 transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded bg-white text-slate-800">
                            <img class="mx-auto" src="{{ asset('admin/images/no-record-found-new.png') }}" alt="">
                        </div>
                    </div>
                @endif
                <div class="intro-y col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
                    @include('pharmacy.partials.pagination-footer', ['count' => $queries->count()])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const pharmacy = document.querySelector('[data-query-pharmacy]');
        const date = document.querySelector('[data-query-date]');
        const apply = () => document.querySelectorAll('[data-query-row]').forEach((row) => {
            const pharmacyOk = !pharmacy || pharmacy.value === '' || row.dataset.pharmacy === pharmacy.value;
            const dateOk = date.value === '' || row.dataset.date === date.value;
            row.classList.toggle('hidden', !(pharmacyOk && dateOk));
        });
        pharmacy?.addEventListener('change', apply);
        date.addEventListener('change', apply);

        window.resetQueryFilters = function () {
            if (pharmacy) { pharmacy.value = ''; }
            date.value = '';
            document.querySelector('[data-list-search]').value = '';
            document.querySelectorAll('[data-query-row]').forEach((row) => row.classList.remove('hidden'));
        };
    })();
</script>
@endpush
