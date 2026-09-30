@extends('layouts.web')

@php $meds = $demo['prescriptions']['repeat_meds']; @endphp

@section('content')
<div class="flex gap-6">
    <x-web.prescriptions-tabs active="prescriptions-repeat-meds" />

    <div class="flex-1">
        <div class="flex items-center gap-2 mb-3">
            <label class="flex items-center gap-2 text-sm text-lightBlue bg-white px-3 py-1.5 rounded-full">
                <input type="checkbox"> Select All
            </label>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden mb-16">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-400 text-xs">
                    <tr>
                        <th class="px-4 py-3"></th>
                        <th class="text-left px-4 py-3 font-medium">Medicine</th>
                        <th class="text-left px-4 py-3 font-medium">Last Issue Date</th>
                        <th class="text-left px-4 py-3 font-medium">Dose</th>
                        <th class="text-left px-4 py-3 font-medium">Status</th>
                        <th class="text-left px-4 py-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($meds as $med)
                        <tr class="border-t border-slate-50">
                            <td class="px-4 py-3"><input type="checkbox"></td>
                            <td class="px-4 py-3 text-lightBlue font-medium">{{ $med['medicine'] }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $med['last_issue'] }}</td>
                            <td class="px-4 py-3 text-slate-500 flex items-center gap-1"><i data-lucide="alarm-clock" class="w-3.5 h-3.5"></i> {{ $med['dose'] }}</td>
                            <td class="px-4 py-3">
                                @if ($med['status'])
                                    <span class="text-xs px-2 py-1 rounded-full bg-orange-100 text-orange-700">{{ $med['status'] }}</span>
                                @else
                                    <span class="text-xs text-slate-400">&mdash;</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <button onclick="document.getElementById('appDownloadModal').showModal()" class="text-blue text-xs underline">
                                    Setup a reminder
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="fixed bottom-0 left-64 right-0 bg-white border-t border-slate-100 px-6 py-4 flex items-center gap-4">
            <input type="text" placeholder="Your comment to GP (optional)" class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm">
            <button class="bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white px-5 py-2 rounded-full text-sm font-semibold">Request Medications</button>
        </div>
    </div>
</div>

@include('web.partials.qr-download-modal')
@endsection
