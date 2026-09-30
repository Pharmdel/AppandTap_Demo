{{-- appointment-detail.blade.php: "Send email Popop" and "Send email Popop in Popop" --}}
@foreach ($modals as $modalId => [$idField, $emailField, $inPopup])
    <div aria-hidden="true" tabindex="-1" id="{{ $modalId }}"
        class="modal group bg-black/60 transition-[visibility,opacity] w-screen h-screen fixed right-0 top-0 [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.4s] flex justify-center items-center">
        <div class="box max-w-md mx-5 w-full bg-gradient-to-t from-[#002B56] to-[#0070D5] h-auto relative p-5">
            <a class="close-btn bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white w-10 h-10 rounded-full absolute -right-4 -top-4 flex justify-center items-center cursor-pointer" data-tw-dismiss="modal">
                <i data-lucide="x" class="stroke-2 w-6 h-6 text-white"></i>
            </a>
            <div class="">
                <label class="text-base text-white font-medium inline-block mb-3">Choose Email</label>
                <div class="choose_pharmacy">
                    <input type="hidden" id="{{ $idField }}" value="">
                    <div class="max-w-3xl w-full bg-white rounded-lg">
                        <input name="email" type="text" placeholder="Email"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-lg placeholder:text-slate-400/90 intro-x block min-w-full px-4 py-3 xl:min-w-[350px] my-1"
                            value="" id="{{ $emailField }}">
                    </div>
                    <div class="text-danger alert alert-danger hidden" data-form-errors></div>
                    <span class="text-sm text-redclr mt-2 block">
                        Note: For sending emails to multiple recipients, please separate each email address with a comma.
                    </span>
                </div>
            </div>
            <div class="flex justify-center mt-7">
                <button type="button" onclick="if (validateEmails('{{ $emailField }}')) appointmentUpdate('resend', document.getElementById('{{ $idField }}').value, {{ $inPopup ? 'true' : 'false' }})"
                    class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-5 rounded-md font-medium cursor-pointer bg-white border-lightBblue text-lightBblue text-base">Submit</button>
            </div>
        </div>
    </div>
@endforeach
