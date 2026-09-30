@props(['title' => ''])

{{-- dashboard_screen.dart CustomAppBar.appBarWithoutAction(isNotVisible: true,
     backgroundColor: white): no back button (30px leading), black 20px title. --}}
<div class="h-[60px] shrink-0 bg-white flex items-center pl-[30px] pr-4">
    <p class="text-black font-bold text-[20px] leading-[1.3] truncate">{{ $title }}</p>
</div>
