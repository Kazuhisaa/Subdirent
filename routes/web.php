<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\MaintenanceRequestAdminController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/apply', function () {
    return view('apply');
})->name('apply');

Route::get('/admin/admin', function () {
    return view('admin.admin');
})->name('admin.dashboard');

Route::get('/admin/addUnit', function () {
    return view('admin.addUnit');
})->name('admin.units');

Route::get('/admin/addTenant', function () {
    return view('admin.addTenant');
})->name('admin.tenants');

Route::get('/admin/bookings', [BookingController::class, 'index'])
    ->name('admin.bookings');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');


Route::get('/admin/tenants', [TenantController::class, 'manageTenants'])->name('tenants.index');
Route::post('/admin/tenants', [TenantController::class, 'store'])->name('tenants.store');

Route::get('/admin/analytics', function () {
    return view('admin.analytics');
})->name('admin.analytics');


Route::get('/tenant/{tenant}/dashboard', [PaymentController::class, 'dashboard'])
    ->name('tenant.dashboard');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
});


Route::post('/tenants/{tenant}/payments', [PaymentController::class, 'createPayment'])
    ->name('payments.create');

Route::get('/tenant/{tenant}/dashboard', [PaymentController::class, 'dashboard'])
    ->name('tenant.dashboard');

Route::get('/tenant/{tenant}/payment/success', [PaymentController::class, 'success'])
    ->name('payment.success');

Route::get('/tenant/{tenant}/payment/cancel', [PaymentController::class, 'cancel'])
    ->name('payment.cancel');


Route::get('/', [UnitsController::class, 'listView'])->name('home');

Route::get('/admin/maintenance', [MaintenanceRequestAdminController::class, 'index'])
    ->name('admin.maintenance'); // wala nang auth o is_admin middleware

Route::post('/applications', [ApplicationController::class, 'store'])
    ->name('applications.store');
Route::get('/admin/applications', [ApplicationController::class, 'index'])
    ->name('admin.applications');
