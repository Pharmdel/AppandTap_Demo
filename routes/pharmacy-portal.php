<?php

use App\Http\Controllers\PharmacyPortalController;
use App\Support\Portal;
use Illuminate\Support\Facades\Route;

Route::redirect('/pharmacy', '/pharmacy/dashboard');

Route::prefix('pharmacy')->name('pharmacy-portal.')->controller(PharmacyPortalController::class)->group(function () {
    Route::get('/portal/{portal}/{pharmacy?}', 'switchPortal')
        ->whereIn('portal', Portal::all())
        ->whereNumber('pharmacy')
        ->name('switch');

    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/patients', 'patients')->name('patients');
    Route::get('/new-patients', 'newPatients')->name('new-patients');
    Route::get('/patients/{id}', 'patientDetail')->whereNumber('id')->name('patient-detail');
    Route::get('/appointments', 'appointments')->name('appointments');
    Route::get('/orders', 'orders')->name('orders');
    Route::get('/new-orders', 'newOrders')->name('new-orders');
    Route::get('/repeat-orders', 'repeatOrders')->name('repeat-orders');
    Route::get('/repeat-orders/{id}', 'repeatOrderMedicine')->whereNumber('id')->name('repeat-order-medicine');
    Route::get('/broadcast', 'broadcast')->name('broadcast');
    Route::get('/broadcast/create', 'broadcastCreate')->name('broadcast-create');
    Route::get('/info', 'info')->name('info');
    Route::get('/services', 'services')->name('services');
    Route::get('/settings', 'settings')->name('settings');
    Route::get('/notifications', 'notifications')->name('notifications');
    Route::get('/pharmacy-first-queries', 'pharmacyFirstQueries')->name('pharmacy-first-queries');
    Route::get('/pharmacy-first-queries/{id}', 'pharmacyFirstQuery')->whereNumber('id')->name('pharmacy-first-query');
    Route::get('/categorise-options', 'categoriseOptions')->name('categorise-options');
    Route::get('/dispensed-consultations', 'dispensedConsultations')->name('dispensed-consultations');
});
