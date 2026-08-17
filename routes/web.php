<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::get('/products/{version}/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
Route::get('/products/{version}/show/{id}', [ProductController::class, 'show'])->name('products.show');
