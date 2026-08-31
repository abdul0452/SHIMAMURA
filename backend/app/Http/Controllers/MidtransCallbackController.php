<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransCallbackController extends Controller
{
    // Endpoint ini didaftarkan sebagai "Payment Notification URL" di dashboard Midtrans.
    // Midtrans akan memanggil endpoint ini setiap kali status pembayaran berubah
    // (pending -> settlement/capture, atau expire/deny/cancel).
    public function __invoke(Request $request, MidtransService $midtrans)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        $notification = new Notification();

        // order_id yang dikirim Midtrans formatnya "ORDER-{id}-{timestamp}",
        // kita ambil id order aslinya dari situ.
        $midtransOrderId = $notification->order_id;
        $orderId = (int) (explode('-', $midtransOrderId)[1] ?? null);

        $order = Order::with('payment')->find($orderId);

        if (! $order || ! $order->payment) {
            Log::warning('Midtrans callback: order tidak ditemukan', ['order_id' => $midtransOrderId]);

            return response()->json(['message' => 'Order not found'], 404);
        }

        // Logika mapping status (capture/settlement/expire/dll) sekarang ada
        // di MidtransService::applyTransactionStatus(), dipakai bareng juga
        // sama endpoint sync-status supaya nggak ditulis dua kali.
        $midtrans->applyTransactionStatus($order, $order->payment, $notification->getResponse());

        return response()->json(['message' => 'OK']);
    }
}