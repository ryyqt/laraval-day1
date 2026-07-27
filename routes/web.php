<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');
Route::resource('products', ProductController::class);

// Category CRUD routes
Route::resource('categories', CategoryController::class)->only([
    'index', 'create', 'store', 'edit', 'update', 'destroy',
]);
