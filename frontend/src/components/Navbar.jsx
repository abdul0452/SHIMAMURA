import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { Search, ShoppingCart, User, LogOut, Menu, X, ChevronDown, Store } from 'lucide-react';
import { useCart } from '../context/CartContext';
import { useAuth } from '../context/AuthContext';
import { useApi } from '../hooks/useApi';

export default function Navbar() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isStoreOpen, setIsStoreOpen] = useState(false);
  const [isUserOpen, setIsUserOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const { totalItems } = useCart();
  const { user, isAuthenticated, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    setIsUserOpen(false);
    navigate('/');
  };

  const { data: stores } = useApi('/stores');

  const handleSearch = (e) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      navigate(`/shop?search=${searchQuery}`);
    }
  };

  return (
    <nav className="bg-white shadow-sm sticky top-0 z-50">
      <div className="container-custom">
        <div className="flex items-center justify-between h-16 lg:h-20">
          <Link to="/" className="text-2xl font-bold text-primary tracking-wider">
            SHIMAMURA
          </Link>

          <div className="hidden lg:flex items-center space-x-8">
            <Link to="/" className="text-sm font-medium hover:text-accent transition">Home</Link>
            <Link to="/shop" className="text-sm font-medium hover:text-accent transition">Shop</Link>

            <div className="relative">
              <button
                onClick={() => setIsStoreOpen(!isStoreOpen)}
                className="flex items-center text-sm font-medium hover:text-accent transition"
              >
                <Store className="w-4 h-4 mr-1" />
                Stores
                <ChevronDown className="w-4 h-4 ml-1" />
              </button>

              {isStoreOpen && (
                <div className="absolute top-full left-0 mt-2 w-64 bg-white shadow-lg rounded-md py-2 border">
                  <Link
                    to="/stores"
                    className="block px-4 py-2 text-sm hover:bg-secondary"
                    onClick={() => setIsStoreOpen(false)}
                  >
                    All Stores
                  </Link>
                  <hr className="my-1" />
                  {stores?.map((store) => (
                    <Link
                      key={store.id}
                      to={`/store/${store.id}`}
                      className="block px-4 py-2 text-sm hover:bg-secondary"
                      onClick={() => setIsStoreOpen(false)}
                    >
                      <div className="font-medium">{store.store_name}</div>
                      <div className="text-xs text-gray-500">{store.address}</div>
                    </Link>
                  ))}
                </div>
              )}
            </div>

            <Link to="/contact" className="text-sm font-medium hover:text-accent transition">Contact</Link>
          </div>

          <form onSubmit={handleSearch} className="hidden md:flex flex-1 max-w-md mx-8">
            <div className="relative w-full">
              <input
                type="text"
                placeholder="Search products..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full pl-4 pr-10 py-2 border border-gray-200 rounded-full text-sm focus:outline-none focus:border-accent"
              />
              <button type="submit" className="absolute right-3 top-1/2 -translate-y-1/2">
                <Search className="w-4 h-4 text-gray-400" />
              </button>
            </div>
          </form>

          <div className="flex items-center space-x-5">
            <Link to="/cart" className="relative hover:text-accent transition">
              <ShoppingCart className="w-5 h-5" />
              {totalItems > 0 && (
                <span className="absolute -top-2 -right-2 bg-accent text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                  {totalItems}
                </span>
              )}
            </Link>

            {isAuthenticated ? (
              <div className="relative">
                <button
                  onClick={() => setIsUserOpen(!isUserOpen)}
                  className="flex items-center space-x-2 hover:text-accent transition"
                >
                  <div className="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-medium">
                    {user?.name?.charAt(0)?.toUpperCase() || <User className="w-4 h-4" />}
                  </div>
                  <span className="hidden md:inline text-sm font-medium max-w-[100px] truncate">
                    {user?.name || 'Account'}
                  </span>
                  <ChevronDown className="hidden md:inline w-4 h-4" />
                </button>

                {isUserOpen && (
                  <div className="absolute top-full right-0 mt-2 w-48 bg-white shadow-lg rounded-md py-2 border">
                    <div className="px-4 py-2 border-b">
                      <div className="text-sm font-medium truncate">{user?.name}</div>
                      <div className="text-xs text-gray-500 truncate">{user?.email}</div>
                    </div>
                    <button
                      onClick={handleLogout}
                      className="w-full flex items-center gap-2 px-4 py-2 text-sm text-left hover:bg-secondary text-red-600"
                    >
                      <LogOut className="w-4 h-4" />
                      Logout
                    </button>
                  </div>
                )}
              </div>
            ) : (
              <Link to="/login" className="hover:text-accent transition">
                <User className="w-5 h-5" />
              </Link>
            )}

            <button
              className="lg:hidden"
              onClick={() => setIsMenuOpen(!isMenuOpen)}
            >
              {isMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </div>

        {isMenuOpen && (
          <div className="lg:hidden py-4 border-t">
            <div className="flex flex-col space-y-3">
              <Link to="/" className="text-sm font-medium py-2" onClick={() => setIsMenuOpen(false)}>Home</Link>
              <Link to="/shop" className="text-sm font-medium py-2" onClick={() => setIsMenuOpen(false)}>Shop</Link>
              <Link to="/stores" className="text-sm font-medium py-2" onClick={() => setIsMenuOpen(false)}>Stores</Link>
              <Link to="/contact" className="text-sm font-medium py-2" onClick={() => setIsMenuOpen(false)}>Contact</Link>
              {isAuthenticated && (
                <button
                  onClick={() => { handleLogout(); setIsMenuOpen(false); }}
                  className="text-sm font-medium py-2 text-left text-red-600"
                >
                  Logout
                </button>
              )}
            </div>
          </div>
        )}
      </div>
    </nav>
  );
}