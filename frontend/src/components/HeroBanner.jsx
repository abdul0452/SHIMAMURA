import { Link } from 'react-router-dom';
import { ArrowRight } from 'lucide-react';

export default function HeroBanner() {
  return (
    <section className="relative h-[500px] lg:h-[600px] overflow-hidden">
      <div className="absolute inset-0">
        <img
          src="https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?q=80&w=1920&auto=format&fit=crop"
          alt="Fashion Banner"
          className="w-full h-full object-cover object-top"
        />
        <div className="absolute inset-0 bg-black/30" />
      </div>

      {/* Class px-4 sm:px-6 lg:px-8 & max-w-7xl diganti dengan container-custom */}
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
            className="inline-flex items-center bg-white text-gray-900 px-8 py-3 rounded-md font-medium hover:bg-gray-100 transition-all duration-300"
          >
            Shop Now
            <ArrowRight className="w-4 h-4 ml-2" />
          </Link>
        </div>
      </div>
    </section>
  );
}