<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>AppAndTap</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        {{-- Stamped with the file's own mtime so a change is picked up
             immediately instead of a stale copy being served from cache. --}}
        <link rel="stylesheet" href="{{ asset('css/shell.css') }}?v={{ @filemtime(public_path('css/shell.css')) ?: 1 }}">
    </head>
    <body>
        <div class="preview-bar">
            <div class="mode-switch" role="tablist" aria-label="View mode">
                <span class="mode-switch-indicator" aria-hidden="true"></span>
                <button type="button" class="mode-btn is-active" data-mode="web" role="tab" aria-selected="true">Web</button>
                <button type="button" class="mode-btn" data-mode="app" role="tab" aria-selected="false">App</button>
            </div>
        </div>

        <section class="mode-panel is-active" data-panel="web">
            <iframe class="web-frame" src="{{ route('pharmacy-portal.dashboard') }}" title="AppAndTap portal"></iframe>
        </section>

        <section class="mode-panel" data-panel="app">
            <div class="app-stage">
                {{-- The screen cutout in the frame PNG is transparent, so the
                     app sits behind the image and the bezel and Dynamic Island
                     fall over it. The app is driven by tapping inside the phone. --}}
                <div class="phone">
                    <div class="phone-screen">
                        <iframe class="app-frame" title="AppAndTap mobile app" src="{{ route('app.home') }}"></iframe>
                    </div>
                    <img class="phone-shell" src="{{ asset('images/iphone-15-pro.png') }}" alt="iPhone 15 Pro">
                </div>
            </div>
        </section>

        <script src="{{ asset('js/shell.js') }}?v={{ @filemtime(public_path('js/shell.js')) ?: 1 }}" defer></script>
    </body>
</html>
