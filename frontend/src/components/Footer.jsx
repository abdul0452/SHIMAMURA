import { Link } from 'react-router-dom';
import { Send } from 'lucide-react';
import { FaFacebookF, FaInstagram, FaTwitter, FaYoutube } from 'react-icons/fa';

export default function Footer() {
  return (
    <footer className="bg-slate-900 text-white pt-14 pb-8">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
          
          {/* Brand */}
          <div>
            <h3 className="text-xl font-bold tracking-widest mb-3">SHIMAMURA</h3>
            <p className="text-slate-400 text-sm leading-relaxed">
              Discover the latest fashion trends with our curated collection of premium styles for every occasion.
            </p>
            <div className="flex space-x-3 mt-5">
              {[FaFacebookF, FaInstagram, FaTwitter, FaYoutube].map((Icon, i) => (
                <a 
                  key={i} 
                  href="#" 
                  className="text-slate-400 hover:text-white transition-colors"
                >
                  <Icon className="w-4 h-4" />
                </a>
              ))}
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="text-sm font-semibold mb-3 text-slate-200">Quick Links</h4>
            <ul className="space-y-2 text-sm text-slate-400">
              <li><Link to="/" className="hover:text-white transition">Home</Link></li>
              <li><Link to="/shop" className="hover:text-white transition">Shop</Link></li>
              <li><Link to="/stores" className="hover:text-white transition">Stores</Link></li>
              <li><Link to="/about" className="hover:text-white transition">About Us</Link></li>
              <li><Link to="/contact" className="hover:text-white transition">Contact</Link></li>
            </ul>
          </div>

          {/* Customer Service */}
          <div>
            <h4 className="text-sm font-semibold mb-3 text-slate-200">Customer Service</h4>
            <ul className="space-y-2 text-sm text-slate-400">
              <li><a href="#" className="hover:text-white transition">FAQ</a></li>
              <li><a href="#" className="hover:text-white transition">Shipping Info</a></li>
              <li><a href="#" className="hover:text-white transition">Returns & Exchanges</a></li>
              <li><a href="#" className="hover:text-white transition">Size Guide</a></li>
              <li><a href="#" className="hover:text-white transition">Track Order</a></li>
            </ul>
          </div>

          {/* Newsletter */}
          <div>
            <h4 className="text-sm font-semibold mb-3 text-slate-200">Newsletter</h4>
            <p className="text-slate-400 text-sm mb-3 leading-relaxed">
              Subscribe to get special offers, free giveaways, and once-in-a-lifetime deals.
            </p>
            <div className="flex">
              <input
                type="email"
                placeholder="Your email"
                className="flex-1 px-3 py-2 bg-slate-800 border border-slate-700 text-white placeholder-slate-500 rounded-l-md focus:outline-none focus:border-slate-500 text-sm"
              />
              <button className="bg-slate-700 px-3 py-2 rounded-r-md hover:bg-slate-600 transition border border-l-0 border-slate-700">
                <Send className="w-4 h-4 text-slate-300" />
              </button>
            </div>
          </div>
        </div>

        <div className="border-t border-slate-800 pt-6 text-center text-xs text-slate-500">
          <p>SHIMAMURA. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
}