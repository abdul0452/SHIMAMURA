<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShopController extends Controller
{
    // Daftar produk yang masih ada stoknya
    public function index()
    {
        $products = Product::with('store')
            ->where('stock', '>', 0)
            ->latest()
            ->get();

        return view('shop.index', compact('products'));
    }
}
