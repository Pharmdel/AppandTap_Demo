@extends('layouts.pharmacy')

@php
    // livewire/admin/broadcast/create.blade.php (a Group Owner's own page is
    // group-owner-broadcast-create.blade.php, which adds a pharmacy picker).
    // The selects are jquery.multiselect / select2 upstream.
    $groupCreate = $isGroupOwner && ! $showFrame;
    $select = 'transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md focus:ring-4 focus:ring-primary focus:ring-opacity-20 bg-white';
    $filters = [
        'age' => ['Select Age', ['1-18' => 'Under 18', '18-25' => '18-24', '24-35' => '25-34', '35-45' => '35-44', '45-55' => '45-54', '55-65' => '55-64', '65-200' => 'Above 65']],
        'gender' => ['Select Gender', ['Male' => 'Male', 'Female' => 'Female', 'Indeterminate' => 'Indeterminate', 'Not known' => 'Not known']],
        'lastorder' => ['Last Ordered', ['0-0' => 'Never ordered', '0-30' => 'Ordered 0-29 days ago', '30-60' => 'Ordered 30-59 days ago', '60-90' => 'Ordered 60-89 days ago', '90-180' => 'Ordered 90-179 days ago', '180-500' => 'Ordered 180+ days ago']],
        'medicine' => ['Search Medicine....', collect($demo['staff_repeat_order_patients'])->pluck('medicines')->flatten(1)->pluck('medicine', 'medicine')->all()],
    ];
    if ($groupCreate) {
        $filters = ['pharmacy' => ['Select Pharmacy', collect($demo['staff_pharmacies'])->pluck('name', 'id')->all()]] + $filters;
    }
    $reach = $demo['staff_dashboard_stats']['active_patients'];
    $backUrl = route('pharmacy-portal.broadcast', array_filter(['tab' => $showFrame ? 1 : null, 'pharmacy' => $showFrame ? $framePharmacy['id'] : null]));
@endphp

@unless ($showFrame)
    @section('content-class', 'pt-6')
@endunless

@section('content')
<div>
    <div class="block">
        <form id="broadcastForm" autocomplete="off" novalidate>
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg overflow-hidden">
                    <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] px-5 py-2 flex items-center justify-between">
                        <div class="flex items-center">
                            <button type="button" class="text-white inline-flex items-center justify-center py-2 px-3">Create Broadcast</button>
                        </div>
                        <div class="text-white">
                            <span>You are reaching :</span>
                            <span class="border border-white bg-white/20 px-3 py-1 inline-block ml-4" data-reach>{{ $reach }} Patients</span>
                        </div>
                    </div>
                    <div>
                        <div class="w-full leading-relaxed active visible opacity-100">
                            <div class="grid grid-cols-3 lg:grid-cols-4 gap-5 px-5 transition duration-200 ease-in-out transform cursor-pointer bg-white text-slate-800 pt-4">
                                @foreach ($filters as $name => [$placeholder, $options])
                                    <div>
                                        <select class="{{ $select }}" name="{{ $name }}" data-broadcast-filter>
                                            <option value="">{{ $placeholder }}</option>
                                            @foreach ($options as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                                <div class="col-span-full ">
                                    <label for="message" class="font-semibold">Message</label>
                                    <textarea class="mt-5 p-4 w-full border border-slate-200 rounded-lg" name="message" id="message" rows="3" maxlength="255"></textarea>
                                    <div class="error-message text-danger text-sm hidden" data-message-error>Please enter message</div>
                                </div>
                                <div class="col-span-full ">
                                    <div class="opacity-50 pointer-events-none select-none">
                                        <input type="checkbox" name="send_mail" value="1" disabled>
                                        <label class="font-semibold ml-2">Send Email</label>
                                    </div>
                                </div>
                                <div class=" pb-5">
                                    <button type="submit" class="transition duration-200 inline-flex items-center justify-center py-2 px-5 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white mr-2 shadow-md">Send Broadcast</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const total = {{ $reach }};
        // Each filter narrows the audience ("You are reaching") the way the Livewire counts do.
        document.querySelectorAll('[data-broadcast-filter]').forEach((select) => select.addEventListener('change', () => {
            const active = [...document.querySelectorAll('[data-broadcast-filter]')].filter((s) => s.value !== '').length;
            document.querySelector('[data-reach]').textContent = Math.max(0, Math.round(total / Math.pow(2.6, active))) + ' Patients';
        }));

        document.getElementById('broadcastForm').addEventListener('submit', (event) => {
            event.preventDefault();
            const message = document.getElementById('message');
            const missing = message.value.trim() === '';
            document.querySelector('[data-message-error]').classList.toggle('hidden', !missing);
            message.classList.toggle('invalid-field', missing);
            if (missing) { return; }
            Swal.fire({ title: 'Broadcast sent successfully.', icon: 'success', timer: 1600, showConfirmButton: false })
                .then(() => { window.location = @json($backUrl); });
        });
    })();
</script>
@endpush
