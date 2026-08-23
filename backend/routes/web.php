<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransCallbackController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- Storefront (frontend simple) ---
Route::get('/produk', [ShopController::class, 'index'])->name('shop.index');

Route::get('/checkout/{product}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{product}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/{order}/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
Route::get('/checkout/{order}/status', [CheckoutController::class, 'status'])->name('checkout.status');

// --- Webhook Midtrans (dipanggil server Midtrans, bukan browser) ---
Route::post('/midtrans/callback', MidtransCallbackController::class)->name('midtrans.callback');
