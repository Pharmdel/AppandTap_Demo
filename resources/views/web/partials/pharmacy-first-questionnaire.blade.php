@php
    $pgd = collect($demo['services'])->firstWhere('is_pharmacy_first', true);
@endphp

<div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white px-5 py-4 flex items-center justify-between">
    <h3 class="font-semibold">{{ $pgd['title'] ?? 'Eligibility Questionnaire' }}</h3>
    <button onclick="document.getElementById('pgdModal').close()" class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center">&times;</button>
</div>

<div id="pgdSection1" class="p-6">
    <p class="text-xs text-slate-400 mb-1">Section 1/2</p>
    <div class="h-1.5 bg-slate-100 rounded-full mb-5"><div class="h-1.5 w-1/2 bg-gradient-to-t from-[#002B56] to-[#0070D5] rounded-full"></div></div>

    <p class="text-sm font-medium text-lightBlue mb-2">How long have you had symptoms?</p>
    <div class="flex flex-wrap gap-2 mb-5">
        @foreach (['Less than 3 days', '3-7 days', 'More than 7 days'] as $opt)
            <label class="text-xs px-3 py-1.5 rounded-full border border-slate-200 cursor-pointer has-[:checked]:bg-gradient-to-t has-[:checked]:from-[#002B56] has-[:checked]:to-[#0070D5] has-[:checked]:text-white">
                <input type="radio" name="q1" class="hidden"> {{ $opt }}
            </label>
        @endforeach
    </div>

    <p class="text-sm font-medium text-lightBlue mb-2">Do you have a fever?</p>
    <div class="flex gap-2 mb-6">
        <label class="text-xs px-4 py-1.5 rounded-full border border-slate-200 cursor-pointer has-[:checked]:bg-gradient-to-t has-[:checked]:from-[#002B56] has-[:checked]:to-[#0070D5] has-[:checked]:text-white">
            <input type="radio" name="q2" class="hidden"> Yes
        </label>
        <label class="text-xs px-4 py-1.5 rounded-full border border-slate-200 cursor-pointer has-[:checked]:bg-gradient-to-t has-[:checked]:from-[#002B56] has-[:checked]:to-[#0070D5] has-[:checked]:text-white">
            <input type="radio" name="q2" class="hidden"> No
        </label>
    </div>

    <div class="flex justify-end">
        <button onclick="document.getElementById('pgdSection1').classList.add('hidden'); document.getElementById('pgdSection2').classList.remove('hidden')"
                class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white text-sm px-5 py-2 rounded-full font-semibold">Next</button>
    </div>
</div>

<div id="pgdSection2" class="p-6 hidden">
    <p class="text-xs text-slate-400 mb-1">Section 2/2</p>
    <div class="h-1.5 bg-slate-100 rounded-full mb-5"><div class="h-1.5 w-full bg-gradient-to-t from-[#002B56] to-[#0070D5] rounded-full"></div></div>

    <p class="text-sm font-medium text-lightBlue mb-2">Any known drug allergies?</p>
    <div class="flex gap-2 mb-6">
        <label class="text-xs px-4 py-1.5 rounded-full border border-slate-200 cursor-pointer has-[:checked]:bg-gradient-to-t has-[:checked]:from-[#002B56] has-[:checked]:to-[#0070D5] has-[:checked]:text-white">
            <input type="radio" name="q3" class="hidden"> Yes
        </label>
        <label class="text-xs px-4 py-1.5 rounded-full border border-slate-200 cursor-pointer has-[:checked]:bg-gradient-to-t has-[:checked]:from-[#002B56] has-[:checked]:to-[#0070D5] has-[:checked]:text-white">
            <input type="radio" name="q3" class="hidden"> No
        </label>
    </div>

    <div class="flex justify-between">
        <button onclick="document.getElementById('pgdSection2').classList.add('hidden'); document.getElementById('pgdSection1').classList.remove('hidden')"
                class="border border-slate-200 text-slate-600 text-sm px-5 py-2 rounded-full font-semibold">Previous</button>
        <button onclick="document.getElementById('pgdSection2').classList.add('hidden'); document.getElementById('pgdSummary').classList.remove('hidden')"
                class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white text-sm px-5 py-2 rounded-full font-semibold">Submit</button>
    </div>
</div>

<div id="pgdSummary" class="p-6 hidden">
    <h4 class="font-semibold text-lightBlue mb-4">Review your answers</h4>
    <div class="border-l-2 border-slate-100 pl-4 flex flex-col gap-3 mb-6">
        <div>
            <p class="text-xs text-slate-400">Section 1</p>
            <span class="inline-block text-xs mt-1 px-3 py-1 rounded-full bg-slate-100 text-slate-600">Symptoms: 3-7 days</span>
        </div>
        <div>
            <p class="text-xs text-slate-400">Section 2</p>
            <span class="inline-block text-xs mt-1 px-3 py-1 rounded-full bg-slate-100 text-slate-600">Allergies: No</span>
        </div>
    </div>
    <div class="flex justify-between">
        <button onclick="document.getElementById('pgdSummary').classList.add('hidden'); document.getElementById('pgdSection1').classList.remove('hidden')"
                class="border border-slate-200 text-slate-600 text-sm px-5 py-2 rounded-full font-semibold">Edit</button>
        <button onclick="document.getElementById('pgdModal').close()"
                class="bg-green text-white text-sm px-5 py-2 rounded-full font-semibold">Save &amp; Book Appointment</button>
    </div>
</div>
