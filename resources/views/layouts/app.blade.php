@php
    // Colour of the status-bar strip under the Dynamic Island when the app is
    // shown inside the preview shell's phone frame — matches each screen's top.
    $statusBar = match (true) {
        in_array($appbar ?? 'appbar', ['home-appbar', 'appbar', 'pharmacy-header', 'prescriptions-header', 'reminders-header', 'myday-header'], true),
        in_array($screen ?? null, ['account-menu', 'choose-pharmacy-search'], true) => '#0069c8',
        in_array($screen ?? null, ['pharmacy-first-questionnaire', 'service-questionnaire'], true) => '#0069c8',
        ($screen ?? null) === 'video-conference' => '#006654',
        ($screen ?? null) === 'external-webview' => '#FEF7FF',
        ($appbar ?? null) === 'white-appbar' => '#ffffff',
        default => '#F4F6F5',
    };
    // Below the content on screens without the nav bar (the SafeArea over the
    // home indicator): the page's own colour where it runs edge to edge.
    $safeBottom = match ($screen ?? null) {
        'video-conference' => '#006654',
        'add-dependent' => '#FEF7FF',
        'external-webview', 'pharmacy-gate', 'awaiting-approval', 'chat', 'service-details', 'profile-details', 'find-your-gp', 'request-medication', 'do-you-have-linkage-key', 'login-settings', 'book-appointment', 'reminders-add', 'reminders-order-medicine', 'pharmacy-first-questionnaire', 'service-questionnaire' => '#ffffff',
        default => 'transparent',
    };
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'AppAndTap' }} &middot; AppAndTap App</title>
    {{-- Inside the preview shell the phone frame is drawn by the shell, so the
         app drops its own bezel and switcher and fills the iframe. --}}
    <script>
        if (window.self !== window.top) { document.documentElement.classList.add('embedded'); }
    </script>
    <style>
        .app-status-bar { display: none; }
        html.embedded body { background: transparent; }
        html.embedded .app-stage-local { min-height: 100vh; padding: 0; display: block; }
        html.embedded .app-device { width: 100vw; height: 100vh; border: 0; border-radius: 0; box-shadow: none; }
        html.embedded .app-screen { border-radius: 0; }
        html.embedded .app-notch { display: none; }
        /* iPhone 15 Pro safe-area insets: 59pt under the Dynamic Island, 34pt
           over the home indicator (the nav bar's SafeArea fills it white).
           The strip matches each screen's top colour, so it overlaps the
           header by 1px to hide the seam the preview's down-scaling leaves. */
        html.embedded .app-status-bar { display: block; position: relative; z-index: 1; height: 60px; margin-bottom: -1px; flex-shrink: 0; }
        html.embedded .app-bottom-safe { padding-bottom: 34px; }
        .app-safe-bottom { display: none; }
        html.embedded .app-safe-bottom { display: block; height: 34px; flex-shrink: 0; }
    </style>
    @include('partials.tailwind-cdn')
</head>
<body class="bg-slate-200 min-h-screen">

    <div class="app-stage-local min-h-screen flex items-center justify-center py-10">
        <div class="app-device relative w-[390px] h-[844px] rounded-[44px] border-[10px] border-black shadow-2xl bg-black overflow-hidden">
            <div class="app-notch absolute top-0 left-1/2 -translate-x-1/2 w-[120px] h-[28px] bg-black rounded-b-2xl z-10"></div>

            <div class="app-screen absolute inset-[0px] rounded-[34px] overflow-hidden bg-bodyBgColor flex flex-col font-DMSans">
                <div class="app-status-bar" style="background-color: {{ $statusBar }}"></div>

                @if (($appbar ?? 'appbar') === 'home-appbar')
                    <x-app.home-appbar />
                @elseif (($appbar ?? 'appbar') === 'pharmacy-header')
                    <x-app.pharmacy-header />
                @elseif (in_array($appbar ?? 'appbar', ['prescriptions-header', 'reminders-header', 'myday-header'], true))
                    {{-- Screens whose AppBar is their own (title + dependent picker, tabs, ...). --}}
                    <x-dynamic-component :component="'app.'.$appbar" />
                @elseif (($appbar ?? 'appbar') === 'white-appbar')
                    <x-app.white-appbar :title="$appTitle ?? ''" />
                @elseif (($appbar ?? 'appbar') === 'appbar')
                    <x-app.appbar :title="$appTitle ?? $title ?? ''" />
                @endif

                {{-- Positioned, so a page's absolute bottom-* buttons sit above the
                     safe area rather than on the phone's curved bottom edge. --}}
                <div class="flex-1 min-h-0 relative flex flex-col">
                    <div class="flex-1 overflow-y-auto">
                        @yield('content')
                    </div>
                </div>

                @if (($bottomNav ?? 'none') !== 'none')
                    <x-app.bottom-nav :active="$bottomNav" />
                @else
                    <div class="app-safe-bottom" style="background-color: {{ $safeBottom }}"></div>
                @endif

                {{-- Page dialogs cover the whole screen, above the bars. --}}
                @stack('overlays')
            </div>
        </div>
    </div>

    <script>
        if (window.lucide) { lucide.createIcons(); }

        // In-phone dialogs (showDialog upstream): [data-open="#id"] shows one,
        // [data-close] hides the dialog it sits in, and [data-toast] shows a
        // Fluttertoast-style message at the bottom of the screen.
        document.addEventListener('click', (event) => {
            const opener = event.target.closest('[data-open]');
            if (opener) { document.querySelector(opener.dataset.open)?.classList.remove('hidden'); return; }
            const closer = event.target.closest('[data-close]');
            if (closer) { closer.closest('.absolute.inset-0')?.classList.add('hidden'); return; }
            const toaster = event.target.closest('[data-toast]');
            if (toaster) {
                const toast = document.createElement('div');
                toast.textContent = toaster.dataset.toast;
                toast.className = 'absolute left-1/2 -translate-x-1/2 bottom-[70px] z-[60] max-w-[80%] px-4 py-2 rounded-[20px] bg-black/80 text-white text-[14px] text-center';
                document.querySelector('.app-screen').appendChild(toast);
                setTimeout(() => toast.remove(), 2000);
            }
        });
    </script>
</body>
</html>
