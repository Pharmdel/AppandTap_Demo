{{-- livewire/admin/pharmacy/pharmacy-list.blade.php --}}
@php $pharmacies = $demo['staff_pharmacies']; @endphp

<div>
    <div class="flex justify-between items-center">
        <h1 class="transition duration-200 inline-flex items-center justify-center py-2 px-3 rounded-md font-medium text-xl">
            Pharmacies ({{ count($pharmacies) }})</h1>
        <div class="flex justify-between gap-0">
            <a href="javascript:void(0);" class="px-2" title="Import pharmacy">
                <i data-lucide="file-down" class="stroke-1.5 w-7 h-7 mx-auto block"></i>
            </a>
            <a href="javascript:void(0);" class="px-2" title="Create pharmacy">
                <i data-lucide="plus-square" class="stroke-1.5 w-7 h-7 mx-auto block"></i>
            </a>
        </div>
    </div>
    <div class="intro-y col-span-12 mt-2 flex flex-wrap items-center sm:flex-nowrap px-3">
        <div class="mt-3 w-full sm:ml-auto sm:mt-0 md:ml-0 flex justify-between">
            <div class="relative w-full text-slate-500">
                <input type="search" placeholder="Search..."
                    class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 box w-full pr-10">
                <i data-lucide="search" class="stroke-1.5 absolute inset-y-0 right-0 my-auto mr-3 h-4 w-4"></i>
            </div>
            <select class="transition duration-200 ease-in-out w-44 text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 box ml-2">
                <option value="">Status</option>
                <option value="Approved"> Active </option>
                <option value="Unapproved">Inactive</option>
            </select>
        </div>
    </div>
    <div class="mt-5 group_owner_tab overflow-auto" id="pharmacy-list">
        @foreach ($pharmacies as $ph)
            <div class="flex justify-between bg-white transition duration-200 ease-in-out transform border-b border-slate-200/60 hover:bg-[#e2f0f5] text-slate-800 items-center [&.active]:bg-[#e2f0f5] [&.active]:text-black {{ $ph['id'] === $framePharmacy['id'] ? 'active' : '' }}">
                <a href="{{ route('pharmacy-portal.patients', ['pharmacy' => $ph['id']]) }}" class="flex items-center gap-4 w-full p-3">
                    <span class="shrink-0 rounded-full bg-gradient-to-t from-[#002B56] to-[#0070D5] text-xl h-[48px] w-[48px] flex justify-center items-center text-white">{{ $ph['initials'] }}</span>
                    <div class="break-all w-full">{{ $ph['name'] }} ({{ $ph['site_code'] }})
                        <div class="mt-0.5 text-xs text-slate-500 flex items-center gap-1">
                            <span class="shrink-0"><i data-lucide="mail" class="w-3 h-3"></i></span>
                            {{ $ph['email'] }}
                        </div>
                        <div class="mt-0.5 text-xs text-slate-500 flex items-center gap-1">
                            <span class="shrink-0"><i data-lucide="phone" class="w-3 h-3"></i></span>
                            {{ $ph['phone'] }}
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
