<?php

namespace App\Support;

use Illuminate\Support\Carbon;

/**
 * Single source of truth for every hardcoded value shown across both the
 * Web and App clones. Both formats read the same arrays so displayed
 * content can never diverge between them - only chrome/layout differs.
 */
class DemoData
{
    public static function patient(): array
    {
        return [
            'id' => 1,
            'first_name' => 'Sarah',
            'last_name' => 'Bennett',
            'email' => 'sarah.bennett@example.com',
            'dob' => '14/03/1991',
            'contact_number' => '07123 456789',
            'is_nhs_patient' => true,
            'is_gp_linked' => true,
            'avatar' => null,
            'is_multi_pharmacy_admin' => true,
        ];
    }

    public static function pharmacy(): array
    {
        return [
            'id' => 1,
            'name' => 'Wellcare Pharmacy - High Street',
            'phone' => '020 7946 0958',
            'address' => '24 High Street, Kensington, London',
            'postcode' => 'W8 4RT',
            'status' => 'active',
            'ods_code' => 'FLM29',
            'manager_name' => 'Priya Anand',
            'pmr_type' => 'Cegedim (Nexphase)',
            'city' => 'London',
            'country' => 'United Kingdom',
            'review_link' => 'https://google.com/reviews/wellcare-high-street',
            'allow_video_appointment' => true,
            'show_on_website' => true,
            'chat_status' => 'real_time_response',
            'chat_text_limit' => 140,
            'welcome_message' => "Welcome to Wellcare Pharmacy! We're here to help with your prescriptions, appointments and any health questions.",
            'hours' => [
                ['day' => 'Monday', 'open' => '09:00', 'close' => '18:00'],
                ['day' => 'Tuesday', 'open' => '09:00', 'close' => '18:00'],
                ['day' => 'Wednesday', 'open' => '09:00', 'close' => '18:00'],
                ['day' => 'Thursday', 'open' => '09:00', 'close' => '18:00'],
                ['day' => 'Friday', 'open' => '09:00', 'close' => '19:00'],
                ['day' => 'Saturday', 'open' => '09:00', 'close' => '13:00'],
                ['day' => 'Sunday', 'open' => null, 'close' => null],
            ],
        ];
    }

    public static function services(): array
    {
        return [
            [
                'id' => 1,
                'label' => 'Book By App',
                'label_color' => 'bg-blue text-white',
                'number' => 1,
                'title' => 'NHS Flu Vaccination',
                'price' => 0,
                'description' => 'Protect yourself this winter with a free NHS flu vaccination administered by our qualified pharmacist. Takes around 10 minutes, no appointment paperwork needed on the day.',
                'is_pharmacy_first' => false,
                'appointment_types' => ['In Pharmacy'],
                'delivery_types' => [],
                'book_by' => 'app',
                'image' => '/assets/app/images/pharmacy_service_image.svg',
            ],
            [
                'id' => 2,
                'label' => 'Video Call',
                'label_color' => 'bg-sky text-white',
                'number' => 2,
                'title' => 'Pharmacist Consultation',
                'price' => 15,
                'description' => 'A one-to-one video consultation with our pharmacist to discuss any minor ailment, medication query, or general health concern from the comfort of your home.',
                'is_pharmacy_first' => false,
                'appointment_types' => ['Video Call'],
                'delivery_types' => [],
                'book_by' => 'video',
                'image' => '/assets/app/images/pharmacy_service_image.svg',
            ],
            [
                'id' => 3,
                'label' => 'Book By App / Video Call',
                'label_color' => 'bg-purple text-white',
                'number' => 3,
                'title' => 'Pharmacy First - Sore Throat',
                'price' => 0,
                'description' => 'Under the NHS Pharmacy First service, get assessed and treated for a sore throat without needing a GP appointment. A short eligibility questionnaire is completed before booking.',
                'is_pharmacy_first' => true,
                'appointment_types' => ['In Pharmacy', 'Video Call'],
                'delivery_types' => [],
                'book_by' => 'app',
                'image' => '/assets/app/images/pharmacy_service_image.svg',
            ],
            [
                'id' => 4,
                'label' => 'Book By Phone',
                'label_color' => 'bg-orange text-white',
                'number' => 4,
                'title' => 'Blood Pressure Check',
                'price' => 0,
                'description' => 'A free NHS blood pressure check for patients aged 40 and over, or anyone with concerns about their blood pressure.',
                'is_pharmacy_first' => false,
                'appointment_types' => ['In Pharmacy'],
                'delivery_types' => [],
                'book_by' => 'phone',
                'image' => '/assets/app/images/pharmacy_service_image.svg',
            ],
            [
                'id' => 5,
                'label' => 'Book IP Service',
                'label_color' => 'bg-green text-white',
                'number' => 5,
                'title' => 'Travel Health Clinic',
                'price' => 35,
                'description' => 'Travelling abroad? Book a travel health consultation covering vaccinations and antimalarial advice tailored to your destination.',
                'is_pharmacy_first' => false,
                'appointment_types' => ['In Pharmacy'],
                'delivery_types' => ['Collect in branch'],
                'book_by' => 'app',
                'image' => '/assets/app/images/pharmacy_service_image.svg',
            ],
            [
                'id' => 6,
                'label' => 'Book By App',
                'label_color' => 'bg-pink text-white',
                'number' => 6,
                'title' => 'Weight Management Support',
                'price' => 20,
                'description' => 'Ongoing one-to-one support sessions with our pharmacist to help you reach and maintain a healthy weight.',
                'is_pharmacy_first' => false,
                'appointment_types' => ['In Pharmacy', 'Video Call'],
                'delivery_types' => [],
                'book_by' => 'app',
                'image' => '/assets/app/images/pharmacy_service_image.svg',
            ],
        ];
    }

    public static function appointments(): array
    {
        return [
            [
                'booking_id' => 'BK-10231',
                'service' => 'Pharmacist Consultation',
                'pharmacy' => 'Wellcare Pharmacy - High Street',
                'type' => 'Video Call',
                'date' => '02 Oct 2026',
                'slot' => '10:30 AM',
                'amount' => 15,
                'notes' => 'Query about interaction between ibuprofen and current blood pressure medication.',
                'status' => 'Approved',
                'payment_status' => 'Paid',
                'is_meeting_active' => true,
                'meeting_link' => 'https://meet.appandtap.example/room/bk-10231',
                'tab' => 'upcoming',
                'can_cancel' => true,
                'can_reschedule' => true,
            ],
            [
                'booking_id' => 'BK-10198',
                'service' => 'NHS Flu Vaccination',
                'pharmacy' => 'Wellcare Pharmacy - High Street',
                'type' => 'In Pharmacy',
                'date' => '05 Oct 2026',
                'slot' => '02:00 PM',
                'amount' => 0,
                'notes' => '',
                'status' => 'Pending Approval',
                'payment_status' => 'N/A',
                'is_meeting_active' => false,
                'meeting_link' => null,
                'tab' => 'upcoming',
                'can_cancel' => true,
                'can_reschedule' => true,
            ],
            [
                'booking_id' => 'BK-09876',
                'service' => 'Blood Pressure Check',
                'pharmacy' => 'Wellcare Pharmacy - High Street',
                'type' => 'In Pharmacy',
                'date' => '18 Sep 2026',
                'slot' => '11:15 AM',
                'amount' => 0,
                'notes' => '',
                'status' => 'Attended',
                'payment_status' => 'N/A',
                'is_meeting_active' => false,
                'meeting_link' => null,
                'tab' => 'previous',
                'can_cancel' => false,
                'can_reschedule' => false,
            ],
            [
                'booking_id' => 'BK-09654',
                'service' => 'Travel Health Clinic',
                'pharmacy' => 'Wellcare Pharmacy - High Street',
                'type' => 'In Pharmacy',
                'date' => '02 Sep 2026',
                'slot' => '09:30 AM',
                'amount' => 35,
                'notes' => '',
                'status' => 'Cancelled',
                'payment_status' => 'Refunded',
                'is_meeting_active' => false,
                'meeting_link' => null,
                'tab' => 'previous',
                'can_cancel' => false,
                'can_reschedule' => false,
            ],
            [
                'booking_id' => 'BK-09410',
                'service' => 'Pharmacy First - Sore Throat',
                'pharmacy' => 'Wellcare Pharmacy - High Street',
                'type' => 'Video Call',
                'date' => '20 Aug 2026',
                'slot' => '04:45 PM',
                'amount' => 0,
                'notes' => '',
                'status' => 'Dispensed',
                'payment_status' => 'N/A',
                'is_meeting_active' => false,
                'meeting_link' => null,
                'tab' => 'consultations',
                'can_cancel' => false,
                'can_reschedule' => false,
            ],
        ];
    }

    public static function prescriptions(): array
    {
        return [
            'rx_orders' => [
                ['medicine' => 'Amoxicillin 500mg Capsules', 'quantity' => '21 capsule', 'date_requested' => '20 Sep 2026', 'status' => 'Issued'],
                ['medicine' => 'Atorvastatin 20mg Tablets', 'quantity' => '28 tablet', 'date_requested' => '12 Sep 2026', 'status' => 'Requested'],
                ['medicine' => 'Salbutamol Inhaler 100mcg', 'quantity' => '1 inhaler', 'date_requested' => '30 Aug 2026', 'status' => 'Rejected'],
            ],
            'repeat_meds' => [
                ['id' => 1, 'medicine' => 'Metformin 500mg Tablets (56)', 'last_issue' => '28 Aug 2026', 'dose' => '1 tablet, twice daily', 'status' => null, 'reminder_times' => ['08:00', '20:00'], 'days_to_go' => '6 days to go'],
                ['id' => 2, 'medicine' => 'Atorvastatin 20mg Tablets (28)', 'last_issue' => '02 Sep 2026', 'dose' => '1 tablet at night', 'status' => 'Requested 12 Sep 2026', 'reminder_times' => null],
                ['id' => 3, 'medicine' => 'Ramipril 5mg Capsules (28)', 'last_issue' => '15 Aug 2026', 'dose' => '1 capsule each morning', 'status' => null, 'reminder_times' => null],
                ['id' => 4, 'medicine' => 'Levothyroxine 100mcg Tablets (28)', 'last_issue' => '09 Sep 2026', 'dose' => '1 tablet each morning', 'status' => null, 'reminder_times' => ['07:00'], 'days_to_go' => '15 days to go'],
            ],
        ];
    }

    public static function gpAppointments(): array
    {
        return [
            ['doctor' => 'Dr. Helen Foster', 'staff_role' => 'GP Appointment', 'start' => '09:20 AM', 'end' => '09:35 AM', 'date' => '29 Sep 2026', 'location' => 'Kensington Park Surgery', 'can_cancel' => true],
            ['doctor' => 'Dr. Michael Osei', 'staff_role' => 'Telephone Appointment', 'start' => '02:00 PM', 'end' => '02:10 PM', 'date' => '10 Sep 2026', 'location' => 'Kensington Park Surgery', 'can_cancel' => false],
        ];
    }

    public static function reminders(): array
    {
        return [
            ['id' => 1, 'medicine' => 'Metformin 500mg Tablets', 'dose' => '1 tablet, twice daily', 'icon' => '/assets/app/images/pill4.svg', 'times' => ['08:00 AM', '08:00 PM'], 'end_date' => null, 'status_today' => 'pending'],
            ['id' => 2, 'medicine' => 'Levothyroxine 100mcg Tablets', 'dose' => '1 tablet each morning', 'icon' => '/assets/app/images/pill2.svg', 'times' => ['07:00 AM'], 'end_date' => null, 'status_today' => 'taken'],
            ['id' => 3, 'medicine' => 'Amoxicillin 500mg Capsules', 'dose' => '1 capsule, four times daily', 'icon' => '/assets/app/images/pill1.svg', 'times' => ['09:00 AM', '01:00 PM', '05:00 PM', '09:00 PM'], 'end_date' => '30 Sep 2026', 'status_today' => 'missed'],
        ];
    }

    public static function chat(): array
    {
        return [
            'enabled' => true,
            'mode' => 'multi',
            'messages' => [
                ['id' => 1, 'from' => 'pharmacy', 'text' => 'Hi Sarah, your NHS flu vaccination is confirmed for 5 Oct at 2:00 PM.', 'time' => '09:12 AM', 'date' => '24 Sep 2026'],
                ['id' => 2, 'from' => 'patient', 'text' => 'Thank you! Do I need to bring anything with me?', 'time' => '09:15 AM', 'date' => '24 Sep 2026'],
                ['id' => 3, 'from' => 'pharmacy', 'text' => 'Just yourself - no paperwork needed. See you then!', 'time' => '09:16 AM', 'date' => '24 Sep 2026'],
                ['id' => 4, 'from' => 'pharmacy', 'text' => 'Your video consultation with the pharmacist is ready to join.', 'time' => '10:25 AM', 'date' => '02 Oct 2026', 'meeting_link' => 'https://meet.appandtap.example/room/bk-10231'],
            ],
        ];
    }

    public static function notificationPreferences(): array
    {
        return [
            ['category' => 'Appointment Reminders', 'icon' => '/assets/app/images/appointment_icon.svg', 'chat' => true, 'email' => true, 'notification' => true],
            ['category' => 'Prescription Updates', 'icon' => '/assets/app/images/medicine_b_icon.svg', 'chat' => true, 'email' => false, 'notification' => true],
            ['category' => 'Pharmacy Messages', 'icon' => '/assets/app/images/chat_icon.svg', 'chat' => true, 'email' => true, 'notification' => true],
            ['category' => 'Marketing & Offers', 'icon' => '/assets/app/images/notification_icon.svg', 'chat' => false, 'email' => false, 'notification' => false],
        ];
    }

    public static function consultations(): array
    {
        return [
            [
                'date' => '20 Aug 2026',
                'condition' => 'Sore Throat',
                'pharmacy' => 'Wellcare Pharmacy - High Street',
                'status' => 'Dispensed',
                'payment_status' => 'N/A',
                'summary' => [
                    'Patient Details' => 'Sarah Bennett, DOB 14/03/1991',
                    'Appointment Details' => '20 Aug 2026, 04:45 PM, Video Call',
                    'Consultation Details' => 'Pharmacy First - Sore Throat assessment',
                    'Clinician Decision' => 'Supplied - Phenoxymethylpenicillin 250mg',
                ],
            ],
        ];
    }

    /**
     * The Group Owner business that owns the pharmacies below; it heads the
     * header's portal dropdown. 'color' is the group's white-label colour
     * (AppServiceProvider's view composer on the real site); null keeps the
     * AppAndTap default blue theme. '#f25a2a' is the orange sampled from the
     * live portal, if a white-labelled look is wanted.
     */
    public static function staffGroupOwner(): array
    {
        return [
            'name' => 'Wellcare Healthcare Group',
            'site_code' => 'G-0001',
            'color' => null,
            'logo' => '/assets/web/images/appntap-logo-big-coloured.svg',
        ];
    }

    public static function staffPharmacies(): array
    {
        return [
            ['id' => 1, 'name' => 'Wellcare Pharmacy - High Street', 'site_code' => 'P-0006', 'initials' => 'WH', 'email' => 'highstreet@wellcare.example', 'phone' => '020 7946 0958', 'address' => '24 High Street, Kensington, London W8 4RT', 'active' => true, 'patients' => 96, 'awaiting' => 2],
            ['id' => 2, 'name' => 'Wellcare Pharmacy - Riverside', 'site_code' => 'P-0007', 'initials' => 'WR', 'email' => 'riverside@wellcare.example', 'phone' => '020 7946 0412', 'address' => '8 Wharf Lane, London SE1 9PQ', 'active' => true, 'patients' => 58, 'awaiting' => 1],
            ['id' => 3, 'name' => 'Wellcare Pharmacy - Northgate', 'site_code' => 'P-0009', 'initials' => 'WN', 'email' => 'northgate@wellcare.example', 'phone' => '020 7946 0733', 'address' => '51 Northgate Road, London N14 5RT', 'active' => false, 'patients' => 23, 'awaiting' => 0],
        ];
    }

    /**
     * Chat messages between the pharmacy and its patients, newest first, as
     * the dashboard widget and livewire/admin/notification/index list them.
     * Each row opens that patient's Messages tab.
     */
    public static function staffNotifications(): array
    {
        return [
            ['patient_id' => 1, 'pharmacy_id' => 1, 'patient' => 'Emily Carter', 'message' => 'Your repeat prescription is ready to collect.', 'type' => 'sent', 'datetime' => '28-09-2026 09:14:22', 'is_read' => true],
            ['patient_id' => 6, 'pharmacy_id' => 1, 'patient' => 'Priya Shah', 'message' => 'Can I move my flu jab to Thursday afternoon?', 'type' => 'received', 'datetime' => '27-09-2026 16:40:05', 'is_read' => false],
            ['patient_id' => 3, 'pharmacy_id' => 1, 'patient' => 'Sarah Bennett', 'message' => 'Your video consultation link is now active.', 'type' => 'sent', 'datetime' => '27-09-2026 10:25:41', 'is_read' => true],
            ['patient_id' => 1, 'pharmacy_id' => 1, 'patient' => 'Emily Carter', 'message' => 'Thank you, I received the medication today.', 'type' => 'received', 'datetime' => '26-09-2026 13:02:17', 'is_read' => true],
            ['patient_id' => 7, 'pharmacy_id' => 2, 'patient' => 'Daniel Hughes', 'message' => 'Reminder: blood pressure check on 2 Oct.', 'type' => 'sent', 'datetime' => '25-09-2026 08:30:00', 'is_read' => false],
        ];
    }

    /**
     * Series behind the dashboard's lower widgets (No. of Sign Ups, Service
     * Bookings and Rx Orders, Top Services, Revenue by Service, Revenue
     * Generated), each defaulting to "This Year" as in the source.
     */
    public static function staffDashboardWidgets(): array
    {
        return [
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            'signups' => [8, 12, 15, 11, 19, 23, 17, 26, 21],
            'rx_orders' => [34, 41, 38, 45, 52, 49, 57, 61, 58],
            'service_bookings' => [12, 18, 22, 19, 27, 31, 29, 35, 33],
            'patients_not_ordered' => [
                ['name' => 'Michael Osei', 'days' => 64, 'nhs_number' => '485 777 4410', 'dob' => '09/05/1963', 'address' => '3 Church Lane', 'last_order' => '25/07/2026'],
                ['name' => 'Olivia Grant', 'days' => 47, 'nhs_number' => '485 777 9988', 'dob' => '28/07/1979', 'address' => '45 Kensington Road', 'last_order' => '11/08/2026'],
                ['name' => 'James Whitfield', 'days' => 39, 'nhs_number' => '485 777 2073', 'dob' => '02/11/1985', 'address' => '8 Palace Gardens', 'last_order' => '19/08/2026'],
                ['name' => 'Priya Shah', 'days' => 33, 'nhs_number' => '485 777 6621', 'dob' => '17/01/1994', 'address' => '19 Earls Court Road', 'last_order' => '25/08/2026'],
            ],
            'top_services' => [
                ['label' => 'NHS Flu Vaccination', 'total' => 142, 'color' => '#0070D5'],
                ['label' => 'Pharmacist Consultation', 'total' => 86, 'color' => '#368DDD'],
                ['label' => 'Pharmacy First - Sore Throat', 'total' => 54, 'color' => '#8FD689'],
                ['label' => 'Blood Pressure Check', 'total' => 41, 'color' => '#FF9153'],
            ],
            'revenue_by_service' => [
                ['label' => 'Travel Health Clinic', 'total' => 2450, 'percentage' => 49, 'color' => '#0070D5'],
                ['label' => 'Pharmacist Consultation', 'total' => 1290, 'percentage' => 26, 'color' => '#368DDD'],
                ['label' => 'Weight Management Support', 'total' => 860, 'percentage' => 17, 'color' => '#8FD689'],
                ['label' => 'Other', 'total' => 400, 'percentage' => 8, 'color' => '#FF9153'],
            ],
            'revenue_generated' => [320, 410, 385, 470, 520, 610, 580, 690, 640],
            // revenue-generated.blade.php popup: a full year, split Jan-Jun / Jul-Dec.
            'revenue_years' => [2026, 2025, 2024],
            'revenue_monthly' => [
                'January' => 320, 'February' => 410, 'March' => 385, 'April' => 470, 'May' => 520, 'June' => 610,
                'July' => 580, 'August' => 690, 'September' => 640, 'October' => 0, 'November' => 0, 'December' => 0,
            ],
        ];
    }

    /**
     * The pharmacy's patients (Admin.Patient.PatientList). 'status' pending
     * means the account awaits approval; 'chat' feeds the Messages tab and
     * 'repeat_meds' the Prescription > Repeat Meds tab.
     */
    public static function staffPatients(): array
    {
        $patients = [
            [
                'id' => 1, 'first_name' => 'Emily', 'last_name' => 'Carter', 'dob' => '14/03/1991', 'email' => 'emily.carter@example.com', 'contact_number' => '07234 567890',
                'address_line_1' => '12 Bridge Street', 'postcode' => 'W8 5RF', 'nhs_number' => '485 777 3456', 'user_type' => 'NHS', 'status' => 'active', 'pharmacy_id' => 1, 'created_at' => '2026-01-12',
                'notes' => 'Prefers collection after 5pm. Allergic to penicillin.',
                'repeat_meds' => [
                    ['medicine' => 'Metformin 500mg Tablets', 'quantity' => 56, 'last_issued' => '20/08/2026', 'dose' => 'One tablet twice a day', 'requested' => '20/09/2026'],
                    ['medicine' => 'Ramipril 5mg Capsules', 'quantity' => 28, 'last_issued' => '02/09/2026', 'dose' => 'One capsule daily', 'requested' => null],
                ],
                'chat' => [
                    ['from' => 'patient', 'text' => 'Hi, is my Metformin ready to collect yet?', 'time' => '08:52'],
                    ['from' => 'pharmacy', 'text' => 'Hi Emily, yes - your repeat prescription is ready to collect.', 'time' => '09:14'],
                    ['from' => 'patient', 'text' => 'Thank you, I received the medication today.', 'time' => '13:02'],
                ],
            ],
            [
                'id' => 2, 'first_name' => 'James', 'last_name' => 'Whitfield', 'dob' => '02/11/1985', 'email' => 'james.whitfield@example.com', 'contact_number' => '07345 678901',
                'address_line_1' => '8 Palace Gardens', 'postcode' => 'W8 4RU', 'nhs_number' => null, 'user_type' => 'Private', 'status' => 'active', 'pharmacy_id' => 2, 'created_at' => '2026-02-03', 'is_guest' => true,
                'notes' => '', 'repeat_meds' => [], 'chat' => [],
            ],
            [
                'id' => 3, 'first_name' => 'Sarah', 'last_name' => 'Bennett', 'dob' => '22/08/1988', 'email' => 'sarah.bennett@example.com', 'contact_number' => '07123 456789',
                'address_line_1' => '24 High Street', 'postcode' => 'W8 4RT', 'nhs_number' => '485 777 1122', 'user_type' => 'NHS', 'status' => 'active', 'pharmacy_id' => 1, 'created_at' => '2026-02-20',
                'notes' => '',
                'repeat_meds' => [
                    ['medicine' => 'Atorvastatin 20mg Tablets', 'quantity' => 28, 'last_issued' => '12/09/2026', 'dose' => 'One tablet at night', 'requested' => null],
                ],
                'chat' => [
                    ['from' => 'pharmacy', 'text' => 'Your video consultation link is now active.', 'time' => '10:25'],
                    ['from' => 'patient', 'text' => 'Great, joining now.', 'time' => '10:27'],
                ],
            ],
            [
                'id' => 4, 'first_name' => 'Michael', 'last_name' => 'Osei', 'dob' => '09/05/1963', 'email' => 'michael.osei@example.com', 'contact_number' => '07456 789012',
                'address_line_1' => '3 Church Lane', 'postcode' => 'W8 6TY', 'nhs_number' => null, 'user_type' => 'Private', 'status' => 'pending', 'pharmacy_id' => 1, 'created_at' => '2026-09-18',
                'notes' => '', 'repeat_meds' => [], 'chat' => [],
            ],
            [
                'id' => 5, 'first_name' => 'Olivia', 'last_name' => 'Grant', 'dob' => '28/07/1979', 'email' => 'olivia.grant@example.com', 'contact_number' => '07567 890123',
                'address_line_1' => '45 Kensington Road', 'postcode' => 'W8 5TN', 'nhs_number' => '485 777 9988', 'user_type' => 'NHS', 'status' => 'pending', 'pharmacy_id' => 1, 'created_at' => '2026-09-21',
                'notes' => '', 'repeat_meds' => [], 'chat' => [],
            ],
            [
                'id' => 6, 'first_name' => 'Priya', 'last_name' => 'Shah', 'dob' => '17/01/1994', 'email' => 'priya.shah@example.com', 'contact_number' => '07678 901234',
                'address_line_1' => '19 Earls Court Road', 'postcode' => 'W8 6EB', 'nhs_number' => '485 777 6621', 'user_type' => 'NHS', 'status' => 'active', 'pharmacy_id' => 1, 'created_at' => '2026-09-12',
                'notes' => '',
                'repeat_meds' => [
                    ['medicine' => 'Sertraline 50mg Tablets', 'quantity' => 28, 'last_issued' => '25/08/2026', 'dose' => 'One tablet in the morning', 'requested' => null],
                ],
                'chat' => [
                    ['from' => 'pharmacy', 'text' => 'Hi Priya, your flu vaccination is booked for Tuesday at 11:00.', 'time' => '15:58'],
                    ['from' => 'patient', 'text' => 'Can I move my flu jab to Thursday afternoon?', 'time' => '16:40'],
                ],
            ],
            [
                'id' => 7, 'first_name' => 'Daniel', 'last_name' => 'Hughes', 'dob' => '30/10/1971', 'email' => 'daniel.hughes@example.com', 'contact_number' => '07789 012345',
                'address_line_1' => '2 Wharf Lane', 'postcode' => 'SE1 9PQ', 'nhs_number' => '485 777 5307', 'user_type' => 'NHS', 'status' => 'active', 'pharmacy_id' => 2, 'created_at' => '2026-04-08',
                'notes' => '',
                'repeat_meds' => [
                    ['medicine' => 'Amlodipine 5mg Tablets', 'quantity' => 28, 'last_issued' => '01/09/2026', 'dose' => 'One tablet daily', 'requested' => null],
                ],
                'chat' => [
                    ['from' => 'pharmacy', 'text' => 'Reminder: blood pressure check on 2 Oct.', 'time' => '08:30'],
                ],
            ],
            [
                'id' => 8, 'first_name' => 'Thomas', 'last_name' => 'Reed', 'dob' => '05/06/1982', 'email' => 'thomas.reed@example.com', 'contact_number' => '07812 345670',
                'address_line_1' => '7 Abingdon Road', 'postcode' => 'W8 6AH', 'nhs_number' => '485 777 8142', 'user_type' => 'NHS', 'status' => 'active', 'pharmacy_id' => 1, 'created_at' => '2026-03-05',
                'notes' => '',
                'repeat_meds' => [
                    ['medicine' => 'Salbutamol 100mcg Inhaler', 'quantity' => 1, 'last_issued' => '28/08/2026', 'dose' => 'Two puffs when required', 'requested' => null],
                ],
                'chat' => [],
            ],
            [
                'id' => 9, 'first_name' => 'Aisha', 'last_name' => 'Rahman', 'dob' => '19/12/1990', 'email' => 'aisha.rahman@example.com', 'contact_number' => '07923 456781',
                'address_line_1' => '31 Phillimore Gardens', 'postcode' => 'W8 7QG', 'nhs_number' => '485 777 2659', 'user_type' => 'NHS', 'status' => 'active', 'pharmacy_id' => 1, 'created_at' => '2026-04-17',
                'notes' => '', 'repeat_meds' => [], 'chat' => [],
            ],
            [
                'id' => 10, 'first_name' => 'George', 'last_name' => 'Mitchell', 'dob' => '11/04/1958', 'email' => 'george.mitchell@example.com', 'contact_number' => '07534 567892',
                'address_line_1' => '16 Stratford Road', 'postcode' => 'W8 6QD', 'nhs_number' => null, 'user_type' => 'Private', 'status' => 'active', 'pharmacy_id' => 1, 'created_at' => '2026-05-29',
                'notes' => '', 'repeat_meds' => [], 'chat' => [],
            ],
            [
                'id' => 11, 'first_name' => 'Chloe', 'last_name' => 'Evans', 'dob' => '03/09/2001', 'email' => 'chloe.evans@example.com', 'contact_number' => '07645 678903',
                'address_line_1' => '9 Scarsdale Villas', 'postcode' => 'W8 6PT', 'nhs_number' => null, 'user_type' => 'Private', 'status' => 'active', 'pharmacy_id' => 1, 'created_at' => '2026-07-02', 'is_guest' => true,
                'notes' => '', 'repeat_meds' => [], 'chat' => [],
            ],
            [
                'id' => 12, 'first_name' => 'Hannah', 'last_name' => 'Brooks', 'dob' => '25/02/1976', 'email' => 'hannah.brooks@example.com', 'contact_number' => '07756 789014',
                'address_line_1' => '52 Earls Court Road', 'postcode' => 'W8 6EJ', 'nhs_number' => '485 777 4038', 'user_type' => 'NHS', 'status' => 'active', 'pharmacy_id' => 1, 'created_at' => '2026-08-14',
                'notes' => '',
                'repeat_meds' => [
                    ['medicine' => 'Levothyroxine 75mcg Tablets', 'quantity' => 28, 'last_issued' => '10/09/2026', 'dose' => 'One tablet in the morning', 'requested' => null],
                ],
                'chat' => [],
            ],
            [
                'id' => 13, 'first_name' => 'Ryan', 'last_name' => 'Walsh', 'dob' => '14/07/1987', 'email' => 'ryan.walsh@example.com', 'contact_number' => '07867 890125',
                'address_line_1' => '14 Tooley Street', 'postcode' => 'SE1 2TF', 'nhs_number' => '485 777 6190', 'user_type' => 'NHS', 'status' => 'active', 'pharmacy_id' => 2, 'created_at' => '2026-05-11',
                'notes' => '', 'repeat_meds' => [], 'chat' => [],
            ],
            [
                'id' => 14, 'first_name' => 'Megan', 'last_name' => 'Lloyd', 'dob' => '08/01/1995', 'email' => 'megan.lloyd@example.com', 'contact_number' => '07978 901236',
                'address_line_1' => '5 Bermondsey Street', 'postcode' => 'SE1 3UW', 'nhs_number' => null, 'user_type' => 'Private', 'status' => 'pending', 'pharmacy_id' => 2, 'created_at' => '2026-09-24',
                'notes' => '', 'repeat_meds' => [], 'chat' => [],
            ],
            [
                'id' => 15, 'first_name' => 'Arjun', 'last_name' => 'Patel', 'dob' => '21/10/1968', 'email' => 'arjun.patel@example.com', 'contact_number' => '07489 012347',
                'address_line_1' => '40 Southwark Street', 'postcode' => 'SE1 1UN', 'nhs_number' => '485 777 3724', 'user_type' => 'NHS', 'status' => 'active', 'pharmacy_id' => 2, 'created_at' => '2026-06-30',
                'notes' => '', 'repeat_meds' => [], 'chat' => [],
            ],
        ];

        // Upstream's patient queries all orderBy('created_at', 'DESC').
        return collect([...$patients, ...self::rosterPatients($patients)])->sortByDesc('created_at')->values()->all();
    }

    /**
     * Older, approved sign-ups that pad each active pharmacy out to the
     * roster size staffPharmacies() reports, so the lists paginate as they do
     * upstream. Deterministic, and all dated before the hand-written patients.
     */
    private static function rosterPatients(array $existing): array
    {
        $firstNames = ['Adam', 'Alice', 'Amelia', 'Ben', 'Charlotte', 'Connor', 'Deborah', 'Edward', 'Eleanor', 'Fatima', 'Freya', 'Graham', 'Harriet', 'Isaac', 'Jack', 'Jasmine', 'Kate', 'Liam', 'Lucy', 'Mohammed', 'Nadia', 'Nathan', 'Patrick', 'Rachel', 'Rosie', 'Samuel', 'Sanjay', 'Tariq', 'Victoria', 'William'];
        $lastNames = ['Ahmed', 'Allen', 'Baker', 'Clarke', 'Cooper', 'Davies', 'Edwards', 'Fletcher', 'Green', 'Hall', 'Harris', 'Hussain', 'Jackson', 'Khan', 'King', 'Lewis', 'Marshall', 'Morgan', 'Murphy', 'Nolan', 'Okafor', 'Parker', 'Price', 'Roberts', 'Scott', 'Singh', 'Taylor', 'Thompson', 'Ward', 'Wright'];
        $areas = [
            1 => ['postcode' => 'W8', 'streets' => ['Kensington Church Street', 'Holland Park Avenue', 'Argyll Road', 'Campden Hill Road', 'Edwardes Square', 'Marloes Road', 'Pembroke Road', 'Allen Street', 'Victoria Road', 'Lexham Gardens']],
            2 => ['postcode' => 'SE1', 'streets' => ['Borough High Street', 'Union Street', 'Great Suffolk Street', 'Long Lane', 'Snowsfields', 'Weston Street', 'Crucifix Lane', 'Druid Street', 'Lant Street', 'Tabard Street']],
        ];
        $letters = 'ABDEFGHJLNPQRSTUWXY';

        $taken = collect($existing)->map(fn (array $p) => $p['first_name'].' '.$p['last_name'])->flip()->all();
        $id = collect($existing)->max('id');
        $n = 0;
        $roster = [];

        foreach (self::staffPharmacies() as $pharmacy) {
            if (! $pharmacy['active']) {
                continue;
            }

            $missing = $pharmacy['patients'] - collect($existing)->where('pharmacy_id', $pharmacy['id'])->count();
            $area = $areas[$pharmacy['id']];

            for ($i = 0; $i < $missing; $n++) {
                $first = $firstNames[$n % count($firstNames)];
                $last = $lastNames[($n * 7 + intdiv($n, count($firstNames))) % count($lastNames)];
                if (isset($taken["$first $last"])) {
                    continue;
                }
                $taken["$first $last"] = true;
                $i++;

                $isNhs = $n % 4 !== 3;
                $k = $n + 11;
                $roster[] = [
                    'id' => ++$id, 'first_name' => $first, 'last_name' => $last,
                    'dob' => sprintf('%02d/%02d/%d', ($k * 7) % 28 + 1, ($k * 5) % 12 + 1, 1945 + ($k * 13) % 60),
                    'email' => strtolower("$first.$last").'@example.com',
                    'contact_number' => sprintf('07%03d %06d', 100 + ($k * 37) % 900, ($k * 7919) % 1000000),
                    'address_line_1' => (($k * 3) % 90 + 1).' '.$area['streets'][$k % count($area['streets'])],
                    'postcode' => sprintf('%s %d%s%s', $area['postcode'], $k % 9 + 1, $letters[$k % 19], $letters[($k * 5) % 19]),
                    'nhs_number' => $isNhs ? sprintf('485 777 %04d', ($k * 7331 + 17) % 10000) : null,
                    'user_type' => $isNhs ? 'NHS' : 'Private', 'status' => 'active', 'pharmacy_id' => $pharmacy['id'],
                    'created_at' => Carbon::parse('2025-12-28')->subDays($n * 5)->toDateString(),
                    'is_guest' => ! $isNhs && $n % 3 === 0,
                    'notes' => '', 'repeat_meds' => [], 'chat' => [],
                ];
            }
        }

        return $roster;
    }

    public static function staffAppointments(): array
    {
        return [
            [
                'id' => 1, 'appointment_no' => 'APT-240926-0012', 'patient_id' => 1, 'patient' => 'Emily Carter', 'email' => 'emily.carter@example.com',
                'pharmacy' => 'Wellcare Pharmacy - High Street', 'nhs_number' => '485 777 3456', 'dob' => '14/03/1991',
                'service' => 'Pharmacist Consultation', 'date' => '29/09/2026', 'time' => '09:30 AM', 'appointment_type' => 'Video Call', 'amount' => 25,
                'requested_on' => '24/09/2026 09:12 AM', 'status' => 'Approved', 'payment_status' => 'Paid',
                'is_video' => true, 'is_meeting_active' => true,
            ],
            [
                'id' => 2, 'appointment_no' => 'APT-230926-0009', 'patient_id' => 2, 'patient' => 'James Whitfield', 'email' => 'james.whitfield@example.com',
                'pharmacy' => 'Wellcare Pharmacy - Riverside', 'nhs_number' => null, 'dob' => '02/11/1985',
                'service' => 'NHS Flu Vaccination', 'date' => '29/09/2026', 'time' => '11:00 AM', 'appointment_type' => 'In Pharmacy', 'amount' => 0,
                'requested_on' => '23/09/2026 02:05 PM', 'status' => 'Pending Approval', 'payment_status' => 'N/A',
                'is_video' => false, 'is_meeting_active' => false,
            ],
            [
                'id' => 3, 'appointment_no' => 'APT-220926-0004', 'patient_id' => 3, 'patient' => 'Sarah Bennett', 'email' => 'sarah.bennett@example.com',
                'pharmacy' => 'Wellcare Pharmacy - High Street', 'nhs_number' => '485 777 1122', 'dob' => '22/08/1988',
                'service' => 'Pharmacist Consultation', 'date' => '30/09/2026', 'time' => '02:00 PM', 'appointment_type' => 'Video Call', 'amount' => 25,
                'requested_on' => '22/09/2026 08:40 AM', 'status' => 'Approved', 'payment_status' => 'Paid',
                'is_video' => true, 'is_meeting_active' => false,
            ],
            [
                'id' => 4, 'appointment_no' => 'APT-200926-0021', 'patient_id' => 5, 'patient' => 'Olivia Grant', 'email' => 'olivia.grant@example.com',
                'pharmacy' => 'Wellcare Pharmacy - High Street', 'nhs_number' => '485 777 9988', 'dob' => '28/07/1979',
                'service' => 'Travel Health Clinic', 'date' => '01/10/2026', 'time' => '10:15 AM', 'appointment_type' => 'In Pharmacy', 'amount' => 45,
                'requested_on' => '20/09/2026 04:22 PM', 'status' => 'Cancelled', 'payment_status' => 'Refunded',
                'is_video' => false, 'is_meeting_active' => false,
            ],
            [
                'id' => 5, 'appointment_no' => 'APT-190926-0017', 'patient_id' => 4, 'patient' => 'Michael Osei', 'email' => 'michael.osei@example.com',
                'pharmacy' => 'Wellcare Pharmacy - Riverside', 'nhs_number' => null, 'dob' => '09/05/1963',
                'service' => 'Blood Pressure Check', 'date' => '02/10/2026', 'time' => '03:30 PM', 'appointment_type' => 'In Pharmacy', 'amount' => 0,
                'requested_on' => '19/09/2026 11:03 AM', 'status' => 'Pending Approval', 'payment_status' => 'N/A',
                'is_video' => false, 'is_meeting_active' => false,
            ],
        ];
    }

    /**
     * "Today's Re-order Reminder": repeat-order reminders patients set up.
     * 'today' rows are due today; the rest are overdue and not actioned.
     */
    public static function staffReorderReminders(): array
    {
        return [
            ['id' => 1, 'patient' => 'Emily Carter', 'nhs_number' => '485 777 3456', 'dob' => '14/03/1991', 'medicine' => 'Metformin 500mg Tablets', 'order_date' => '28/09/2026', 'repeat_type' => '28 Days', 'is_ordered' => false, 'today' => true],
            ['id' => 2, 'patient' => 'Sarah Bennett', 'nhs_number' => '485 777 1122', 'dob' => '22/08/1988', 'medicine' => 'Atorvastatin 20mg Tablets', 'order_date' => '28/09/2026', 'repeat_type' => '28 Days', 'is_ordered' => true, 'today' => true],
            ['id' => 3, 'patient' => 'Priya Shah', 'nhs_number' => '485 777 6621', 'dob' => '17/01/1994', 'medicine' => 'Sertraline 50mg Tablets', 'order_date' => '28/09/2026', 'repeat_type' => 'Monthly', 'is_ordered' => false, 'today' => true],
            ['id' => 4, 'patient' => 'Daniel Hughes', 'nhs_number' => '485 777 5307', 'dob' => '30/10/1971', 'medicine' => 'Amlodipine 5mg Tablets', 'order_date' => '24/09/2026', 'repeat_type' => '28 Days', 'is_ordered' => false, 'today' => false],
            ['id' => 5, 'patient' => 'Olivia Grant', 'nhs_number' => '485 777 9988', 'dob' => '28/07/1979', 'medicine' => 'Levothyroxine 50mcg Tablets', 'order_date' => '21/09/2026', 'repeat_type' => '56 Days', 'is_ordered' => false, 'today' => false],
            ['id' => 6, 'patient' => 'Michael Osei', 'nhs_number' => '485 777 4410', 'dob' => '09/05/1963', 'medicine' => 'Omeprazole 20mg Capsules', 'order_date' => '15/09/2026', 'repeat_type' => 'Monthly', 'is_ordered' => false, 'today' => false],
        ];
    }

    /** "Patients who changed their Pharmacy": patients released to another pharmacy. */
    public static function staffChangedPharmacy(): array
    {
        return [
            ['name' => 'Thomas Reid', 'nhs_number' => '485 777 8123', 'dob' => '11/04/1958', 'address' => '7 Abingdon Villas', 'date_of_change' => '26/09/2026', 'days' => 2],
            ['name' => 'Hannah Moore', 'nhs_number' => '485 777 3390', 'dob' => '03/12/1996', 'address' => '31 Phillimore Walk', 'date_of_change' => '21/09/2026', 'days' => 7],
            ['name' => 'Aisha Rahman', 'nhs_number' => '485 777 6704', 'dob' => '19/06/1983', 'address' => '14 Allen Street', 'date_of_change' => '12/09/2026', 'days' => 16],
            ['name' => 'George Patel', 'nhs_number' => '485 777 1286', 'dob' => '25/02/1949', 'address' => '60 Stratford Road', 'date_of_change' => '02/09/2026', 'days' => 26],
        ];
    }

    public static function staffDashboardStats(): array
    {
        $reminders = collect(self::staffReorderReminders());

        return [
            'total_patients' => collect(self::staffPharmacies())->sum('patients'),
            'today_reorder_reminders' => $reminders->where('today', true)->count(),
            'reorder_not_actioned' => $reminders->where('is_ordered', false)->count(),
            'active_patients' => 162,
            'rx_orders' => count(self::staffOrders()),
            'patients_changed_pharmacy' => count(self::staffChangedPharmacy()),
            'video_call_mins' => 46,
        ];
    }

    /**
     * IP clinic consultation requests (ip-consultation-request.blade.php):
     * the table row plus everything its "View" popup shows.
     */
    public static function staffIpConsultations(): array
    {
        return [
            [
                'id' => 1, 'patient_id' => 6, 'patient' => 'Priya Shah', 'dob' => '17/01/1994', 'email' => 'priya.shah@example.com', 'mobile' => '07678 901234', 'address' => '19 Earls Court Road W8 6EB',
                'request_date' => '24/09/2026 10:42 AM', 'appointment_date' => '27/09/2026 11:30 AM', 'service' => 'Genital Chlamydia Trachomatis',
                'clinician' => 'Dr. Femi Adeyemi', 'gphc_number' => '2087341', 'clinician_email' => 'f.adeyemi@wellcare.example', 'e_sign' => 'F. Adeyemi',
                'status' => 'Requested', 'payment_status' => 'Paid',
                'questionnaire' => [
                    ['question' => 'Have you been diagnosed with chlamydia in the last 6 weeks?', 'answer' => 'Yes'],
                    ['question' => 'Do you have any of the following symptoms?', 'answer' => 'Discharge, Pain when urinating'],
                    ['question' => 'Are you pregnant or breastfeeding?', 'answer' => 'No'],
                    ['question' => 'Do you have any allergies to antibiotics?', 'answer' => 'No'],
                ],
                'vitals' => ['Respiratory Rate' => '16', 'Heart Rate' => '72', 'Tympanic Temperature' => '36.8', 'Blood Pressure' => '118/76'],
                'clinician_decision' => 'Suitable for treatment under the PGD. Doxycycline supplied with counselling on partner notification.',
                'prescription' => [
                    ['medicine_name' => 'Doxycycline 100mg Capsules', 'quantity' => '14', 'price' => 12.5, 'dosage' => 'One capsule twice a day for 7 days', 'note' => 'Take with plenty of water.'],
                ],
                'service_amount' => 30.0, 'paid_amount' => 42.5,
            ],
            [
                'id' => 2, 'patient_id' => 5, 'patient' => 'Olivia Grant', 'dob' => '28/07/1979', 'email' => 'olivia.grant@example.com', 'mobile' => '07567 890123', 'address' => '45 Kensington Road W8 5TN',
                'request_date' => '18/09/2026 09:05 AM', 'appointment_date' => '20/09/2026 02:15 PM', 'service' => 'Pharmacy First - Sore Throat',
                'clinician' => 'Dr. Femi Adeyemi', 'gphc_number' => '2087341', 'clinician_email' => 'f.adeyemi@wellcare.example', 'e_sign' => 'F. Adeyemi',
                'status' => 'Dispensed', 'payment_status' => 'N/A', 'sign_by' => 'Asha Patel', 'pharmacist_gphc' => '2081234', 'follow_up_available' => true,
                'questionnaire' => [
                    ['question' => 'How long have you had a sore throat?', 'answer' => '3 days'],
                    ['question' => 'FeverPAIN score', 'answer' => '4'],
                    ['question' => 'Are you allergic to penicillin?', 'answer' => 'No'],
                ],
                'vitals' => ['Tympanic Temperature' => '38.1', 'Heart Rate' => '88'],
                'clinician_decision' => 'FeverPAIN 4 - supplied Phenoxymethylpenicillin 250mg.',
                'prescription' => [
                    ['medicine_name' => 'Phenoxymethylpenicillin 250mg Tablets', 'quantity' => '40', 'price' => 0, 'dosage' => 'Two tablets four times a day for 5 days', 'note' => ''],
                ],
                'service_amount' => 0.0, 'paid_amount' => 0.0,
            ],
            [
                'id' => 3, 'patient_id' => 1, 'patient' => 'Emily Carter', 'dob' => '14/03/1991', 'email' => 'emily.carter@example.com', 'mobile' => '07234 567890', 'address' => '12 Bridge Street W8 5RF',
                'request_date' => '12/09/2026 03:20 PM', 'appointment_date' => '13/09/2026 10:00 AM', 'service' => 'Uncomplicated UTI (Women 16-64)',
                'clinician' => 'Dr. Femi Adeyemi', 'gphc_number' => '2087341', 'clinician_email' => 'f.adeyemi@wellcare.example', 'e_sign' => 'F. Adeyemi',
                'status' => 'Dispense Declined', 'payment_status' => 'N/A',
                'questionnaire' => [
                    ['question' => 'Do you have a burning sensation when urinating?', 'answer' => 'Yes'],
                    ['question' => 'Are you pregnant?', 'answer' => 'No'],
                    ['question' => 'Have you had a UTI in the last 3 months?', 'answer' => 'Yes'],
                ],
                'vitals' => ['Tympanic Temperature' => '37.2'],
                'clinician_decision' => 'Recurrent UTI within 3 months - referred to GP; supply declined.',
                'prescription' => [
                    ['medicine_name' => 'Nitrofurantoin 100mg MR Capsules', 'quantity' => '6', 'price' => 0, 'dosage' => 'One capsule twice a day for 3 days', 'note' => ''],
                ],
                'service_amount' => 0.0, 'paid_amount' => 0.0,
            ],
        ];
    }

    public static function staffOrders(): array
    {
        $orders = [
            ['patient_id' => 1, 'patient' => 'Emily Carter', 'email' => 'emily.carter@example.com', 'phone' => '07234 567890', 'medicine' => 'Metformin 500mg Tablets (56)', 'date_requested' => '20/09/2026', 'status' => 'Requested'],
            ['patient_id' => 3, 'patient' => 'Sarah Bennett', 'email' => 'sarah.bennett@example.com', 'phone' => '07123 456789', 'medicine' => 'Atorvastatin 20mg Tablets (28)', 'date_requested' => '12/09/2026', 'status' => 'Issued'],
            ['patient_id' => 2, 'patient' => 'James Whitfield', 'email' => 'james.whitfield@example.com', 'phone' => '07345 678901', 'medicine' => 'Amoxicillin 500mg Capsules (21)', 'date_requested' => '30/08/2026', 'status' => 'Rejected'],
        ];

        // More NHS repeat orders per pharmacy: [Requested, Issued, Rejected].
        $plan = [1 => [2, 17, 5], 2 => [1, 5, 0]];
        $medicines = ['Amlodipine 5mg Tablets (28)', 'Omeprazole 20mg Capsules (28)', 'Levothyroxine 50mcg Tablets (28)', 'Ramipril 5mg Capsules (28)', 'Lansoprazole 30mg Capsules (28)', 'Bisoprolol 2.5mg Tablets (28)', 'Simvastatin 40mg Tablets (28)', 'Citalopram 20mg Tablets (28)', 'Losartan 50mg Tablets (28)', 'Montelukast 10mg Tablets (28)', 'Folic Acid 5mg Tablets (28)', 'Salbutamol 100mcg Inhaler (1)'];
        $patients = collect(self::staffPatients())->where('status', 'active')->whereNotNull('nhs_number');

        foreach ($plan as $pharmacyId => [$requested, $issued, $rejected]) {
            $pool = $patients->where('pharmacy_id', $pharmacyId)->values();
            $statuses = array_fill(0, $requested, 'Requested');
            $processed = $issued + $rejected;
            for ($i = 0; $i < $processed; $i++) {
                $statuses[] = ($i % 4 === 3 && $rejected-- > 0) ? 'Rejected' : 'Issued';
            }

            foreach ($statuses as $i => $status) {
                $p = $pool[($i * 7 + $pharmacyId) % $pool->count()];
                $med = $p['repeat_meds'][0] ?? null;
                // New requests are the latest; processed ones stretch back ~3 days apart.
                $date = $status === 'Requested'
                    ? Carbon::parse('2026-09-27')->subDays($i * 2)
                    : Carbon::parse('2026-09-23')->subDays(($i - $requested) * 3);
                $orders[] = [
                    'patient_id' => $p['id'], 'patient' => $p['first_name'].' '.$p['last_name'], 'email' => $p['email'], 'phone' => $p['contact_number'],
                    'medicine' => $med ? "{$med['medicine']} ({$med['quantity']})" : $medicines[($i + $p['id']) % count($medicines)],
                    'date_requested' => $date->format('d/m/Y'), 'status' => $status,
                ];
            }
        }

        // Pharmacy::repeatMedicineOrders*() are ->latest().
        return collect($orders)->sortByDesc(fn (array $o) => Carbon::createFromFormat('d/m/Y', $o['date_requested'])->toDateString())->values()->all();
    }

    /**
     * The pharmacy's services list (livewire/admin/pharmacy-service/index):
     * the patient app's services plus the group's IP clinic services, with
     * the per-service toggles the table shows.
     */
    public static function staffPharmacyServices(): array
    {
        $toggles = [
            1 => ['pin_it' => true, 'same_day' => true, 'schedule' => true, 'book_app' => true, 'book_phone' => false, 'video' => false, 'status' => true],
            2 => ['pin_it' => false, 'same_day' => true, 'schedule' => true, 'book_app' => false, 'book_phone' => false, 'video' => true, 'status' => true],
            3 => ['pin_it' => true, 'same_day' => false, 'schedule' => true, 'book_app' => true, 'book_phone' => false, 'video' => true, 'status' => true],
            4 => ['pin_it' => false, 'same_day' => false, 'schedule' => false, 'book_app' => false, 'book_phone' => true, 'video' => false, 'status' => true],
            5 => ['pin_it' => false, 'same_day' => false, 'schedule' => true, 'book_app' => true, 'book_phone' => false, 'video' => false, 'status' => true],
            6 => ['pin_it' => false, 'same_day' => false, 'schedule' => true, 'book_app' => true, 'book_phone' => false, 'video' => true, 'status' => false],
        ];

        $services = array_map(fn (array $s) => [
            'id' => $s['id'],
            'title' => $s['title'],
            'amount' => $s['price'],
            'description' => $s['description'],
            'is_ip_clinic' => false,
            'is_pharmacy_first' => $s['is_pharmacy_first'],
        ] + $toggles[$s['id']], self::services());

        $services[] = ['id' => 7, 'title' => 'Genital Chlamydia Trachomatis', 'amount' => 30, 'description' => 'Consultation and treatment for uncomplicated genital chlamydia under the independent prescriber clinic.', 'is_ip_clinic' => true, 'is_pharmacy_first' => false, 'pin_it' => false, 'same_day' => true, 'schedule' => true, 'book_app' => true, 'book_phone' => false, 'video' => true, 'status' => true];
        $services[] = ['id' => 8, 'title' => 'Uncomplicated UTI (Women 16-64)', 'amount' => 0, 'description' => 'Assessment and supply of antibiotics for uncomplicated urinary tract infections in women aged 16 to 64.', 'is_ip_clinic' => true, 'is_pharmacy_first' => false, 'pin_it' => false, 'same_day' => true, 'schedule' => true, 'book_app' => true, 'book_phone' => false, 'video' => false, 'status' => true];

        return $services;
    }

    /**
     * Everything the Calendar (FullCalendar in appointment-info.blade.php)
     * plots: the dashboard's upcoming appointments plus this month's past
     * ones, each with ISO start/end times.
     */
    public static function staffCalendarAppointments(): array
    {
        $past = [
            ['id' => 6, 'appointment_no' => 'APT-080926-0003', 'patient_id' => 3, 'patient' => 'Sarah Bennett', 'email' => 'sarah.bennett@example.com', 'pharmacy' => 'Wellcare Pharmacy - High Street', 'nhs_number' => '485 777 1122', 'dob' => '22/08/1988', 'service' => 'NHS Flu Vaccination', 'date' => '08/09/2026', 'time' => '10:00 AM', 'appointment_type' => 'In Pharmacy', 'amount' => 0, 'requested_on' => '02/09/2026 09:20 AM', 'status' => 'Attended', 'payment_status' => 'N/A', 'is_video' => false, 'is_meeting_active' => false],
            ['id' => 7, 'appointment_no' => 'APT-150926-0011', 'patient_id' => 6, 'patient' => 'Priya Shah', 'email' => 'priya.shah@example.com', 'pharmacy' => 'Wellcare Pharmacy - High Street', 'nhs_number' => '485 777 6621', 'dob' => '17/01/1994', 'service' => 'Weight Management Support', 'date' => '15/09/2026', 'time' => '02:30 PM', 'appointment_type' => 'Video Call', 'amount' => 20, 'requested_on' => '10/09/2026 06:45 PM', 'status' => 'Not Attended', 'payment_status' => 'Paid', 'is_video' => true, 'is_meeting_active' => false],
            ['id' => 8, 'appointment_no' => 'APT-220926-0015', 'patient_id' => 1, 'patient' => 'Emily Carter', 'email' => 'emily.carter@example.com', 'pharmacy' => 'Wellcare Pharmacy - High Street', 'nhs_number' => '485 777 3456', 'dob' => '14/03/1991', 'service' => 'Blood Pressure Check', 'date' => '22/09/2026', 'time' => '11:15 AM', 'appointment_type' => 'In Pharmacy', 'amount' => 0, 'requested_on' => '18/09/2026 08:05 AM', 'status' => 'Attended', 'payment_status' => 'N/A', 'is_video' => false, 'is_meeting_active' => false],
            ['id' => 9, 'appointment_no' => 'APT-240926-0019', 'patient_id' => 7, 'patient' => 'Daniel Hughes', 'email' => 'daniel.hughes@example.com', 'pharmacy' => 'Wellcare Pharmacy - Riverside', 'nhs_number' => '485 777 5307', 'dob' => '30/10/1971', 'service' => 'Pharmacy First - Sore Throat', 'date' => '24/09/2026', 'time' => '04:00 PM', 'appointment_type' => 'In Pharmacy', 'amount' => 0, 'requested_on' => '24/09/2026 09:40 AM', 'status' => 'Attended', 'payment_status' => 'N/A', 'is_video' => false, 'is_meeting_active' => false],
        ];

        return array_map(function (array $a) {
            $start = Carbon::createFromFormat('d/m/Y h:i A', "{$a['date']} {$a['time']}");

            return $a + [
                'start' => $start->format('Y-m-d H:i:s'),
                'end' => $start->copy()->addMinutes(15)->format('Y-m-d H:i:s'),
                'notes' => $a['status'] === 'Attended' ? 'Seen in consultation room 1.' : '',
            ];
        }, array_merge(self::staffAppointments(), $past));
    }

    /** livewire/admin/broadcast/index.blade.php */
    public static function staffBroadcasts(): array
    {
        return [
            ['pharmacy_id' => 1, 'pharmacy' => 'Wellcare Pharmacy - High Street', 'message' => 'Our NHS flu vaccination clinic is now open. Book your free jab in the app today.', 'received_by' => 142, 'read_by' => 97, 'sent_at' => '20/09/2026 10:15 AM', 'filter' => ['age' => ['65-200', '55-65'], 'lastorder' => [], 'medicine' => []]],
            ['pharmacy_id' => 1, 'pharmacy' => 'Wellcare Pharmacy - High Street', 'message' => 'Reminder: we are closed on the bank holiday Monday. Please order repeat medicines by Friday.', 'received_by' => 138, 'read_by' => 121, 'sent_at' => '25/08/2026 04:30 PM', 'filter' => ['age' => [], 'lastorder' => ['0-30', '30-60'], 'medicine' => []]],
            ['pharmacy_id' => 2, 'pharmacy' => 'Wellcare Pharmacy - Riverside', 'message' => 'Your Metformin repeat can now be ordered straight from the app.', 'received_by' => 12, 'read_by' => 9, 'sent_at' => '02/08/2026 09:00 AM', 'filter' => ['age' => [], 'lastorder' => [], 'medicine' => ['Metformin 500mg Tablets']]],
        ];
    }

    /**
     * Repeat Orders (livewire/admin/repeat-order): patients the pharmacy
     * reminds to re-order. App patients carry an id; walk-ins are added by hand.
     */
    public static function staffRepeatOrderPatients(): array
    {
        return [
            [
                'id' => 1, 'patient_id' => 1, 'name' => 'Emily Carter', 'nhs_number' => '485 777 3456', 'nhs_verified' => true, 'dob' => '14/03/1991', 'contact' => '07234 567890', 'email' => 'emily.carter@example.com', 'address' => '12 Bridge Street', 'postcode' => 'W8 5RF',
                'medicines' => [
                    ['id' => 1, 'medicine' => 'Metformin 500mg Tablets', 'quantity' => 56, 'is_nhs' => true, 'next_reminder' => '28/09/2026', 'repeat_type' => 'Every 28 days', 'history' => ['31/08/2026 - 10:12', '03/08/2026 - 09:47']],
                    ['id' => 2, 'medicine' => 'Ramipril 5mg Capsules', 'quantity' => 28, 'is_nhs' => true, 'next_reminder' => null, 'repeat_type' => null, 'history' => []],
                ],
            ],
            [
                'id' => 2, 'patient_id' => 6, 'name' => 'Priya Shah', 'nhs_number' => '485 777 6621', 'nhs_verified' => true, 'dob' => '17/01/1994', 'contact' => '07678 901234', 'email' => 'priya.shah@example.com', 'address' => '19 Earls Court Road', 'postcode' => 'W8 6EB',
                'medicines' => [
                    ['id' => 3, 'medicine' => 'Sertraline 50mg Tablets', 'quantity' => 28, 'is_nhs' => true, 'next_reminder' => '28/09/2026', 'repeat_type' => 'Every 28 days', 'history' => ['31/08/2026 - 16:20']],
                ],
            ],
            [
                'id' => 3, 'patient_id' => null, 'name' => 'Margaret Ellis', 'nhs_number' => '485 777 9012', 'nhs_verified' => false, 'dob' => '04/02/1946', 'contact' => '020 7946 0188', 'email' => null, 'address' => '6 Hornton Street', 'postcode' => 'W8 7NT',
                'medicines' => [
                    ['id' => 4, 'medicine' => 'Levothyroxine 50mcg Tablets', 'quantity' => 56, 'is_nhs' => false, 'next_reminder' => '21/09/2026', 'repeat_type' => 'Every 56 days', 'history' => ['27/07/2026 - 11:05']],
                    ['id' => 5, 'medicine' => 'Omeprazole 20mg Capsules', 'quantity' => 28, 'is_nhs' => false, 'next_reminder' => '15/09/2026', 'repeat_type' => 'Custom', 'custom_days' => 30, 'history' => []],
                ],
            ],
        ];
    }

    /** livewire/admin/pharmacy-first-query/index.blade.php and its show page. */
    public static function staffPharmacyFirstQueries(): array
    {
        return [
            [
                'id' => 1, 'pharmacy_id' => 1, 'pharmacy' => 'Wellcare Pharmacy - High Street', 'patient' => 'Olivia Grant', 'contact' => '07567 890123', 'nhs' => '485 777 9988', 'dob' => '28/07/1979',
                'service' => 'Pharmacy First - Sore Throat', 'amount' => 0, 'datetime' => '18/09/2026 11:20 AM', 'status' => 'Appointment Booked',
                'answers' => [
                    1 => [
                        ['question' => 'How old are you?', 'options' => [['name' => 'Under 5', 'checked' => false], ['name' => '5 years or older', 'checked' => true]]],
                        ['question' => 'Are you pregnant?', 'options' => [['name' => 'Yes', 'checked' => false], ['name' => 'No', 'checked' => true]]],
                    ],
                    2 => [
                        ['question' => 'Do you have any of the following?', 'options' => [['name' => 'Fever in the last 24 hours', 'checked' => true], ['name' => 'Pus on tonsils', 'checked' => true], ['name' => 'Attended rapidly (within 3 days)', 'checked' => true], ['name' => 'Severely inflamed tonsils', 'checked' => false], ['name' => 'No cough or cold symptoms', 'checked' => true]]],
                    ],
                ],
            ],
            [
                'id' => 2, 'pharmacy_id' => 1, 'pharmacy' => 'Wellcare Pharmacy - High Street', 'patient' => 'Emily Carter', 'contact' => '07234 567890', 'nhs' => '485 777 3456', 'dob' => '14/03/1991',
                'service' => 'Pharmacy First - Insect Bite', 'amount' => 0, 'datetime' => '22/09/2026 07:35 AM', 'status' => 'Complete',
                'answers' => [
                    1 => [
                        ['question' => 'When were you bitten or stung?', 'options' => [['name' => 'Within the last 48 hours', 'checked' => true], ['name' => 'More than 48 hours ago', 'checked' => false]]],
                    ],
                    2 => [
                        ['question' => 'Do you have any of these symptoms?', 'options' => [['name' => 'Spreading redness', 'checked' => false], ['name' => 'Swelling', 'checked' => true], ['name' => 'Difficulty breathing', 'checked' => false]]],
                    ],
                ],
            ],
            [
                'id' => 3, 'pharmacy_id' => 2, 'pharmacy' => 'Wellcare Pharmacy - Riverside', 'patient' => 'James Whitfield', 'contact' => '07345 678901', 'nhs' => null, 'dob' => '02/11/1985',
                'service' => 'Pharmacy First - Sinusitis', 'amount' => 0, 'datetime' => '24/09/2026 03:10 PM', 'status' => 'Incomplete',
                'answers' => null,
            ],
        ];
    }

    /**
     * Patient Info > additional info and Patient Vitals (shown when the group
     * runs PGD / the IP clinic), keyed by patient id.
     */
    public static function staffPatientClinical(): array
    {
        return [
            1 => [
                'allergies' => ['Penicillin'], 'medicines' => ['Metformin 500mg', 'Ramipril 5mg'], 'history' => ['Type 2 Diabetes'], 'family' => ['Hypertension'], 'lifestyle' => ['Non-smoker'], 'examination' => [],
                'vitals' => ['height' => "5'6\"", 'weight' => 68, 'bmi' => 24.1, 'respiratory_rate' => 16, 'temperature' => 36.7, 'blood_pressure' => '124/82', 'heart_rate' => 72],
                'updated' => '14 Sep 2026, 01:11 PM',
            ],
            6 => [
                'allergies' => ['Wheat'], 'medicines' => ['Sertraline 50mg'], 'history' => [], 'family' => [], 'lifestyle' => [], 'examination' => [],
                'vitals' => ['height' => "5'4\"", 'weight' => 61, 'bmi' => 23.2, 'respiratory_rate' => 15, 'temperature' => 36.9, 'blood_pressure' => '118/76', 'heart_rate' => 70],
                'updated' => '27 Sep 2026, 11:30 AM',
            ],
        ];
    }

    /**
     * Pharmacies the app's search (SearchPharmacyController.searchPharmacyListApi)
     * can return; the patient's own is the first.
     */
    public static function pharmacyDirectory(): array
    {
        return [
            ['id' => 1, 'name' => 'Wellcare Pharmacy - High Street', 'location' => '24 High Street, Kensington, London', 'postcode' => 'W8 4RT', 'phone' => '020 7946 0958'],
            ['id' => 2, 'name' => 'Wellcare Pharmacy - Riverside', 'location' => '8 Wharf Lane, London', 'postcode' => 'SE1 9PQ', 'phone' => '020 7946 0412'],
            ['id' => 11, 'name' => 'Boots Pharmacy - Kensington High Street', 'location' => '127 Kensington High Street, London', 'postcode' => 'W8 5SF', 'phone' => '020 7937 0223'],
            ['id' => 12, 'name' => 'Lloyds Pharmacy - Earls Court Road', 'location' => '215 Earls Court Road, London', 'postcode' => 'SW5 9AN', 'phone' => '020 7373 2206'],
            ['id' => 13, 'name' => 'Superdrug Pharmacy - Notting Hill Gate', 'location' => '94 Notting Hill Gate, London', 'postcode' => 'W11 3HT', 'phone' => '020 7727 5891'],
        ];
    }

    public static function dependents(): array
    {
        return [
            ['id' => 1, 'name' => 'Oliver Bennett', 'dob' => '02/06/2015', 'relation' => 'Son', 'verified_by' => 'Email'],
        ];
    }

    public static function all(): array
    {
        return [
            'patient' => self::patient(),
            'pharmacy' => self::pharmacy(),
            'pharmacy_directory' => self::pharmacyDirectory(),
            'services' => self::services(),
            'appointments' => self::appointments(),
            'prescriptions' => self::prescriptions(),
            'gp_appointments' => self::gpAppointments(),
            'reminders' => self::reminders(),
            'chat' => self::chat(),
            'notification_preferences' => self::notificationPreferences(),
            'consultations' => self::consultations(),
            'dependents' => self::dependents(),
            'staff_group_owner' => self::staffGroupOwner(),
            'staff_pharmacies' => self::staffPharmacies(),
            'staff_patients' => self::staffPatients(),
            'staff_appointments' => self::staffAppointments(),
            'staff_orders' => self::staffOrders(),
            'staff_broadcasts' => self::staffBroadcasts(),
            'staff_dashboard_stats' => self::staffDashboardStats(),
            'staff_ip_consultations' => self::staffIpConsultations(),
            'staff_notifications' => self::staffNotifications(),
            'staff_pharmacy_first_queries' => self::staffPharmacyFirstQueries(),
            'staff_dashboard_widgets' => self::staffDashboardWidgets(),
            'staff_reorder_reminders' => self::staffReorderReminders(),
            'staff_changed_pharmacy' => self::staffChangedPharmacy(),
            'staff_pharmacy_services' => self::staffPharmacyServices(),
            'staff_calendar_appointments' => self::staffCalendarAppointments(),
            'staff_repeat_order_patients' => self::staffRepeatOrderPatients(),
            'staff_patient_clinical' => self::staffPatientClinical(),
        ];
    }
}
