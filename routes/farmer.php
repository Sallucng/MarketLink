<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Farmer / Vendor Routes (Owned by Person 2)
|--------------------------------------------------------------------------
| Prefix: /farmer, Middleware: ['auth', 'role:farmer']
*/

Route::middleware(['auth', 'role:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('farmer.dashboard');
    })->name('dashboard');
});
