<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TenantController;


Route::prefix('booking')->group(function () {
    Route::get('/book-slot', [BookingController::class, 'getByDate']);
    Route::post('/schedule', [BookingController::class, 'store']);
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

Route::get('/test', [TestController::class, 'test']);
