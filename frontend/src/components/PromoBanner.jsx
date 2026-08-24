import { Link } from 'react-router-dom';
import { ArrowRight } from 'lucide-react';

const promos = [
  {
    id: 1,
    title: "Spring Style",
    subtitle: "New Collection",
    image: "https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=500&h=300&fit=crop",
    link: "/shop",
  },
  {
    id: 2,
    title: "25% Off Items",
    subtitle: "Limited Time",
    image: "https://images.unsplash.com/photo-1445205170230-053b83016050?w=500&h=300&fit=crop",
    link: "/shop",
  },
  {
    id: 3,
    title: "New Arrivals",
    subtitle: "Just Landed",
    image: "https://images.unsplash.com/photo-1483985988355-763728e1935b?w=500&h=300&fit=crop",
    link: "/shop",
  },
];

export default function PromoBanner() {
  return (
    <section className="py-16">
      <div className="container-custom">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {promos.map((promo) => (
            <Link
              key={promo.id}
              to={promo.link}
              className="group relative h-64 lg:h-80 rounded-lg overflow-hidden"
            >
              <img
                src={promo.image}
                alt={promo.title}
                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              />
              <div className="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition" />
              <div className="absolute inset-0 flex flex-col items-center justify-center text-white">
                <p className="text-sm font-medium mb-1">{promo.subtitle}</p>
                <h3 className="text-2xl lg:text-3xl font-bold mb-4">{promo.title}</h3>
                <span className="inline-flex items-center text-sm font-medium border-b border-white pb-1 group-hover:border-accent group-hover:text-accent transition">
                  Shop Now
                  <ArrowRight className="w-4 h-4 ml-2" />
                </span>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
}