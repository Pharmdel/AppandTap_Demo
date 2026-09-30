// Keeps the outer page pointed at whichever portal page the iframe is showing.
// The path lives in the URL hash (so refresh, bookmarking and new tabs all
// work) with sessionStorage as a fallback, same as the Pharmdel preview.
const STORE_PAGE = 'appandtap.page';
const STORE_MODE = 'appandtap.mode';
const DEFAULT_PAGE = '/pharmacy/dashboard';

function readStore(key) {
    try {
        return window.sessionStorage.getItem(key);
    } catch (e) {
        return null;
    }
}

function writeStore(key, value) {
    try {
        window.sessionStorage.setItem(key, value);
    } catch (e) {
        // Private browsing / blocked storage — the hash still carries the page.
    }
}

// Only ever accept a same-origin portal path, never a full URL.
function safePath(value) {
    return value && /^\/pharmacy[A-Za-z0-9\-_/?=&.%+ ]*$/.test(value) ? value : null;
}

function currentPage() {
    return safePath(decodeURIComponent(window.location.hash.replace(/^#/, ''))) || safePath(readStore(STORE_PAGE)) || DEFAULT_PAGE;
}

// The app is authored at 393px wide — the iPhone 15 Pro's logical width — so
// it has to be scaled to however wide the frame's screen cutout is drawn.
function fitPhoneScreen() {
    const phone = document.querySelector('.phone');
    const cutout = document.querySelector('.phone-screen');

    if (phone && cutout && cutout.clientWidth) {
        phone.style.setProperty('--screen-scale', cutout.clientWidth / 393);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const modeSwitch = document.querySelector('.mode-switch');
    const frame = document.querySelector('.web-frame');

    if (frame) {
        const startPage = currentPage();
        const srcPath = new URL(frame.getAttribute('src'), window.location.origin).pathname;

        // Only redirect when the remembered page differs from the markup's src,
        // otherwise the first visit would load the dashboard twice.
        if (startPage.split('?')[0] !== srcPath) {
            frame.setAttribute('src', startPage);
        }

        // Also called by the portal when a list re-renders in place (no load).
        window.syncPortalPath = (path) => {
            writeStore(STORE_PAGE, path);
            // replaceState, not pushState: browsing inside the iframe already
            // adds history entries, so this would double them up.
            window.history.replaceState(null, '', window.location.pathname + window.location.search + '#' + path);
        };

        frame.addEventListener('load', () => {
            try {
                const loc = frame.contentWindow.location;
                window.syncPortalPath(loc.pathname + loc.search);
            } catch (e) {
                // Cross-origin — nothing to remember.
            }
        });
    }

    fitPhoneScreen();
    window.addEventListener('resize', fitPhoneScreen);

    if (!modeSwitch) {
        return;
    }

    const modeButtons = modeSwitch.querySelectorAll('.mode-btn');
    const modePanels = document.querySelectorAll('.mode-panel');

    const activate = (mode) => {
        modeButtons.forEach((btn) => {
            const isActive = btn.dataset.mode === mode;
            btn.classList.toggle('is-active', isActive);
            btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        modeSwitch.classList.toggle('is-app', mode === 'app');

        modePanels.forEach((panel) => {
            panel.classList.toggle('is-active', panel.dataset.panel === mode);
        });

        // The phone only has a measurable size once its panel is shown.
        if (mode === 'app') {
            fitPhoneScreen();
        }
    };

    modeButtons.forEach((button) => {
        button.addEventListener('click', () => {
            activate(button.dataset.mode);
            writeStore(STORE_MODE, button.dataset.mode);

            // Also put it in the URL: sessionStorage alone doesn't survive
            // every kind of reload (e.g. a VS Code webview recreating the
            // panel), so the URL is the reliable source of truth on refresh.
            const url = new URL(window.location.href);
            url.searchParams.set('mode', button.dataset.mode);
            window.history.replaceState(null, '', url.pathname + url.search + window.location.hash);
        });
    });

    // ?mode= wins over the remembered mode so either panel can be linked to.
    const queryMode = new URLSearchParams(window.location.search).get('mode');
    const startMode = queryMode || readStore(STORE_MODE);
    if (startMode === 'app' || startMode === 'web') {
        activate(startMode);
    }
});
