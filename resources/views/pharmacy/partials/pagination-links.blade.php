@php
    // admin/includes/custom-pagination-links.blade.php: Laravel's own page
    // window, the "Showing x to y of z" line and the per-page select. With
    // $live, pharmacy-portal.js swaps the list in place, as Livewire does.
    $live = $live ?? false;
    $arrow = 'relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-slate-200 leading-5';
    $pageLink = 'relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-slate-200 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150';
    $mobile = 'relative inline-flex items-center px-4 py-2 text-sm font-medium bg-white border border-slate-200 leading-5 rounded-md';
    $prevIcon = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>';
    $nextIcon = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>';
@endphp
<div class="flex w-full justify-between">
    @if ($paginator->count() > 0)
        <nav role="navigation" aria-label="Pagination Navigation" class="w-full sm:mr-auto sm:w-auto">
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

                        @foreach ($elements as $element)
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-slate-200 cursor-default leading-5 select-none">{{ $element }}</span>
                                </span>
                            @endif

                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span>
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-[#dedede] border border-slate-200 cursor-default leading-5 select-none">{{ $page }}</span>
                                            </span>
                                        @else
                                            <a href="{{ $url }}" data-live-page class="{{ $pageLink }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                                        @endif
                                    </span>
                                @endforeach
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
                <div class="pl-20">
                    <p class="text-sm text-gray-700 leading-5">
                        <span>{!! __('Showing') !!}</span>
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('to') !!}</span>
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        <span>{!! __('results') !!}</span>
                    </p>
                </div>
            </div>
        </nav>
        <select class="transition duration-200 ease-in-out text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 box md:mt-3 w-20 sm:mt-0 mr-16" @if ($live) data-live-per-page @endif>
            @foreach ([10, 25, 50, 75, 100] as $size)
                <option @selected($live && $paginator->perPage() === $size)>{{ $size }}</option>
            @endforeach
        </select>
    @endif
</div>
