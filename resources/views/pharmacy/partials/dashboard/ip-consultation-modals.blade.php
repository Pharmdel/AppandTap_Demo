@php
    // ip-consultation-request.blade.php: #markAsDispensedPopUp and #markAsCancelPopUp.
    $modal = 'modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed right-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s] flex justify-center items-center';
    $input = 'transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 px-4 py-2';
    $label = 'inline-block font-semibold';
@endphp
<input type="hidden" id="selectedConsultationIdRequest" value="">

<div aria-hidden="true" tabindex="-1" id="markAsDispensedPopUp" class="{{ $modal }}">
    <div class="w-[90vw] max-w-[450px] flex flex-col">
        <!-- heading block  -->
        <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] px-8 py-3 rounded-t flex items-center relative">
            <a class="cancelConsultation bg-white text-darkBlue w-10 h-10 rounded-full absolute -right-4 -top-4 flex justify-center items-center cursor-pointer" data-tw-dismiss="modal">
                <i data-lucide="x" class="stroke-2 w-6 h-6"></i>
            </a>
            <h2 class="text-white text-xl font-medium text-center grow">Mark As Dispensed</h2>
        </div>
        <div class="bg-white">
            <div class="px-2.5 py-3 min-h-[180px] flex flex-col">
                <div class="mb-6">
                    <div class="bg-gray-100 p-4 rounded-md shadow-sm mt-3">
                        <label class="{{ $label }}">Pharmacist Name</label>
                        <div class="inputGroup mt-1"><input id="name" type="text" placeholder="Enter name" autocomplete="off" class="{{ $input }}"></div>
                        <label class="{{ $label }} mt-3">GPhC Number</label>
                        <div class="inputGroup mt-1"><input id="gphcNumberRequest" type="text" placeholder="Enter GPhC number" autocomplete="off" class="{{ $input }}"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="rounded-b-lg overflow-hidden" style="background: linear-gradient(180deg, white, transparent);">
            <div class="mt-auto text-right py-3 px-2 rounded-lg border border-blue flex justify-between bg-white">
                <button type="button" data-tw-dismiss="modal"
                    class="cancelConsultation transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-white border-lightBlue text-lightBlue w-28">Cancel</button>
                <button type="button" onclick="markAsDispensed()"
                    class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white ml-auto">Dispensed</button>
            </div>
        </div>
    </div>
</div>

<div aria-hidden="true" tabindex="-1" id="markAsCancelPopUp" class="{{ $modal }}">
    <div class="bg-white rounded-lg shadow-lg w-[50vw] max-w-[600px] relative question_form h-fit flex flex-col">
        <a class="closeCancelPopup bg-white text-darkBlue w-10 h-10 rounded-full absolute -right-4 -top-4 flex justify-center items-center cursor-pointer" data-tw-dismiss="modal">
            <i data-lucide="x" class="stroke-2 w-6 h-6"></i>
        </a>
        <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] px-8 py-4 rounded-t-md">
            <h2 class="text-white text-2xl font-semibold text-center">Cancel Consultation</h2>
        </div>
        <div class="p-8 space-y-6">
            <div class="space-y-1.5 group transition-all">
                <div class="bg-gray-100 p-4 rounded-md shadow-sm mt-3">
                    <label class="{{ $label }}">Pharmacist Name</label>
                    <div class="inputGroup mt-1"><input id="cancelPharmName" type="text" placeholder="Enter name" autocomplete="off" class="{{ $input }}"></div>
                    <label class="{{ $label }} mt-3">GPhC Number</label>
                    <div class="inputGroup mt-1"><input id="cancelGphcNumberRequest" type="text" placeholder="Enter GPhC number" autocomplete="off" class="{{ $input }}"></div>
                    <label class="{{ $label }} mt-3">Cancel Reason</label>
                    <div class="relative w-full text-sm mt-1">
                        <select id="parkedReason" class="{{ $input }} bg-white">
                            <option value="">Select cancel reason</option>
                            <option>Patient did not attend</option>
                            <option>Not clinically suitable</option>
                            <option>Referred to GP</option>
                            <option>Medicine out of stock</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-semibold text-[#454646]">Remark</label>
                <textarea id="cancelRemark" rows="3" class="{{ $input }}" placeholder="Enter remark"></textarea>
            </div>
            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-4">
                <button type="button" data-tw-dismiss="modal"
                    class="closeCancelPopup transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer bg-white border-lightBlue text-lightBlue w-28">Cancel</button>
                <button type="button" onclick="markAsCancel()"
                    class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white px-8 py-3 rounded-lg font-semibold hover:opacity-90 transition shadow-sm">Submit</button>
            </div>
        </div>
    </div>
</div>
