@extends('layouts.pharmacy')

@php
    // Scoped to whichever pharmacy is open: the current login (Pharmacy), or
    // the one chosen in the Group Owner's frame (?pharmacy=).
    $patients = collect($demo['staff_patients'])->where('status', 'active')->where('pharmacy_id', $pharmacyId);
@endphp

@unless ($showFrame)
    @section('content-class', 'patient-page-offset')
@endunless

@section('content')
@if ($showFrame)
    {{-- livewire/admin/pharmacy/patient-list.blade.php (pharmacy.show > Patients) --}}
    @php
        $cell = 'px-5 py-3 border-b box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r';
        $detailUrl = fn (array $p, string $type) => route('pharmacy-portal.patient-detail', ['id' => $p['id'], 'pharmacy' => $framePharmacy['id'], 'type' => $type]);
        // Admin\Pharmacy\PatientList: patientSearch(..., pageName: 'patient-page')->onEachSide(0).
        $search = trim((string) request('search'));
        foreach (array_filter(explode(' ', $search)) as $term) {
            $patients = $patients->filter(fn (array $p) => collect([$p['first_name'], $p['last_name'], $p['address_line_1'], $p['postcode'], $p['email']])
                ->contains(fn (string $value) => str_contains(mb_strtolower($value), mb_strtolower($term))));
        }
        $perPage = in_array(request()->integer('perPage'), [10, 25, 50, 75, 100], true) ? request()->integer('perPage') : 10;
        $page = max(1, request()->integer('patient-page', 1));
        $paginator = (new \Illuminate\Pagination\LengthAwarePaginator($patients->forPage($page, $perPage)->values(), $patients->count(), $perPage, $page, [
            'path' => url()->current(),
            'query' => request()->except('patient-page'),
            'pageName' => 'patient-page',
        ]))->onEachSide(0);
    @endphp
    <div data-live="patients" data-live-page-name="patient-page">
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <div class="mt-3 w-full sm:ml-auto sm:mt-0 sm:w-auto md:ml-0 flex gap-5 justify-end">
                <div class="w-[38px] h-[38px] relative group border border-[#002c57] bg-transparent hover:bg-gradient-to-t hover:from-[#002B56] hover:to-[#0070D5] rounded">
                    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-[#002B56] text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition duration-200 whitespace-nowrap z-50 pointer-events-none">Download Report</div>
                    <a href="javascript:void(0);" class="block h-full flex items-center justify-center" data-tw-toggle="modal" data-tw-target="#pdf_filter_poup">
                        <svg viewBox="0 0 512 512" class="w-[18px] h-auto text-[#002c57] group-hover:text-white"><path d="M256 398L122 264h88V32h92v232h88L256 398zM0 368v48c0 53 43 96 96 96h320c53 0 96-43 96-96v-48h-64v48c0 18-14 32-32 32H96c-18 0-32-14-32-32v-48H0z" fill="currentColor" /></svg>
                    </a>
                </div>
                <div class="relative w-56 text-slate-500">
                    <input type="search" placeholder="Search Patient..." value="{{ $search }}" data-live-search class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 box w-56 pr-10">
                    <div>
                        <i data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                    </div>
                </div>
            </div>
            <table class="w-full text-left -mt-2 border-separate border-spacing-y-[10px]">
                <thead>
                    <tr>
                        <th class="font-semibold px-5 py-3 whitespace-nowrap border-b-0">Patient info</th>
                        <th class="font-semibold px-5 py-3 whitespace-nowrap border-b-0">Address</th>
                        <th class="font-semibold px-5 py-3 whitespace-nowrap border-b-0 text-center">Action</th>
                    </tr>
                </thead>
                <tbody data-live-region="rows">
                    @foreach ($paginator as $patient)
                        <tr class="intro-x transition duration-200 ease-in-out transform hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:rounded-lg" data-patient-row>
                            <td class="{{ $cell }}">
                                <div class="flex items-center">
                                    <div class="image-fit ml-2">
                                        <a href="{{ $detailUrl($patient, $patient['user_type'] === 'NHS' ? 'prescription' : 'patientinfo') }}">
                                            {{ $patient['first_name'] }} {{ $patient['last_name'] }} {{ ($patient['is_guest'] ?? false) ? '(Guest)' : '' }}
                                        </a>
                                        <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500">
                                            <div class="flex items-center gap-1">
                                                <span class="shrink-0">@include('pharmacy.partials.icon', ['name' => 'phone'])</span>
                                                {{ $patient['contact_number'] }}
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <span class="shrink-0">@include('pharmacy.partials.icon', ['name' => 'mail'])</span>
                                                {{ $patient['email'] }}
                                            </div>
                                            @if ($patient['nhs_number'])
                                                <div class="flex items-center gap-1">
                                                    <span class="shrink-0">@include('pharmacy.partials.icon', ['name' => 'nhs'])</span>
                                                    {{ $patient['nhs_number'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="{{ $cell }}">
                                <a class="whitespace-nowrap">{{ $patient['address_line_1'] }}<br>{{ $patient['postcode'] }}</a>
                            </td>
                            <td class="{{ $cell }} w-56 text-left">
                                <div class="flex items-center justify-center">
                                    <div class="flex space-x-2 items-center">
                                        <div class="relative group"></div>
                                        <div class="w-4 h-4">
                                            @if ($patient['user_type'] === 'NHS')
                                                <a href="{{ $detailUrl($patient, 'message') }}" class=""><img src="{{ asset('admin/images/message-icon.svg') }}" alt=""></a>
                                            @else
                                                <a href="{{ $detailUrl($patient, 'patientinfo') }}" class=""><img src="{{ asset('admin/images/view-icon.svg') }}" alt=""></a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div data-live-region="pagination" class="intro-y col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
            {{ $paginator->links('pharmacy.partials.pagination-links', ['live' => true]) }}
        </div>
    </div>
    @include('pharmacy.partials.patient-export-modal')
@else
    {{-- admin/patient/index.blade.php: PatientController@index defaults
         $patient to the pharmacy's most recently added one, so this page
         essentially never shows the empty "no record found" state on its
         own — that's only for a pharmacy with literally no patients yet. --}}
    @php $defaultPatient = $patients->sortByDesc('created_at')->first(); @endphp
    <div class="h-full">
        <div class="grid grid-cols-12 gap-6 h-full">
            <div class="col-span-12 lg:col-span-3 2xl:col-span-3 border-r border-slate-300 sticky top-[100px] max-h-[calc(100vh-100px)] z-[1]">
                @include('pharmacy.partials.patient-list', ['selectedId' => $defaultPatient['id'] ?? null])
            </div>
            <div class="col-span-12 lg:col-span-9 2xl:col-span-9">
                @if ($defaultPatient)
                    @php $patient = $defaultPatient; @endphp
                    <div class="flex space-x-3">
                        <div class="flex items-center space-x-5">
                            <h2 class="">{{ $patient['first_name'] }} {{ $patient['last_name'] }}</h2>
                        </div>
                    </div>
                    @include('pharmacy.partials.patient-info')
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
    @include('pharmacy.partials.patient-export-modal')
@endif
@endsection
