import HeroBanner from '../components/HeroBanner';
import ProductGrid from '../components/ProductGrid';
import CategoryCircle from '../components/CategoryCircle';
import PromoBanner from '../components/PromoBanner';
import TrustBadge from '../components/TrustBadge';
import { useApi } from '../hooks/useApi';

export default function Home() {
  const { data: trendingProducts, loading: trendingLoading } = useApi('/products?limit=8');
  const { data: bestSelling, loading: bestLoading } = useApi('/products?best_seller=true');

  return (
    <div>
      <HeroBanner />
      <ProductGrid
        title="Trending Product"
        subtitle="Discover our most popular and best-selling items this week"
        products={trendingProducts}
        loading={trendingLoading}
      />
      <PromoBanner />
      <CategoryCircle />
      <TrustBadge />
      <ProductGrid
        title="Best Selling Product"
        subtitle="These are our customers' favorite picks right now"
        products={bestSelling}
        loading={bestLoading}
      />
    </div>
  );
}