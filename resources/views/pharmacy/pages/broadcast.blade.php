@extends('layouts.pharmacy')

@php
    // livewire/admin/broadcast/index.blade.php (Pharmacy, and pharmacy.show's
    // Broadcast tab); a Group Owner's own sidebar page is
    // group-owner-broadcast-index.blade.php with a pharmacy filter and column.
    $groupList = $isGroupOwner && ! $showFrame;
    $broadcasts = collect($demo['staff_broadcasts']);
    if (! $isGroupOwner || $showFrame) {
        $pharmacyId = $showFrame ? $framePharmacy['id'] : $currentPharmacy['id'];
        $broadcasts = $broadcasts->where('pharmacy_id', $pharmacyId);
    }
    $createUrl = route('pharmacy-portal.broadcast-create', array_filter(['tab' => $showFrame ? 1 : null, 'pharmacy' => $showFrame ? $framePharmacy['id'] : null]));
    $head = 'font-semibold px-5 py-3 border-b-0';
@endphp

@unless ($showFrame)
    @section('content-class', 'pt-6')
@endunless

@section('content')
<div>
    <div class="block">
        <div class="lg:col-span-3">
            <div class="lg:col-span-3 ">
                <div class="bg-white rounded-lg overflow-hidden">
                    <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] px-5 py-2">
                        <div class="flex items-center {{ $groupList ? 'justify-between' : '' }}">
                            @if ($groupList)
                                <div class="select-type-3">
                                    <select class="transition duration-200 ease-in-out w-[200px] text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 box" data-broadcast-pharmacy>
                                        <option value="">All</option>
                                        @foreach ($demo['staff_pharmacies'] as $ph)
                                            <option value="{{ $ph['id'] }}">{{ $ph['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <a href="{{ $createUrl }}" class="bg-white text-lightBlue border-lightBlue transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer">
                                <i data-lucide="plus" class="stroke-1.5 mr-2 h-4 w-4"></i>
                                Add broadcast
                            </a>
                        </div>
                    </div>
                    <div class="">
                        <div class="tab-pane w-full leading-relaxed active visible opacity-100">
                            @if ($broadcasts->isNotEmpty())
                                <div>
                                    <div class="">
                                        <div class="flex bg-white border-b border-slate-200">
                                            @if ($groupList)
                                                <div class="w-[35%] {{ $head }}">Pharmacy</div>
                                            @endif
                                            <div class="w-[35%] {{ $head }}">Message</div>
                                            <div class="w-[15%] {{ $head }} text-center">Received By</div>
                                            <div class="w-[15%] {{ $head }} text-center">Read By</div>
                                            <div class="w-[25%] {{ $head }}  text-center">Date Sent</div>
                                            <div class="w-[23%] {{ $head }} text-center">Filters</div>
                                        </div>
                                    </div>
                                    <div>
                                        @foreach ($broadcasts as $key => $b)
                                            <div class="flex transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:rounded bg-white text-slate-800" data-broadcast-row data-pharmacy="{{ $b['pharmacy_id'] }}">
                                                @if ($groupList)
                                                    <div class="w-[35%] px-5 py-6 flex items-center justify-start break-words hyphens-auto" style="word-break: break-word;"><div class="">{{ $b['pharmacy'] }}</div></div>
                                                @endif
                                                <div class="w-[35%] px-5 py-6 flex items-center justify-start break-words hyphens-auto" style="word-break: break-word;"><div class="">{{ $b['message'] }}</div></div>
                                                <div class="w-[15%] px-5 py-6 flex items-center justify-center"><div class=""><span class="block text-left mt-1">{{ $b['received_by'] }}</span></div></div>
                                                <div class="w-[15%] px-5 py-6 flex items-center justify-center"><div class=""><span class="block text-left mt-1">{{ $b['read_by'] }}</span></div></div>
                                                <div class="w-[25%] px-5 py-6 flex items-center justify-center"><div class=""><span class="block text-left mt-1"> {{ $b['sent_at'] }}</span></div></div>
                                                <div class="w-[23%] py-6 flex items-center justify-center">
                                                    <div class="text-center text-xs">
                                                        <div>
                                                            <button type="button" data-tw-toggle="modal" data-tw-target="#services-modal-preview{{ $key }}" class="text-darkBlue hover:underline">
                                                                <div class="w-6 h-6 rounded-md"><img src="{{ asset('admin/images/eye_second.svg') }}" alt=""></div>
                                                            </button>
                                                            <div aria-hidden="true" tabindex="-1" id="services-modal-preview{{ $key }}"
                                                                class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 flex justify-center items-center [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
                                                                <div class="relative w-full min-w-[500px] w-max max-w-[90vw] bg-white rounded-lg shadow-lg">
                                                                    <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] rounded-t-lg p-4 text-center">
                                                                        <p class="text-2xl font-bold text-white">Filter Details</p>
                                                                    </div>
                                                                    <div class="p-5 text-black text-sm space-y-3 max-h-[75vh] overflow-auto text-left">
                                                                        <div class="flex gap-2"><span class="font-semibold w-[130px] shrink-0">Age:</span><span>{{ $b['filter']['age'] ? implode(', ', $b['filter']['age']) : 'N/A' }}</span></div>
                                                                        <div class="flex gap-2"><span class="font-semibold w-[130px] shrink-0">Last Order:</span><span>{{ $b['filter']['lastorder'] ? implode(', ', $b['filter']['lastorder']) : 'N/A' }}</span></div>
                                                                        <div class="flex gap-2"><span class="font-semibold w-[130px] shrink-0">Medicines:</span><span class="break-words">{{ $b['filter']['medicine'] ? implode(', ', $b['filter']['medicine']) : 'N/A' }}</span></div>
                                                                    </div>
                                                                    <div class="text-center pb-4">
                                                                        <button type="button" class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white px-8 py-2 rounded-lg" data-tw-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
                        </div>
                    </div>
                </div>
                <div class="intro-y col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap mt-2">
                    @include('pharmacy.partials.pagination-footer', ['count' => $broadcasts->count()])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if ($groupList)
    @push('scripts')
    <script>
        // applyPharmacyfilter() upstream re-queries Livewire.
        document.querySelector('[data-broadcast-pharmacy]').addEventListener('change', (event) => {
            document.querySelectorAll('[data-broadcast-row]').forEach((row) => {
                row.classList.toggle('hidden', event.target.value !== '' && row.dataset.pharmacy !== event.target.value);
            });
        });
    </script>
    @endpush
@endif
