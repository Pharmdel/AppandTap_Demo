@extends('layouts.web')

@section('content')
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-400 text-xs">
            <tr>
                <th class="text-left px-4 py-3 font-medium">Date</th>
                <th class="text-left px-4 py-3 font-medium">Condition / Service</th>
                <th class="text-left px-4 py-3 font-medium">Pharmacy</th>
                <th class="text-left px-4 py-3 font-medium">Status</th>
                <th class="text-left px-4 py-3 font-medium">Payment Status</th>
                <th class="text-left px-4 py-3 font-medium"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($demo['consultations'] as $i => $c)
                <tr class="border-t border-slate-50">
                    <td class="px-4 py-3 text-slate-500">{{ $c['date'] }}</td>
                    <td class="px-4 py-3 text-lightBlue font-medium">{{ $c['condition'] }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $c['pharmacy'] }}</td>
                    <td class="px-4 py-3"><span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">{{ $c['status'] }}</span></td>
                    <td class="px-4 py-3 text-slate-500">{{ $c['payment_status'] }}</td>
                    <td class="px-4 py-3">
                        <button onclick="document.getElementById('consultSummary{{ $i }}').showModal()" class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white text-xs px-3 py-1.5 rounded-full">Summary</button>
                    </td>
                </tr>
                <dialog id="consultSummary{{ $i }}" class="rounded-xl p-0 w-[90%] max-w-lg backdrop:bg-black/40">
                    <div class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white px-5 py-4 flex justify-between items-center">
                        <h3 class="font-semibold">Consultation Summary</h3>
                        <button onclick="document.getElementById('consultSummary{{ $i }}').close()" class="text-white">&times;</button>
                    </div>
                    <div class="p-5 flex flex-col gap-3 max-h-[60vh] overflow-y-auto">
                        @foreach ($c['summary'] as $label => $value)
                            <div>
                                <p class="text-xs text-slate-400">{{ $label }}</p>
                                <p class="text-sm text-lightBlue">{{ $value }}</p>
                            </div>
                        @endforeach
                        <button class="mt-2 border border-slate-200 rounded-full py-2 text-sm flex items-center justify-center gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i> Download PDF
                        </button>
                    </div>
                </dialog>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
