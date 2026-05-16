<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShopHeaderController;
use App\Http\Controllers\SuperAdmin\ActivityLogController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SettingsController;
use App\Http\Controllers\SuperAdmin\ShopManagementController;
use App\Http\Controllers\SuperAdmin\ShopContextController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'create'])->name('login');
    Route::get('/login', [AuthController::class, 'create'])->name('login.form');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:super_admin,owner'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/search', GlobalSearchController::class)->name('global-search.index');
    Route::get('/shop-header', [ShopHeaderController::class, 'edit'])->name('shop-header.edit');
    Route::put('/shop-header', [ShopHeaderController::class, 'update'])->name('shop-header.update');

    Route::resource('customers', CustomerController::class);
    Route::get('measurements/{measurement}/copy', [MeasurementController::class, 'copy'])->name('measurements.copy');
    Route::resource('measurements', MeasurementController::class);
    Route::get('measurements/{measurement}/print', [MeasurementController::class, 'print'])->name('measurements.print');
    Route::resource('orders', OrderController::class);
    Route::get('orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('orders/{order}/payments/create', [PaymentController::class, 'create'])->name('orders.payments.create');
    Route::post('orders/{order}/payments', [PaymentController::class, 'store'])->name('orders.payments.store');
});

Route::prefix('super-admin')
    ->name('superadmin.')
    ->middleware(['auth', 'role:super_admin'])
    ->group(function () {
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/shops', [ShopManagementController::class, 'index'])->name('shops.index');
        Route::post('/shops/{shop}/manage', [ShopContextController::class, 'activate'])->name('shops.manage');
        Route::patch('/shops/{shop}/status', [ShopManagementController::class, 'toggleStatus'])->name('shops.toggle-status');
        Route::delete('/shops/{shop}', [ShopManagementController::class, 'destroy'])->name('shops.destroy');
        Route::delete('/shops/manage', [ShopContextController::class, 'clear'])->name('shops.clear-manage');
        Route::resource('users', SuperAdminUserController::class)->except(['show']);
        Route::patch('users/{user}/status', [SuperAdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

