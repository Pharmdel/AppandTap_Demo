@php
    // AppServiceProvider's composer on the real site: a group colour turns on
    // white-labelling; without one the portal keeps the AppAndTap defaults.
    $groupOwner = $demo['staff_group_owner'];
    $isWhiteLabel = ! empty($groupOwner['color']);
    $colorCode = $groupOwner['color'] ?: '#007ac2';
    $bgGradient = $isWhiteLabel ? '' : 'bg_gradient';
    $logo = $groupOwner['logo'];
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $groupOwner['name'] }} | {{ $title ?? 'DashBoard' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    {{-- The live site's own compiled stylesheets, in the order mainsite.blade.php loads them. --}}
    <link rel="stylesheet" href="{{ asset('admin/css/side-nav.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/mobile-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/patient.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/dataTables.tailwindcss.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pharmacy-portal.css') }}?v={{ @filemtime(public_path('css/pharmacy-portal.css')) ?: 1 }}">
    @if ($isWhiteLabel)
        @include('pharmacy.partials.white-label')
    @endif
</head>

<body class="font-Poppins">
    <div
        class="enigma py-5 px-5 md:py-0 sm:px-8 md:px-0 before:content-[''] before:bg-gradient-to-b before:from-theme-1 before:to-theme-2 dark:before:from-darkmode-800 dark:before:to-darkmode-800 md:before:bg-none bg-[#222222]/80 md:dark:bg-[#2a2a2a] before:fixed before:inset-0 before:z-[-1]">
        <!-- BEGIN: Top Bar -->
        <x-pharmacy.topbar :title="$title ?? 'Dashboard'" :portal="$portal" :pharmacy="$currentPharmacy ?? null" />
        <!-- END: Top Bar -->
        <div class="flex overflow-visible">
            <!-- BEGIN: Side Menu -->
            <nav class="side-nav no-scroll px-5 -mt-4 hidden w-[100px] overflow-x-hidden pb-16 pt-24-- md:block xl:w-[260px] wdsm leftpart {{ $bgGradient }} !z-[99]"
                style="background: {{ $colorCode }};">
                <x-pharmacy.sidebar :active-tab="$activeTab ?? null" :portal="$portal" :logo="$logo" />
            </nav>
            <!-- END: Side Menu -->
            <div
                class="rightpart max-w-full md:max-w-none rounded-[30px] md:rounded-none px-4 md:px-[22px] min-w-0 min-h-screen bg-slate-100 flex-1 md:pt-14 pb-10 md:mt-1 relative dark:bg-lightgreyclr before:content-[''] before:w-full before:h-px before:block">
                @if ($showFrame ?? false)
                    {{-- Group Owner's pharmacy.show (admin/pharmacy/index.blade.php):
                         pharmacy list on the left, tabs on the right. --}}
                    <div class="h-full mt-5 md:mt-1 md:pt-6">
                        <div class="grid grid-cols-12 gap-6 h-full">
                            <div class="col-span-12 lg:col-span-3 2xl:col-span-3 border-r border-slate-300 sticky top-[100px] max-h-[calc(100vh-100px)] z-[1]">
                                @include('pharmacy.partials.pharmacy-list')
                            </div>
                            <div class="col-span-12 lg:col-span-9 2xl:col-span-9">
                                @include('pharmacy.partials.pharmacy-show-header')
                                <div class="tab-content">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="@yield('content-class', 'px-6 py-6')">
                        @yield('content')
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    {{-- mainsite.blade.php loads SweetAlert2 v11 for every confirmation. --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/pharmacy-portal.js') }}?v={{ @filemtime(public_path('js/pharmacy-portal.js')) ?: 1 }}"></script>
    @stack('scripts')
    <script>
        if (window.lucide) { lucide.createIcons(); }
    </script>
</body>

</html>
