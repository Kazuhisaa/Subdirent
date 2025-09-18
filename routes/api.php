<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RevenuePredictionController;
use App\Http\Controllers\OccupancyPredictionController;

use App\Models\RevenuePrediction;
use App\Services\RevenuePredictionService;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AutopayController;



Route::prefix('booking')->group(function () {
    Route::get('/book-slot', [BookingController::class, 'getByDate']);
    Route::post('/schedule', [BookingController::class, 'store']);
    Route::get('/allBookings',[BookingController::class,'showAllBooking']);
    Route::get('/Testing', [BookingController::class, 'testApi']);
});



Route::prefix('units')->group(function () {
    Route::post('/newunit', [UnitsController::class, 'store']);
    Route::get('/allunits', [UnitsController::class, 'index']);
    Route::get('/findunit/{id}', [UnitsController::class, 'show']);
    Route::put('/updateUnit/{unit}', [UnitsController::class, 'update']);
    Route::delete('/deleteunit/{unit}', [UnitsController::class, 'destroy']);
});



Route::prefix('tenants')->group(function () {
    Route::post('/newTenant', [TenantController::class, 'store']);
    Route::get('/allTenant', [TenantController::class, 'index']);
    Route::get('/findTenant/{id}', [TenantController::class, 'show']);
    Route::put('/updateTenant/{tenant}', [TenantController::class, 'update']);
    Route::delete('/deleteTenant/{tenant}', [TenantController::class, 'destroy']);
});

Route::prefix('revenue')->group(function () {
    //prediction Monthly
    Route::get('/predictionMonth', [RevenuePredictionController::class, 'showRevenuePredictionMonthly']);
    //prediction Quarterly
    Route::get('/predictionQuarter', [RevenuePredictionController::class, 'showRevenuePredictionQuarterly']);
    // prediction annually
    Route::get('/predictionAnnual', [RevenuePredictionController::class, 'showRevenuePredictionAnnualy']);
      //get all the date at historical revenue para maipakita sa graph
      Route::get('/history',[RevenuePredictionController::class,'showRevenueHistory']);
      
      Route::get('/error',[RevenuePredictionController::class,'showError']);
});

 
Route::prefix('occupancy')->group(function(){

    //predict next month
     Route::get('/predictionMonth',[OccupancyPredictionController::class,'showOccupancyByMonth']);
     //predict quarter
     Route::get('/predictionQuarter',[OccupancyPredictionController::class,'showOccupancyByQuarter']);
     //predict Annual
      Route::get('/predictionAnnual',[OccupancyPredictionController::class,'showOccupancyByAnnual']);
 //get all the date at historical revenue para maipakita sa graph
      Route::get('/history',[OccupancyPredictionController::class,'showRevenueHistory']);
    
});
 


Route::get('/test', [TestController::class, 'test']);


Route::get('/tenant/{tenant}/dashboard', [PaymentController::class, 'dashboard'])
    ->name('tenant.dashboard');

// Payment Routes
Route::post('/tenant/{tenant}/pay', [PaymentController::class, 'createPayment'])
    ->name('payments.create');

Route::get('/tenant/{tenant}/payment/success', [PaymentController::class, 'success'])
    ->name('payment.success');

Route::get('/tenant/{tenant}/payment/cancel', [PaymentController::class, 'cancel'])
    ->name('payment.cancel');

// Webhook (PayMongo callback)
Route::post('/api/paymongo/webhook', [PaymentController::class, 'webhook'])
    ->name('payment.webhook');



Route::prefix('tenants')->group(function () {
    Route::post('{tenant}/autopay', [AutopayController::class, 'storeOrUpdate']);
    Route::get('{tenant}/autopay', [AutopayController::class, 'show']);
    Route::delete('{tenant}/autopay', [AutopayController::class, 'destroy']);
});
