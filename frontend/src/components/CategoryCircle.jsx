import { Link } from 'react-router-dom';

const categories = [
  { id: 1, name: "Men's Fashion", image: "https://images.unsplash.com/photo-1617137968427-85924c800a22?w=200&h=200&fit=crop" },
  { id: 2, name: "Women's Fashion", image: "https://images.unsplash.com/photo-1581044777550-4cfa60707c03?w=200&h=200&fit=crop" },
  { id: 3, name: "Kids", image: "https://images.unsplash.com/photo-1519238263530-99bdd11df2ea?w=200&h=200&fit=crop" },
  { id: 4, name: "Accessories", image: "https://images.unsplash.com/photo-1611085583191-a3b181a88401?w=200&h=200&fit=crop" },
  { id: 5, name: "Shoes", image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=200&h=200&fit=crop" },
  { id: 6, name: "Bags", image: "https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=200&h=200&fit=crop" },
];

export default function CategoryCircle() {
  return (
    <section className="py-16 bg-secondary">
      <div className="container-custom">
        <div className="text-center mb-12">
          <h2 className="text-3xl font-bold mb-2">Top Category</h2>
          <p className="text-gray-500">Browse our most popular categories</p>
        </div>

        <div className="flex flex-wrap justify-center gap-6 lg:gap-10">
          {categories.map((cat) => (
            <Link
              key={cat.id}
              to={`/shop?category=${cat.name}`}
              className="group flex flex-col items-center"
            >
              <div className="w-24 h-24 lg:w-32 lg:h-32 rounded-full overflow-hidden border-2 border-transparent group-hover:border-accent transition-all duration-300">
                <img
                  src={cat.image}
                  alt={cat.name}
                  className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                />
              </div>
              <span className="mt-3 text-sm font-medium group-hover:text-accent transition">
                {cat.name}
              </span>
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
}