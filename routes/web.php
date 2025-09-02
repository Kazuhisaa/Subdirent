<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationCtrl;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/apply', function () {
    return view('apply');
})->name('apply');

Route::post('/apply', [ApplicationCtrl::class, 'submit'])->name('apply.submit');
