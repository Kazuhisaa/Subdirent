<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UnitsController;

Route::prefix('booking')->group(function () {
    Route::get('/book-slot', [BookingController::class, 'getByDate']);
    Route::post('/schedule', [BookingController::class, 'store']); 
    Route::get('/Testing', [BookingController::class, 'testApi']);
});

Route::prefix('units')->group(function () {
    Route::post('/newunit', [UnitsController::class, 'store']);
    Route::get('/allunits',[UnitsController::class,'index']);
    Route::get('/findunit/{id}',[UnitsController::class,'show']);
    Route::put('/updateUnit/{unit}',[UnitsController::class,'update']);
    Route::delete('/deleteunit/{unit}',[UnitsController::class,'destroy']);
});
