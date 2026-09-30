@php
    // appointment-detail.blade.php: each status gets its own buttons. All
    // three sets are rendered so appointmentUpdate() can swap them in place
    // once a Confirm/Cancel is accepted.
    $isPopup = $isPopup ?? false;
    $td = 'py-3 px-2 text-xs leading-none text-twilight-blue font-semibold';
    $btnPrimary = 'transition text-[10px] duration-200 border shadow-sm inline-flex items-center justify-center py-1 px-2 rounded-md font-medium cursor-pointer hover:bg-opacity-90 hover:border-opacity-90 text-center bg-gradient-to-t from-[#002B56] to-[#0070D5] border-darkBlue text-white';
    $btnCancel = 'bg-white text-[10px] text-lightBlue border-lightBlue transition duration-200 border shadow-sm inline-flex items-center justify-center py-1 px-2 rounded-md font-medium cursor-pointer';
    $flag = $isPopup ? 'true' : 'false';
    $actionSets = [
        'Pending Approval' => [['Confirm', 'booked'], ['Cancel', 'cancel']],
        'Approved' => [['Resend booking email', 'resend'], ['Cancel', 'cancel']],
        'Cancelled' => [['Confirm', 'booked']],
    ];
@endphp
<tr class="" data-appointment="{{ $appt['id'] }}">
    <td class="py-3 px-2 text-xs leading-none text-twilight-blue font-bold">{{ $appt['patient'] }}</td>
    @if ($isGroupOwner)
        <td class="py-3 px-2 text-xs leading-none text-twilight-blue font-bold ">{{ $appt['pharmacy'] }}</td>
    @endif
    <td class="{{ $td }} text-center--">{{ $appt['nhs_number'] ? $appt['nhs_number'].' - '.$appt['dob'] : $appt['dob'] }}</td>
    <td class="{{ $td }} text-center--">{{ $appt['service'] }}</td>
    <td class="{{ $td }} text-center--">{{ $appt['date'] }}-{{ $appt['time'] }}</td>
    <td class="{{ $td }} text-center--">{{ $appt['requested_on'] }}</td>
    <td class="{{ $td }} text-center--" data-appointment-status>{{ $appt['status'] }}</td>
    <td class="{{ $td }} text-center--">{{ $appt['payment_status'] }}</td>
    <td class="{{ $td }} text-center">
        <div class="flex justify-center gap-2">
            @if ($appt['is_video'] && $appt['status'] === 'Approved')
                <button type="button" data-join-button
                    class="shrink-0 transition text-[10px] duration-200 border shadow-sm inline-flex items-center justify-center py-1 px-2 rounded-md font-medium cursor-pointer text-center group {{ $appt['is_meeting_active'] ? 'bg-gradient-to-t from-[#002B56] to-[#0070D5] border-darkBlue text-white isMeetingActive' : 'opacity-60 bg-grey border-grey cursor-not-allowed text-black' }}"
                    @if ($appt['is_meeting_active'])
                        onclick="window.open('{{ route('app.video-conference') }}', '_blank')"
                    @else
                        onclick="joinTimeWarning()"
                    @endif
                ><img src="{{ asset('admin/images/video_call.png') }}" class="mr-2 w-auto h-3 group-[.isMeetingActive]:invert" alt="Video">
                    <span class="mt-[1px]"> Join</span>
                </button>
            @endif
            @foreach ($actionSets as $status => $buttons)
                <div class="{{ $status === $appt['status'] ? '' : 'hidden' }}" data-status-actions="{{ $status }}">
                    <div class="flex justify-center gap-2">
                        @foreach ($buttons as [$label, $type])
                            @if ($type === 'resend')
                                <button type="button" data-tw-toggle="modal" data-tw-target="{{ $isPopup ? '#book_confirm_popup' : '#book_confirm' }}"
                                    class="{{ $btnPrimary }}"
                                    onclick="{{ $isPopup ? 'openResendEmailPopup' : 'openResendEmail' }}('{{ $appt['email'] }}', '{{ $appt['id'] }}')">
                                    <span class="mt-[1px]">{{ $label }}</span>
                                </button>
                            @else
                                <button type="button" onclick="appointmentUpdate('{{ $type }}', '{{ $appt['id'] }}', {{ $flag }})"
                                    class="{{ $type === 'cancel' ? $btnCancel : $btnPrimary }}">
                                    <span class="mt-[1px]">{{ $label }}</span>
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </td>
</tr>
