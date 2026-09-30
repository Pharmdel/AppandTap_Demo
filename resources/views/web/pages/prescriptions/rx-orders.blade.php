@extends('layouts.web')

@php
    $orders = $demo['prescriptions']['rx_orders'];
    $statusColor = fn ($s) => match ($s) {
        'Issued' => 'bg-green-100 text-green-700',
        'Requested' => 'bg-yellow-100 text-yellow-700',
        'Rejected' => 'bg-red-100 text-red-700',
        default => 'bg-slate-100 text-slate-600',
    };
@endphp

@section('content')
<div class="flex gap-6">
    <x-web.prescriptions-tabs active="prescriptions-rx-orders" />

    <div class="flex-1">
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-xs rounded-lg px-4 py-3 mb-4">
            <strong>Important:</strong> your prescription information is sensitive data, handled securely under NHS data protection rules.
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-400 text-xs">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium">Medicine</th>
                        <th class="text-left px-4 py-3 font-medium">Date Requested</th>
                        <th class="text-left px-4 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-t border-slate-50">
                            <td class="px-4 py-3 text-lightBlue font-medium">{{ $order['medicine'] }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $order['date_requested'] }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-1 rounded-full {{ $statusColor($order['status']) }}">{{ $order['status'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
