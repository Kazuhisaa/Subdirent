<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TenantController;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
});

Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
// Admin side
Route::get('/admin/applications', [ApplicationController::class, 'index'])->name('admin.applications');
Route::patch('/applications/{id}/accept', [ApplicationController::class, 'accept'])->name('applications.accept');
Route::patch('/applications/{id}/decline', [ApplicationController::class, 'decline'])->name('applications.decline');


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
