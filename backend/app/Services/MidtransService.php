<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Minta Snap Token ke Midtrans untuk sebuah Order.
     * Snap Token inilah yang dipakai di frontend untuk membuka popup pembayaran.
     */
    public function createSnapToken(Order $order): string
    {
        $order->loadMissing('orderDetails.product');

        $items = $order->orderDetails->map(fn ($detail) => [
            'id' => (string) $detail->product_id,
            'price' => (int) $detail->unit_price,
            'quantity' => (int) $detail->quantity,
            'name' => substr($detail->product->name ?? 'Produk', 0, 50),
        ])->toArray();

        $params = [
            'transaction_details' => [
                // order_id harus unik di Midtrans, jadi digabung dengan waktu
                'order_id' => 'ORDER-'.$order->id.'-'.time(),
                'gross_amount' => (int) $order->total_amount,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $order->buyer_name,
                'email' => $order->buyer_email,
                'phone' => $order->guest_phone,
            ],
        ];

        return Snap::getSnapToken($params);
    }
}
