<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransCallbackController extends Controller
{
    // Endpoint ini didaftarkan sebagai "Payment Notification URL" di dashboard Midtrans.
    // Midtrans akan memanggil endpoint ini setiap kali status pembayaran berubah
    // (pending -> settlement/capture, atau expire/deny/cancel).
    public function __invoke(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        $notification = new Notification();

        // order_id yang dikirim Midtrans formatnya "ORDER-{id}-{timestamp}",
        // kita ambil id order aslinya dari situ.
        $midtransOrderId = $notification->order_id;
        $orderId = (int) explode('-', $midtransOrderId)[1] ?? null;

        $order = Order::with('payment')->find($orderId);

        if (! $order || ! $order->payment) {
            Log::warning('Midtrans callback: order tidak ditemukan', ['order_id' => $midtransOrderId]);

            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? null;

        $payment = $order->payment;
        $payment->transaction_id = $notification->transaction_id;
        $payment->payload = $notification->getResponse();

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept') {
                $payment->status = 'settlement';
                $payment->paid_at = now();
                $order->status = Order::STATUS_PAID;
            }
        } elseif ($transactionStatus === 'settlement') {
            $payment->status = 'settlement';
            $payment->paid_at = now();
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

        return response()->json(['message' => 'OK']);
    }
}
