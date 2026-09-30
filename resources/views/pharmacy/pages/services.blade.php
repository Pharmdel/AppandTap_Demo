@extends('layouts.pharmacy')

@php
    // admin/pharmacy-service/index.blade.php -> livewire/admin/pharmacy-service/index.blade.php
    $services = $demo['staff_pharmacy_services'];
    $th = 'font-semibold px-5 py-3 whitespace-nowrap border-b-0 text-center align-top leading-none';
    $toggle = "transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 [&[type='radio']]:checked:bg-green [&[type='radio']]:checked:border-primary [&[type='radio']]:checked:border-opacity-10 [&[type='checkbox']]:checked:bg-green [&[type='checkbox']]:checked:border-primary [&[type='checkbox']]:checked:border-opacity-10 [&:disabled:not(:checked)]:bg-slate-100 [&:disabled:not(:checked)]:cursor-not-allowed [&:disabled:checked]:opacity-70 [&:disabled:checked]:cursor-not-allowed w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:bg-white checked:bg-redclr checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white bg-redclr";
    $allPinned = collect($services)->every(fn ($s) => $s['pin_it']);
    $allSameDay = collect($services)->every(fn ($s) => $s['same_day']);
    $columns = ['book_app' => 'Book by app', 'book_phone' => 'Book by phone', 'video' => 'Video call'];
@endphp

@section('content-class', 'pt-6')

@section('content')
<div class="intro-y mt-4 justify-between items-center mb-5"></div>
<div class="grid lg:grid-cols-4 gap-6">
    <div class="lg:col-span-4">
        <div>
            <div class="bg-white rounded-lg relative">
                <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] rounded-t-md px-5 py-2">
                    <div class="flex items-center">
                        <ul role="tablist" class="w-full flex gap-8">
                            <li id="Services_tab" role="presentation" class="focus-visible:outline-none">
                                <a role="tab" class="cursor-pointer block appearance-none text-white [&.active]:border-b-2 [&.active]:border-darkBlue [&.active]:text-white border-b-2 border-transparent [&.active]:font-medium w-full py-2 active" href="{{ route('pharmacy-portal.services') }}" aria-selected="true">Services</a>
                            </li>
                        </ul>
                        {{-- pharmacy-service.create (not part of the demo) --}}
                        <a href="javascript:void(0);"
                            class="ml-auto bg-white text-lightBlue border-lightBlue transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer whitespace-nowrap">
                            <i data-lucide="plus" class="stroke-1.5 mr-2 h-4 w-4"></i>
                            Add service
                        </a>
                    </div>
                </div>
                <div class="mt-4 px-5">
                    <div class="tab-content mt-5">
                        <div id="Services-1" role="tabpanel" class="tab-pane leading-relaxed active visible opacity-100">
                            <div>
                                <div class="relative w-56 text-slate-500 mb-2">
                                    <input type="search" placeholder="Search Services..." data-list-search="[data-service-row]"
                                        class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 box w-56 pr-10">
                                    <div>
                                        <i data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
                                    </div>
                                </div>
                                <table class="w-full text-left -mt-2 border-separate border-spacing-y-[10px]">
                                    <thead>
                                        <tr>
                                            <th class="font-semibold px-5 py-3 whitespace-nowrap border-b-0 align-top leading-none">Service name</th>
                                            <th class="{{ $th }}">Pin It <br><br>
                                                <div class="flex justify-center"><input type="checkbox" @checked($allPinned) class="{{ $toggle }}" data-toggle-all="pin_it"></div>
                                            </th>
                                            <th class="{{ $th }}">Same Day<br>Booking
                                                <div class="flex justify-center"><input type="checkbox" @checked($allSameDay) class="{{ $toggle }}" data-toggle-all="same_day"></div>
                                            </th>
                                            <th class="{{ $th }}">Schedule</th>
                                            <th class="{{ $th }}">Book by<br> app</th>
                                            <th class="{{ $th }}">Book by<br> phone</th>
                                            <th class="{{ $th }}">Video call</th>
                                            <th class="{{ $th }}">Status</th>
                                            <th class="{{ $th }}">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($services as $key => $service)
                                            <tr class="" data-service-row>
                                                <td class="px-5 py-3 w-400">
                                                    <div class="flex gap-4">
                                                        <a href="javascript:void(0)" class="underline hover:text-darkBlue font-bold">{{ $service['title'] }} @if ($service['is_ip_clinic'])<span class="text-danger">(IP Service)</span>@endif</a>
                                                        <p class="text-redclr text-sm font-bold">{{ $service['amount'] > 0 ? '£'.$service['amount'] : '' }}</p>
                                                    </div>
                                                    <div class="limitthreeline text-xs">{{ \Illuminate\Support\Str::limit($service['description'], 100) }}</div>
                                                    <div class="flex items-center gap-10 mt-2">
                                                        @if (strlen($service['description']) > 80)
                                                            <div>
                                                                <button type="button" data-tw-toggle="modal" data-tw-target="#services-modal-preview{{ $key }}" class="text-darkBlue hover:underline">Read More</button>
                                                                <div aria-hidden="true" tabindex="-1" id="services-modal-preview{{ $key }}"
                                                                    class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 flex justify-center items-center [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s]">
                                                                    <div class="w-[1024px] max-w-[90vw] max-h-[80vh] mx-auto bg-white relative rounded-md shadow-md p-8 flex flex-col">
                                                                        <button type="button" class="absolute -top-4 -right-4 bg-white w-10 h-10 rounded-full flex justify-center items-center" data-tw-dismiss="modal">
                                                                            <i data-lucide="x" class="stroke-1.5 w-8 h-8 text-slate-400"></i>
                                                                        </button>
                                                                        <h3 class="text-lg font-bold mb-4">{{ $service['title'] }}</h3>
                                                                        <div class="summernote-content text-base overflow-auto pr-4">{{ $service['description'] }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td><div class="flex justify-center"><input type="checkbox" @checked($service['pin_it']) class="{{ $toggle }}" data-toggle="pin_it"></div></td>
                                                <td><div class="flex justify-center"><input type="checkbox" @checked($service['same_day']) class="{{ $toggle }}" data-toggle="same_day"></div></td>
                                                <td>
                                                    <div class="flex justify-center">
                                                        <label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" @checked($service['schedule']) class="{{ $toggle }}"></label>
                                                    </div>
                                                </td>
                                                @foreach ($columns as $column => $label)
                                                    <td>
                                                        <div class="flex justify-center">
                                                            <label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" @checked($service[$column]) class="{{ $toggle }}"></label>
                                                        </div>
                                                    </td>
                                                @endforeach
                                                <td class="text-center">
                                                    <a href="javascript:void(0);" onclick="toggleServiceStatus(this)"
                                                        class="flex items-center justify-center font-medium {{ $service['status'] ? 'text-success' : 'text-danger' }}">
                                                        {{ $service['status'] ? 'Active' : 'Inactive' }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="flex justify-center items-center">
                                                        <div>
                                                            <a class="flex items-center text-danger ml-2 font-medium" href="javascript:void(0);" onclick="deleteService(this)">
                                                                <i data-lucide="trash" class="stroke-1.5 mr-1 h-4 w-4"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="intro-y col-span-12 flex flex-wrap items-center sm:flex-row sm:flex-nowrap my-5">
                                @include('pharmacy.partials.pagination-footer', ['count' => count($services)])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // public/admin/js/custom.js: status_info() and delete_info().
    function toggleServiceStatus(link) {
        Swal.fire({
            title: 'Do you want to change status ?', icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes, Change it!',
        }).then((result) => {
            if (!result.isConfirmed) { return; }
            const active = link.textContent.trim() === 'Active';
            link.textContent = active ? 'Inactive' : 'Active';
            link.classList.toggle('text-success', !active);
            link.classList.toggle('text-danger', active);
        });
    }

    function deleteService(link) {
        Swal.fire({
            title: 'Are you sure to delete?', text: "You won't be able to revert this!", icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes, Delete it.',
        }).then((result) => {
            if (result.isConfirmed) { link.closest('tr').remove(); }
        });
    }

    // The header toggles switch a column for every service (manageToggle upstream).
    document.querySelectorAll('[data-toggle-all]').forEach((master) => master.addEventListener('change', () => {
        document.querySelectorAll(`[data-toggle="${master.dataset.toggleAll}"]`).forEach((box) => { box.checked = master.checked; });
    }));
</script>
@endpush
