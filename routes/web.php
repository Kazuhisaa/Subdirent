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




// RestApi routes for Booking
// Route::prefix('booking')->group(function(){
//     // GET /booking/book-slot?date=YYYY-MM-DD
//     // POST /booking/schedule
//     // Required POST data: date (string, format 'Y-m-d'), time (string, format 'H:i'), user_id (integer)
//     // Expected response: JSON object with booking confirmation details or error message

//     Route::get('/book-slot',[BookingController::class,'getByDate']);
//     Route::post('/schedule',[BookingController::class,'store']);
//     Route::get('/Testing',[BookingController::class,'testApi']);
// });

// Route::prefix('units')->group(function(){
 
//     Route::post('/newunit',[UnitsController::class,'store']);

// });