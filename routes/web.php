<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;

// Front
Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/category/{id}', [StoreController::class, 'index'])->name('category');

// Cart
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/goods', [CartController::class, 'goods'])->name('cart.goods');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/save', [CartController::class, 'save'])->name('cart.save');