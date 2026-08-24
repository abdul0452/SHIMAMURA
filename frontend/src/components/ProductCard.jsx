import { Link } from 'react-router-dom';
import { ShoppingCart, Star } from 'lucide-react';
import { useCart } from '../context/CartContext';

export default function ProductCard({ product }) {
  const { addToCart } = useCart();

  const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
    }).format(price);
  };

  return (
    <div className="group bg-white rounded-lg overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300">
      <div className="relative overflow-hidden aspect-[3/4]">
        <img
          src={product.image || `https://via.placeholder.com/300x400?text=${encodeURIComponent(product.name)}`}
          alt={product.name}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
        />

        <div className="absolute top-3 left-3 flex flex-col gap-1">
          {product.stock <= 5 && (
            <span className="bg-red-500 text-white text-xs px-2 py-1 rounded">Hot</span>
          )}
          {product.created_at && new Date(product.created_at) > new Date(Date.now() - 7 * 24 * 60 * 60 * 1000) && (
            <span className="bg-primary text-white text-xs px-2 py-1 rounded">New</span>
          )}
        </div>

        <button
          onClick={() => addToCart(product)}
          className="absolute bottom-3 right-3 bg-white p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300 hover:bg-accent hover:text-white"
        >
          <ShoppingCart className="w-5 h-5" />
        </button>
      </div>

      <div className="p-4">
        <div className="flex items-center gap-1 mb-1">
          {[...Array(5)].map((_, i) => (
            <Star key={i} className="w-3 h-3 fill-accent text-accent" />
          ))}
        </div>
        <Link to={`/product/${product.id}`}>
          <h3 className="font-medium text-sm mb-1 hover:text-accent transition line-clamp-1">
            {product.name}
          </h3>
        </Link>
        <p className="text-accent font-semibold">{formatPrice(product.price)}</p>
      </div>
    </div>
  );
}