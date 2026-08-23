<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreApiController extends Controller
{
    // GET /api/stores -> daftar semua toko
    public function index()
    {
        $stores = Store::withCount('products')->get();

        return response()->json($stores);
    }

    // GET /api/stores/{id} -> detail 1 toko + produknya
    public function show($id)
    {
        $store = Store::with(['products.productDetail'])->find($id);

        if (!$store) {
            return response()->json([
                'message' => 'Store not found',
            ], 404);
        }

        return response()->json($store);
    }
}