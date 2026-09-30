@props(['title' => null, 'portal' => 'group-owner', 'pharmacy' => null])

@php
    $groupOwner = $demo['staff_group_owner'];
    $isGroupOwner = $portal === 'group-owner';
    $pharmacy ??= $demo['staff_pharmacies'][0];
    $isDashboard = $title === 'Dashboard';
@endphp

<div class="h-[70px] md:h-[65px] z-[99] bg-slate-100 border-b border-white/[0.08] mt-12 md:mt-0 -mx-5 sm:-mx-8 md:-mx-0 px-3 md:border-b-0 relative md:fixed md:inset-x-0 md:top-0 sm:px-8 md:px-10 md:pt-2 dark:md:from-[#222] after:absolute after:inset-0 after:h-[65px] after:mx-3 after:mt-1 after:rounded-xl after:hidden after:md:block after:dark:bg-darkmode-600 after:-z-10 duration-200 topbarHover">
    <div class="flex h-full items-center">
        <!-- BEGIN: Breadcrumb -->
        <nav aria-label="breadcrumb" class="flex h-[45px] md:ml-10 md:border-l border-white/[0.08] dark:border-white/[0.08] mr-auto -intro-x md:pl-6">
            <ol class="flex items-center font-bold dark:text-slate-300 text-darkBlue">
                <li class="{{ $isDashboard ? 'text-xl' : 'text-sm font-medium' }}">
                    <a href="{{ route('pharmacy-portal.dashboard') }}">Dashboard</a>
                </li>
                @unless ($isDashboard)
                    <li class="relative text-xl ml-1.5 pl-0.5 text-darkBlue">
                        <a href="javascript:void(0);">/ {{ $title }}</a>
                    </li>
                @endunless
            </ol>
        </nav>
        <!-- END: Breadcrumb -->

        {{-- The header's "name (site code) / role" label, made the portal switcher:
             the Group Owner plus each of its pharmacies. --}}
        <div id="portalDropdown" class="relative">
            <button type="button" id="portalDropdownButton" aria-haspopup="listbox" aria-expanded="false" class="flex items-center gap-2 text-left">
                <span>
                    @if ($isGroupOwner)
                        <span class="font-bold mr-2 text-darkBlue block">{{ $groupOwner['name'] }} ({{ $groupOwner['site_code'] }})</span>
                        <span class="text-xs font-medium mr-2 text-darkBlue block text-right">Group Owner</span>
                    @else
                        <span class="font-bold mr-2 text-darkBlue block">{{ $pharmacy['name'] }} ({{ $pharmacy['site_code'] }})</span>
                        <span class="text-xs font-medium mr-2 text-darkBlue block text-right">Pharmacy</span>
                    @endif
                </span>
                <i id="portalDropdownArrow" data-lucide="chevron-down" class="stroke-1.5 w-4 h-4 text-darkBlue mr-3 transition-transform duration-200"></i>
            </button>

            <div id="portalDropdownList" role="listbox" class="hidden absolute right-3 top-full mt-2 w-[340px] rounded-md bg-white shadow-[0px_3px_10px_#00000017] overflow-hidden z-[9999]">
                <div class="p-2 border-b border-slate-200">
                    <input type="text" id="portalDropdownSearch" placeholder="Search pharmacy..."
                           class="w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 px-3 py-2">
                </div>
                <div class="max-h-[280px] overflow-auto">
                    <a href="{{ route('pharmacy-portal.switch', 'group-owner') }}" role="option" aria-selected="{{ $isGroupOwner ? 'true' : 'false' }}"
                       class="portal-option block px-4 py-2 border-b border-slate-200 hover:bg-skyBlueClr {{ $isGroupOwner ? 'bg-skyBlueClr' : '' }}">
                        <span class="block font-semibold text-darkBlue">{{ $groupOwner['name'] }}</span>
                        <span class="block text-xs text-slate-500">Group Owner</span>
                    </a>
                    @foreach ($demo['staff_pharmacies'] as $ph)
                        @php $isSelected = ! $isGroupOwner && $ph['id'] === $pharmacy['id']; @endphp
                        <a href="{{ route('pharmacy-portal.switch', ['pharmacy', $ph['id']]) }}" role="option" aria-selected="{{ $isSelected ? 'true' : 'false' }}"
                           class="portal-option block px-4 py-2 border-b border-slate-200 last:border-b-0 hover:bg-skyBlueClr {{ $isSelected ? 'bg-skyBlueClr' : '' }}">
                            <span class="block font-semibold text-slate-800">{{ $ph['name'] }} ({{ $ph['site_code'] }})</span>
                            <span class="block text-xs text-slate-500">{{ $ph['address'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <span class="font-bold mr-4 text-slate-100">
            <a href="javascript:void(0);" onclick="document.getElementById('helpdeskModal').showModal()"
               class="transition duration-200 border shadow-sm inline-flex items-center justify-center px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] border-darkBlue text-white border-blue"
               style="padding-block:5px;">Help Desk</a>
        </span>

        <!-- BEGIN: Notifications (Pharmacy role only in the source) -->
        @unless ($isGroupOwner)
            <div class="relative intro-x mr-4 sm:mr-6 js-header-dropdown">
                <div class="js-header-dropdown-toggle cursor-pointer relative bg-redclr flex justify-center items-center h-8 w-8 rounded-full text-white outline-none">
                    <i data-lucide="bell" class="stroke-1.5 w-5 h-5 text-white"></i>
                </div>
                <sup class="absolute -top-2.5 -right-2 bg-white flex justify-center items-center rounded-full hdr_sup">{{ count($demo['staff_notifications']) }}</sup>
                <div class="js-header-dropdown-menu hidden absolute right-0 top-full mt-2 z-[9999]">
                    <div class="rounded-md bg-white shadow-[0px_3px_10px_#00000017] w-[280px] overflow-hidden">
                        <div class="flex justify-between items-center bg-gradient-to-t from-[#002B56] to-[#0070D5] p-2">
                            <div class="font-medium text-white">Notifications</div>
                            <div class="w-10 h-10 rounded-full flex justify-center items-center bg-[#8CD086]">
                                <img src="{{ asset('admin/images/messenger-notify.svg') }}" alt="">
                            </div>
                        </div>
                        <div class="overflow-auto no-scroll max-h-[205px] h-full odd_even">
                            @foreach ($demo['staff_notifications'] as $n)
                                <a href="{{ route('pharmacy-portal.notifications') }}" class="cursor-pointer relative flex items-center p-2 border-b border-slate-200">
                                    <div class="overflow-hidden w-full">
                                        <div class="flex gap-3 items-center">
                                            <span class="w-10 h-10 flex justify-center items-center shrink-0">
                                                <img src="{{ asset('admin/images/profile.png') }}" alt="" class="h-full w-full object-cover">
                                            </span>
                                            <div class="min-w-0">
                                                <span class="mr-5 truncate font-medium block">{{ $n['message'] }}</span>
                                                <div class="whitespace-nowrap text-xs text-green">{{ $n['datetime'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endunless
        <!-- END: Notifications -->

        <!-- BEGIN: Account Menu -->
        <div class="relative js-header-dropdown">
            <button type="button" class="js-header-dropdown-toggle cursor-pointer image-fit zoom-in intro-x block h-8 w-8 scale-110 overflow-hidden rounded-full shadow-lg">
                <img src="{{ asset('images/no-images-avtaar.jpg') }}" alt="{{ $isGroupOwner ? $groupOwner['name'] : $pharmacy['name'] }}">
            </button>
            <div class="js-header-dropdown-menu hidden absolute right-0 top-full mt-1 z-[9999]">
                <div class="rounded-md p-2 shadow-[0px_3px_10px_#00000017] relative mt-px w-56 bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">
                    <a class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-white hover:text-darkBlue" href="{{ route('pharmacy-portal.info') }}"><i data-lucide="user" class="stroke-1.5 mr-2 h-4 w-4"></i>Profile</a>
                    <a class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-white hover:text-darkBlue" href="javascript:void(0);" onclick="document.getElementById('changePasswordModal').showModal()"><i data-lucide="lock" class="stroke-1.5 mr-2 h-4 w-4"></i>Change Password</a>
                    @unless ($isGroupOwner)
                        <a class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-white hover:text-darkBlue" href="{{ route('pharmacy-portal.settings') }}"><i data-lucide="settings" class="stroke-1.5 mr-2 h-4 w-4"></i>System Setting</a>
                    @endunless
                    <div class="h-px my-2 -mx-2 bg-white/[0.08]"></div>
                    <a class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-white hover:text-darkBlue" href="javascript:void(0);"><i data-lucide="toggle-right" class="stroke-1.5 mr-2 h-4 w-4"></i>Logout</a>
                </div>
            </div>
        </div>
        <!-- END: Account Menu -->
    </div>
</div>

<dialog id="helpdeskModal" class="rounded-md p-0 w-[460px] max-w-[90vw] backdrop:bg-black/60">
    <div class="flex justify-between items-center bg-gradient-to-t from-[#002B56] to-[#0070D5] px-5 py-3">
        <h2 class="font-medium text-base text-white">Help Desk</h2>
        <button type="button" onclick="document.getElementById('helpdeskModal').close()" class="text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
    </div>
    <div class="p-5 flex flex-col gap-3">
        <label class="font-semibold inline-block">Subject</label>
        <input type="text" class="w-full text-sm border-slate-200 shadow-sm rounded-md px-4 py-3">
        <label class="font-semibold inline-block">Message</label>
        <textarea rows="4" class="w-full text-sm border-slate-200 shadow-sm rounded-md px-4 py-3"></textarea>
        <div class="flex justify-end gap-2 mt-2">
            <button type="button" onclick="document.getElementById('helpdeskModal').close()" class="bg-white text-lightBlue border-lightBlue border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium">Cancel</button>
            <button type="button" onclick="document.getElementById('helpdeskModal').close()" class="border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium bg-gradient-to-t from-[#002B56] to-[#0070D5] border-darkBlue text-white">Submit</button>
        </div>
    </div>
</dialog>

<dialog id="changePasswordModal" class="rounded-md p-0 w-[460px] max-w-[90vw] backdrop:bg-black/60">
    <div class="flex justify-between items-center bg-gradient-to-t from-[#002B56] to-[#0070D5] px-5 py-3">
        <h2 class="font-medium text-base text-white">Change Password</h2>
        <button type="button" onclick="document.getElementById('changePasswordModal').close()" class="text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
    </div>
    <div class="p-5 flex flex-col gap-3">
        @foreach (['Current password', 'New password', 'Confirm password'] as $field)
            <label class="font-semibold inline-block">{{ $field }}</label>
            <input type="password" class="w-full text-sm border-slate-200 shadow-sm rounded-md px-4 py-3">
        @endforeach
        <div class="flex justify-end gap-2 mt-2">
            <button type="button" onclick="document.getElementById('changePasswordModal').close()" class="bg-white text-lightBlue border-lightBlue border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium">Cancel</button>
            <button type="button" onclick="document.getElementById('changePasswordModal').close()" class="border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium bg-gradient-to-t from-[#002B56] to-[#0070D5] border-darkBlue text-white">Save</button>
        </div>
    </div>
</dialog>

<script>
    (function () {
        // Portal switcher: open/close, search, close on outside click / Escape.
        const root = document.getElementById('portalDropdown');
        if (root) {
            const button = document.getElementById('portalDropdownButton');
            const list = document.getElementById('portalDropdownList');
            const search = document.getElementById('portalDropdownSearch');
            const arrow = () => document.getElementById('portalDropdownArrow');
            const setOpen = (open) => {
                list.classList.toggle('hidden', !open);
                button.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (arrow()) { arrow().style.transform = open ? 'rotate(180deg)' : ''; }
                if (open) { search.focus(); }
            };
            button.addEventListener('click', (e) => { e.stopPropagation(); setOpen(list.classList.contains('hidden')); });
            search.addEventListener('input', () => {
                const q = search.value.toLowerCase();
                list.querySelectorAll('.portal-option').forEach((o) => o.classList.toggle('hidden', !o.textContent.toLowerCase().includes(q)));
            });
            document.addEventListener('click', (e) => { if (!root.contains(e.target)) { setOpen(false); } });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { setOpen(false); } });
        }

        // Bell and account menus (Enigma's data-tw-toggle dropdowns upstream).
        document.querySelectorAll('.js-header-dropdown').forEach((dropdown) => {
            const menu = dropdown.querySelector('.js-header-dropdown-menu');
            dropdown.querySelector('.js-header-dropdown-toggle').addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.js-header-dropdown-menu').forEach((m) => { if (m !== menu) { m.classList.add('hidden'); } });
                menu.classList.toggle('hidden');
            });
            document.addEventListener('click', (e) => { if (!dropdown.contains(e.target)) { menu.classList.add('hidden'); } });
        });
    })();
</script>
