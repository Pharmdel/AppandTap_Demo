@php
    // admin/includes/custom-pagination-left-links.blade.php (the Patients
    // sidebar). Upstream always lists 1, 2, ..., last-1, last once there are
    // more than 4 pages, whichever page is open. Livewire's wire:click buttons
    // are links here; pharmacy-portal.js swaps the list in place, as Livewire does.
    $current = $paginator->currentPage();
    $last = $paginator->lastPage();
    $pages = $last <= 4 ? range(1, $last) : [1, 2, '...', $last - 1, $last];
    $arrow = 'relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 leading-5';
    $pageLink = 'relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150';
    $mobile = 'relative inline-flex items-center px-4 py-2 text-sm font-medium bg-white border border-gray-300 leading-5 rounded-md';
    $prevIcon = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>';
    $nextIcon = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>';
@endphp
<div class="flex w-full justify-between">
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="w-full sm:mx-auto sm:w-auto">
            <div class="flex justify-between flex-1 sm:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <span class="{{ $mobile }} text-gray-500 cursor-default select-none">{!! __('pagination.previous') !!}</span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" data-live-page class="{{ $mobile }} text-gray-700 hover:text-gray-500">{!! __('pagination.previous') !!}</a>
                    @endif
                </span>
                <span>
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" data-live-page class="{{ $mobile }} ml-3 text-gray-700 hover:text-gray-500">{!! __('pagination.next') !!}</a>
                    @else
                        <span class="{{ $mobile }} ml-3 text-gray-500 cursor-default select-none">{!! __('pagination.next') !!}</span>
                    @endif
                </span>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <span class="relative z-0 inline-flex rounded-md shadow-sm">
                        <span>
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <span class="{{ $arrow }} cursor-default rounded-l-md" aria-hidden="true">{!! $prevIcon !!}</span>
                                </span>
                            @else
                                <a href="{{ $paginator->previousPageUrl() }}" data-live-page class="{{ $arrow }} rounded-l-md hover:text-gray-400 focus:z-10 focus:outline-none active:bg-gray-100 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">{!! $prevIcon !!}</a>
                            @endif
                        </span>

                        @foreach ($pages as $page)
                            @if ($page === '...')
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5 select-none">...</span>
                                </span>
                            @else
                                <span>
                                    @if ($page == $current)
                                        <span aria-current="page">
                                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-[#dedede] border border-gray-300 cursor-default leading-5 select-none">{{ $page }}</span>
                                        </span>
                                    @else
                                        <a href="{{ $paginator->url($page) }}" data-live-page class="{{ $pageLink }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                                    @endif
                                </span>
                            @endif
                        @endforeach

                        <span>
                            @if ($paginator->hasMorePages())
                                <a href="{{ $paginator->nextPageUrl() }}" data-live-page class="{{ $arrow }} -ml-px rounded-r-md hover:text-gray-400 focus:z-10 focus:outline-none active:bg-gray-100 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">{!! $nextIcon !!}</a>
                            @else
                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                    <span class="{{ $arrow }} -ml-px cursor-default rounded-r-md" aria-hidden="true">{!! $nextIcon !!}</span>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
