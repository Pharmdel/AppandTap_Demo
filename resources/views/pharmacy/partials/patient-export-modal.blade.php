@php
    $input = 'transition duration-200 ease-in-out w-full text-base border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 bg-white intro-x block min-w-full w-full';
@endphp
{{-- admin/patient/index.blade.php: #pdf_filter_poup ("Download Report") --}}
<div aria-hidden="true" tabindex="-1" id="pdf_filter_poup"
    class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed right-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s] flex justify-center items-center modal-overlap overflow-y-auto z-[1500]">
    <div class="w-[90vw] max-w-[450px] flex flex-col">
        <!-- heading block  -->
        <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] px-8 py-3 rounded-t flex items-center relative">
            <a class="bg-white text-darkBlue w-10 h-10 rounded-full absolute -right-4 -top-4 flex justify-center items-center cursor-pointer" href="javascript:void(0);" data-tw-dismiss="modal">
                <i data-lucide="x" class="stroke-2 w-6 h-6"></i>
            </a>
            <h2 class="text-white text-xl font-medium text-center grow">Export Patients</h2>
        </div>
        <div class="bg-white">
            <form class="px-2.5 py-3 min-h-[180px] flex flex-col" onsubmit="event.preventDefault(); hideModal('#pdf_filter_poup'); Swal.fire({ title: 'Your patient report is being prepared.', icon: 'success', timer: 1800, showConfirmButton: false });">
                <div class="mb-6">
                    <label class="inline-block font-semibold mb-2">Select Type</label>
                    <div class="preview relative">
                        <div class="w-full text-slate-500">
                            <select class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md intro-x block" name="print_type">
                                <option value="pdf" selected>Pdf</option>
                                <option value="csv">CSV</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>
                    <label class="inline-block mt-3 font-semibold">Sign Up Date</label>
                    <div class="grid grid-cols-2 gap-5 mt-2">
                        <div class="relative">
                            <input type="date" placeholder="Select start date" class="{{ $input }}" name="start_date">
                        </div>
                        <div class="relative">
                            <input type="date" placeholder="Select end date" class="{{ $input }}" name="end_date">
                        </div>
                    </div>
                </div>
                <div class="rounded-b-lg overflow-hidden" style="background: linear-gradient(180deg, white, transparent);">
                    <div class="mt-auto text-right py-3 px-2 rounded-lg border border-blue flex justify-between bg-white">
                        <button type="submit" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
