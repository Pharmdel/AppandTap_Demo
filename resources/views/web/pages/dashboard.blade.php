@extends('layouts.web')

@php
    $state = request('state', 'active'); // nominate | pending | active
    $pharmacy = $demo['pharmacy'];
    $services = collect($demo['services']);
    $upcoming = collect($demo['appointments'])->where('tab', 'upcoming');
@endphp

@section('content')

    @if ($state === 'nominate')
        <div class="max-w-lg mx-auto bg-white rounded-2xl shadow p-10 text-center">
            <i data-lucide="building-2" class="w-10 h-10 mx-auto text-blue mb-4"></i>
            <h2 class="text-lg font-semibold text-lightBlue mb-2">Nominate your Community Pharmacy</h2>
            <p class="text-slate-500 text-sm mb-6">Search for and nominate a pharmacy to start using AppAndTap.</p>
            <input type="text" placeholder="Search pharmacy by name or postcode" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm">
        </div>
    @elseif ($state === 'pending')
        <div class="max-w-lg mx-auto bg-white rounded-2xl shadow p-10 text-center">
            <img src="/assets/app/images/awaiting_icon.svg" class="w-16 h-16 mx-auto mb-4" alt="">
            <h2 class="text-lg font-semibold text-lightBlue mb-2">Waiting for approval</h2>
            <p class="text-slate-500 text-sm">Your request has been sent to {{ $pharmacy['name'] }}.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-lightBlue">Our Commonly Booked Services</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($services->take(6) as $service)
                        <div class="bg-white rounded-xl shadow p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[11px] px-2 py-0.5 rounded-full {{ $service['label_color'] }}">{{ $service['label'] }}</span>
                                <span class="w-6 h-6 rounded-full bg-lightBlue text-white text-xs flex items-center justify-center">{{ $service['number'] }}</span>
                            </div>
                            <h3 class="font-semibold text-lightBlue text-sm">{{ $service['title'] }}</h3>
                            <p class="text-xs text-slate-400 mt-1">{{ $service['price'] > 0 ? '£'.$service['price'] : 'Free' }}</p>
                            <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ $service['description'] }}</p>
                            <a href="{{ route('web.book-appointment') }}?service={{ $service['id'] }}"
                               class="mt-3 inline-block bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white text-xs px-4 py-1.5 rounded-full font-semibold">
                                Book now
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <div class="bg-white rounded-xl shadow p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <i data-lucide="calendar-clock" class="w-5 h-5 text-blue"></i>
                        <h3 class="font-semibold text-lightBlue text-sm">Upcoming appointment</h3>
                    </div>
                    <div class="flex flex-col gap-3 max-h-64 overflow-y-auto">
                        @forelse ($upcoming as $appt)
                            <div class="border border-slate-100 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-lightBlue">{{ $appt['service'] }}</p>
                                    @if ($appt['is_meeting_active'])
                                        <a href="{{ $appt['meeting_link'] }}" class="text-redclr text-xs flex items-center gap-1">
                                            <i data-lucide="video" class="w-3 h-3"></i> Join
                                        </a>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-400">{{ $appt['date'] }} &middot; {{ $appt['slot'] }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">No upcoming appointments.</p>
                        @endforelse
                    </div>
                    <a href="{{ route('web.book-appointment') }}" class="mt-4 block text-center bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white text-sm py-2 rounded-full font-semibold">
                        Book appointment
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <i data-lucide="building-2" class="w-5 h-5 text-blue"></i>
                        <h3 class="font-semibold text-lightBlue text-sm">My Nominated Pharmacy</h3>
                    </div>
                    <p class="text-sm font-medium text-lightBlue">{{ $pharmacy['name'] }}</p>
                    <p class="text-xs text-slate-400">{{ $pharmacy['phone'] }}</p>
                    <p class="text-xs text-slate-400">{{ $pharmacy['address'] }}, {{ $pharmacy['postcode'] }}</p>
                </div>
            </div>
        </div>
    @endif
@endsection
