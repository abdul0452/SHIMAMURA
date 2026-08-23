@extends('shop.layout')

@section('title', 'Status Order')

@php
    $badge = match ($order->status) {
        'paid', 'processing', 'shipped', 'completed' => 'badge-success',
        'pending_payment' => 'badge-warning',
        default => 'badge-danger',
    };

    $label = match ($order->status) {
        'pending_payment' => 'Menunggu Pembayaran',
        'paid' => 'Sudah Dibayar',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'expired' => 'Kedaluwarsa',
        'cancelled' => 'Dibatalkan',
        default => $order->status,
    };
@endphp

@section('content')
    <h1>Status Order #{{ $order->id }}</h1>

    <div class="card">
        <span class="badge {{ $badge }}">{{ $label }}</span>

        <p style="margin-top:16px;">Pembeli: {{ $order->buyer_name }}</p>
        <p>Email: {{ $order->buyer_email }}</p>
        <p>Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>

        @if ($order->status === 'pending_payment')
            <a href="{{ route('checkout.pay', $order) }}" class="btn" style="margin-top:12px;">
                Lanjutkan Pembayaran
            </a>
        @endif
    </div>

    <div class="card">
        <p style="font-weight:600; margin-bottom:8px;">Produk</p>
        @foreach ($order->orderDetails as $detail)
            <p>{{ $detail->product->name ?? '-' }} x{{ $detail->quantity }} —
                Rp {{ number_format($detail->unit_price * $detail->quantity, 0, ',', '.') }}</p>
        @endforeach
    </div>
@endsection
