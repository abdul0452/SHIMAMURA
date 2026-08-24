import { Link } from 'react-router-dom';
import { ArrowRight } from 'lucide-react';

export default function HeroBanner() {
  return (
    <section className="relative h-[500px] lg:h-[600px] bg-secondary overflow-hidden">
      <div className="absolute inset-0">
        <img
          src="https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1920&h=800&fit=crop"
          alt="Fashion Banner"
          className="w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-black/20" />
      </div>

      <div className="relative container-custom h-full flex items-center">
        <div className="max-w-xl text-white">
          <p className="text-sm font-medium mb-2 tracking-widest uppercase">Extra 50% Spring Clearance</p>
          <h1 className="text-4xl lg:text-6xl font-bold mb-6 leading-tight">
            Summer Style Sensations.
          </h1>
          <p className="text-lg mb-8 text-white/90">
            Discover the hottest trends this season with our exclusive summer collection.
          </p>
          <Link
            to="/shop"
            className="inline-flex items-center bg-white text-primary px-8 py-3 rounded-md font-medium hover:bg-accent hover:text-white transition-all duration-300"
          >
            Shop Now
            <ArrowRight className="w-4 h-4 ml-2" />
          </Link>
        </div>
      </div>
    </section>
  );
}