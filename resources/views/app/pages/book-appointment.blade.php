@extends('layouts.app')

@php
    // booking_appointment_screen/book_appointment.dart on white. ?service= picks
    // the service (as Book Now does); ?reschedule={booking} is the reschedule
    // mode, which only asks for a new date and slot.
    $services = collect($demo['services']);
    $selected = $services->firstWhere('id', request()->integer('service'));
    $reschedule = collect($demo['appointments'])->firstWhere('booking_id', request('reschedule'));
    $types = $selected['appointment_types'] ?? ['In Pharmacy'];
    // The pharmacy's slot list for the chosen day; greyed ones are taken.
    $slots = ['09:00' => true, '09:30' => true, '10:00' => false, '10:30' => true, '11:00' => true, '11:30' => true, '14:00' => false, '14:30' => true];
    $label = 'text-[16px] font-medium text-[#737373]';
    $serviceText = fn (array $s) => $s['title'].' '.($s['price'] > 0 ? '(£'.$s['price'].')' : '');
@endphp

@section('content')
<div class="min-h-full bg-white px-[30px] pt-5 pb-[130px] leading-[1.3]">
    @unless ($reschedule)
        <p class="{{ $label }}">Whom are you booking this appointment for?</p>
        <div class="flex" data-radio-group>
            @include('app.partials.radio', ['name' => 'booking_for', 'label' => 'Only for myself', 'checked' => true])
            @include('app.partials.radio', ['name' => 'booking_for', 'label' => 'A Group', 'checked' => false])
        </div>
        {{-- "A Group": the people stepper (BtnCustom.mainButton 40x40 either side of the count). --}}
        <div class="hidden" data-group>
            <p class="mt-5 text-[16px] text-[#737373]">How many people would you like to book for?</p>
            <div class="mt-[10px] mb-[10px] flex items-center gap-[10px]">
                <button type="button" data-people="-1" class="w-10 h-10 rounded-[40px] bg-primaryColor flex items-center justify-center"><svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#737373" stroke-width="2" stroke-linecap="round"><path d="M5 12h14"/></svg></button>
                <span class="w-[50px] h-10 border-b border-[#737373] flex items-center justify-center text-[20px] font-medium text-black" data-people-count>0</span>
                <button type="button" data-people="1" class="w-10 h-10 rounded-[40px] bg-primaryColor flex items-center justify-center"><svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="#737373" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button>
            </div>
        </div>

        {{-- DropdownSearch<ServicesData> on a black underline. --}}
        <label class="mt-[15px] relative block px-[15px] border-b border-black">
            <select class="w-full h-12 appearance-none bg-transparent text-[16px] text-black outline-none pr-8"
                onchange="location.search = '?service=' + this.value">
                <option value="" {{ $selected ? '' : 'selected' }} disabled>Select Service</option>
                @foreach ($services as $service)
                    <option value="{{ $service['id'] }}" @selected($selected && $service['id'] === $selected['id'])>{{ $serviceText($service) }}</option>
                @endforeach
            </select>
            <svg class="absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 pointer-events-none" viewBox="0 0 24 24" fill="#000"><path d="M7 10l5 5 5-5z"/></svg>
        </label>

        @if ($selected)
            <div class="mt-[10px] px-[10px] py-[10px] bg-greyLightColor border-b border-[#4B4B4B] flex justify-between text-[16px] font-medium text-[#737373]">
                <p class="flex-1">{{ $selected['title'] }}</p>
                <p>{{ $selected['price'] > 0 ? '£'.$selected['price'] : '' }}</p>
            </div>
            @if (in_array($selected['book_by'], ['app', 'video'], true))
                <div class="mt-[10px] flex justify-end">
                    <a href="{{ route($selected['is_pharmacy_first'] ? 'app.pharmacy-first-questionnaire' : 'app.service-questionnaire') }}" class="w-[130px] h-10 rounded-[40px] bg-primaryColor flex items-center justify-center gap-[5px] text-white text-[16px] font-bold">
                        <span class="w-5 h-5 bg-white [mask:url('/assets/app/images/edit_icon.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/edit_icon.svg')_center/contain_no-repeat]"></span>
                        Fill Form
                    </a>
                </div>
            @endif
        @endif

        <div class="h-[30px]"></div>
        <p class="{{ $label }}">Consultation Type</p>
        <div class="flex" data-radio-group>
            @foreach ($types as $type)
                @include('app.partials.radio', ['name' => 'consultation_type', 'label' => $type, 'checked' => $loop->first])
            @endforeach
        </div>
        <div class="h-5"></div>
    @else
        <div class="py-3 border-b border-[#737373] text-[14px] text-[#393837]">{{ $reschedule['service'] }}</div>
        <div class="h-5"></div>
    @endunless

    {{-- TextFieldSimple(readOnly) with calendar prefix and suffix; picking a day loads its slots. --}}
    <label class="relative flex items-end h-12 border-b border-[#737373] cursor-pointer">
        <span class="shrink-0 px-[10px] pb-[10px]"><span class="block w-6 h-6 bg-[#737373] [mask:url('/assets/app/images/calender_icon.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/calender_icon.svg')_center/contain_no-repeat]"></span></span>
        <span class="flex-1 pb-[13px] text-[14px] text-[#6A6868]" data-date-label>Choose Date</span>
        <span class="shrink-0 px-[15px] pb-[10px]"><span class="block w-6 h-6 bg-[#737373] [mask:url('/assets/app/images/calender_icon.svg')_center/contain_no-repeat] [-webkit-mask:url('/assets/app/images/calender_icon.svg')_center/contain_no-repeat]"></span></span>
        <input type="date" class="absolute inset-0 opacity-0 cursor-pointer" min="{{ now('Europe/London')->toDateString() }}" onclick="this.showPicker && this.showPicker()" data-date-input>
    </label>

    <div class="hidden" data-slots>
        <p class="mt-5 {{ $label }}">Available Slots</p>
        <div class="mt-[10px] grid grid-cols-4 gap-[10px]">
            @foreach ($slots as $time => $available)
                <button type="button" @if ($available) data-slot @else disabled @endif
                    class="aspect-[2/1] py-[5px] rounded-[5px] border border-[#737373] text-[14px] text-[#737373] {{ $available ? 'bg-white' : 'bg-greyLightColor' }}">{{ $time }}</button>
            @endforeach
        </div>
    </div>

    @unless ($reschedule)
        <div class="h-5"></div>
        {{-- TextFieldSimple (maxLines 10, contentPadding vertical 20), underlined. --}}
        <textarea rows="1" placeholder="Additional Notes (Optional)" oninput="this.style.height = 'auto'; this.style.height = this.scrollHeight + 'px'" class="w-full pt-5 pb-5 bg-transparent border-b border-[#737373] text-[14px] text-black placeholder:text-[#6A6868] outline-none resize-none overflow-hidden"></textarea>
    @endunless
</div>

{{-- bottomNavigationBar: SafeArea > Padding(h30, v20), shown once a slot is picked. --}}
<div class="hidden absolute bottom-5 left-[30px] right-[30px]" data-book-button>
    <a href="{{ route('app.appointments-list') }}" class="block px-[15px] py-[15px] rounded-[5px] bg-primaryColor text-center text-white text-[16px]">{{ $reschedule ? 'Confirm Reschedule' : 'Book Appointment' }}</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const group = document.querySelector('[data-group]');
    document.querySelectorAll('input[name="booking_for"]').forEach((r, i) => r.addEventListener('change', () => group.classList.toggle('hidden', i === 0)));
    document.querySelectorAll('[data-people]').forEach((b) => b.addEventListener('click', () => {
        const count = document.querySelector('[data-people-count]');
        count.textContent = Math.max(0, Number(count.textContent) + Number(b.dataset.people));
    }));
    const input = document.querySelector('[data-date-input]');
    input.addEventListener('change', () => {
        document.querySelector('[data-date-label]').textContent = input.value ? input.value.split('-').reverse().join('-') : 'Choose Date';
        document.querySelector('[data-date-label]').classList.toggle('text-black', !!input.value);
        document.querySelector('[data-slots]').classList.toggle('hidden', !input.value);
    });
    document.querySelectorAll('[data-slot]').forEach((slot) => slot.addEventListener('click', () => {
        document.querySelectorAll('[data-slot]').forEach((s) => {
            const on = s === slot;
            s.classList.toggle('bg-primaryColor', on); s.classList.toggle('text-white', on); s.classList.toggle('border-transparent', on);
            s.classList.toggle('bg-white', !on); s.classList.toggle('text-[#737373]', !on);
        });
        document.querySelector('[data-book-button]').classList.remove('hidden');
    }));
});
</script>
@endsection
