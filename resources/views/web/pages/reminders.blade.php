@extends('layouts.web')

@php $meds = $demo['prescriptions']['repeat_meds']; @endphp

@section('content')
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-400 text-xs">
            <tr>
                <th class="text-left px-4 py-3 font-medium">Medicine</th>
                <th class="text-left px-4 py-3 font-medium">Last Issue Date</th>
                <th class="text-left px-4 py-3 font-medium">Dose</th>
                <th class="text-left px-4 py-3 font-medium">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($meds as $med)
                <tr class="border-t border-slate-50">
                    <td class="px-4 py-3 text-lightBlue font-medium">{{ $med['medicine'] }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $med['last_issue'] }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $med['dose'] }}</td>
                    <td class="px-4 py-3">
                        @if ($med['reminder_times'])
                            <span class="text-green font-semibold underline">{{ implode(', ', $med['reminder_times']) }}</span>
                        @else
                            <button onclick="document.getElementById('appDownloadModal').showModal()" class="text-blue text-xs underline">Setup a reminder</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('web.partials.qr-download-modal')
@endsection
