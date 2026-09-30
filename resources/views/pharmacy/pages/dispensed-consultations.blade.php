@extends('layouts.pharmacy')

@php
    // livewire/admin/ip-consultation-request/index.blade.php
    $consultations = $demo['staff_ip_consultations'];
    $statusBadge = fn (string $status) => match (true) {
        $status === 'Passed' => 'bg-green-100 text-green-700',
        in_array($status, ['Failed', 'Declined', 'Dispense Declined'], true) => 'bg-red-100 text-red-700',
        $status === 'Requested' => 'bg-blue-100 text-blue-700',
        in_array($status, ['Under Review', 'Awaiting Dispense'], true) => 'inline-flex items-center bg-orange-100 text-orange-700',
        default => 'bg-green-100 text-green-700',
    };
    $headCell = 'font-semibold px-3 py-3 border-b-0 text-sm';
    $pharmacy = $currentPharmacy;
@endphp

@section('content-class', 'pt-6')

@section('content')
<div>
    <div class="h-full">
        <div class="flex items-center gap-5 mb-4">
            <div class="flex flex-col gap-2">
                <div class="select-type-3">
                    <select class="transition duration-200 ease-in-out w-[200px] text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 box" data-consultation-status-filter>
                        <option value="">Status</option>
                        <option value="Dispensed">Dispensed</option>
                        <option value="Dispense Declined">Dispense Declined</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 w-full sm:ml-auto sm:mt-0 sm:w-auto md:ml-0 flex">
                <div class="relative w-56 text-slate-500">
                    <input type="search" placeholder="Search..." data-list-search="[data-consultation-row]"
                        class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 box w-56 pr-10">
                    <div>
                        <i data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6">
            <!-- BEGIN: Data List -->
            <div class="intro-y col-span-12">
                <div>
                    <div class="flex">
                        <div class="w-[18%] {{ $headCell }}">Patient</div>
                        <div class="w-[21%] {{ $headCell }}">Service</div>
                        <div class="w-[19%] {{ $headCell }}">Clinician</div>
                        <div class="w-[16%] {{ $headCell }}">Status</div>
                        <div class="w-[25%] {{ $headCell }}">Action</div>
                    </div>
                </div>
                <div>
                    @foreach ($consultations as $c)
                        <div class="flex transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded bg-white text-slate-800"
                            data-consultation-row data-status="{{ $c['status'] }}" data-ip-consultation="{{ $c['id'] }}">
                            <div class="w-[18%] px-3 py-3 flex items-center">
                                <div class="font-medium text-slate-700 text-sm">
                                    {{ $c['patient'] }}
                                    <br>
                                    <p>Dob: <span class="text-gray-500 text-xs"> {{ $c['dob'] }}</span></p>
                                </div>
                            </div>
                            <div class="w-[21%] px-3 py-3 flex items-center">
                                <div class="font-medium text-slate-700 text-sm">{{ $c['service'] }}</div>
                            </div>
                            <div class="w-[19%] px-3 py-3 flex items-center">
                                <div class="font-medium text-slate-700 text-sm break-words">{{ $c['clinician'] }} ({{ $c['gphc_number'] }})</div>
                            </div>
                            <div class="w-[16%] px-3 py-3 flex items-center">
                                <span class="px-2 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide {{ $statusBadge($c['status']) }}" data-ip-status>{{ $c['status'] }}</span>
                            </div>
                            <div class="w-[25%] px-3 py-3 flex items-left justify-left gap-1.5 flex-wrap">
                                <a data-popup-open="ip-view-{{ $c['id'] }}"
                                    class="transition duration-200 border shadow-sm inline-flex items-left justify-left py-3 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] whitespace-nowrap text-white text-[13px]">
                                    View
                                </a>
                                @if ($c['status'] === 'Dispensed')
                                    <a data-popup-open="gp-letter-{{ $c['id'] }}"
                                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-1.5 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] whitespace-nowrap text-white text-[13px]">
                                        GP Letter
                                    </a>
                                @endif
                                @if ($c['status'] === 'Dispensed' && ($c['follow_up_available'] ?? false))
                                    <button type="button"
                                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-1.5 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] whitespace-nowrap text-white text-[13px]"
                                        onclick="Swal.fire({ title: 'Follow Up', text: 'A follow-up consultation would open here.', icon: 'info', confirmButtonText: 'Ok' })">
                                        <span class="translate-y-[-1px]">Follow Up</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @include('pharmacy.partials.pagination-footer', ['count' => count($consultations)])
            </div>
        </div>
    </div>

    @foreach ($consultations as $c)
        @include('pharmacy.partials.dashboard.ip-consultation-view', ['c' => $c])

        @if ($c['status'] === 'Dispensed')
            @include('pharmacy.partials.gp-letter-panel', ['c' => $c, 'pharmacy' => $pharmacy])
        @endif
    @endforeach
    @include('pharmacy.partials.dashboard.ip-consultation-modals')
</div>
@endsection

@push('scripts')
<script>
    // statusFilter() upstream re-queries Livewire.
    document.querySelector('[data-consultation-status-filter]').addEventListener('change', (event) => {
        document.querySelectorAll('[data-consultation-row]').forEach((row) => {
            row.classList.toggle('hidden', event.target.value !== '' && row.dataset.status !== event.target.value);
        });
    });

</script>
@endpush
