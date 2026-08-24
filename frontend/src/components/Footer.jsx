import { Link } from 'react-router-dom';
import { Facebook, Instagram, Twitter, Youtube, Send } from 'lucide-react';

export default function Footer() {
  return (
    <footer className="bg-primary text-white pt-16 pb-8">
      <div className="container-custom">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
          <div>
            <h3 className="text-2xl font-bold mb-4 tracking-wider">SHIMAMURA</h3>
            <p className="text-gray-400 text-sm leading-relaxed">
              Discover the latest fashion trends with our curated collection of premium styles for every occasion.
            </p>
            <div className="flex space-x-4 mt-6">
              <a href="#" className="hover:text-accent transition"><Facebook className="w-5 h-5" /></a>
              <a href="#" className="hover:text-accent transition"><Instagram className="w-5 h-5" /></a>
              <a href="#" className="hover:text-accent transition"><Twitter className="w-5 h-5" /></a>
              <a href="#" className="hover:text-accent transition"><Youtube className="w-5 h-5" /></a>
            </div>
          </div>

          <div>
            <h4 className="font-semibold mb-4">Quick Links</h4>
            <ul className="space-y-2 text-sm text-gray-400">
              <li><Link to="/" className="hover:text-accent transition">Home</Link></li>
              <li><Link to="/shop" className="hover:text-accent transition">Shop</Link></li>
              <li><Link to="/stores" className="hover:text-accent transition">Stores</Link></li>
              <li><Link to="/about" className="hover:text-accent transition">About Us</Link></li>
              <li><Link to="/contact" className="hover:text-accent transition">Contact</Link></li>
            </ul>
          </div>

          <div>
            <h4 className="font-semibold mb-4">Customer Service</h4>
            <ul className="space-y-2 text-sm text-gray-400">
              <li><a href="#" className="hover:text-accent transition">FAQ</a></li>
              <li><a href="#" className="hover:text-accent transition">Shipping Info</a></li>
              <li><a href="#" className="hover:text-accent transition">Returns & Exchanges</a></li>
              <li><a href="#" className="hover:text-accent transition">Size Guide</a></li>
              <li><a href="#" className="hover:text-accent transition">Track Order</a></li>
            </ul>
          </div>

          <div>
            <h4 className="font-semibold mb-4">Newsletter</h4>
            <p className="text-gray-400 text-sm mb-4">
              Subscribe to get special offers, free giveaways, and once-in-a-lifetime deals.
            </p>
            <div className="flex">
              <input
                type="email"
                placeholder="Your email"
                className="flex-1 px-4 py-2 bg-white/10 text-white placeholder-gray-500 rounded-l-md focus:outline-none text-sm"
              />
              <button className="bg-accent px-4 py-2 rounded-r-md hover:bg-opacity-90 transition">
                <Send className="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>

        <div className="border-t border-white/10 pt-8 text-center text-sm text-gray-500">
          <p>SHIMAMURA. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
}