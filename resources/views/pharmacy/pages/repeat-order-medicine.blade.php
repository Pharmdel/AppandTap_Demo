@extends('layouts.pharmacy')

@php
    // admin/repeat-order/repeat-order-medicine.blade.php -> livewire/admin/repeat-order/medicine-list.blade.php
    $head = 'font-semibold px-5 py-3 border-b-0 flex items-end';
    $field = 'transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 block min-w-full px-4 py-3';
    $modal = 'modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed right-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s] flex justify-center items-center';
    $cycle = fn (array $m) => $m['repeat_type'] === 'Custom' ? 'Every '.$m['custom_days'].' Days' : $m['repeat_type'];
@endphp

@section('content-class', 'pt-6')

@section('content')
<div>
    <div class="grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap">
            <button type="button" data-tw-toggle="modal" data-tw-target="#addMedicine"
                class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] border-blue text-white mr-2 shadow-md">
                Add Medicine
            </button>
            <div class="mt-3 w-full sm:ml-auto sm:mt-0 sm:w-auto md:ml-0 flex">
                <div class="relative w-56 text-slate-500">
                    <input type="search" placeholder="Search..." data-list-search="[data-medicine-row]"
                        class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 box w-56 pr-10">
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
                        <div class="w-4/12 {{ $head }} whitespace-nowrap">Medicine</div>
                        <div class="w-4/12 {{ $head }} justify-center whitespace-nowrap">Next Reminder & Repeat Cycle</div>
                        <div class="w-4/12 {{ $head }} justify-center whitespace-nowrap text-left">Action</div>
                    </div>
                </div>
                <div>
                    @forelse ($repeatPatient['medicines'] as $m)
                        <div class="flex transition duration-200 ease-in-out will-change-transform transform cursor-pointer border-b border-slate-200/60 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded bg-white text-slate-800" data-medicine-row>
                            <div class="w-4/12 px-5 py-3 flex items-center">
                                <div>
                                    <span class="block font-semibold flex items-center gap-1">
                                        {{ $m['medicine'] }} ({{ $m['quantity'] }})
                                        @if ($m['is_nhs'])
                                            <img class="mx-auto w-auto h-3 mt-1" src="{{ asset('admin/images/nhs-new.png') }}" alt="NHS Verified">
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="w-4/12 px-5 py-3 text-center flex items-center justify-center">
                                <a href="javascript:void(0)" data-tw-toggle="modal" data-tw-target="#set_repeat_freq" class="add_reorder_reminder underline" onclick="openReminderPopup(this)"
                                    data-date="{{ $m['next_reminder'] }}" data-cycle="{{ $m['repeat_type'] }}" data-days="{{ $m['custom_days'] ?? '' }}">
                                    @if ($m['repeat_type'])
                                        {{ $m['next_reminder'] }} & {{ $cycle($m) }}
                                    @else
                                        Add Re-order Reminder
                                    @endif
                                </a>
                            </div>
                            <div class="w-4/12 px-5 py-3 text-center flex items-center justify-center">
                                <div class="flex lg:flex-row flex-col items-center justify-center">
                                    <a class="transition duration-200 inline-flex items-center justify-center rounded-md font-medium cursor-pointer ml-5" data-popup-open="history-{{ $m['id'] }}">
                                        order history
                                    </a>
                                    @if ($m['is_nhs'])
                                        <a class="tooltip flex items-center text-gray ml-5 font-medium" aria-disabled="true" title="This is NHS patient You can't delete the Meds">
                                            <i data-lucide="trash" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                            Delete
                                        </a>
                                    @else
                                        <a class="flex items-center text-danger ml-5 font-medium" href="javascript:void(0);" onclick="deleteMedicineRow(this)">
                                            <i data-lucide="trash" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                            Delete
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 bg-white text-slate-800">
                            <img class="mx-auto" src="{{ asset('admin/images/no-record-found-new.png') }}" alt="">
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- END: Data List -->
        <div class="intro-y col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap">
            @include('pharmacy.partials.pagination-footer', ['count' => count($repeatPatient['medicines'])])
        </div>
    </div>

    {{-- #set_repeat_freq: "Add Re-order Reminder" --}}
    <div tabindex="-1" id="set_repeat_freq" class="{{ $modal }}">
        <div class="w-[90vw] max-w-[450px] flex flex-col">
            <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] px-8 py-3 rounded-t flex items-center relative">
                <a class="bg-white text-darkBlue w-10 h-10 rounded-full absolute -right-4 -top-4 flex justify-center items-center cursor-pointer" data-tw-dismiss="modal">
                    <i data-lucide="x" class="stroke-2 w-6 h-6"></i>
                </a>
                <h2 class="text-white text-xl font-medium text-center grow">Add Re-order Reminder</h2>
            </div>
            <form class="bg-white" onsubmit="return saveReminder(event)">
                <div class="px-2.5 py-3 min-h-[200px] flex flex-col gap-4">
                    <div>
                        <label class="inline-block font-semibold mb-2">Next Order Date</label>
                        <input type="date" name="date" class="{{ $field }}" required>
                    </div>
                    <div>
                        <label class="inline-block mb-2 font-semibold">Frequency</label>
                        <select name="cycle" class="{{ $field }}" required>
                            <option value="">Select Frequency</option>
                            @foreach (['One Time', 'Every 28 days', 'Every 56 days', 'Every 84 days', 'Custom'] as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="hidden" data-custom-days>
                        <label class="inline-block font-semibold mb-2">Custom Days</label>
                        <input type="number" name="days" min="1" placeholder="Select Days" class="{{ $field }}">
                    </div>
                </div>
                <div class="rounded-b-lg overflow-hidden">
                    <div class="mt-auto text-right py-3 px-2 rounded-lg border border-blue flex justify-between bg-white">
                        <button type="submit" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">Add Reminder</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- "Order History" slide-overs --}}
    @foreach ($repeatPatient['medicines'] as $m)
        <section id="history-{{ $m['id'] }}" class="hidden" data-popup>
            <div class="fixed inset-0 bg-black/60 z-[99]" data-popup-close></div>
            <div class="fixed right-0 top-0 z-[99] w-[90%] sm:w-[500px] h-screen flex flex-col bg-white shadow-md text-[#222222]">
                <a class="absolute top-0 left-0 right-auto mt-4 -ml-10 sm:-ml-12" data-popup-close href="javascript:void(0);">
                    <i data-lucide="x" class="stroke-1.5 w-8 h-8 text-slate-400"></i>
                </a>
                <div class="flex items-center px-5 py-3 border-b border-slate-200/60 p-5">
                    <h2 class="text-base font-semibold">Order History</h2>
                </div>
                <div class="flex flex-col gap-3 px-5 py-4 overflow-auto">
                    <p class="font-semibold">{{ $m['medicine'] }} ({{ $m['quantity'] }})</p>
                    @forelse ($m['history'] as $orderedOn)
                        <div class="px-2.5 py-2 text-sm border border-slate-200 shadow-sm rounded-md">
                            <p class="font-semibold">Ordered On :- {{ $orderedOn }}</p>
                        </div>
                    @empty
                        <img class="mx-auto" src="{{ asset('admin/images/no-record-found-new.png') }}" alt="">
                    @endforelse
                </div>
            </div>
        </section>
    @endforeach

    {{-- #addMedicine: "Add New Medicine" --}}
    <div tabindex="-1" id="addMedicine" class="{{ $modal }}">
        <div class="w-[90vw] max-w-[450px] flex flex-col">
            <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] px-8 py-3 rounded-t flex items-center relative">
                <a class="bg-white text-darkBlue w-10 h-10 rounded-full absolute -right-4 -top-4 flex justify-center items-center cursor-pointer" data-tw-dismiss="modal">
                    <i data-lucide="x" class="stroke-2 w-6 h-6"></i>
                </a>
                <h2 class="text-white text-xl font-medium text-center grow">Add New Medicine</h2>
            </div>
            <form class="bg-white" onsubmit="return addMedicine(event)">
                <div class="px-2.5 py-3 min-h-[180px] flex flex-col">
                    <label class="inline-block mb-2 font-semibold">Select Medicine</label>
                    <input type="text" name="medicine" placeholder="Search Medicine...." class="{{ $field }}" required>
                </div>
                <div class="rounded-b-lg overflow-hidden">
                    <div class="mt-auto text-right py-3 px-2 rounded-lg border border-blue flex justify-between bg-white">
                        <button type="submit" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        let reminderLink = null;
        const form = document.querySelector('#set_repeat_freq form');
        form.elements.cycle.addEventListener('change', () => {
            document.querySelector('[data-custom-days]').classList.toggle('hidden', form.elements.cycle.value !== 'Custom');
        });

        window.openReminderPopup = function (link) {
            reminderLink = link;
            form.reset();
            form.elements.date.value = link.dataset.date ? link.dataset.date.split('/').reverse().join('-') : '';
            form.elements.cycle.value = link.dataset.cycle || '';
            form.elements.days.value = link.dataset.days || '';
            document.querySelector('[data-custom-days]').classList.toggle('hidden', link.dataset.cycle !== 'Custom');
        };

        window.saveReminder = function (event) {
            event.preventDefault();
            const date = form.elements.date.value.split('-').reverse().join('/');
            const cycle = form.elements.cycle.value === 'Custom' ? `Every ${form.elements.days.value} Days` : form.elements.cycle.value;
            reminderLink.textContent = `${date} & ${cycle}`;
            hideModal('#set_repeat_freq');
            return false;
        };

        window.addMedicine = function (event) {
            event.preventDefault();
            hideModal('#addMedicine');
            Swal.fire({ title: 'Medicine added successfully.', icon: 'success', timer: 1800, showConfirmButton: false });
            return false;
        };

        window.deleteMedicineRow = function (link) {
            Swal.fire({
                title: 'Are you sure to delete?', text: "You won't be able to revert this!", icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes, Delete it.',
            }).then((result) => { if (result.isConfirmed) { link.closest('[data-medicine-row]').remove(); } });
        };
    })();
</script>
@endpush
