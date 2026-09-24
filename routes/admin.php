<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Portal Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/farmers/{id}/approve', [DashboardController::class, 'approveFarmer'])->name('farmers.approve');
    Route::post('/farmers/{id}/suspend', [DashboardController::class, 'suspendFarmer'])->name('farmers.suspend');
    Route::post('/customers/{id}/toggle-status', [DashboardController::class, 'toggleCustomerStatus'])->name('customers.toggle');
});
