import React, { useState, useEffect } from 'react';
import api from './services/api';

export default function App() {
  const [products, setProducts] = useState([]);
  const [cart, setCart] = useState([]);
  const [loading, setLoading] = useState(true);

  // Mengambil data produk dari Laravel
  useEffect(() => {
    fetchProducts();
  }, []);

  const fetchProducts = async () => {
    try {
      const response = await api.get('/products');
      setProducts(response.data.data);
    } catch (error) {
      console.error("Gagal memuat produk", error);
    } finally {
      setLoading(false);
    }
  };

  // Fungsi menambah ke keranjang
  const addToCart = (product) => {
    setCart(prevCart => {
      const existing = prevCart.find(item => item.id === product.id);
      if (existing) {
        return prevCart.map(item => 
          item.id === product.id ? { ...item, quantity: item.quantity + 1 } : item
        );
      }
      return [...prevCart, { ...product, quantity: 1 }];
    });
  };

  // Fungsi Checkout
  const handleCheckout = async () => {
    if (cart.length === 0) {
        alert("Keranjang masih kosong!");
        return;
    }

    try {
      const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
      
      const payload = {
        store_id: cart[0].store_id || 1, // Mengambil ID Toko dari produk pertama
        total_price: total,
        items: cart.map(item => ({
          product_id: item.id,
          quantity: item.quantity,
          price: item.price
        }))
      };

      const res = await api.post('/checkout', payload);
      alert(res.data.message + "\nOrder ID: " + res.data.data.id);
      setCart([]); // Kosongkan keranjang setelah berhasil
    } catch (error) {
      console.error("Checkout gagal", error);
      alert("Terjadi kesalahan saat melakukan checkout.");
    }
  };

  if (loading) return <div className="flex justify-center items-center h-screen font-bold text-xl">Memuat produk...</div>;

  return (
    <div className="min-h-screen bg-gray-100 p-8 font-sans">
      <div className="max-w-7xl mx-auto">
        <h1 className="text-3xl font-bold mb-8 text-gray-800">Toko Online</h1>
        
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {/* List Produk */}
          <div className="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
            {products.length > 0 ? (
                products.map(product => (
                <div key={product.id} className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 transition hover:shadow-md">
                    <h3 className="font-bold text-xl mb-2 text-gray-800">{product.name}</h3>
                    <p className="text-blue-600 font-semibold mb-4">Rp {parseFloat(product.price).toLocaleString('id-ID')}</p>
                    <button 
                    onClick={() => addToCart(product)}
                    className="w-full bg-blue-600 text-white font-medium px-4 py-2 rounded-lg hover:bg-blue-700 transition"
                    >
                    Tambah ke Keranjang
                    </button>
                </div>
                ))
            ) : (
                <p className="text-gray-500 col-span-2">Belum ada data produk di database.</p>
            )}
          </div>

          {/* Keranjang Belanja */}
          <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
            <h2 className="text-2xl font-bold mb-6 text-gray-800">Keranjang</h2>
            {cart.length === 0 ? (
              <p className="text-gray-500 italic">Keranjang belanja kosong.</p>
            ) : (
              <div>
                <div className="space-y-4 mb-6">
                    {cart.map(item => (
                    <div key={item.id} className="flex justify-between items-center border-b pb-3">
                        <div>
                            <p className="font-semibold text-gray-800">{item.name}</p>
                            <p className="text-sm text-gray-500">{item.quantity}x @ Rp {parseFloat(item.price).toLocaleString('id-ID')}</p>
                        </div>
                        <p className="font-bold text-gray-800">
                            Rp {(item.price * item.quantity).toLocaleString('id-ID')}
                        </p>
                    </div>
                    ))}
                </div>
                
                <div className="flex justify-between items-center mb-6 pt-4 border-t-2 border-gray-800">
                    <span className="font-bold text-lg">Total</span>
                    <span className="font-bold text-xl text-blue-600">
                        Rp {cart.reduce((sum, item) => sum + (item.price * item.quantity), 0).toLocaleString('id-ID')}
                    </span>
                </div>

                <button 
                  onClick={handleCheckout}
                  className="w-full bg-green-500 text-white font-bold py-3 rounded-lg hover:bg-green-600 transition"
                >
                  Checkout Pesanan
                </button>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}