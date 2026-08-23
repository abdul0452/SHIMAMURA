<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShopApiController; // Tambahkan baris ini
use App\Http\Controllers\Api\StoreApiController; // Tambahkan baris ini

// Ini kode bawaan Laravel, biarkan saja
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Tambahkan kode route untuk toko kita di bawah sini
Route::get('/products', [ShopApiController::class, 'index']);
Route::post('/checkout', [ShopApiController::class, 'checkout']);

// Route untuk daftar toko & detail toko
Route::get('/stores', [StoreApiController::class, 'index']);
Route::get('/stores/{id}', [StoreApiController::class, 'show']);