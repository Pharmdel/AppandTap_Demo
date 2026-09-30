@extends('layouts.web')

@php $patient = $demo['patient']; @endphp

@section('content')
<div class="flex gap-6">
    <div class="w-56 shrink-0 flex flex-col gap-1">
        <a href="{{ route('web.profile-details') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white">Personal Information</a>
        <a href="{{ route('web.change-pharmacy') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-lightBlue bg-white hover:bg-slate-50">Pharmacy</a>
        <a href="{{ route('web.notification-settings') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-lightBlue bg-white hover:bg-slate-50">Setting</a>
    </div>

    <div class="flex-1 bg-white rounded-xl shadow p-6 max-w-2xl">
        <h2 class="text-lg font-semibold text-lightBlue mb-5">Profile</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs text-slate-400">First name</label>
                <input value="{{ $patient['first_name'] }}" disabled class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-slate-50">
            </div>
            <div>
                <label class="text-xs text-slate-400">Last name</label>
                <input value="{{ $patient['last_name'] }}" disabled class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-slate-50">
            </div>
            <div>
                <label class="text-xs text-slate-400">Email</label>
                <input value="{{ $patient['email'] }}" disabled class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-slate-50">
            </div>
            <div>
                <label class="text-xs text-slate-400">Date of Birth</label>
                <input value="{{ $patient['dob'] }}" disabled class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-slate-50">
            </div>
            <div class="sm:col-span-2">
                <label class="text-xs text-slate-400">Contact number</label>
                <div class="flex items-center gap-2">
                    <input id="contactInput" value="{{ $patient['contact_number'] }}" disabled class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm bg-slate-50">
                    <button onclick="const i = document.getElementById('contactInput'); i.disabled = !i.disabled; i.classList.toggle('bg-slate-50')" class="text-blue text-xs font-semibold">Edit</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
