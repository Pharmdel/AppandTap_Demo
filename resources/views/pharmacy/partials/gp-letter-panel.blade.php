{{-- ip-consultation-request.blade.php: "GP Letter Details" slide-over (gpLetterDetailePopup) --}}
<section id="gp-letter-{{ $c['id'] }}" class="hidden" data-popup>
    <div class="fixed inset-0 bg-black bg-opacity-25 z-[90]"></div>
    <div class="fixed transition overflow-visible duration-300 right-0 top-0 transform w-[90%] md:w-[725px] h-screen bg-white overflow-hidden z-[99]">
        <div class="top-0 h-full overflow-y-scroll">
            <div class="p-5 overflow-y-auto flex-1">
                <div class="grid grid-cols-12 gap-6">
                    <div class="intro-y col-span-12 lg:col-span-12 text-[#333] font-sans">
                        <div class="flex items-center justify-between mb-4 border-b pb-1">
                            <h3 class="text-lg font-bold">GP Letter Details</h3>
                            <div class="flex items-center gap-2">
                                <a href="javascript:void(0)" onclick="printGpLetter(this)"
                                    class="relative group w-[32px] h-[32px] flex items-center justify-center rounded bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">
                                    <svg viewBox="0 0 512 512" class="w-[14px] h-[14px] text-white"><path d="M256 398L122 264h88V32h92v232h88L256 398zM0 368v48c0 53 43 96 96 96h320c53 0 96-43 96-96v-48h-64v48c0 18-14 32-32 32H96c-18 0-32-14-32-32v-48H0z" fill="currentColor" /></svg>
                                </a>
                            </div>
                        </div>
                        <div class="flex flex-col gap-6 text-[14px] leading-relaxed" data-letter-body>
                            <p class="text-slate-600 gp-latter-para">
                                <b>Date {{ \Illuminate\Support\Str::before($c['appointment_date'], ' ') }}</b><br /><br />
                                <b>Patient Details:</b><br />
                                <strong>Name:</strong> {{ $c['patient'] }}<br />
                                <strong>Date of Birth:</strong> {{ $c['dob'] }}<br />
                                <strong>Address:</strong> {{ $c['address'] }},<br />
                                <br /><br />
                                <b>Pharmacy Details:</b><br />
                                <strong>Pharmacist:</strong> {{ $c['sign_by'] }}<br />
                                <strong>GPhC number:</strong> {{ $c['pharmacist_gphc'] }}<br />
                                <strong>Address:</strong> {{ $pharmacy['address'] }}<br />
                            </p>
                            <div class="overflow-auto mt-5">
                                <h2 class="text-lg font-bold mb-3">Prescription</h2>
                                <div class="flex flex-col gap-3 border rounded-md p-3">
                                    @foreach ($c['prescription'] as $rx)
                                        <div class="rounded-md border border-gray-200 p-3">
                                            <div class="font-semibold text-[14px] text-slate-800 break-words mb-3">{{ $loop->iteration }}. {{ $rx['medicine_name'] }}</div>
                                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-3 text-[13px]">
                                                <div class="min-w-0">
                                                    <span class="block text-[11px] font-bold uppercase tracking-wide text-slate-800 mb-1">Quantity</span>
                                                    <span class="text-slate-700 break-words">{{ $rx['quantity'] }}</span>
                                                </div>
                                                <div class="min-w-0">
                                                    <span class="block text-[11px] font-bold uppercase tracking-wide text-slate-800 mb-1">Dosage</span>
                                                    <span class="text-slate-700 break-words">{{ $rx['dosage'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="javascript:;" data-popup-close class="fixed z-[999] top-4 right-[730px]"><svg xmlns="http://www.w3.org/2000/svg"
                width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </a>
    </div>
</section>
