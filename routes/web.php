<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::resource('customers', CustomerController::class);
Route::resource('measurements', MeasurementController::class);
Route::get('measurements/{measurement}/print', [MeasurementController::class, 'print'])->name('measurements.print');
Route::resource('orders', OrderController::class);
Route::get('orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
Route::get('orders/{order}/payments/create', [PaymentController::class, 'create'])->name('orders.payments.create');
Route::post('orders/{order}/payments', [PaymentController::class, 'store'])->name('orders.payments.store');
