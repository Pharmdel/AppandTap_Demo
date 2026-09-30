@props(['screen'])

@php
    $currentFormat = request()->routeIs('app.*') ? 'app' : 'web';
    $title = \App\Support\ScreenRegistry::get($screen)['title'] ?? '';
    $fromId = request()->query('from');
    $fromTitle = $fromId ? (\App\Support\ScreenRegistry::get($fromId)['title'] ?? null) : null;
@endphp

<div class="portal-switcher fixed top-3 left-1/2 -translate-x-1/2 z-[100] flex flex-col items-center gap-1">
    <div class="flex items-center rounded-full shadow-lg bg-white p-1 border border-slate-200">
        <a href="{{ route('web.'.$screen) }}"
           class="px-4 py-1.5 rounded-full text-sm font-semibold transition {{ $currentFormat === 'web' ? 'bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white' : 'text-slate-500 hover:text-slate-700' }}">
            Web
        </a>
        <a href="{{ route('app.'.$screen) }}"
           class="px-4 py-1.5 rounded-full text-sm font-semibold transition {{ $currentFormat === 'app' ? 'bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white' : 'text-slate-500 hover:text-slate-700' }}">
            App
        </a>
    </div>
    <span class="text-[11px] text-slate-400 bg-white/80 px-2 py-0.5 rounded-full">{{ $title }}</span>
</div>

@if ($fromTitle)
    <div class="portal-switcher fixed top-16 left-1/2 -translate-x-1/2 z-[99] w-[90%] max-w-md">
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-xs rounded-lg px-3 py-2 text-center shadow">
            Showing the closest {{ $currentFormat === 'app' ? 'App' : 'Web' }} equivalent to
            "<strong>{{ $fromTitle }}</strong>" &mdash; the real {{ $currentFormat === 'app' ? 'app' : 'website' }} doesn't have a dedicated screen for this.
        </div>
    </div>
@endif
