<?php

use App\Http\Controllers\AjaxProductPageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');
Route::resource('products', ProductController::class);

// Category CRUD routes
Route::resource('categories', CategoryController::class)->only([
    'index', 'create', 'store', 'edit', 'update', 'destroy',
]);

// Customer CRUD routes
Route::resource('customers', CustomerController::class)->only([
    'index', 'create', 'store', 'edit', 'update', 'destroy',
]);

// Day 9 – AJAX Products page
Route::get('/ajax-products', [AjaxProductPageController::class, 'index'])->name('ajax-products.index');
