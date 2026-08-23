<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopApiController extends Controller
{
    // Mengambil semua produk
    public function index()
    {
       $products = Product::with('store')->get();

        return response()->json($products);
    }

    // Proses Checkout
    public function checkout(Request $request)
    {
        DB::beginTransaction();
        try {
            // Membuat data Order utama
            $order = Order::create([
                'user_id' => $request->user_id ?? 1, // Default user 1 jika tidak ada auth
                'store_id' => $request->store_id,
                'total_price' => $request->total_price,
                'status' => 'pending',
            ]);

            // Menyimpan detail produk yang dibeli
            foreach ($request->items as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'data' => $order
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
