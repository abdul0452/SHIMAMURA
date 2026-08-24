import { useParams, Link } from 'react-router-dom';
import { useState } from 'react';
import { useApi } from '../hooks/useApi';
import { useCart } from '../context/CartContext';
import { ShoppingCart, Heart, Star, Minus, Plus, ArrowLeft, Store } from 'lucide-react';

export default function ProductDetail() {
  const { id } = useParams();
  const { data: product, loading } = useApi(`/products/${id}`);
  const { addToCart } = useCart();
  const [qty, setQty] = useState(1);

  const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
    }).format(price);
  };

  if (loading) {
    return (
      <div className="container-custom py-16">
        <div className="animate-pulse grid grid-cols-1 lg:grid-cols-2 gap-12">
          <div className="bg-gray-200 aspect-square rounded-lg" />
          <div>
            <div className="bg-gray-200 h-8 rounded w-3/4 mb-4" />
            <div className="bg-gray-200 h-6 rounded w-1/4 mb-6" />
            <div className="bg-gray-200 h-4 rounded w-full mb-2" />
            <div className="bg-gray-200 h-4 rounded w-full mb-2" />
            <div className="bg-gray-200 h-4 rounded w-2/3 mb-8" />
            <div className="bg-gray-200 h-12 rounded w-1/3" />
          </div>
        </div>
      </div>
    );
  }

  if (!product) {
    return (
      <div className="container-custom py-16 text-center">
        <h2 className="text-2xl font-bold mb-2">Product Not Found</h2>
        <Link to="/shop" className="text-accent hover:underline">
          Back to Shop
        </Link>
      </div>
    );
  }

  return (
    <div className="py-8">
      <div className="container-custom">
        <Link to="/shop" className="inline-flex items-center text-sm text-gray-500 hover:text-accent mb-6">
          <ArrowLeft className="w-4 h-4 mr-1" />
          Back to Shop
        </Link>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
          <div className="bg-secondary rounded-lg overflow-hidden">
            <img
              src={product.image || `https://via.placeholder.com/600x600?text=${encodeURIComponent(product.name)}`}
              alt={product.name}
              className="w-full aspect-square object-cover"
            />
          </div>

          <div>
            <div className="flex items-center gap-2 mb-2">
              {[...Array(5)].map((_, i) => (
                <Star key={i} className="w-4 h-4 fill-accent text-accent" />
              ))}
              <span className="text-sm text-gray-500">(128 reviews)</span>
            </div>

            <h1 className="text-3xl font-bold mb-2">{product.name}</h1>

            <p className="text-2xl text-accent font-semibold mb-4">
              {formatPrice(product.price)}
            </p>

            {product.store && (
              <Link
                to={`/store/${product.store.id}`}
                className="inline-flex items-center text-sm text-gray-600 mb-6 hover:text-accent"
              >
                <Store className="w-4 h-4 mr-1" />
                Sold by: {product.store.store_name}
              </Link>
            )}

            <p className="text-gray-600 mb-6 leading-relaxed">
              {product.description || product.detail?.description || 'No description available.'}
            </p>

            <div className="flex items-center gap-4 mb-6">
              <span className="text-sm font-medium">Quantity:</span>
              <div className="flex items-center border border-gray-200 rounded-md">
                <button
                  onClick={() => setQty(Math.max(1, qty - 1))}
                  className="px-3 py-2 hover:bg-secondary transition"
                >
                  <Minus className="w-4 h-4" />
                </button>
                <span className="px-4 py-2 border-x border-gray-200 min-w-[3rem] text-center">
                  {qty}
                </span>
                <button
                  onClick={() => setQty(qty + 1)}
                  className="px-3 py-2 hover:bg-secondary transition"
                >
                  <Plus className="w-4 h-4" />
                </button>
              </div>
              <span className="text-sm text-gray-500">
                {product.stock} items available
              </span>
            </div>

            <div className="flex gap-4">
              <button
                onClick={() => addToCart({ ...product, qty })}
                className="flex-1 bg-primary text-white py-3 rounded-md font-medium hover:bg-opacity-90 transition flex items-center justify-center gap-2"
              >
                <ShoppingCart className="w-5 h-5" />
                Add to Cart
              </button>
              <button className="p-3 border border-gray-200 rounded-md hover:bg-secondary transition">
                <Heart className="w-5 h-5" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}