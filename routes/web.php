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

// Booking page
Route::get('/booking', function () {
    return view('booking'); 
})->name('booking');
