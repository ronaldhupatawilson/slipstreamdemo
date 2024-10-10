<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');


Route::get('/customers', [CustomerController::class, 'index'])
    ->name('customers');

Route::get('/customers/{searchText}', [CustomerController::class, 'search'])
->name('customerSearch');

Route::post('/customer/delete', [CustomerController::class, 'destroy'])->name('customers.destroy');

Route::post('/customer/{customerId}/contacts', [CustomerController::class, 'getCustomerContacts'])->name('customers.getCustomerContacts');

Route::post('/customer/store', [CustomerController::class, 'store'])->name('customers.store');

Route::post('/customer/{customerId}/update', [CustomerController::class, 'udpate'])->name('customers.update');

require __DIR__.'/auth.php';