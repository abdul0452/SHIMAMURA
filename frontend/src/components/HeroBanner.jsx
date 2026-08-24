import { Link } from 'react-router-dom';
import { ArrowRight } from 'lucide-react';

export default function HeroBanner() {
  return (
    <section className="relative h-[500px] lg:h-[600px] overflow-hidden">
      <div className="absolute inset-0">
        <img
          src="https://images.pexels.com/photos/994234/pexels-photo-994234.jpeg?auto=compress&cs=tinysrgb&w=1920&h=800&fit=crop"
          alt="Fashion Banner"
          className="w-full h-full object-cover object-top"
        />
        <div className="absolute inset-0 bg-black/30" />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
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