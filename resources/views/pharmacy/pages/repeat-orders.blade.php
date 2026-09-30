@extends('layouts.pharmacy')

@php
    // admin/repeat-order/index.blade.php -> livewire/admin/repeat-order/index.blade.php
    $rows = $demo['staff_repeat_order_patients'];
    $head = 'font-semibold px-5 py-3 border-b-0 flex items-end';
    $field = 'transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 block min-w-full px-4 py-3';
    $modal = 'modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed right-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]';
    $appPatients = collect($demo['staff_patients'])->where('status', 'active');
@endphp

@section('content-class', 'pt-6')

@section('content')
<div>
    <div class="grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <button type="button" data-tw-toggle="modal" data-tw-target="#appdownload"
                class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] border-blue text-white mr-2 shadow-md">
                Add Patient
            </button>
            <div class="mt-3 w-full sm:ml-auto sm:mt-0 sm:w-auto md:ml-0 flex">
                <div class="relative w-56 text-slate-500">
                    <input type="search" placeholder="Search..." data-list-search="[data-repeat-row]"
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
                        <div class="w-2/12 {{ $head }} whitespace-nowrap">Patient</div>
                        <div class="w-1/12 {{ $head }} justify-center whitespace-nowrap">DOB</div>
                        <div class="w-2/12 {{ $head }} justify-center whitespace-nowrap text-left">Contact</div>
                        <div class="w-3/12 {{ $head }} justify-center whitespace-nowrap text-left">Email</div>
                        <div class="w-2/12 {{ $head }} text-left">Address</div>
                        <div class="w-2/12 {{ $head }} justify-center whitespace-nowrap text-left">Action</div>
                    </div>
                </div>
                <div>
                    @foreach ($rows as $row)
                        <div class="flex transition duration-200 ease-in-out will-change-transform transform cursor-pointer border-b border-slate-200/60 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded bg-white text-slate-800" data-repeat-row>
                            <div class="w-2/12 px-5 py-3 flex items-center">
                                <div>
                                    <span class="block font-semibold flex items-center gap-1">
                                        {{ $row['name'] }}
                                        @if ($row['nhs_verified'])
                                            <img class="mx-auto w-auto h-3 mt-1" src="{{ asset('admin/images/nhs-new.png') }}" alt="NHS Verified">
                                        @endif
                                    </span>
                                    <span class="block">{{ $row['nhs_number'] }}</span>
                                </div>
                            </div>
                            <div class="w-1/12 px-5 py-3 text-center flex items-center justify-center">{{ $row['dob'] }}</div>
                            <div class="w-2/12 px-5 py-3 text-center flex items-center justify-center">{{ $row['contact'] ?? '-' }}</div>
                            <div class="w-3/12 px-5 py-3 text-center flex items-center justify-center break-all">{{ $row['email'] ?? '-' }}</div>
                            <div class="w-2/12 px-5 py-3 flex items-center">
                                <div>
                                    <span class="block">{{ $row['address'] }}</span>
                                    <span class="block">{{ $row['postcode'] }}</span>
                                </div>
                            </div>
                            <div class="w-2/12 px-5 py-3 text-center flex items-center justify-center gap-x-5">
                                <a href="{{ route('pharmacy-portal.repeat-order-medicine', $row['id']) }}" aria-label=" Medicines" class="flex items-center justify-center">
                                    @include('pharmacy.partials.medicine-icon')
                                </a>
                                @if ($row['patient_id'] === null)
                                    <a class="transition duration-200 inline-flex items-center justify-center rounded-md font-medium cursor-pointer" aria-label=" Edit" data-tw-toggle="modal" data-tw-target="#add-new-patient" onclick="editRepeatPatient(this)"
                                        data-patient='@json($row)'>
                                        <i data-lucide="check-square" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                    </a>
                                @else
                                    <a class="transition duration-200 inline-flex items-center justify-center rounded-md font-medium opacity-70 cursor-not-allowed" aria-label=" disabled Edit">
                                        <i data-lucide="check-square" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                    </a>
                                @endif
                                <a class="flex items-center text-danger font-medium" aria-label="Delete" href="javascript:void(0);" onclick="deleteRepeatRow(this)">
                                    <i data-lucide="trash" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- END: Data List -->
        <div class="intro-y col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
            @include('pharmacy.partials.pagination-footer', ['count' => count($rows)])
        </div>

        {{-- side popup: add / edit a walk-in patient (#add-new-patient) --}}
        <div id="add-new-patient" tabindex="-1" role="dialog" class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s] overflow-y-auto">
            <div class="w-[90%] ml-auto h-screen flex flex-col bg-white relative shadow-md transition-[margin-right] duration-[0.6s] -mr-[100%] group-[.show]:mr-0 sm:w-[500px] text-[#222222]">
                <a class="absolute top-0 left-0 right-auto mt-4 -ml-10 sm:-ml-12" data-tw-dismiss="modal" href="javascript:void(0);">
                    <i data-lucide="x" class="stroke-1.5 w-8 h-8 text-slate-400"></i>
                </a>
                <div class="mt-2 top-0 h-full overflow-y-scroll">
                    <form class="h-full flex flex-col" id="add_custom_patient" autocomplete="off" onsubmit="return submitCustomPatient(event)">
                        <div class="flex items-center px-5 py-3 border-b border-slate-200/60 p-5">
                            <h2 class="text-base font-semibold">Add New Patient</h2>
                        </div>
                        <div class="flex-1 flex">
                            <div class="grid grid-cols-12 gap-6 w-full">
                                <div class="intro-y col-span-12 lg:col-span-12 flex flex-col">
                                    <div class="flex flex-col gap-6 grow h-5 overflow-auto px-5 py-4">
                                        @foreach (['name' => ['Name*', 'Enter Patient Name', 'text'], 'dob' => ['DOB*', 'Select date', 'date'], 'nhs_number' => ['NHS*', 'Enter NHS Number', 'text'], 'email' => ['Email', 'Enter Email', 'email'], 'contact' => ['Contact', 'Enter Contact Number', 'text'], 'address' => ['Address*', 'Enter Address', 'text'], 'postcode' => ['Postcode*', 'Enter Postcode', 'text']] as $name => [$label, $placeholder, $type])
                                            <div>
                                                <label class="inline-block font-semibold mb-2">{{ $label }}</label>
                                                <input type="{{ $type }}" name="{{ $name }}" placeholder="{{ $placeholder }}" class="{{ $field }}" @if (str_ends_with($label, '*')) required @endif>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-auto text-right py-3 px-5 border-t border-slate-200/60 flex justify-between">
                                        <button type="submit" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- popup: pick an app patient, or "Create New" (#appdownload) --}}
        <div aria-hidden="false" tabindex="-1" id="appdownload" class="{{ $modal }} flex justify-center items-center">
            <div class="w-[90vw] max-w-[450px] flex flex-col">
                <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] px-8 py-3 rounded-t flex items-center relative">
                    <a class="bg-white text-darkBlue w-10 h-10 rounded-full absolute -right-4 -top-4 flex justify-center items-center cursor-pointer" data-tw-dismiss="modal">
                        <i data-lucide="x" class="stroke-2 w-6 h-6"></i>
                    </a>
                    <h2 class="text-white text-xl font-medium text-center grow">Add New Patient</h2>
                </div>
                <div class="bg-white">
                    <div class="px-2.5 py-3 min-h-[250px] flex flex-col">
                        <div class="mb-6 absolute-tom-select color-theme-tom-select">
                            <label class="inline-block mb-2 font-semibold">Select Patient Or Dependant</label>
                            <div class="w-full text-slate-500">
                                <select class="{{ $field }}" id="existingPatient">
                                    <option value="">Select patient</option>
                                    @foreach ($appPatients as $p)
                                        <option value="{{ $p['first_name'] }} {{ $p['last_name'] }}">{{ $p['first_name'] }} {{ $p['last_name'] }} ({{ $p['dob'] }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-7 text-center">or</div>
                        <div class="mt-7 text-center">
                            <a href="javascript:void(0)" data-tw-dismiss="modal" onclick="openCustomPatientModel()" class="bg-white text-lightBlue border-lightBlue transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer">Create New</a>
                        </div>
                    </div>
                </div>
                <div class="rounded-b-lg bg-white overflow-hidden hidden" data-existing-confirm>
                    <div class="mt-auto text-right py-3 px-2 rounded-lg border border-blue flex justify-between items-center gap-1">
                        <div class="flex gap-1 items-center">
                            <img src="{{ asset('admin/images/user-patient.svg') }}" alt="">
                            <span class="break-all" data-existing-name></span>
                        </div>
                        <button type="button" onclick="addExistingPatient()" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">Add Patient</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const existing = document.getElementById('existingPatient');
        existing.addEventListener('change', () => {
            document.querySelector('[data-existing-confirm]').classList.toggle('hidden', existing.value === '');
            document.querySelector('[data-existing-name]').textContent = 'Patient Name : ' + existing.value;
        });

        window.addExistingPatient = function () {
            hideModal('#appdownload');
            Swal.fire({ title: existing.value + ' added to repeat orders.', icon: 'success', timer: 1800, showConfirmButton: false });
        };

        // openCustomPatientModel(): swap the picker for the side form.
        window.openCustomPatientModel = function () {
            document.getElementById('add_custom_patient').reset();
            setTimeout(() => showModal('#add-new-patient'), 50);
        };

        window.editRepeatPatient = function (link) {
            const data = JSON.parse(link.dataset.patient);
            const form = document.getElementById('add_custom_patient');
            ['name', 'nhs_number', 'email', 'contact', 'address', 'postcode'].forEach((key) => { form.elements[key].value = data[key] || ''; });
            form.elements.dob.value = data.dob.split('/').reverse().join('-');
        };

        window.submitCustomPatient = function (event) {
            event.preventDefault();
            if (!event.target.reportValidity()) { return false; }
            hideModal('#add-new-patient');
            Swal.fire({ title: 'Patient saved successfully.', icon: 'success', timer: 1800, showConfirmButton: false });
            return false;
        };

        window.deleteRepeatRow = function (link) {
            Swal.fire({
                title: 'Are you sure to delete?', text: "You won't be able to revert this!", icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes, Delete it.',
            }).then((result) => { if (result.isConfirmed) { link.closest('[data-repeat-row]').remove(); } });
        };
    })();
</script>
@endpush
