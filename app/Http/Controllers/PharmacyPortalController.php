<?php

namespace App\Http\Controllers;

use App\Support\DemoData;
use App\Support\Portal;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PharmacyPortalController extends Controller
{
    /**
     * Pages that only exist as tabs of the Group Owner's pharmacy.show page
     * (admin/pharmacy/index.blade.php), mapped to the tab they highlight.
     */
    private const TAB_ONLY_PAGES = [
        'patients' => 'patients',
        'new-patients' => 'new-patients',
        'patient-detail' => 'patients',
        'appointments' => 'appointments',
        'orders' => 'orders',
        'new-orders' => 'new-orders',
    ];

    /**
     * Pages a Group Owner also reaches outside pharmacy.show (sidebar or the
     * header's person menu); they sit inside the tab frame only when opened
     * from a tab, which adds ?tab=1.
     */
    private const SHARED_PAGES = [
        'broadcast' => 'broadcast',
        'broadcast-create' => 'broadcast',
        'info' => 'info',
        'services' => 'info',
        'settings' => 'info',
    ];

    /**
     * The header dropdown's choice. Upstream this signs in as the chosen
     * pharmacy or Group Owner; here it just stores the context and lands on
     * that login's dashboard, as DashboardController does for both roles.
     */
    public function switchPortal(string $portal, ?int $pharmacy = null): RedirectResponse
    {
        if ($portal === Portal::PHARMACY) {
            abort_if($pharmacy === null || Portal::findPharmacy($pharmacy) === null, 404);

            session(['pharmacy_id' => $pharmacy]);
        }

        session(['portal' => $portal]);

        return redirect()->route('pharmacy-portal.dashboard');
    }

    public function dashboard(): View
    {
        return $this->page('dashboard', 'dashboard', 'Dashboard');
    }

    public function patients(): View
    {
        return $this->page('patients', 'patients', 'Patient List');
    }

    public function newPatients(): View
    {
        return $this->page('new-patients', 'patients', 'Patient List');
    }

    /**
     * patient.show (Pharmacy) / pharmacy.show > patientDetail (Group Owner).
     * ?type= picks the tab; the patient lists always link with one, so a bare
     * URL lands where they would: Consultation from a Pharmacy's list (the
     * group runs the IP clinic); from a Group Owner's, Prescription for an
     * approved NHS patient, otherwise Patient Info.
     */
    public function patientDetail(int $id): View|RedirectResponse
    {
        $patient = collect(DemoData::staffPatients())->firstWhere('id', $id);

        abort_if($patient === null, 404);

        // A Pharmacy only ever sees its own patients; a Group Owner only the
        // ones in the pharmacy frame it's viewing (?pharmacy=).
        $isGroupOwner = Portal::isGroupOwner();
        $framePharmacy = Portal::findPharmacy(request()->integer('pharmacy')) ?? DemoData::staffPharmacies()[0];
        $pharmacyId = $isGroupOwner ? $framePharmacy['id'] : Portal::pharmacy()['id'];

        abort_if($patient['pharmacy_id'] !== $pharmacyId, 404);

        if (! request()->filled('type')) {
            $type = match (true) {
                ! Portal::isGroupOwner() => 'consultation',
                $patient['status'] === 'active' && $patient['user_type'] === 'NHS' => 'prescription',
                default => 'patientinfo',
            };

            return redirect()->route('pharmacy-portal.patient-detail', ['id' => $id, 'type' => $type] + request()->query());
        }

        return $this->page('patient-detail', 'patients', 'Patient List', ['patient' => $patient]);
    }

    public function appointments(): View
    {
        return $this->page('appointments', 'appointments', 'Appointment List');
    }

    public function orders(): View
    {
        return $this->page('orders', 'orders', 'Patient Orders');
    }

    public function newOrders(): View
    {
        return $this->page('new-orders', 'orders', 'Patient Orders');
    }

    public function repeatOrders(): View
    {
        return $this->page('repeat-orders', 'repeat-orders', 'Repeat Order');
    }

    public function repeatOrderMedicine(int $id): View
    {
        $repeatPatient = collect(DemoData::staffRepeatOrderPatients())->firstWhere('id', $id);

        abort_if($repeatPatient === null, 404);

        return $this->page('repeat-order-medicine', 'repeat-orders', 'Repeat Order Medicine', ['repeatPatient' => $repeatPatient]);
    }

    public function broadcast(): View
    {
        return $this->page('broadcast', 'broadcast', 'Broadcast List');
    }

    public function broadcastCreate(): View
    {
        return $this->page('broadcast-create', 'broadcast', 'Broadcast Create');
    }

    public function info(): View
    {
        return $this->page('info', 'info', 'Personal Information');
    }

    public function services(): View
    {
        return $this->page('services', 'services', 'Service List');
    }

    public function settings(): View
    {
        return $this->page('settings', 'settings', 'System Setting');
    }

    public function notifications(): View
    {
        return $this->page('notifications', 'notifications', 'Notification');
    }

    public function pharmacyFirstQueries(): View
    {
        return $this->page('pharmacy-first-queries', 'pharmacy-first-queries', 'Pharmacy First Query');
    }

    public function pharmacyFirstQuery(int $id): View
    {
        $query = collect(DemoData::staffPharmacyFirstQueries())->firstWhere('id', $id);

        abort_if($query === null || empty($query['answers']), 404);

        return $this->page('pharmacy-first-query', 'pharmacy-first-queries', 'Pharmacy First Query', ['query' => $query]);
    }

    public function categoriseOptions(): View
    {
        return $this->page('categorise-options', 'categorise-options', 'Categorise Options');
    }

    public function dispensedConsultations(): View
    {
        return $this->page('dispensed-consultations', 'dispensed-consultations', 'Dispensed Consultations');
    }

    private function page(string $view, string $activeTab, string $title, array $data = []): View
    {
        $portal = Portal::current();
        $isGroupOwner = $portal === Portal::GROUP_OWNER;

        $frameTab = self::TAB_ONLY_PAGES[$view] ?? (request()->boolean('tab') ? (self::SHARED_PAGES[$view] ?? null) : null);
        $showFrame = $isGroupOwner && $frameTab !== null;

        $currentPharmacy = Portal::pharmacy();
        // The pharmacy open in the Group Owner's pharmacy.show frame (?pharmacy=).
        $framePharmacy = Portal::findPharmacy(request()->integer('pharmacy')) ?? DemoData::staffPharmacies()[0];

        return view("pharmacy.pages.{$view}", [
            'portal' => $portal,
            'isGroupOwner' => $isGroupOwner,
            'currentPharmacy' => $currentPharmacy,
            // Each page's meta_title upstream; everything inside pharmacy.show is "Pharmacy List".
            'title' => $showFrame ? 'Pharmacy List' : $title,
            'showFrame' => $showFrame,
            'frameTab' => $frameTab,
            'framePharmacy' => $framePharmacy,
            // Which pharmacy's patients/orders/etc. this page shows: the
            // frame's when framed, otherwise the current login's. Unused by
            // an unframed Group Owner page (dashboard, broadcast, ...),
            // which is organisation-wide.
            'pharmacyId' => $showFrame ? $framePharmacy['id'] : $currentPharmacy['id'],
            'activeTab' => $showFrame ? 'pharmacies' : $activeTab,
        ] + $data);
    }
}
