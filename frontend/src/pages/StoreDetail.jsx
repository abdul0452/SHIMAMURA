import { useParams, Link } from 'react-router-dom';
import { useApi } from '../hooks/useApi';
import ProductCard from '../components/ProductCard';
import { Store, MapPin, ArrowLeft } from 'lucide-react';

export default function StoreDetail() {
  const { id } = useParams();
  const { data: store, loading } = useApi(`/stores/${id}`);

  if (loading) {
    return (
      <div className="container-custom py-16">
        <div className="animate-pulse">
          <div className="bg-gray-200 h-64 rounded-lg mb-8" />
          <div className="bg-gray-200 h-8 rounded w-1/3 mb-4" />
          <div className="bg-gray-200 h-4 rounded w-1/2" />
        </div>
      </div>
    );
  }

  if (!store) {
    return (
      <div className="container-custom py-16 text-center">
        <h2 className="text-2xl font-bold mb-2">Store Not Found</h2>
        <Link to="/stores" className="text-accent hover:underline">
          Back to Stores
        </Link>
      </div>
    );
  }

  return (
    <div className="py-8">
      <div className="container-custom">
        <Link to="/stores" className="inline-flex items-center text-sm text-gray-500 hover:text-accent mb-6">
          <ArrowLeft className="w-4 h-4 mr-1" />
          Back to Stores
        </Link>

        <div className="bg-secondary rounded-lg p-8 mb-12">
          <div className="flex flex-col md:flex-row items-start md:items-center gap-6">
            <div className="w-20 h-20 bg-white rounded-lg flex items-center justify-center">
              <Store className="w-10 h-10 text-accent" />
            </div>
            <div>
              <h1 className="text-3xl font-bold mb-2">{store.store_name}</h1>
              <div className="flex items-center text-gray-600">
                <MapPin className="w-4 h-4 mr-2" />
                {store.address}
              </div>
            </div>
          </div>
        </div>

        <div>
          <h2 className="text-2xl font-bold mb-6">
            Products from {store.store_name}
          </h2>

          {store.products?.length > 0 ? (
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
              {store.products.map((product) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          ) : (
            <div className="text-center py-12 text-gray-500">
              No products available from this store yet.
            </div>
          )}
        </div>
      </div>
    </div>
  );
}