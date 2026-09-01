<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/vehicles', [VehicleController::class, 'index'])
    ->name('vehicles.index');

Route::get('/vehicles/{vehicle}/book', [BookingController::class, 'create'])
    ->name('bookings.create');

Route::post('/vehicles/{vehicle}/book', [BookingController::class, 'store'])
    ->name('bookings.store');

Route::get('/bookings', [BookingController::class, 'index'])
    ->name('bookings.index');

Route::get('/admin/bookings', [BookingController::class, 'adminIndex'])
    ->name('admin.bookings');

Route::patch('/admin/bookings/{booking}/status', [BookingController::class, 'updateStatus'])
    ->name('admin.bookings.status');