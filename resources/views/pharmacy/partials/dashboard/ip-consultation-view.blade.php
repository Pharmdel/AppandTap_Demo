@php
    // ip-consultation-request.blade.php: "Service Questionnaire Popup" (View).
    $initials = collect(explode(' ', $c['patient']))->map(fn ($part) => mb_substr($part, 0, 1))->implode('');
    $prescriptionTotal = collect($c['prescription'])->sum('price');
    $totalAmount = $c['service_amount'] + $prescriptionTotal;
    $sectionTitle = 'text-xs font-medium text-gray-500 uppercase tracking-widest';
@endphp
<section id="ip-view-{{ $c['id'] }}" class="hidden" data-popup>
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[99] p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl relative flex flex-col max-h-[90vh]">

            {{-- Close Button --}}
            <button type="button" data-popup-close
                class="bg-white text-[#013464] w-9 h-9 rounded-full absolute -right-3 -top-3 flex justify-center items-center cursor-pointer shadow-md z-10 hover:bg-gray-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            {{-- Header --}}
            <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] px-8 py-5 rounded-t-2xl relative overflow-hidden flex-shrink-0">
                <div class="absolute inset-0 opacity-[0.06]" style="background-image: repeating-linear-gradient(45deg, #fff 0px, #fff 1px, transparent 1px, transparent 8px);"></div>
                <p class="text-white/60 text-[10px] text-center uppercase tracking-widest mb-0.5">IP Consultation</p>
                <h2 class="text-white text-xl font-medium text-center relative z-10">{{ $c['service'] }}</h2>
            </div>

            {{-- Scrollable Body --}}
            <div class="px-7 py-6 overflow-y-auto grow space-y-6">

                {{-- Patient + Clinician Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                        <div class="flex items-center gap-3 pb-3 mb-3 border-b border-gray-100">
                            <div class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center text-xs font-semibold text-blue-700 flex-shrink-0">{{ strtoupper($initials) }}</div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0">Patient</p>
                                <p class="text-sm font-medium text-gray-800 leading-tight">{{ $c['patient'] }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-[13px]">
                            <div class="flex justify-between items-center"><span class="text-gray-400">Date of Birth</span><span class="font-medium text-gray-700">{{ $c['dob'] }}</span></div>
                            <div class="flex justify-between items-center"><span class="text-gray-400">Email</span><span class="text-blue-600 text-xs truncate ml-4">{{ $c['email'] }}</span></div>
                            <div class="flex justify-between items-center"><span class="text-gray-400">Mobile</span><span class="font-medium text-gray-700">{{ $c['mobile'] }}</span></div>
                            <div class="flex justify-between items-center"><span class="text-gray-400">Adddress</span><span class="font-medium text-gray-700">{{ $c['address'] }}</span></div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                        <div class="flex items-center gap-3 pb-3 mb-3 border-b border-gray-100">
                            <div class="w-9 h-9 rounded-full bg-green-50 flex items-center justify-center text-xs font-semibold text-green-700 flex-shrink-0">{{ strtoupper(mb_substr($c['clinician'], 0, 2)) }}</div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0">Clinician</p>
                                <p class="text-sm font-medium text-gray-800 leading-tight">{{ $c['clinician'] }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-[13px]">
                            <div class="flex justify-between items-center"><span class="text-gray-400">GPhC Number</span><span class="bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $c['gphc_number'] }}</span></div>
                            <div class="flex justify-between items-center"><span class="text-gray-400">Email</span><span class="text-blue-600 text-xs truncate ml-4">{{ $c['clinician_email'] }}</span></div>
                            <div class="flex justify-between items-center"><span class="text-gray-400 whitespace-nowrap">E-signature:</span><span class="text-blue-600 text-xs truncate ml-4">{{ $c['e_sign'] }}</span></div>
                        </div>
                    </div>
                </div>

                {{-- Questionnaire --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-0.5 h-5 bg-blue-500 rounded-full"></div>
                        <h3 class="{{ $sectionTitle }}">Questionnaire</h3>
                    </div>
                    <div class="border border-gray-100 rounded-xl overflow-hidden">
                        @foreach ($c['questionnaire'] as $q)
                            <div class="flex items-start justify-between px-4 py-3 text-[13px] {{ $loop->even ? 'bg-gray-50' : 'bg-white' }} {{ ! $loop->last ? 'border-b border-gray-100' : '' }}">
                                <span class="text-gray-500 pr-4">{{ $loop->iteration }}. {{ $q['question'] }}</span>
                                <div class="flex flex-wrap justify-end gap-1.5 max-w-[55%]">
                                    @foreach (array_map('trim', explode(',', $q['answer'])) as $answer)
                                        <span class="bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1 rounded-full break-words">{{ $answer }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Vitals --}}
                @if (! empty($c['vitals']))
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-0.5 h-5 bg-green-600 rounded-full"></div>
                            <h3 class="{{ $sectionTitle }}">Vitals</h3>
                        </div>
                        <div class="space-y-2">
                            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    @foreach ($c['vitals'] as $label => $value)
                                        <span>{{ $label }}: <span class="font-medium text-gray-700">{{ $value }}</span></span>
                                        @unless ($loop->last)
                                            <span>|</span>
                                        @endunless
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Clinician Decision --}}
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-0.5 h-5 bg-green-600 rounded-full"></div>
                        <h3 class="{{ $sectionTitle }}">Clinician Decision</h3>
                    </div>
                    <div class="flex flex-col gap-6 text-[14px] leading-relaxed border p-3">
                        <p class="text-slate-600">{{ $c['clinician_decision'] }}</p>
                    </div>
                </div>

                {{-- Prescription --}}
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-0.5 h-5 bg-green-600 rounded-full"></div>
                            <h3 class="{{ $sectionTitle }}">Prescription</h3>
                        </div>
                        <div class="space-y-3">
                            @foreach ($c['prescription'] as $rx)
                                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 space-y-3">
                                    <p class="text-sm font-semibold text-gray-800 break-words">{{ $rx['medicine_name'] }}</p>
                                    <div class="grid grid-cols-[100px_1fr] gap-x-3 gap-y-1.5 text-xs">
                                        <span class="text-gray-500">Qty</span>
                                        <span class="font-medium text-gray-700 break-words">{{ $rx['quantity'] }}</span>
                                        <span class="text-gray-500">Price</span>
                                        <span class="font-medium text-gray-700 break-words">£{{ number_format($rx['price'], 2) }}</span>
                                        <span class="text-gray-500">Dosage</span>
                                        <span class="font-medium text-gray-700 break-words">{{ $rx['dosage'] }}</span>
                                        <span class="text-gray-500">Additional Note</span>
                                        <span class="font-medium text-gray-700 break-words">{{ $rx['note'] ?: '--' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Payment Detail --}}
                @if ($totalAmount > 0)
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-0.5 h-5 bg-green-600 rounded-full"></div>
                            <h3 class="{{ $sectionTitle }}">Payment Detail</h3>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                            <div class="space-y-2 text-[13px]">
                                <div class="flex justify-between items-center"><span class="text-gray-400">Service Amount</span><span class="font-medium text-gray-700">£{{ number_format($c['service_amount'], 2) }}</span></div>
                                <div class="flex justify-between items-center"><span class="text-gray-400">Prescription Amount</span><span class="font-medium text-gray-700">£{{ number_format($prescriptionTotal, 2) }}</span></div>
                                <div class="flex justify-between items-center"><span class="text-gray-400">Paid Amount</span><span class="font-medium text-gray-700">£{{ number_format($c['paid_amount'], 2) }}</span></div>
                                <div class="flex justify-between items-center"><span class="text-gray-400">Pending Amount</span><span class="font-medium text-gray-700">£{{ number_format(max($totalAmount - $c['paid_amount'], 0), 2) }}</span></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            @if ($c['status'] === 'Requested')
                <div class="flex justify-end items-center px-7 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex-shrink-0 gap-3" data-ip-footer>
                    <button type="button" data-tw-toggle="modal" data-tw-target="#markAsCancelPopUp" onclick="selectIpConsultation({{ $c['id'] }})"
                        class="bg-white text-lightBlue border-lightBlue transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer">
                        <i data-lucide="x" class="mr-2 h-4 w-4"></i>
                        Decline
                    </button>
                    <button type="button" data-tw-toggle="modal" data-tw-target="#markAsDispensedPopUp" onclick="selectIpConsultation({{ $c['id'] }})"
                        class="inline-flex items-center gap-2 bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white text-sm font-medium px-5 py-2.5 rounded-lg hover:opacity-90 transition-opacity">
                        <i data-lucide="check" class="h-3.5 w-3.5"></i>
                        Mark as Dispensed
                    </button>
                </div>
            @else
                <div class="flex justify-between items-end gap-3 px-7 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl flex-shrink-0">
                    <a href="javascript:void(0)" onclick="printIpConsultation(this)"
                        class="relative group w-[32px] h-[32px] flex items-center justify-center rounded bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white" title="Download PDF">
                        <svg viewBox="0 0 512 512" class="w-[14px] h-[14px] text-white"><path d="M256 398L122 264h88V32h92v232h88L256 398zM0 368v48c0 53 43 96 96 96h320c53 0 96-43 96-96v-48h-64v48c0 18-14 32-32 32H96c-18 0-32-14-32-32v-48H0z" fill="currentColor" /></svg>
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
