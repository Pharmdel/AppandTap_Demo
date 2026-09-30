@extends('layouts.pharmacy')

@php
    // livewire/admin/parked-option/index.blade.php: the reasons a pharmacy can
    // park an IP consultation under. config('global.parked_option_defaults')
    // is fixed; the rest are the pharmacy's own.
    $defaults = ['Uncontactable Patient'];
    $options = [
        ['option_name' => 'Call back later', 'allow_email' => false],
        ['option_name' => 'Awaiting GP response', 'allow_email' => true],
        ['option_name' => 'Medicine on order', 'allow_email' => true],
    ];
    $inputClass = 'option-input w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition';
    $removeClass = 'remove-entry-btn shrink-0 w-8 h-8 flex items-center justify-center rounded-lg text-red-400 hover:text-white hover:bg-red-500 border border-red-200 hover:border-red-500 transition-all duration-150';
    $labelClass = 'block text-xs font-medium text-slate-500 mb-1 uppercase tracking-wide';
@endphp

@section('content-class', 'pt-6')

@section('content')
<div>
    <div class="h-full ml-5">
        <div class="max-w-full md:max-w-none rounded-[30px] md:rounded-none min-w-0 min-h-[calc(100vh-90px)] h-full bg-slate-100 flex-1 pb-10 mt-5 md:mt-1 relative">
            <!-- BEGIN: Form -->
            <form id="optionForm" autocomplete="off">
                <div class="p-5">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <div id="option-entries-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            {{-- Fixed static options - cannot be edited or removed --}}
                            @foreach ($defaults as $defaultOption)
                                <div class="option-entry relative">
                                    <label class="{{ $labelClass }}">
                                        Option Name
                                        <span class="ml-1 text-xs normal-case tracking-normal font-normal text-slate-400">(Default)</span>
                                    </label>
                                    <div class="flex items-center gap-1.5">
                                        <div class="relative w-full">
                                            <input type="text" value="{{ $defaultOption }}" readonly disabled
                                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-400 text-sm cursor-not-allowed select-none pointer-events-none" />
                                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="w-8 shrink-0"></div>
                                    </div>
                                </div>
                            @endforeach
                            @foreach ($options as $i => $option)
                                <div class="option-entry relative">
                                    <label class="{{ $labelClass }}">
                                        Option Name
                                        @if ($i === 0)
                                            <span class="text-red-400">*</span>
                                        @endif
                                    </label>
                                    <div class="flex items-center gap-1.5">
                                        <input type="text" value="{{ $option['option_name'] }}" placeholder="e.g. call later.." @if ($i === 0) required @endif class="{{ $inputClass }}" />
                                        <button type="button" class="{{ $removeClass }}" title="Remove">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                    <label class="flex items-center gap-2 mt-2 cursor-pointer select-none">
                                        <input type="checkbox" value="1" @checked($option['allow_email']) class="option-email-checkbox rounded border-slate-300 text-primary focus:ring-primary focus:ring-opacity-20">
                                        <span class="text-xs text-slate-500">Send email notification</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-100">
                            <button type="button" id="add-option-btn"
                                class="inline-flex items-center gap-1.5 px-4 py-2 transition duration-200 border shadow-sm justify-center rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] border-darkBlue text-white border-lightBlue">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                Add option
                            </button>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center gap-3 justify-end">
                        <a href="{{ route('pharmacy-portal.categorise-options') }}"
                            class="bg-white text-lightBlue border-lightBlue transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer">
                            Cancel
                        </a>
                        <button type="submit" id="submitBtn"
                            class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] border-darkBlue text-white border-lightBlue">
                            Save All options
                        </button>
                    </div>
                </div>
            </form>
            <!-- END: Form -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // The page's own script upstream: add / remove rows, then POST parked-option.store.
    (function () {
        const container = document.getElementById('option-entries-container');
        const removeRow = (row) => {
            row.style.cssText = 'opacity:0;transform:scale(0.97);transition:opacity .15s,transform .15s';
            setTimeout(() => row.remove(), 160);
        };
        container.querySelectorAll('.remove-entry-btn').forEach((btn) => btn.addEventListener('click', () => removeRow(btn.closest('.option-entry'))));

        document.getElementById('add-option-btn').addEventListener('click', () => {
            const div = document.createElement('div');
            div.className = 'option-entry relative';
            div.innerHTML = `
                <label class="{{ $labelClass }}">Option Name</label>
                <div class="flex items-center gap-1.5">
                    <input type="text" placeholder="e.g. call later.." class="{{ $inputClass }}" />
                    <button type="button" class="{{ $removeClass }}" title="Remove">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <label class="flex items-center gap-2 mt-2 cursor-pointer select-none">
                    <input type="checkbox" value="1" class="option-email-checkbox rounded border-slate-300 text-primary focus:ring-primary focus:ring-opacity-20">
                    <span class="text-xs text-slate-500">Send email notification</span>
                </label>`;
            div.querySelector('.remove-entry-btn').addEventListener('click', () => removeRow(div));
            container.appendChild(div);
            div.querySelector('input[type="text"]').focus();
        });

        document.getElementById('optionForm').addEventListener('submit', (event) => {
            event.preventDefault();
            Swal.fire({ title: 'Options saved successfully.', icon: 'success', timer: 1800, showConfirmButton: false });
        });
    })();
</script>
@endpush
