import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import { CheckCircle, Clock, XCircle } from 'lucide-react';
import api from '../services/api';

const STATUS_MAP = {
  pending_payment: { label: 'Menunggu Pembayaran', icon: Clock, color: 'text-yellow-500' },
  paid: { label: 'Pembayaran Berhasil', icon: CheckCircle, color: 'text-green-500' },
  processing: { label: 'Sedang Diproses', icon: Clock, color: 'text-blue-500' },
  shipped: { label: 'Sedang Dikirim', icon: Clock, color: 'text-blue-500' },
  completed: { label: 'Selesai', icon: CheckCircle, color: 'text-green-500' },
  expired: { label: 'Kedaluwarsa', icon: XCircle, color: 'text-red-500' },
  cancelled: { label: 'Dibatalkan', icon: XCircle, color: 'text-red-500' },
};

export default function OrderStatus() {
  const { id } = useParams();
  const [order, setOrder] = useState(null);
  const [loading, setLoading] = useState(true);

  const formatPrice = (price) =>
    new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
    }).format(price);

  const fetchOrder = async () => {
    try {
      // pakai sync-status supaya status order dicocokkan langsung ke Midtrans
      // (jaga-jaga kalau webhook belum sampai ke backend)
      const res = await api.post(`/orders/${id}/sync-status`);
      setOrder(res.data);
    } catch {
      setOrder(null);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchOrder();
    // Status bisa berubah async lewat webhook Midtrans, jadi cek ulang tiap beberapa detik
    const interval = setInterval(fetchOrder, 5000);
    return () => clearInterval(interval);
  }, [id]);

  if (loading) {
    return <div className="container-custom py-16 text-center text-gray-500">Memuat status pesanan...</div>;
  }

  if (!order) {
    return (
      <div className="container-custom py-16 text-center">
        <h2 className="text-2xl font-bold mb-2">Pesanan Tidak Ditemukan</h2>
        <Link to="/shop" className="text-accent hover:underline">Kembali Belanja</Link>
      </div>
    );
  }

  const statusInfo = STATUS_MAP[order.status] || { label: order.status, icon: Clock, color: 'text-gray-500' };
  const StatusIcon = statusInfo.icon;

  return (
    <div className="py-16">
      <div className="container-custom max-w-lg text-center">
        <StatusIcon className={`w-16 h-16 mx-auto mb-4 ${statusInfo.color}`} />
        <h1 className="text-2xl font-bold mb-1">{statusInfo.label}</h1>
        <p className="text-gray-500 mb-8">Order #{order.id}</p>

        <div className="bg-white border border-gray-100 rounded-lg p-6 text-left space-y-2 mb-8">
          {order.items.map((item, i) => (
            <div key={i} className="flex justify-between text-sm">
              <span>{item.product_name} x{item.quantity}</span>
              <span>{formatPrice(item.unit_price * item.quantity)}</span>
            </div>
          ))}
          <div className="border-t pt-3 flex justify-between font-semibold">
            <span>Total</span>
            <span>{formatPrice(order.total_amount)}</span>
          </div>
        </div>

        {order.status === 'pending_payment' && (
          <p className="text-sm text-gray-500 mb-4">
            Status pembayaran akan otomatis diperbarui setelah pembayaran diterima.
          </p>
        )}

        <Link
          to="/shop"
          className="inline-flex items-center bg-primary text-white px-6 py-3 rounded-md hover:bg-opacity-90 transition"
        >
          Lanjut Belanja
        </Link>
      </div>
    </div>
  );
}