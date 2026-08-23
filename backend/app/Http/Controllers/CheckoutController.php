<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Tampilkan form beli untuk 1 produk (qty + data pembeli)
    public function create(Product $product)
    {
        return view('shop.checkout', compact('product'));
    }

    // Proses "Beli": buat Order + OrderDetail + Payment, lalu minta Snap Token
    public function store(Request $request, Product $product, MidtransService $midtrans)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$product->stock],
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['required', 'string', 'max:20'],
        ]);

        $totalAmount = $product->price * $validated['quantity'];

        $order = DB::transaction(function () use ($product, $validated, $totalAmount) {
            $order = Order::create([
                'user_id' => auth()->id(), // null kalau guest, otomatis terisi kalau nanti sudah pakai login
                'guest_name' => $validated['guest_name'],
                'guest_email' => $validated['guest_email'],
                'guest_phone' => $validated['guest_phone'],
                'status' => Order::STATUS_PENDING_PAYMENT,
                'total_amount' => $totalAmount,
            ]);

            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'unit_price' => $product->price,
            ]);

            Payment::create([
                'order_id' => $order->id,
                'method' => 'midtrans',
                'amount' => $totalAmount,
                'status' => 'pending',
            ]);

            return $order;
        });

        // Minta Snap Token ke Midtrans, lalu simpan supaya bisa dipakai ulang
        // kalau halaman pembayaran di-refresh (tidak minta token baru terus).
        $snapToken = $midtrans->createSnapToken($order);
        $order->payment->update(['snap_token' => $snapToken]);

        return redirect()->route('checkout.pay', $order);
    }

    // Halaman yang membuka popup pembayaran Midtrans (Snap)
    public function pay(Order $order)
    {
        abort_if($order->status !== Order::STATUS_PENDING_PAYMENT, 404);

        return view('shop.pay', [
            'order' => $order,
            'snapToken' => $order->payment->snap_token,
            'clientKey' => config('midtrans.client_key'),
            'isProduction' => config('midtrans.is_production'),
        ]);
    }

    // Halaman status akhir order (dicek manual oleh pembeli / redirect dari Snap)
    public function status(Order $order)
    {
        $order->load('payment', 'orderDetails.product');

        return view('shop.status', compact('order'));
    }
}
