{{-- Verbatim from admin/mainsite.blade.php's @if($isWhiteLabel) block: the group's
     colour replaces the default navy/blue gradient across the portal. --}}
<style>
    :root {
        --theme-color:
            {{ $colorCode }}
        ;
    }

    .text-darkBlue,
    .enigma .side-nav>ul>li>.side-menu.side-menu--active .side-menu__title,
    .enigma .side-nav>ul>li>.side-menu:hover:not(.side-menu--active):not(.side-menu--open),
    .hover\:text-darkBlue:hover,
    .text-lightBlue,
    .select-type-1 .select2-dropdown,
    .select-type-2 .select2-dropdown,
    .select-type-3 .select2-dropdown,
    .select-type-4 .select2-dropdown,
    .color-theme-tom-select .tom-select .ts-dropdown,
    .color-theme-tom-select .tom-select .ts-control,
    .color-theme-tom-select .tom-select .ts-control input,
    .enigma .side-nav>ul>li>ul>li>.side-menu:hover .side-menu__icon svg,
    .enigma .side-nav>ul>li>ul>li>.side-menu:hover .side-menu__title,
    .enigma .side-nav>ul>li>ul>li>.side-menu.side-menu--active .side-menu__title,
    .enigma .side-nav>ul>li>ul>li>.side-menu.side-menu--active .side-menu__icon,
    .text-theme-color {
        color:
            {{$colorCode}}
        ;
    }

    .select-type-4 .select2-container--default .select2-selection--single .select2-selection__rendered {
         color: {{$colorCode}} !important ;
    }

    .bg-gradient-to-t,
    .bg-gradient-to-b,
    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:disabled,
    .fc .fc-button-primary:hover,
    .\[\&\.active\]\:bg-gradient-to-t.active,
    .fc .fc-button-primary:not(:disabled):active,
    button.swal2-confirm.swal2-styled,
    .select-type-1 .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable,
    .select-type-2 .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable,
    .select-type-3 .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable,
    .select-type-4 .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable,
    .patient_book_appointment .mainbg,
    .litepicker .container__days .day-item.is-start-date,
    .litepicker .container__days .day-item.is-start-date:hover,
    .litepicker .container__days .day-item.is-end-date,
    .litepicker .container__days .day-item.is-end-date:hover,
    .litepicker .container__footer .button-apply,
    .national-holidays-calendar .litepicker .litepicker-custom-holiday-date,
    .national-holidays-calendar .litepicker .litepicker-custom-holiday-date:hover,
    .color-theme-tom-select .tom-select .ts-dropdown .option[data-selectable]:hover:not(.selected),
    .color-theme-tom-select .tom-select .ts-dropdown .option[data-selectable]:hover,
    .bg-theme-color,
    .hover-bg-theme-color:hover {
        background:
            {{$colorCode}}
        ;
    }

    .hover\:bg-gradient-to-t.from-\[\#002B56\].to-\[\#0070D5\]:hover {
        background:
            {{$colorCode}}
        ;
    }

    .bar_graph_table input:checked,
    .bar_graph_table input:checked:hover {
        background:
            {{$colorCode}}
            !important;
    }

    /* use class "whitelabel-checked-background" for styling on input checked */
    input.whitelabel-checked-background:checked,
    input.whitelabel-checked-background:checked:hover,
    input.whitelabel-checked-background:checked:focus {
        background-color:
            {{$colorCode}}
            !important;
    }

    .enigma .side-nav>ul>li>.side-menu.side-menu--active .side-menu__icon svg,
    .enigma .side-nav>ul>li>.side-menu:hover:not(.side-menu--active):not(.side-menu--open),
    .enigma .side-nav>ul>li>ul>li>.side-menu.side-menu--active .side-menu__icon svg {
        stroke:
            {{$colorCode}}
        ;
    }

    .\[\&\.active\]\:text-darkBlue.active,
    .litepicker .container__footer .button-cancel {
        color:
            {{$colorCode}}
        ;
    }

    .\[\&\.active\]\:border-b-darkBlue.active,
    .hover\:border-b-blue:hover {
        border-bottom-color:
            {{$colorCode}}
        ;
    }

    .border-lightBlue,
    .litepicker .container__footer .button-cancel,
    .border-blue,
    .border-theme-color {
        border-color:
            {{$colorCode}}
        ;
    }

    button.swal2-cancel.swal2-styled {
        color:
            {{$colorCode}}
        ;
        border: 1px solid
            {{$colorCode}}
        ;
    }

    div:where(.swal2-icon).swal2-warning {
        background: url(/admin/images/Information-white.svg),
            {{$colorCode}}
        ;
        background-position: center;
        background-repeat: no-repeat;
    }

    *:not(html) {
        scrollbar-color:
            {{$colorCode}}
            transparent;
    }

    .patient_book_appointment .mainbg {
        color: white;
    }

    .bg-\[\#007BFF\] {
        background-color: #007BFF;
    }

    .bg-\[\#20C997\] {
        background-color: #20C997;
    }

    .bg-\[\#66B2FF\] {
        background-color: #66B2FF;
    }

    .bg-\[\#FF9F40\] {
        background-color: #FF9F40;
    }

    .bg-\[\#4BC0C0\] {
        background-color: #4BC0C0;
    }

    .bg-\[\#FF6384\] {
        background-color: #FF6384;
    }

    .bg-\[\#36A2EB\] {
        background-color: #36A2EB;
    }

    .bg-\[\#9966FF\] {
        background-color: #9966FF;
    }

    .peer:checked~.peer-checked\:bg-themeColor {
        background-color:
            {{ $colorCode }}
            !important;
    }
</style>
