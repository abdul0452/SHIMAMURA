<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Total pendapatan hanya dari pembayaran yang sudah settlement (lunas) di Midtrans
        $totalRevenue = Payment::where('status', 'settlement')->sum('amount');

        // Produk dengan stok menipis (di bawah 10) dijadikan indikator peringatan
        $lowStockCount = Product::where('stock', '<', 10)->count();

        // Order yang masih menunggu pembayaran perlu perhatian admin
        $pendingOrders = Order::where('status', 'pending_payment')->count();

        return [
            Stat::make('Total Pengguna', User::count())
                ->description('Jumlah akun terdaftar')
                ->descriptionIcon(Heroicon::OutlinedUserGroup)
                ->color('info'),

            Stat::make('Total Toko', Store::count())
                ->description('Toko yang aktif berjualan')
                ->descriptionIcon(Heroicon::OutlinedBuildingStorefront)
                ->color('success'),

            Stat::make('Total Karyawan', Employee::count())
                ->description('Karyawan di seluruh toko')
                ->descriptionIcon(Heroicon::OutlinedIdentification)
                ->color('warning'),

            Stat::make('Total Produk', Product::count())
                ->description($lowStockCount > 0
                    ? $lowStockCount.' produk stok menipis'
                    : 'Stok semua produk aman')
                ->descriptionIcon($lowStockCount > 0
                    ? Heroicon::OutlinedExclamationTriangle
                    : Heroicon::OutlinedCheckCircle)
                ->color($lowStockCount > 0 ? 'danger' : 'success'),

            Stat::make('Total Order', Order::count())
                ->description($pendingOrders.' order menunggu diproses')
                ->descriptionIcon(Heroicon::OutlinedShoppingCart)
                ->color($pendingOrders > 0 ? 'warning' : 'success'),

            Stat::make('Total Pendapatan', 'Rp '.number_format($totalRevenue, 0, ',', '.'))
                ->description('Dari seluruh pembayaran yang tercatat')
                ->descriptionIcon(Heroicon::OutlinedBanknotes)
                ->color('primary'),
        ];
    }
}
