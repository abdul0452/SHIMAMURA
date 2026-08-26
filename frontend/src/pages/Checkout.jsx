import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useCart } from '../context/CartContext';
import { useAuth } from '../context/AuthContext';
import api from '../services/api';

export default function Checkout() {
  const { cart, totalPrice, clearCart } = useCart();
  const { user } = useAuth();
  const navigate = useNavigate();

  const [form, setForm] = useState({
    buyer_name: user?.name || '',
    buyer_email: user?.email || '',
    buyer_phone: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const formatPrice = (price) =>
    new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
    }).format(price);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handlePay = async (e) => {
    e.preventDefault();
    setError('');

    if (cart.length === 0) return;

    setLoading(true);
    try {
      const payload = {
        buyer_name: form.buyer_name,
        buyer_email: form.buyer_email,
        buyer_phone: form.buyer_phone,
        items: cart.map((item) => ({
          product_id: item.id,
          quantity: item.qty,
        })),
      };

      const res = await api.post('/checkout', payload);
      const { snap_token, order_id } = res.data;

      if (!window.snap) {
        setError('Gagal memuat sistem pembayaran. Coba refresh halaman.');
        setLoading(false);
        return;
      }

      window.snap.pay(snap_token, {
        onSuccess: () => {
          clearCart();
          navigate(`/order/${order_id}/status`);
        },
        onPending: () => {
          clearCart();
          navigate(`/order/${order_id}/status`);
        },
        onError: () => {
          setError('Pembayaran gagal. Silakan coba lagi.');
          setLoading(false);
        },
        onClose: () => {
          // popup ditutup tanpa bayar; order tetap tersimpan sebagai pending
          setLoading(false);
        },
      });
    } catch (err) {
      setError(err.response?.data?.message || 'Gagal memproses pesanan. Silakan coba lagi.');
      setLoading(false);
    }
  };

  if (cart.length === 0) {
    return (
      <div className="container-custom py-16 text-center">
        <h2 className="text-2xl font-bold mb-2">Keranjang Kosong</h2>
        <p className="text-gray-500 mb-6">Tambahkan produk dulu sebelum checkout.</p>
        <Link
          to="/shop"
          className="inline-flex items-center bg-primary text-white px-6 py-3 rounded-md hover:bg-opacity-90 transition"
        >
          Belanja Sekarang
        </Link>
      </div>
    );
  }

  return (
    <div className="py-8">
      <div className="container-custom max-w-2xl">
        <h1 className="text-3xl font-bold mb-8">Checkout</h1>

        <div className="bg-white border border-gray-100 rounded-lg p-6 mb-6">
          <h3 className="font-semibold text-lg mb-4">Ringkasan Pesanan</h3>
          <div className="space-y-2 mb-4">
            {cart.map((item) => (
              <div key={item.id} className="flex justify-between text-sm">
                <span>{item.name} x{item.qty}</span>
                <span>{formatPrice(item.price * item.qty)}</span>
              </div>
            ))}
          </div>
          <div className="border-t pt-3 flex justify-between font-semibold">
            <span>Total</span>
            <span>{formatPrice(totalPrice)}</span>
          </div>
        </div>

        <form onSubmit={handlePay} className="bg-white border border-gray-100 rounded-lg p-6 space-y-4">
          <h3 className="font-semibold text-lg mb-2">Data Pembeli</h3>

          {error && (
            <div className="bg-red-50 text-red-600 text-sm rounded-md p-3">{error}</div>
          )}

          <div>
            <label className="block text-sm font-medium mb-1">Nama Lengkap</label>
            <input
              type="text"
              name="buyer_name"
              value={form.buyer_name}
              onChange={handleChange}
              required
              className="w-full border border-gray-200 rounded-md px-4 py-2 text-sm focus:outline-none focus:border-accent"
            />
          </div>

          <div>
            <label className="block text-sm font-medium mb-1">Email</label>
            <input
              type="email"
              name="buyer_email"
              value={form.buyer_email}
              onChange={handleChange}
              required
              className="w-full border border-gray-200 rounded-md px-4 py-2 text-sm focus:outline-none focus:border-accent"
            />
          </div>

          <div>
            <label className="block text-sm font-medium mb-1">Nomor HP</label>
            <input
              type="tel"
              name="buyer_phone"
              value={form.buyer_phone}
              onChange={handleChange}
              required
              placeholder="08xxxxxxxxxx"
              className="w-full border border-gray-200 rounded-md px-4 py-2 text-sm focus:outline-none focus:border-accent"
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full bg-primary text-white py-3 rounded-md font-medium hover:bg-opacity-90 transition disabled:opacity-50"
          >
            {loading ? 'Memproses...' : `Bayar ${formatPrice(totalPrice)}`}
          </button>
        </form>
      </div>
    </div>
  );
}