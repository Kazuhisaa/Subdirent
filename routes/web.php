<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\PaymentController;




Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/apply', function () {
    return view('apply');
})->name('apply');

Route::post('/apply', [ApplicationController::class, 'submit'])->name('apply.submit');


Route::get('/admin/admin', function () {
    return view('admin.admin');
})->name('admin.dashboard');

Route::get('/admin/addUnit', function () {
    return view('admin.addUnit');
})->name('admin.units');

Route::get('/admin/tenants', function () {
    return view('admin.addTenant');
})->name('admin.tenants');

Route::get('/login', function () {
    return 'Login page (placeholder)';
})->name('login');


Route::get('/tenant/{tenant}/dashboard', [PaymentController::class, 'dashboard'])
    ->name('tenant.dashboard');

Route::get('/tenant/{tenant}/pay', [PaymentController::class, 'createPayment'])
    ->name('payments.create');

Route::get('/tenant/{tenant}/payment/success', [PaymentController::class, 'success'])
    ->name('payment.success');

Route::get('/tenant/{tenant}/payment/cancel', [PaymentController::class, 'cancel'])
    ->name('payment.cancel');
