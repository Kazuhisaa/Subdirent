<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UnitsController;



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

Route::get('/admin/addTenant', function () {
    return view('admin.addTenant');
})->name('admin.tenants');

Route::get('/admin/analytics', function () {
    return view('admin.analytics');
})->name('admin.analytics');

