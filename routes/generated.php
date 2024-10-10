<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;


Route::middleware(['auth', 'verified'])->group(function () {{

Route::resource('category', CategoryController::class);
Route::resource('contact', ContactController::class);
Route::resource('customer', CustomerController::class);


}});
    