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
use App\Http\Controllers\MaintenanceRequestController;


Route::prefix('booking')->group(function () {
    Route::get('/book-slot', [BookingController::class, 'getByDate']);
    Route::post('/schedule', [BookingController::class, 'store']);
    Route::get('/allBookings', [BookingController::class, 'showAllBooking']);
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


Route::post('/paymongo/webhook', [PaymentController::class, 'webhook'])
    ->name('payment.webhook');


Route::post('/autopay/{tenant}/setup', [AutopayController::class, 'store'])->name('autopay.setup');
Route::delete('/autopay/{tenant}/disable', [AutopayController::class, 'destroy'])->name('autopay.disable');


Route::prefix('/maintenance')->group(function () {
    Route::get('/requests', [MaintenanceRequestController::class, 'index'])->name('api.maintenance.index');
    Route::get('/requests/{id}', [MaintenanceRequestController::class, 'show'])->name('api.maintenance.show');
    Route::post('/requests', [MaintenanceRequestController::class, 'store'])->name('api.maintenance.store');
    Route::patch('/requests/{id}/status', [MaintenanceRequestController::class, 'updateStatus'])
        ->name('api.maintenance.updateStatus');
    Route::delete('/requests/{id}', [MaintenanceRequestController::class, 'destroy'])->name('api.maintenance.destroy');
});
