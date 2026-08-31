<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

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
     */
    public function createSnapToken(Order $order): array
    {
        $order->loadMissing('orderDetails.product');

        $items = $order->orderDetails->map(fn ($detail) => [
            'id' => (string) $detail->product_id,
            'price' => (int) $detail->unit_price,
            'quantity' => (int) $detail->quantity,
            'name' => substr($detail->product->name ?? 'Produk', 0, 50),
        ])->toArray();

        // order_id ini kita simpan ke payments.midtrans_order_id supaya nanti
        // bisa dipakai untuk cek status transaksi langsung ke Midtrans (fallback
        // kalau webhook /midtrans/callback belum/tidak sampai ke server kita).
        $midtransOrderId = 'ORDER-'.$order->id.'-'.time();

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) $order->total_amount,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $order->buyer_name,
                'email' => $order->buyer_email,
                'phone' => $order->guest_phone,
            ],
        ];

        return [
            'snap_token' => Snap::getSnapToken($params),
            'midtrans_order_id' => $midtransOrderId,
        ];
    }

    /**
     * Tanya LANGSUNG ke Midtrans status transaksi terbaru untuk sebuah Order,
     * lalu update Order + Payment sesuai hasilnya.
     *
     * Ini jaring pengaman kalau webhook belum sampai ke backend (misalnya
     * server masih di localhost dan belum di-expose lewat ngrok, atau
     * Payment Notification URL di dashboard Midtrans belum diisi).
     */
    public function syncOrderStatus(Order $order): Order
    {
        $order->loadMissing('payment');
        $payment = $order->payment;

        if (! $payment || ! $payment->midtrans_order_id) {
            return $order;
        }

        try {
            $status = Transaction::status($payment->midtrans_order_id);
        } catch (\Exception $e) {
            // Transaksinya belum pernah "disentuh" sama sekali di sisi Midtrans.
            return $order;
        }

        $this->applyTransactionStatus($order, $payment, $status);

        return $order->fresh('payment');
    }

    /**
     * Terapkan status transaksi (baik dari webhook maupun dari syncOrderStatus)
     * ke Order & Payment. Dipakai bareng oleh MidtransCallbackController dan
     * syncOrderStatus() supaya logikanya nggak dobel/ketinggalan salah satu.
     */
    public function applyTransactionStatus(Order $order, Payment $payment, $status): void
    {
        $transactionStatus = $status->transaction_status ?? null;
        $fraudStatus = $status->fraud_status ?? null;

        if (isset($status->transaction_id)) {
            $payment->transaction_id = $status->transaction_id;
        }
        $payment->payload = json_decode(json_encode($status), true);

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $payment->status = 'settlement';
                $payment->paid_at = $payment->paid_at ?? now();
                $order->status = Order::STATUS_PAID;
            }
        } elseif ($transactionStatus === 'settlement') {
            $payment->status = 'settlement';
            $payment->paid_at = $payment->paid_at ?? now();
            $order->status = Order::STATUS_PAID;
        } elseif (in_array($transactionStatus, ['cancel', 'deny'])) {
            $payment->status = $transactionStatus;
            $order->status = Order::STATUS_CANCELLED;
        } elseif ($transactionStatus === 'expire') {
            $payment->status = 'expire';
            $order->status = Order::STATUS_EXPIRED;
        } elseif ($transactionStatus === 'pending') {
            $payment->status = 'pending';
        }

        $payment->save();
        $order->save();
    }
}