<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'AppAndTap' }} &middot; AppAndTap Web</title>
    @include('partials.tailwind-cdn')
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="font-Poppins bg-[#F5F7FA] min-h-screen">

    <x-portal-switcher :screen="$screen" />

    <div class="flex">
        <x-web.sidebar />

        <div class="flex-1 min-w-0">
            <x-web.topbar />

            <main class="p-6 pt-10">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        if (window.lucide) { lucide.createIcons(); }
    </script>
</body>
</html>
