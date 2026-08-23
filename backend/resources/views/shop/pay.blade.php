@extends('shop.layout')

@section('title', 'Pembayaran')

@section('content')
    <h1>Pembayaran</h1>

    <div class="card">
        <p>Order #{{ $order->id }}</p>
        <p class="product-price">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
        <p style="color:#6b7280; font-size:13px;">
            Klik tombol di bawah untuk membuka halaman pembayaran (transfer bank, e-wallet, QRIS, kartu kredit, dll).
        </p>
        <button id="pay-button" class="btn btn-block">Bayar Sekarang</button>
    </div>

    {{-- sandbox: https://app.sandbox.midtrans.com/snap/snap.js --}}
    {{-- production: https://app.midtrans.com/snap/snap.js --}}
    <script
        src="{{ $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ $clientKey }}">
    </script>

    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function () {
                    window.location.href = "{{ route('checkout.status', $order) }}";
                },
                onPending: function () {
                    window.location.href = "{{ route('checkout.status', $order) }}";
                },
                onError: function () {
                    window.location.href = "{{ route('checkout.status', $order) }}";
                },
                onClose: function () {
                    // pembeli menutup popup tanpa menyelesaikan pembayaran,
                    // order tetap 'pending_payment' dan bisa dicoba bayar lagi
                }
            });
        });
    </script>
@endsection
