<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopApiController extends Controller
{
    // Mengambil produk (support limit acak untuk Trending & Best Selling)
    public function index(Request $request)
    {
        $query = Product::with('store');

        if ($request->boolean('best_seller')) {
            // Best Selling Product -> 4 produk acak
            $products = $query->inRandomOrder()->limit(4)->get();
        } elseif ($request->filled('limit')) {
            // Trending Product -> sejumlah $limit produk acak (default dari frontend: 8)
            $products = $query->inRandomOrder()->limit((int) $request->query('limit'))->get();
        } else {
            // Tanpa parameter -> tetap ambil semua produk (misal untuk halaman Shop)
            $products = $query->get();
        }

        return response()->json($products);
    }

    // Proses Checkout: buat Order + OrderDetail + Payment dari isi keranjang,
    // lalu minta Snap Token ke Midtrans supaya bisa dibuka popup pembayarannya di frontend.
    public function checkout(Request $request, MidtransService $midtrans)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'buyer_name' => ['required', 'string', 'max:255'],
            'buyer_email' => ['required', 'email', 'max:255'],
            'buyer_phone' => ['required', 'string', 'max:20'],
        ]);

        try {
            $order = DB::transaction(function () use ($request, $validated) {
                // Ambil harga produk langsung dari database (jangan percaya harga dari frontend)
                $productIds = collect($validated['items'])->pluck('product_id');
                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

                $totalAmount = 0;
                foreach ($validated['items'] as $item) {
                    $product = $products[$item['product_id']];
                    if ($item['quantity'] > $product->stock) {
                        abort(422, "Stok {$product->name} tidak cukup.");
                    }
                    $totalAmount += $product->price * $item['quantity'];
                }

                $order = Order::create([
                    'user_id' => $request->user()?->id,
                    'guest_name' => $validated['buyer_name'],
                    'guest_email' => $validated['buyer_email'],
                    'guest_phone' => $validated['buyer_phone'],
                    'status' => Order::STATUS_PENDING_PAYMENT,
                    'total_amount' => $totalAmount,
                ]);

                foreach ($validated['items'] as $item) {
                    $product = $products[$item['product_id']];
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                    ]);
                }

                Payment::create([
                    'order_id' => $order->id,
                    'method' => 'midtrans',
                    'amount' => $totalAmount,
                    'status' => 'pending',
                ]);

                return $order;
            });

            $snapToken = $midtrans->createSnapToken($order);
            $order->payment->update(['snap_token' => $snapToken]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'order_id' => $order->id,
                'snap_token' => $snapToken,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pesanan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Cek status order (dipakai halaman "Order Status" di frontend)
    public function orderStatus(Request $request, Order $order)
    {
        $order->load('payment', 'orderDetails.product');

        return response()->json([
            'id' => $order->id,
            'status' => $order->status,
            'total_amount' => $order->total_amount,
            'payment_status' => $order->payment?->status,
            'items' => $order->orderDetails->map(fn($d) => [
                'product_name' => $d->product->name ?? '-',
                'quantity' => $d->quantity,
                'unit_price' => $d->unit_price,
            ]),
        ]);
    }
}
