<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\VehicleController as CustomerVehicleController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\FavoriteController;
use App\Http\Controllers\Customer\CompareController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\NotificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\ReplacementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CsvExportController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rakz Rental Web Routes
|--------------------------------------------------------------------------
*/

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public vehicle browsing (also used by guests)
Route::get('/vehicles', [CustomerVehicleController::class, 'index'])->name('customer.vehicles.index');
Route::get('/vehicles/{vehicle:registration_no}', [CustomerVehicleController::class, 'show'])
    ->name('customer.vehicles.show');

// Guest-only auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Area (authenticated customer)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'customer'])->prefix('my')->name('customer.')->group(function () {
    // Booking flow for a specific vehicle (reg_no is the vehicle PK).
    Route::get('/book/{vehicle:registration_no}', [CustomerBookingController::class, 'create'])
        ->name('bookings.create');
    Route::post('/book/{vehicle:registration_no}/check-availability',
        [CustomerBookingController::class, 'checkAvailability'])->name('bookings.check');
    Route::post('/book/{vehicle:registration_no}', [CustomerBookingController::class, 'store'])
        ->name('bookings.store');

    // Booking history & actions
    Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [CustomerBookingController::class, 'show'])->name('bookings.show');
   
    Route::get('/bookings/{booking}/invoice', [InvoiceController::class, 'download'])->name('bookings.invoice');
    Route::post('/bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/bookings/{booking}/invoice', [InvoiceController::class, 'download'])->name('bookings.invoice');
    Route::post('/bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('bookings.cancel');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{vehicle:registration_no}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Compare up to 3 vehicles side-by-side (reads ?v[]=LR3180&v[]=LR3181)
    Route::get('/compare', [CompareController::class, 'show'])->name('compare.show');
});

/*
|--------------------------------------------------------------------------
| Admin Area (authenticated admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Vehicles (reg_no is the PK, so route-model binding uses :registration_no)
    Route::get('/vehicles', [AdminVehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/create', [AdminVehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [AdminVehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle:registration_no}/edit', [AdminVehicleController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicles/{vehicle:registration_no}', [AdminVehicleController::class, 'update'])->name('vehicles.update');
    Route::post('/vehicles/{vehicle:registration_no}/retire', [AdminVehicleController::class, 'retire'])->name('vehicles.retire');
    Route::post('/vehicles/{vehicle:registration_no}/restore', [AdminVehicleController::class, 'restore'])->name('vehicles.restore');
    Route::get('/vehicles/{vehicle:registration_no}/datasheet', [AdminVehicleController::class, 'datasheet'])->name('vehicles.datasheet');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Bookings
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/invoice', [InvoiceController::class, 'download'])->name('bookings.invoice');
    Route::post('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');
    Route::post('/bookings/{booking}/paid', [AdminBookingController::class, 'markPaid'])->name('bookings.paid');

    // Vehicle Replacements
    Route::get('/replacements', [ReplacementController::class, 'index'])->name('replacements.index');
    Route::get('/bookings/{booking}/replace', [ReplacementController::class, 'create'])->name('replacements.create');
    Route::post('/bookings/{booking}/replace', [ReplacementController::class, 'store'])->name('replacements.store');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    // NOTE: /customers/create must be declared BEFORE /customers/{customer},
    // otherwise "create" would be treated as a customer id.
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // CSV Exports (for lecturer / viva demo)
    Route::get('/export/bookings.csv', [CsvExportController::class, 'bookings'])->name('export.bookings');
    Route::get('/export/revenue.csv', [CsvExportController::class, 'revenue'])->name('export.revenue');
    Route::get('/export/utilization.csv', [CsvExportController::class, 'utilization'])->name('export.utilization');
    Route::get('/export/customers.csv', [CsvExportController::class, 'customers'])->name('export.customers');
    Route::get('/export/audit-logs.csv', [CsvExportController::class, 'auditLogs'])->name('export.audit_logs');

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit_logs.index');
});
