@extends('shop.layout')

@section('title', 'Checkout - ' . $product->name)

@section('content')
    <h1>Checkout</h1>

    <div class="card">
        <p class="product-name">{{ $product->name }}</p>
        <p class="product-store">{{ $product->store->store_name ?? '-' }}</p>
        <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        <p style="color:#6b7280; font-size:13px;">Stok tersedia: {{ $product->stock }}</p>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('checkout.store', $product) }}">
            @csrf

            <label for="quantity">Jumlah</label>
            <input type="number" id="quantity" name="quantity" min="1" max="{{ $product->stock }}"
                   value="{{ old('quantity', 1) }}">
            @error('quantity') <div class="error">{{ $message }}</div> @enderror

            <label for="guest_name">Nama</label>
            <input type="text" id="guest_name" name="guest_name" value="{{ old('guest_name') }}">
            @error('guest_name') <div class="error">{{ $message }}</div> @enderror

            <label for="guest_email">Email</label>
            <input type="email" id="guest_email" name="guest_email" value="{{ old('guest_email') }}">
            @error('guest_email') <div class="error">{{ $message }}</div> @enderror

            <label for="guest_phone">No. HP</label>
            <input type="tel" id="guest_phone" name="guest_phone" value="{{ old('guest_phone') }}">
            @error('guest_phone') <div class="error">{{ $message }}</div> @enderror

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-block">Lanjut ke Pembayaran</button>
            </div>
        </form>
    </div>
@endsection
