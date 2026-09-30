<?php

namespace Tests\Feature;

use App\Support\Portal;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PharmacyPortalTest extends TestCase
{
    public static function pageProvider(): array
    {
        $paths = [
            '/pharmacy/dashboard', '/pharmacy/patients', '/pharmacy/new-patients',
            '/pharmacy/patients/1?type=prescription', '/pharmacy/patients/1?type=repeat-medications', '/pharmacy/patients/1?type=message',
            '/pharmacy/patients/1?type=notes', '/pharmacy/patients/1?type=booking', '/pharmacy/patients/1?type=patientinfo',
            '/pharmacy/patients/4?type=patientinfo', '/pharmacy/patients?pharmacy=2',
            '/pharmacy/appointments', '/pharmacy/orders', '/pharmacy/new-orders', '/pharmacy/repeat-orders',
            '/pharmacy/broadcast', '/pharmacy/broadcast/create', '/pharmacy/info', '/pharmacy/services',
            '/pharmacy/settings', '/pharmacy/notifications', '/pharmacy/pharmacy-first-queries',
            '/pharmacy/categorise-options', '/pharmacy/dispensed-consultations',
            '/pharmacy/repeat-orders/1', '/pharmacy/repeat-orders/3', '/pharmacy/pharmacy-first-queries/1',
            '/pharmacy/patients/1?type=consultation', '/pharmacy/patients/6?type=patientinfo', '/pharmacy/broadcast/create?tab=1',
            '/pharmacy/broadcast?tab=1', '/pharmacy/info?tab=1', '/pharmacy/services?tab=1', '/pharmacy/settings?tab=1',
        ];

        $cases = [];
        foreach (Portal::all() as $portal) {
            foreach ($paths as $path) {
                $cases["{$portal} {$path}"] = [$portal, $path];
            }
        }

        return $cases;
    }

    #[DataProvider('pageProvider')]
    public function test_every_portal_page_renders_for_both_roles(string $portal, string $path): void
    {
        $response = $this->withSession(['portal' => $portal])->get($path);

        $response->assertOk();
        $response->assertDontSee('Undefined variable', false);
        $response->assertDontSee('Undefined array key', false);
    }

    public function test_group_owner_is_the_default_portal(): void
    {
        $response = $this->get('/pharmacy/dashboard');

        $response->assertOk();
        $response->assertSee('Group Owner');
        $response->assertSee('All Pharmacies');
        $response->assertDontSee("Today's Re-order Reminder", false);
    }

    public function test_pharmacy_dashboard_shows_pharmacy_only_widgets(): void
    {
        $response = $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/dashboard');

        $response->assertOk();
        $response->assertSee("Today's Re-order Reminder", false);
        $response->assertSee('IP Consultation Request');
        $response->assertDontSee('Revenue Generated');
    }

    public function test_choosing_a_pharmacy_in_the_header_switches_into_it(): void
    {
        $this->get('/pharmacy/portal/pharmacy/2')
            ->assertRedirect(route('pharmacy-portal.dashboard'))
            ->assertSessionHas('portal', Portal::PHARMACY)
            ->assertSessionHas('pharmacy_id', 2);

        $this->get('/pharmacy/dashboard')
            ->assertOk()
            ->assertSee('Wellcare Pharmacy - Riverside (P-0007)')
            ->assertSee("Today's Re-order Reminder", false);
    }

    public function test_choosing_group_owner_in_the_header_switches_back(): void
    {
        $this->withSession(['portal' => Portal::PHARMACY, 'pharmacy_id' => 2])
            ->get('/pharmacy/portal/group-owner')
            ->assertRedirect(route('pharmacy-portal.dashboard'))
            ->assertSessionHas('portal', Portal::GROUP_OWNER);
    }

    public function test_invalid_portal_choices_are_not_found(): void
    {
        $this->get('/pharmacy/portal/super-admin')->assertNotFound();
        $this->get('/pharmacy/portal/pharmacy')->assertNotFound();
        $this->get('/pharmacy/portal/pharmacy/99')->assertNotFound();
    }

    public function test_header_dropdown_lists_group_owner_and_every_pharmacy(): void
    {
        $response = $this->get('/pharmacy/dashboard');

        $response->assertSee('id="portalDropdown"', false);
        $response->assertSee(route('pharmacy-portal.switch', 'group-owner'), false);
        foreach ([1, 2, 3] as $id) {
            $response->assertSee(route('pharmacy-portal.switch', ['pharmacy', $id]), false);
        }
    }

    public function test_portal_uses_the_live_site_stylesheets_and_default_blue_theme(): void
    {
        $response = $this->get('/pharmacy/dashboard');

        $response->assertSee('admin/css/app.css', false);
        $response->assertSee('admin/css/side-nav.css', false);
        $response->assertSee('side-nav no-scroll', false);
        $response->assertSee('background: #007ac2;', false);
        $response->assertSee('bg_gradient', false);
        $response->assertDontSee('--theme-color', false);
        $response->assertDontSee('cdn.tailwindcss.com', false);
    }

    public function test_preview_bar_no_longer_carries_the_role_switch(): void
    {
        $this->get('/')->assertOk()->assertDontSee('role-switch', false);
    }

    public function test_group_owner_reaches_patients_through_the_pharmacy_tabs(): void
    {
        $response = $this->withSession(['portal' => Portal::GROUP_OWNER])->get('/pharmacy/patients');

        $response->assertOk();
        $response->assertSee('Pharmacies (3)');
        $response->assertSee('Pharmacy Info');
    }

    public function test_unknown_patient_is_not_found(): void
    {
        $this->get('/pharmacy/patients/999')->assertNotFound();
    }

    public function test_a_bare_patient_url_opens_the_tab_the_lists_link_to(): void
    {
        $this->get('/pharmacy/patients/1')->assertRedirect('/pharmacy/patients/1?type=prescription');
        $this->get('/pharmacy/patients/4')->assertRedirect('/pharmacy/patients/4?type=patientinfo');
    }

    public function test_group_owner_dashboard_widgets_open_their_popups(): void
    {
        $response = $this->withSession(['portal' => Portal::GROUP_OWNER])->get('/pharmacy/dashboard');

        foreach (['pharmacy-patients-popup', 'changed-pharmacy-popup', 'appointment-popup', 'patient-not-order-popup', 'top-service-popup', 'revenue-service-popup', 'revenue-generated-popup'] as $popup) {
            $response->assertSee('data-popup-open="'.$popup.'"', false);
            $response->assertSee('id="'.$popup.'"', false);
        }
        $response->assertSee("Pharmacy Patient's List", false);
        $response->assertDontSee('id="reorder-reminder-popup"', false);
        $response->assertDontSee('id="ip-consultation-popup"', false);
    }

    public function test_pharmacy_dashboard_widgets_link_and_open_popups_like_the_live_site(): void
    {
        $response = $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/dashboard');

        foreach (['awaiting-patients-popup', 'reorder-reminder-popup', 'changed-pharmacy-popup', 'appointment-popup', 'ip-consultation-popup', 'patient-not-order-popup', 'top-service-popup'] as $popup) {
            $response->assertSee('data-popup-open="'.$popup.'"', false);
            $response->assertSee('id="'.$popup.'"', false);
        }
        $response->assertSee('data-popup-open="ip-view-1"', false);
        $response->assertSee('id="markAsDispensedPopUp"', false);
        $response->assertSee('id="book_confirm"', false);
        $response->assertSee('href="'.route('pharmacy-portal.patients').'"', false);
        $response->assertSee('href="'.route('pharmacy-portal.orders').'"', false);
        $response->assertSee('href="'.route('pharmacy-portal.notifications').'"', false);
        $response->assertSee(route('pharmacy-portal.patient-detail', ['id' => 6, 'type' => 'message']), false);
        $response->assertDontSee('id="revenue-generated-popup"', false);
    }

    public function test_group_owner_notifications_open_the_patient_inside_its_pharmacy(): void
    {
        $url = route('pharmacy-portal.patient-detail', ['id' => 7, 'pharmacy' => 2, 'type' => 'message']);

        $this->withSession(['portal' => Portal::GROUP_OWNER])->get('/pharmacy/dashboard')->assertSee($url);
        $this->withSession(['portal' => Portal::GROUP_OWNER])->get('/pharmacy/notifications')->assertSee($url);

        $this->withSession(['portal' => Portal::GROUP_OWNER])->get($url)
            ->assertOk()
            ->assertSee('Go Back')
            ->assertSee('Wellcare Pharmacy - Riverside (P-0007)')
            ->assertSee('Reminder: blood pressure check on 2 Oct.');
    }

    public function test_patient_messages_tab_shows_the_chat(): void
    {
        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/patients/6?type=message')
            ->assertOk()
            ->assertSeeInOrder(['Messages', '(1)'])
            ->assertSee('Can I move my flu jab to Thursday afternoon?')
            ->assertSee('placeholder="Type your message..."', false);
    }

    public function test_pharmacy_patients_page_is_the_live_patient_list(): void
    {
        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/patients')
            ->assertOk()
            ->assertSee('Patients (96)')
            // Newest sign-up first, as upstream's orderBy('created_at', 'DESC').
            ->assertSeeInOrder(['Olivia Grant (28/07/1979)', 'Priya Shah (17/01/1994)', 'Hannah Brooks', 'Emily Carter (14/03/1991)'])
            ->assertSee('Unapproved')
            ->assertSee('Export Patients')
            // PatientController@index (upstream) defaults the right pane to
            // the pharmacy's most recently added patient, on its Booking tab.
            ->assertSee('Priya Shah')
            ->assertSee('Weight Management Support')
            ->assertDontSee('no-record-found-new.png', false);
    }

    public function test_patient_list_paginates_like_the_live_left_links(): void
    {
        $session = ['portal' => Portal::PHARMACY, 'pharmacy_id' => 1];

        // custom-pagination-left-links: always 1, 2, ..., last-1, last.
        $this->withSession($session)->get('/pharmacy/patients')
            ->assertOk()
            ->assertSeeInOrder(['Go to page 2', '...', 'Go to page 9', 'Go to page 10'])
            ->assertDontSee('Go to page 3');

        // Page 2 is the next ten sign-ups; rows keep the page, as upstream.
        $this->withSession($session)->get('/pharmacy/patients?page=2')
            ->assertOk()
            ->assertSee('Adam Ahmed')
            ->assertDontSee('Emily Carter (14/03/1991)')
            ->assertSee('/pharmacy/patients/16?type=consultation&amp;page=2', false);

        // Tabs of the open patient carry the list's page and search.
        $this->withSession($session)->get('/pharmacy/patients/6?type=booking&page=3')
            ->assertOk()
            ->assertSee('/pharmacy/patients/6?type=notes&amp;page=3', false);
    }

    public function test_patient_list_search_matches_name_address_postcode_and_email(): void
    {
        $session = ['portal' => Portal::PHARMACY, 'pharmacy_id' => 1];

        $this->withSession($session)->get('/pharmacy/patients?search=priya+shah')
            ->assertOk()
            ->assertSee('Patients (1)')
            ->assertSee('/pharmacy/patients/6?type=consultation&amp;search=priya%20shah&amp;page=1', false)
            ->assertDontSee('Go to page 2');

        $this->withSession($session)->get('/pharmacy/patients?search=W8+6EB')
            ->assertOk()
            ->assertSee('Patients (1)');

        $this->withSession($session)->get('/pharmacy/patients?search=nobody-matches')
            ->assertOk()
            ->assertSee('Patients (0)')
            ->assertSee('no-record-found-new.png', false);
    }

    public function test_group_owner_patient_table_paginates_with_the_live_footer(): void
    {
        $session = ['portal' => Portal::GROUP_OWNER];
        $showing = fn (int $from, int $to, int $of) => ['Showing', "<span class=\"font-medium\">$from</span>", "<span class=\"font-medium\">$to</span>", "<span class=\"font-medium\">$of</span>", 'results'];

        // High Street: 96 patients, 2 awaiting approval, so 94 in the table.
        $this->withSession($session)->get('/pharmacy/patients?pharmacy=1')
            ->assertOk()
            ->assertSeeInOrder($showing(1, 10, 94), false)
            ->assertSee('patient-page=2', false);

        $this->withSession($session)->get('/pharmacy/patients?pharmacy=1&patient-page=2')
            ->assertOk()
            ->assertSeeInOrder($showing(11, 20, 94), false);

        $this->withSession($session)->get('/pharmacy/patients?pharmacy=1&perPage=25')
            ->assertOk()
            ->assertSeeInOrder($showing(1, 25, 94), false)
            ->assertSee('<option selected>25</option>', false);
    }

    public function test_patients_page_falls_back_to_no_record_found_when_the_pharmacy_has_none(): void
    {
        $this->withSession(['portal' => Portal::PHARMACY, 'pharmacy_id' => 3])->get('/pharmacy/patients')
            ->assertOk()
            ->assertSee('Patients (0)')
            ->assertSee('no-record-found-new.png', false);
    }

    public function test_patient_list_is_scoped_to_the_open_pharmacy(): void
    {
        // Each active pharmacy's roster matches its staffPharmacies() size
        // (High Street 96, Riverside 58); inactive Northgate has none. A
        // pharmacy login only ever sees its own.
        $this->withSession(['portal' => Portal::PHARMACY, 'pharmacy_id' => 1])->get('/pharmacy/patients')
            ->assertOk()
            ->assertSee('Patients (96)')
            ->assertSee('Emily Carter')
            ->assertSee('Chloe Evans')
            ->assertDontSee('James Whitfield')
            ->assertDontSee('Daniel Hughes');

        $this->withSession(['portal' => Portal::PHARMACY, 'pharmacy_id' => 2])->get('/pharmacy/patients')
            ->assertOk()
            ->assertSee('Patients (58)')
            ->assertSee('James Whitfield')
            ->assertSee('Daniel Hughes')
            ->assertDontSee('Emily Carter');

        // The Group Owner's pharmacy.show frame follows the pharmacy tab.
        $this->withSession(['portal' => Portal::GROUP_OWNER])->get('/pharmacy/patients?pharmacy=2')
            ->assertOk()
            ->assertSee('James Whitfield')
            ->assertDontSee('Emily Carter');

        // A Pharmacy can't reach a patient belonging to another pharmacy.
        $this->withSession(['portal' => Portal::PHARMACY, 'pharmacy_id' => 1])->get('/pharmacy/patients/7?type=patientinfo')
            ->assertNotFound();
        $this->withSession(['portal' => Portal::GROUP_OWNER])->get('/pharmacy/patients/7?type=patientinfo&pharmacy=1')
            ->assertNotFound();
    }

    public function test_rx_orders_page_uses_the_repeat_medicine_orders_table(): void
    {
        // patient.orders: every order (repeatMedicineOrdersAll), newest first,
        // paged by the live DataTables 2.1.2 setup.
        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/orders')
            ->assertOk()
            ->assertSee('Patient Info')
            ->assertSee('Date Requested')
            ->assertSeeInOrder([
                'Hannah Brooks', 'Levothyroxine 75mcg Tablets (28)', '27/09/2026',
                'Emily Carter', 'Metformin 500mg Tablets (56)', '20/09/2026',
                'Sarah Bennett', 'Atorvastatin 20mg Tablets (28)', '12/09/2026',
            ])
            ->assertSee('admin/js/dataTables-new.js', false)
            ->assertSee("new DataTable('#example'", false)
            ->assertSee('menu: [10, 25, 50]', false);
    }

    public function test_group_owner_orders_tabs_split_by_status(): void
    {
        $session = ['portal' => Portal::GROUP_OWNER];

        // High Street: 26 orders, 3 still Requested. Orders is repeatMedicineOrders
        // (status != Requested); New Orders is newRepeatMedicineOrders.
        $this->withSession($session)->get('/pharmacy/orders?pharmacy=1')
            ->assertOk()
            ->assertSee('Orders (23)')
            ->assertSee('New Orders (3)')
            ->assertSee('Atorvastatin 20mg Tablets (28)')
            ->assertDontSee('Metformin 500mg Tablets (56)');

        $this->withSession($session)->get('/pharmacy/new-orders?pharmacy=1')
            ->assertOk()
            ->assertSee('Metformin 500mg Tablets (56)')
            ->assertDontSee('Atorvastatin 20mg Tablets (28)');
    }

    public function test_pharmacy_sidebar_matches_the_live_ip_clinic_pharmacy_login(): void
    {
        $response = $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/dashboard');

        $response->assertSeeInOrder(['Dashboard', 'Patients', 'Calendar', 'Services', 'Broadcast', 'Repeat Orders', 'Categorise Options', 'Dispensed Consultations', 'Notifications', 'Pharmacy First Query']);
        $response->assertSee(route('pharmacy-portal.categorise-options'), false);
        $response->assertSee(route('pharmacy-portal.dispensed-consultations'), false);
        $response->assertDontSee('data-lucide="shopping-cart"', false);
    }

    public function test_group_owner_sidebar_keeps_its_own_items(): void
    {
        $this->withSession(['portal' => Portal::GROUP_OWNER])->get('/pharmacy/dashboard')
            ->assertSee('Pharmacies')
            ->assertDontSee('Categorise Options')
            ->assertDontSee('Dispensed Consultations');
    }

    public function test_header_breadcrumb_uses_each_pages_live_title(): void
    {
        $pharmacy = $this->withSession(['portal' => Portal::PHARMACY]);

        $pharmacy->get('/pharmacy/patients')->assertSee('/ Patient List');
        $pharmacy->get('/pharmacy/patients/1?type=message')->assertSee('/ Patient List');
        $pharmacy->get('/pharmacy/orders')->assertSee('/ Patient Orders');
        $pharmacy->get('/pharmacy/categorise-options')->assertSee('/ Categorise Options');
        $pharmacy->get('/pharmacy/dispensed-consultations')->assertSee('/ Dispensed Consultations');
        $this->withSession(['portal' => Portal::GROUP_OWNER])->get('/pharmacy/patients')->assertSee('/ Pharmacy List');
    }

    public function test_dispensed_consultations_lists_requests_with_their_actions(): void
    {
        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/dispensed-consultations')
            ->assertOk()
            ->assertSee('Dispense Declined')
            ->assertSee('data-popup-open="ip-view-2"', false)
            ->assertSee('data-popup-open="gp-letter-2"', false)
            ->assertSee('GP Letter Details')
            ->assertDontSee('data-popup-open="gp-letter-1"', false);
    }

    public function test_categorise_options_shows_the_fixed_default_option(): void
    {
        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/categorise-options')
            ->assertOk()
            ->assertSee('Uncontactable Patient')
            ->assertSee('Add option')
            ->assertSee('Save All options');
    }

    public function test_calendar_is_the_live_full_calendar_with_its_legend(): void
    {
        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/appointments')
            ->assertOk()
            ->assertSee('fullcalendar@6', false)
            ->assertSee('id="calendar"', false)
            ->assertSee('prevYear,prev,next,nextYear today', false)
            ->assertSeeInOrder(['Approved', 'Cancelled', 'Attended', 'Not Attended', 'Pending Approval'])
            ->assertSee('Book Appointment')
            ->assertSee('Print Appointments')
            ->assertSee('data-appointment-detail="1"', false);
    }

    public function test_services_list_shows_each_service_and_its_toggles(): void
    {
        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/services')
            ->assertOk()
            ->assertSee('NHS Flu Vaccination')
            ->assertSee('(IP Service)')
            ->assertSeeInOrder(['Service name', 'Pin It', 'Same Day', 'Schedule', 'Book by', 'Video call', 'Status', 'Action'])
            ->assertSee('Read More');
    }

    public function test_broadcast_pages_follow_the_role(): void
    {
        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/broadcast')
            ->assertOk()
            ->assertSee('Add broadcast')
            ->assertSee('Filter Details')
            ->assertDontSee('Your Metformin repeat can now be ordered');

        $this->withSession(['portal' => Portal::GROUP_OWNER])->get('/pharmacy/broadcast')
            ->assertOk()
            ->assertSee('data-broadcast-pharmacy', false)
            ->assertSee('Your Metformin repeat can now be ordered');

        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/broadcast/create')
            ->assertSee('You are reaching :')
            ->assertSee('Send Broadcast')
            ->assertDontSee('Select Pharmacy');
        $this->withSession(['portal' => Portal::GROUP_OWNER])->get('/pharmacy/broadcast/create')->assertSee('Select Pharmacy');
    }

    public function test_repeat_orders_list_links_to_each_patients_medicines(): void
    {
        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/repeat-orders')
            ->assertOk()
            ->assertSee('Margaret Ellis')
            ->assertSee(route('pharmacy-portal.repeat-order-medicine', 1), false)
            ->assertSee('Create New');

        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/repeat-orders/1')
            ->assertOk()
            ->assertSee('/ Repeat Order Medicine')
            ->assertSee('Add Re-order Reminder')
            ->assertSee('order history')
            ->assertSee('Ordered On :- 31/08/2026 - 10:12');

        $this->get('/pharmacy/repeat-orders/99')->assertNotFound();
    }

    public function test_pharmacy_first_queries_open_the_patient_response(): void
    {
        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/pharmacy-first-queries')
            ->assertOk()
            ->assertSee('Appointment Booked')
            ->assertSee(route('pharmacy-portal.pharmacy-first-query', 1), false)
            ->assertDontSee('James Whitfield');

        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/pharmacy-first-queries/1')
            ->assertOk()
            ->assertSee('Patient Response')
            ->assertSee('Section 2');

        $this->get('/pharmacy/pharmacy-first-queries/3')->assertNotFound();
    }

    public function test_patient_detail_matches_the_ip_clinic_pharmacy(): void
    {
        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/patients/1')
            ->assertRedirect('/pharmacy/patients/1?type=consultation');

        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/patients/6?type=patientinfo')
            ->assertOk()
            ->assertSee('Consultation')
            ->assertSee('Allergies')
            ->assertSee('Wheat')
            ->assertSee('Patient Vitals')
            ->assertSee('Resting Heart Rate');

        $this->withSession(['portal' => Portal::PHARMACY])->get('/pharmacy/patients/6?type=consultation')
            ->assertOk()
            ->assertSee('Genital Chlamydia Trachomatis')
            ->assertSee('data-popup-open="ip-view-1"', false);

        $this->withSession(['portal' => Portal::GROUP_OWNER])->get('/pharmacy/patients/6?type=patientinfo')
            ->assertOk()
            ->assertDontSee('Patient Vitals');
    }

    public function test_portal_loads_sweetalert_and_its_page_script(): void
    {
        $this->get('/pharmacy/dashboard')
            ->assertSee('sweetalert2@11', false)
            ->assertSee('js/pharmacy-portal.js', false);
    }
}
