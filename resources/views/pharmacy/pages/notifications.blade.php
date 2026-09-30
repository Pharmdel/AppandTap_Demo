@extends('layouts.pharmacy')

@php
    // A row opens the patient's Messages tab: patient.show for a Pharmacy,
    // pharmacy.show > patientDetail for a Group Owner.
    $messageUrl = fn (array $n) => route('pharmacy-portal.patient-detail', array_filter([
        'id' => $n['patient_id'],
        'pharmacy' => $isGroupOwner ? $n['pharmacy_id'] : null,
        'type' => 'message',
    ]));
    $select = 'transition duration-200 ease-in-out w-[200px] text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 box notificationFilterSelect';
@endphp

@section('content-class', 'pt-6')

@section('content')
{{-- livewire/admin/notification/index.blade.php --}}
<div>
    <div class="grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap gap-4">
            <div class="flex space-x-4">
                <div class="flex flex-col gap-2">
                    <div class="select-type-3">
                        <select class="{{ $select }}" data-notification-type-filter>
                            <option value="all">All</option>
                            <option value="sent">Send</option>
                            <option value="received">Received</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    @if ($isGroupOwner)
                        <div class="select-type-3">
                            <select class="{{ $select }}" data-notification-pharmacy-filter>
                                <option value="">All</option>
                                @foreach ($demo['staff_pharmacies'] as $ph)
                                    <option value="{{ $ph['id'] }}">{{ $ph['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>
            <div class="mt-3 w-full sm:ml-auto sm:mt-0 sm:w-auto md:ml-0 flex">
                <div class="relative w-56 text-slate-500">
                    <input type="search" placeholder="Search..." data-list-search="[data-notification-row]"
                        class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 box w-56 pr-10">
                    <div>
                        <i data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <div class="lg:w-full w-max text-left -mt-2">
                <div>
                    <div class="flex">
                        <div class="w-3/12 font-semibold px-5 py-3 whitespace-nowrap border-b-0 ">Patient Name</div>
                        <div class="w-3/12 font-semibold px-5 py-3 whitespace-nowrap border-b-0 ">Message</div>
                        <div class="w-3/12 font-semibold px-5 py-3 whitespace-nowrap border-b-0 ">Type</div>
                        <div class="w-3/12 font-semibold px-5 py-3 whitespace-nowrap border-b-0 ">Date & Time</div>
                        <div class="w-3/12 font-semibold px-5 py-3 whitespace-nowrap border-b-0 ">Status</div>
                    </div>
                </div>
                <div>
                    @foreach ($demo['staff_notifications'] as $n)
                        <a href="{{ $messageUrl($n) }}" data-notification-row data-notification-type="{{ $n['type'] }}" data-pharmacy="{{ $n['pharmacy_id'] }}">
                            <div class="flex transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded bg-white text-slate-800">
                                <div class="w-3/12 px-5 py-3"><u>{{ $n['patient'] }}</u></div>
                                <div class="w-3/12 px-5 py-3 text-xs">{{ $n['message'] }}</div>
                                <div class="w-3/12 px-5 py-3"><span style="color: {{ $n['type'] === 'received' ? '#ee5d50' : '#04cd99' }}">{{ $n['type'] === 'received' ? 'Received' : 'Sent' }}</span></div>
                                <div class="w-3/12 px-5 py-3">{{ $n['datetime'] }}</div>
                                <div class="w-3/12 px-5 py-3">{{ $n['is_read'] ? 'Read' : 'Unread' }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- END: Data List -->
        <div class="intro-y col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
            @include('pharmacy.partials.pagination-footer', ['count' => count($demo['staff_notifications'])])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // notificationTypeFilter() / applyPharmacyfilter() upstream re-query Livewire.
    (function () {
        const type = document.querySelector('[data-notification-type-filter]');
        const pharmacy = document.querySelector('[data-notification-pharmacy-filter]');
        const apply = () => document.querySelectorAll('[data-notification-row]').forEach((row) => {
            const typeOk = type.value === 'all' || row.dataset.notificationType === type.value;
            const pharmacyOk = !pharmacy || pharmacy.value === '' || row.dataset.pharmacy === pharmacy.value;
            row.classList.toggle('hidden', !(typeOk && pharmacyOk));
        });
        type.addEventListener('change', apply);
        pharmacy?.addEventListener('change', apply);
    })();
</script>
@endpush
