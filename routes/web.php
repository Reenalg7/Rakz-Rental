<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminVehicleController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('vehicles.index');
});

Route::get('/vehicles', [VehicleController::class, 'index'])
    ->name('vehicles.index');


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Booking
    |--------------------------------------------------------------------------
    */

    Route::get('/vehicles/{vehicle}/book', [BookingController::class, 'create'])
        ->name('bookings.create');

    Route::post('/vehicles/{vehicle}/book', [BookingController::class, 'store'])
        ->name('bookings.store');


    /*
    |--------------------------------------------------------------------------
    | Customer Booking History
    |--------------------------------------------------------------------------
    */

    Route::get('/bookings', [BookingController::class, 'index'])
        ->name('bookings.index');


    /*
    |--------------------------------------------------------------------------
    | Customer Booking Cancellation
    |--------------------------------------------------------------------------
    */

    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
        ->name('bookings.cancel');


    /*
    |--------------------------------------------------------------------------
    | Customer Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Admin Bookings
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/bookings', [BookingController::class, 'adminIndex'])
        ->name('admin.bookings');

    /*
    | Replace vehicle for an existing customer booking
    */

    Route::get('/admin/bookings/{booking}/replace', [BookingController::class, 'replacement'])
        ->name('admin.bookings.replacement');

    Route::post('/admin/bookings/{booking}/replace', [BookingController::class, 'replaceVehicle'])
        ->name('admin.bookings.replace');

    /*
    | Update booking status
    */

    Route::patch('/admin/bookings/{booking}/status', [BookingController::class, 'updateStatus'])
        ->name('admin.bookings.status');


    /*
    |--------------------------------------------------------------------------
    | Admin Vehicle Management
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/vehicles', [AdminVehicleController::class, 'index'])
        ->name('admin.vehicles');

    /*
    | Add vehicle
    */

    Route::get('/admin/vehicles/create', [AdminVehicleController::class, 'create'])
        ->name('admin.vehicles.create');

    Route::post('/admin/vehicles', [AdminVehicleController::class, 'store'])
        ->name('admin.vehicles.store');


    /*
    | Edit vehicle
    */

    Route::get('/admin/vehicles/{vehicle}/edit', [AdminVehicleController::class, 'edit'])
        ->name('admin.vehicles.edit');

    Route::put('/admin/vehicles/{vehicle}', [AdminVehicleController::class, 'update'])
        ->name('admin.vehicles.update');


    /*
    | Retire vehicle
    */

    Route::delete('/admin/vehicles/{vehicle}', [AdminVehicleController::class, 'destroy'])
        ->name('admin.vehicles.destroy');


    /*
    | Replace vehicle in fleet
    */

    Route::get('/admin/vehicles/{vehicle}/replace', [AdminVehicleController::class, 'replacement'])
        ->name('admin.vehicles.replacement');

    Route::post('/admin/vehicles/{vehicle}/replace', [AdminVehicleController::class, 'replace'])
        ->name('admin.vehicles.replace');

});