@php $patient = $demo['patient']; @endphp

<header class="flex items-center justify-between bg-white border-b border-slate-100 px-6 py-4">
    <div class="text-sm text-slate-400">
        <span class="text-lightBlue font-medium">Dashboard</span>
        <span class="mx-1">/</span>
        <span>{{ $title ?? '' }}</span>
    </div>

    <div class="flex items-center gap-5">
        <a href="{{ route('web.chat') }}" class="relative text-slate-400 hover:text-lightBlue">
            <i data-lucide="bell" class="w-5 h-5"></i>
            <span class="absolute -top-1 -right-1 bg-redclr text-white text-[10px] leading-none rounded-full w-4 h-4 flex items-center justify-center">2</span>
        </a>

        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-full bg-gradient-to-t from-[#002B56] to-[#0070D5] text-white flex items-center justify-center text-sm font-semibold">
                {{ strtoupper(substr($patient['first_name'], 0, 1) . substr($patient['last_name'], 0, 1)) }}
            </div>
            <div class="hidden sm:block leading-tight">
                <p class="text-sm font-semibold text-lightBlue">{{ $patient['first_name'] }} {{ $patient['last_name'] }}</p>
                <p class="text-xs text-slate-400">Patient</p>
            </div>
        </div>
    </div>
</header>
