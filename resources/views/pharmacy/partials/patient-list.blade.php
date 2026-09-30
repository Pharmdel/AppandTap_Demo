@php
    // livewire/admin/patient/patient-list.blade.php (the Pharmacy's Patients
    // page); only ever included unframed, i.e. a Pharmacy's own patients.
    // UserSearch::patientSearch: every search term must match a name,
    // address, postcode or email; newest first, 10 per page.
    $search = trim((string) request('search'));
    $matches = collect($demo['staff_patients'])->where('pharmacy_id', $pharmacyId);
    foreach (array_filter(explode(' ', $search)) as $term) {
        $matches = $matches->filter(fn (array $p) => collect([$p['first_name'], $p['last_name'], $p['address_line_1'], $p['postcode'], $p['email']])
            ->contains(fn (string $value) => str_contains(mb_strtolower($value), mb_strtolower($term))));
    }
    $page = max(1, request()->integer('page', 1));
    $elements = new \Illuminate\Pagination\LengthAwarePaginator($matches->forPage($page, 10)->values(), $matches->count(), 10, $page, [
        'path' => url()->current(),
        'query' => request()->except('page'),
    ]);
    $selectedId = $selectedId ?? null;
    $iconButton = 'relative group size-[32px] border-2 border-[#002c57] bg-transparent hover:bg-gradient-to-t hover:from-[#002B56] hover:to-[#0070D5] rounded';
    $tooltip = 'absolute bottom-full mb-2 left-1/2 -translate-x-1/2 bg-[#002B56] text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition duration-200 whitespace-nowrap z-50 pointer-events-none';
@endphp
<div data-live="patient-list">
    <div class="flex justify-between items-center">
        <h1 data-live-region="count" class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-md font-medium text-xl">Patients ({{ $elements->total() }})</h1>
        <div class="flex items-center gap-2.5">
            <div class="{{ $iconButton }} mr-1">
                <a href="javascript:void(0);" class="flex h-full items-center justify-center">
                    <span><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#002c57] group-hover:text-white"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></span>
                </a>
                <div class="{{ $tooltip }}">Create Patient</div>
            </div>
            <div class="{{ $iconButton }} mr-5">
                <a href="javascript:void(0);" class="block h-full flex items-center justify-center" data-tw-toggle="modal" data-tw-target="#pdf_filter_poup">
                    <span><svg viewBox="0 0 512 512" class="w-[14px] h-[14px] text-[#002c57] group-hover:text-white"><path d="M256 398L122 264h88V32h92v232h88L256 398zM0 368v48c0 53 43 96 96 96h320c53 0 96-43 96-96v-48h-64v48c0 18-14 32-32 32H96c-18 0-32-14-32-32v-48H0z" fill="currentColor" /></svg></span>
                </a>
                <div class="{{ $tooltip }}">Download Report</div>
            </div>
        </div>
    </div>
    <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap px-3">
        <div class="mt-3 w-full sm:ml-auto sm:mt-0 md:ml-0 flex justify-between">
            <div class="relative w-[30rem] text-slate-500">
                <input type="search" placeholder="Search..." value="{{ $search }}" data-live-search class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 box w-full pr-10">
                <div>
                    <i data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div data-live-region="rows">
    @if ($elements->isNotEmpty())
    <div class="mt-5 group_owner_tab overflow-auto {{ $elements->hasPages() ? '' : 'single-page' }}" id="patient-list">
        @foreach ($elements as $p)
            @php
                $approved = $p['status'] === 'active';
                // patient-list.blade.php: 'prescription', or 'consultation' when the group runs the IP clinic.
                $defaultType = 'consultation';
                $rowUrl = fn (string $type) => route('pharmacy-portal.patient-detail', ['id' => $p['id'], 'type' => $type, 'search' => $search ?: null, 'page' => $elements->currentPage()]);
            @endphp
            <div data-patient-row class="flex justify-between space-x-3 bg-white p-3 transition duration-200 ease-in-out border-b border-slate-200/60 hover:bg-[#e2f0f5] text-slate-800 items-center [&.active]:text-black [&.active]:bg-[#e2f0f5] {{ $selectedId === $p['id'] ? 'active' : '' }}">
                <a href="{{ $rowUrl($defaultType) }}" class="flex items-center gap-4">
                    <span class="shrink-0 rounded-full bg-gradient-to-t from-[#002B56] to-[#0070D5] text-xl h-[48px] w-[48px] flex justify-center items-center text-white">{{ mb_substr($p['first_name'], 0, 1) }}{{ mb_substr($p['last_name'], 0, 1) }}</span>
                    <div class="break-all">{{ $p['first_name'] }} {{ $p['last_name'] }} ({{ $p['dob'] }})
                        <div class="mt-0.5 text-xs text-slate-500 flex items-center gap-1">{{ ($p['is_guest'] ?? false) ? '(Guest)' : '' }}</div>
                        <div class="mt-0.5 text-xs text-slate-500 flex items-center gap-1">
                            <span class="shrink-0">@include('pharmacy.partials.icon', ['name' => 'mail'])</span>
                            {{ $p['email'] }}
                        </div>
                        <div class="mt-0.5 text-xs text-slate-500 flex items-center gap-1">
                            <span class="shrink-0">@include('pharmacy.partials.icon', ['name' => 'phone'])</span>
                            {{ $p['contact_number'] }}
                        </div>
                        @if ($p['nhs_number'])
                            <div class="mt-0.5 text-xs text-slate-500 flex items-center gap-1">
                                <span class="shrink-0">@include('pharmacy.partials.icon', ['name' => 'nhs'])</span>
                                {{ $p['nhs_number'] }}
                            </div>
                        @endif
                    </div>
                </a>
                <div class="flex space-x-2 items-center">
                    @if ($approved)
                        @if ($p['user_type'] === 'NHS')
                            <div class="w-5 h-5">
                                <a href="{{ $rowUrl('message') }}" class=""><img src="{{ asset('admin/images/message-icon.svg') }}" alt=""></a>
                            </div>
                        @endif
                    @else
                        <div class="relative group">
                            <span class="relative flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-danger opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-danger"></span>
                            </span>
                            <button type="button" class="hidden group-hover:block absolute left-1/2 -translate-x-1/2 bottom-[22px] text-[9px] leading-normal whitespace-nowrap ml-auto text-white border border-danger bg-danger px-1 rounded-sm uppercase">Unapproved</button>
                        </div>
                        <div class="w-4 h-4"></div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @else
        <div>
            <div class="p-6 transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded bg-white text-slate-800">
                <img class="mx-auto" src="{{ asset('admin/images/no-record-found-new.png') }}" alt="">
            </div>
        </div>
    @endif
    </div>
    <div data-live-region="pagination" class="intro-y col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
        @include('pharmacy.partials.pagination-left-links', ['paginator' => $elements])
    </div>
</div>
