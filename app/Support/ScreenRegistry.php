<?php

namespace App\Support;

/**
 * Every logical screen in the clone, defined once. Each row says how the
 * Web format and the App format each handle that screen: either a real
 * Blade `view`, or an `alias` redirect to the nearest real equivalent
 * (used when the real product genuinely has no dedicated screen for it).
 *
 * routes/demo.php loops this once to register both /web/{id} and /app/{id}
 * for every row - see that file for the route generation.
 */
class ScreenRegistry
{
    public static function all(): array
    {
        return [
            'home' => [
                'title' => 'Dashboard / Home',
                'web' => ['type' => 'view', 'view' => 'web.pages.dashboard'],
                'app' => ['type' => 'view', 'view' => 'app.pages.home', 'appbar' => 'home-appbar', 'bottomNav' => 'home'],
            ],
            'pharmacy-gate' => [
                'title' => 'Choose Pharmacy',
                'web' => ['type' => 'alias', 'target' => 'home'],
                'app' => ['type' => 'view', 'view' => 'app.pages.pharmacy-gate', 'appbar' => 'white-appbar', 'appTitle' => 'Choose Pharmacy', 'bottomNav' => 'none'],
            ],
            'awaiting-approval' => [
                'title' => 'Awaiting Approval',
                'web' => ['type' => 'alias', 'target' => 'home'],
                'app' => ['type' => 'view', 'view' => 'app.pages.awaiting-approval', 'appbar' => 'white-appbar', 'appTitle' => '', 'bottomNav' => 'none'],
            ],
            'choose-pharmacy-search' => [
                'title' => 'Pharmacy Search',
                'web' => ['type' => 'alias', 'target' => 'change-pharmacy'],
                'app' => ['type' => 'view', 'view' => 'app.pages.choose-pharmacy-search', 'appbar' => 'none', 'bottomNav' => 'none'],
            ],
            'change-pharmacy' => [
                'title' => 'Change Pharmacy',
                'web' => ['type' => 'view', 'view' => 'web.pages.change-pharmacy'],
                'app' => ['type' => 'view', 'view' => 'app.pages.pharmacy', 'appbar' => 'pharmacy-header', 'bottomNav' => 'pharmacy'],
            ],
            'prescriptions-rx-orders' => [
                'title' => 'Rx Orders',
                'web' => ['type' => 'view', 'view' => 'web.pages.prescriptions.rx-orders'],
                'app' => ['type' => 'view', 'view' => 'app.pages.prescriptions', 'appbar' => 'prescriptions-header', 'query' => ['tab' => 'rx-orders'], 'bottomNav' => 'none'],
            ],
            'prescriptions-repeat-meds' => [
                'title' => 'Repeat Medications',
                'web' => ['type' => 'view', 'view' => 'web.pages.prescriptions.repeat-meds'],
                'app' => ['type' => 'view', 'view' => 'app.pages.prescriptions', 'appbar' => 'prescriptions-header', 'query' => ['tab' => 'repeat-meds'], 'bottomNav' => 'none'],
            ],
            'prescriptions-gp-appointments' => [
                'title' => 'GP Appointments',
                'web' => ['type' => 'view', 'view' => 'web.pages.prescriptions.gp-appointments'],
                'app' => ['type' => 'view', 'view' => 'app.pages.prescriptions', 'appbar' => 'prescriptions-header', 'query' => ['tab' => 'gp-appointments'], 'bottomNav' => 'none'],
            ],
            'appointments-list' => [
                'title' => 'Appointments',
                'web' => ['type' => 'view', 'view' => 'web.pages.appointments'],
                'app' => ['type' => 'view', 'view' => 'app.pages.appointments', 'appbar' => 'appbar', 'bottomNav' => 'none'],
            ],
            'book-appointment' => [
                'title' => 'Book Appointment',
                'web' => ['type' => 'view', 'view' => 'web.pages.book-appointment'],
                'app' => ['type' => 'view', 'view' => 'app.pages.book-appointment', 'appbar' => 'appbar', 'bottomNav' => 'none'],
            ],
            'pharmacy-first-questionnaire' => [
                'title' => 'Pharmacy First Eligibility Questionnaire',
                'web' => ['type' => 'view', 'view' => 'web.pages.book-appointment'],
                'app' => ['type' => 'view', 'view' => 'app.pages.pharmacy-first-questionnaire', 'appbar' => 'none', 'bottomNav' => 'none'],
            ],
            'service-questionnaire' => [
                'title' => 'Service Questionnaire',
                'web' => ['type' => 'alias', 'target' => 'book-appointment'],
                'app' => ['type' => 'view', 'view' => 'app.pages.service-questionnaire', 'appbar' => 'none', 'bottomNav' => 'none'],
            ],
            'chat' => [
                'title' => 'Chat',
                'web' => ['type' => 'view', 'view' => 'web.pages.chat'],
                'app' => ['type' => 'view', 'view' => 'app.pages.chat', 'appbar' => 'appbar', 'bottomNav' => 'none'],
            ],
            'notification-settings' => [
                'title' => 'Notification Settings',
                'web' => ['type' => 'view', 'view' => 'web.pages.notification-settings'],
                'app' => ['type' => 'view', 'view' => 'app.pages.notification-settings', 'appbar' => 'appbar', 'appTitle' => 'Notification', 'bottomNav' => 'none'],
            ],
            'reminders-all' => [
                'title' => 'Reminders',
                'web' => ['type' => 'view', 'view' => 'web.pages.reminders'],
                'app' => ['type' => 'view', 'view' => 'app.pages.reminders-all', 'appbar' => 'reminders-header', 'bottomNav' => 'none'],
            ],
            'reminders-add' => [
                'title' => 'Add / Edit Reminder',
                'web' => ['type' => 'alias', 'target' => 'reminders-all'],
                'app' => ['type' => 'view', 'view' => 'app.pages.reminders-add', 'appbar' => 'appbar', 'appTitle' => 'Alarm Times', 'bottomNav' => 'none'],
            ],
            'reminders-order-medicine' => [
                'title' => 'Order-Medicine Reminder',
                'web' => ['type' => 'alias', 'target' => 'reminders-all'],
                'app' => ['type' => 'view', 'view' => 'app.pages.reminders-order-medicine', 'appbar' => 'appbar', 'appTitle' => 'Reminder', 'bottomNav' => 'none'],
            ],
            'my-day' => [
                'title' => 'My Day',
                'web' => ['type' => 'alias', 'target' => 'reminders-all'],
                'app' => ['type' => 'view', 'view' => 'app.pages.my-day', 'appbar' => 'myday-header', 'bottomNav' => 'none'],
            ],
            'profile-details' => [
                'title' => 'Profile / Personal Information',
                'web' => ['type' => 'view', 'view' => 'web.pages.profile', 'query' => ['tab' => 'personal']],
                'app' => ['type' => 'view', 'view' => 'app.pages.profile-details', 'appbar' => 'appbar', 'appTitle' => 'Patient Details', 'bottomNav' => 'none'],
            ],
            'account-menu' => [
                'title' => 'Account / Settings',
                'web' => ['type' => 'view', 'view' => 'web.pages.profile', 'query' => ['tab' => 'setting']],
                'app' => ['type' => 'view', 'view' => 'app.pages.account-menu', 'appbar' => 'none', 'bottomNav' => 'profile'],
            ],
            'service-details' => [
                'title' => 'Service Details',
                'web' => ['type' => 'alias', 'target' => 'change-pharmacy'],
                'app' => ['type' => 'view', 'view' => 'app.pages.service-details', 'appbar' => 'appbar', 'appTitle' => 'Services', 'bottomNav' => 'none'],
            ],
            'consultation' => [
                'title' => 'Consultation / PGD History',
                'web' => ['type' => 'view', 'view' => 'web.pages.consultation'],
                'app' => ['type' => 'view', 'view' => 'app.pages.appointments', 'appbar' => 'appbar', 'appTitle' => 'Appointments', 'bottomNav' => 'none', 'query' => ['tab' => 'consultations']],
            ],
            'video-conference' => [
                'title' => 'Video Conference',
                'web' => ['type' => 'alias', 'target' => 'appointments-list'],
                'app' => ['type' => 'view', 'view' => 'app.pages.video-conference', 'appbar' => 'none', 'bottomNav' => 'none'],
            ],
            'add-dependent' => [
                'title' => 'Add Dependent',
                'web' => ['type' => 'alias', 'target' => 'prescriptions-rx-orders'],
                'app' => ['type' => 'view', 'view' => 'app.pages.add-dependent', 'appbar' => 'appbar', 'bottomNav' => 'none'],
            ],
            'request-medication' => [
                'title' => 'Request Medication',
                'web' => ['type' => 'alias', 'target' => 'prescriptions-repeat-meds'],
                'app' => ['type' => 'view', 'view' => 'app.pages.request-medication', 'appbar' => 'appbar', 'appTitle' => '', 'bottomNav' => 'none'],
            ],
            'find-your-gp' => [
                'title' => 'Find Your GP',
                'web' => ['type' => 'alias', 'target' => 'prescriptions-gp-appointments'],
                'app' => ['type' => 'view', 'view' => 'app.pages.find-your-gp', 'appbar' => 'appbar', 'appTitle' => 'Find your GP', 'bottomNav' => 'none'],
            ],
            'do-you-have-linkage-key' => [
                'title' => 'GP Linkage Key',
                'web' => ['type' => 'alias', 'target' => 'prescriptions-gp-appointments'],
                'app' => ['type' => 'view', 'view' => 'app.pages.do-you-have-linkagekey', 'appbar' => 'appbar', 'appTitle' => '', 'bottomNav' => 'none'],
            ],
            'login-settings' => [
                'title' => 'Login Settings',
                'web' => ['type' => 'alias', 'target' => 'account-menu'],
                'app' => ['type' => 'view', 'view' => 'app.pages.login-settings', 'appbar' => 'appbar', 'bottomNav' => 'none'],
            ],
            'external-webview' => [
                'title' => 'External Site',
                'web' => ['type' => 'alias', 'target' => 'home'],
                'app' => ['type' => 'view', 'view' => 'app.pages.external-webview', 'appbar' => 'none', 'bottomNav' => 'none'],
            ],
        ];
    }

    public static function get(string $id): ?array
    {
        return self::all()[$id] ?? null;
    }
}
