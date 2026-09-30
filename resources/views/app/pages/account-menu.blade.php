@extends('layouts.app')

@php
    // lib/screens/account_screen/my__account_screen.dart. patientList(): the
    // account holder (type "Admin") and then their dependents; only the holder
    // is labelled and opens the profile.
    $patient = $demo['patient'];
    $people = [
        ['name' => $patient['first_name'].' '.$patient['last_name'], 'admin' => true],
        ...array_map(fn (array $d) => ['name' => $d['name'], 'admin' => false], $demo['dependents']),
    ];
    $heading = 'text-[20px] font-bold text-primaryColor';
    // buildContainer(): tinted SVG, 15px label, arrow_forward_ios_rounded, then an inset Divider.
    $rows = [
        'terms' => ['label' => 'Terms & Conditions', 'icon' => 'terms_and_condition_icon.svg', 'size' => [20, 25], 'url' => route('app.external-webview')],
        'privacy' => ['label' => 'Privacy Policy', 'icon' => 'privacy_policy_icon.svg', 'size' => [20, 24], 'url' => route('app.external-webview')],
        'nhs' => ['label' => 'Manage my NHS login', 'icon' => 'user_icon.svg', 'size' => [21, 25], 'url' => route('app.external-webview'), 'text' => '#005eb8', 'iconColor' => '#005eb8'],
        'notification' => ['label' => 'Notification', 'icon' => 'notification_icon.svg', 'size' => [25, 25], 'url' => route('app.notification-settings')],
        'logout' => ['label' => 'Logout', 'icon' => 'logout_icon.svg', 'size' => [24, 24], 'url' => null, 'text' => '#B05030', 'iconColor' => 'none', 'accent' => '#B05030'],
    ];
@endphp

@section('content')
<div class="min-h-full bg-[#F1F1F1] leading-[1.3]">
    <div class="bg-primaryColor pt-5 text-white">
        <p class="text-[18px] font-medium text-center">Profile</p>
        <hr class="my-2 border-white">
        <div class="px-[30px] py-5">
            @foreach ($people as $person)
                @unless ($loop->first)
                    <hr class="my-[17px] border-white">
                @endunless
                @if ($person['admin'])
                    <a href="{{ route('app.profile-details') }}" class="block text-[15px] font-medium">{{ $person['name'] }} &nbsp;- Admin</a>
                @else
                    <p class="text-[15px] font-medium">{{ $person['name'] }}</p>
                @endif
            @endforeach
        </div>
        <div class="px-5">
            <div class="bg-white rounded-t-[20px]">
                <p class="px-[10px] py-[10px] {{ $heading }}">Policies</p>
                @include('app.partials.account-row', $rows['terms'])
            </div>
        </div>
    </div>

    <div class="px-5">
        <div class="bg-white rounded-b-[20px] text-center">
            @include('app.partials.account-row', $rows['privacy'])
            <p class="px-[10px] text-left {{ $heading }}">Settings</p>
            @if ($patient['is_nhs_patient'])
                @include('app.partials.account-row', $rows['nhs'])
            @endif
            @include('app.partials.account-row', $rows['notification'])
            @include('app.partials.account-row', $rows['logout'])
            <p class="py-[10px] text-[13px] text-[#393837]">App Version: 1.0.7 (13)</p>
        </div>
    </div>
</div>
@endsection
