{{-- ip-consultation-request.blade.php: the request table (widget and popup). --}}
<table class="w-full text-left border-collapse">
    <thead class="sticky bg-white top-[-1px]">
        <tr>
            <th class="{{ $th }}">Patient/DOB</th>
            <th class="{{ $th }}">Request Date</th>
            <th class="{{ $th }}">Appointment Date</th>
            <th class="{{ $th }}">Service</th>
            <th class="{{ $th }} whitespace-nowrap">Clinician</th>
            <th class="{{ $th }} whitespace-nowrap">Status</th>
            <th class="{{ $th }} whitespace-nowrap">Payment Status</th>
            <th class="{{ $th }} text-center whitespace-nowrap">Action</th>
        </tr>
    </thead>
    <tbody class="">
        @foreach ($ipConsultations as $c)
            <tr class="" data-ip-consultation="{{ $c['id'] }}">
                <td class="py-3 px-2 text-xs leading-none text-twilight-blue font-bold align-top">
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-1">{{ $c['patient'] }}</div>
                        <span class="text-gray-400 font-normal">{{ $c['dob'] }}</span>
                    </div>
                </td>
                <td class="py-3 px-2 text-xs leading-none text-twilight-blue font-semibold align-top ">{{ $c['request_date'] }}</td>
                <td class="py-3 px-2 text-xs leading-none text-twilight-blue font-semibold align-top ">{{ $c['appointment_date'] }}</td>
                <td class="py-3 px-2 text-xs leading-none text-twilight-blue font-semibold text-center--">{{ $c['service'] }}<br><br></td>
                <td class="py-3 px-2 text-xs leading-none text-twilight-blue font-semibold text-center--">{{ $c['clinician'] }} ({{ $c['gphc_number'] }})</td>
                <td class="py-3 px-2 text-xs leading-none text-twilight-blue font-semibold text-center--" data-ip-status>{{ $c['status'] }}</td>
                <td class="py-3 px-2 text-xs leading-none text-twilight-blue font-semibold text-center--">{{ $c['payment_status'] }}</td>
                <td class="py-3 px-2 text-xs leading-none text-twilight-blue font-semibold text-center">
                    <div class="flex justify-center gap-2">
                        <div class="">
                            <div class="flex justify-center gap-2">
                                <button type="button" data-popup-open="ip-view-{{ $c['id'] }}"
                                    class="h[42px] transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] border-darkBlue text-white">View</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
