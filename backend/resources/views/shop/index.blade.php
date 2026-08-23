@extends('shop.layout')

@section('title', 'Daftar Produk')

@section('content')
    <h1>Produk</h1>

    <div class="grid">
        @forelse ($products as $product)
            <div class="card">
                <p class="product-name">{{ $product->name }}</p>
                <p class="product-store">{{ $product->store->store_name ?? '-' }}</p>
                <p class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <a href="{{ route('checkout.create', $product) }}" class="btn btn-block">Beli</a>
            </div>
        @empty
            <p>Belum ada produk yang tersedia.</p>
        @endforelse
    </div>
@endsection
