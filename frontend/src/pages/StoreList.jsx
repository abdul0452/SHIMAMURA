import { Link } from 'react-router-dom';
import { useApi } from '../hooks/useApi';
import { Store, MapPin, Package } from 'lucide-react';

export default function StoreList() {
  const { data: stores, loading } = useApi('/stores');

  if (loading) {
    return (
      <div className="container-custom py-16">
        <div className="text-center mb-12">
          <h1 className="text-3xl font-bold mb-2">Our Stores</h1>
          <p className="text-gray-500">Loading stores...</p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {[...Array(6)].map((_, i) => (
            <div key={i} className="animate-pulse bg-gray-200 h-48 rounded-lg" />
          ))}
        </div>
      </div>
    );
  }

  return (
    <div className="py-16">
      <div className="container-custom">
        <div className="text-center mb-12">
          <h1 className="text-3xl font-bold mb-2">Our Stores</h1>
          <p className="text-gray-500">Explore products from our trusted sellers</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {stores?.map((store) => (
            <Link
              key={store.id}
              to={`/store/${store.id}`}
              className="group bg-white border border-gray-100 rounded-lg p-6 hover:shadow-lg transition-all duration-300"
            >
              <div className="flex items-start gap-4">
                <div className="w-16 h-16 bg-secondary rounded-lg flex items-center justify-center flex-shrink-0">
                  <Store className="w-8 h-8 text-accent" />
                </div>
                <div className="flex-1">
                  <h3 className="font-semibold text-lg mb-1 group-hover:text-accent transition">
                    {store.store_name}
                  </h3>
                  <div className="flex items-center text-sm text-gray-500 mb-2">
                    <MapPin className="w-4 h-4 mr-1" />
                    <span className="line-clamp-1">{store.address}</span>
                  </div>
                  <div className="flex items-center text-sm text-gray-500">
                    <Package className="w-4 h-4 mr-1" />
                    <span>{store.products_count || 0} Products</span>
                  </div>
                </div>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </div>
  );
}