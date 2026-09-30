@php
    // admin/includes/prescription/repeat-medicine-orders.blade.php. The
    // "Patient Info" column is dropped on a patient's own tab (?type= set).
    $withPatient = $withPatient ?? true;
    $cell = 'p-3 border-b box rounded-l-none rounded-r-none border-x-0 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r';
    $pill = fn (string $status) => match ($status) {
        // "duration-200inline-flex" is upstream's own typo, so Requested renders as a block.
        'Requested' => 'transition duration-200inline-flex items-center justify-center py-1.5 px-4 rounded-full font-medium cursor-pointer border-orange text-orange w-auto',
        'Issued' => 'transition duration-200 inline-flex items-center justify-center py-1.5 px-4 rounded-full font-medium cursor-pointer border-green text-green w-auto',
        default => 'transition duration-200 inline-flex items-center justify-center py-1.5 px-4 rounded-full font-medium cursor-pointer border-redclr text-redclr w-auto',
    };
@endphp
@if (count($data))
    <table class="w-full text-left border-separate border-spacing-y-[10px] pt-6" id="example">
        <thead>
            <tr>
                @if ($withPatient)
                    <th class="font-semibold p-3 whitespace-nowrap border-b-0">Patient Info</th>
                @endif
                <th class="font-semibold p-3 whitespace-nowrap border-b-0">Medicine</th>
                <th class="font-semibold p-3 whitespace-nowrap border-b-0 text-center">Date Requested</th>
                <th class="font-semibold p-3 whitespace-nowrap border-b-0 text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $order)
                <tr class="intro-x transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded text-slate-800">
                    @if ($withPatient)
                        <td class="{{ $cell }} w-64">
                            <div class="flex items-center space-x-2">
                                <img src="{{ asset('admin/images/medicine.svg') }}" alt="">
                                <label class="inline-block font-medium cursor-pointer">{{ $order['patient'] }}<br> {{ $order['email'] }}<br> {{ $order['phone'] }}</label>
                            </div>
                        </td>
                    @endif
                    <td class="{{ $cell }}">
                        <div class="flex items-center space-x-2 whitespace-break-spaces">
                            <label class="inline-block font-medium cursor-pointer">{{ $order['medicine'] }}</label>
                        </div>
                    </td>
                    <td class="{{ $cell }} w-64">
                        <div class="flex space-x-1 items-center justify-center">
                            <img src="{{ asset('admin/images/stop-watch.svg') }}" alt="">
                            <span>{{ $order['date_requested'] }}</span>
                        </div>
                    </td>
                    <td class="{{ $cell }} w-56 text-center">
                        <div class="{{ $pill($order['status']) }}">{{ $order['status'] }}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @push('scripts')
        {{-- mainsite.blade.php loads jQuery on every page; only this table needs it here. --}}
        <script src="{{ asset('admin/js/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('admin/js/dataTables-new.js') }}"></script>
        <script src="{{ asset('admin/js/dataTables.tailwindcss.js') }}"></script>
        <script>
            new DataTable('#example', {
                ordering: false,
                layout: {
                    bottomStart: { pageLength: { menu: [10, 25, 50] } },
                    bottomEnd: { paging: { firstLast: false } },
                    topStart: null,
                },
                initComplete: function () {
                    if (this.api().page.info().recordsTotal <= 10) {
                        $('.pagination').hide();
                    }
                },
            });
        </script>
    @endpush
@else
    <div>
        <div class="p-6 transition duration-200 ease-in-out transform cursor-pointer border-b border-slate-200/60 hover:scale-[1.02] hover:relative hover:z-20 hover:shadow-md hover:border hover:rounded bg-white text-slate-800">
            <img class="mx-auto" src="{{ asset('admin/images/no-record-found-new.png') }}" alt="">
        </div>
    </div>
@endif
