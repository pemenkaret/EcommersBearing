@extends('layout.admin.app')

@section('title', 'Dashboard - Admin Bearing Shop')

@section('content')
    <!-- Header -->
    <div class="bg-linear-to-r from-primary-700 to-primary-900 rounded-2xl shadow-xl p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Dashboard</h1>
                <p class="text-primary-100">Selamat datang, {{ auth()->user()->name }}!</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-primary-900 text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Pesanan -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalPesanan) }}</p>
                    <p class="text-xs text-primary-600 mt-1"><i class="fas fa-shopping-cart mr-1"></i>Semua order</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-bag text-primary-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalProduk) }}</p>
                    <p class="text-xs text-purple-600 mt-1"><i class="fas fa-box mr-1"></i>Dalam katalog</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-boxes text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Pelanggan -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Pelanggan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalPelanggan) }}</p>
                    <p class="text-xs text-orange-600 mt-1"><i class="fas fa-users mr-1"></i>Terdaftar</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik & Tabel -->
    <div class="grid lg:grid-cols-2 gap-6 mb-8">
        <!-- Grafik Penjualan 7 Hari -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-chart-bar mr-2 text-primary-600"></i>Penjualan 7 Hari Terakhir
            </h3>
            
            <div class="h-64" id="chartContainer">
                @if($penjualan7Hari->count() > 0)
                    <canvas id="salesChart"></canvas>
                @else
                    <div class="h-full flex items-center justify-center text-gray-400">
                        <div class="text-center">
                            <i class="fas fa-chart-line text-4xl mb-2"></i>
                            <p>Belum ada data penjualan</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Produk Stok Menipis -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-exclamation-triangle mr-2 text-yellow-600"></i>Produk Stok Menipis
                </h3>
                <a href="{{ route('admin.produk.index') }}" class="text-sm text-primary-600 hover:text-primary-800">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse ($produkStokMenipis as $produk)
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-box text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ Str::limit($produk->nama, 25) }}</p>
                                <p class="text-xs text-gray-500">{{ $produk->merk->nama ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Stok: {{ $produk->stok }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Min: {{ $produk->min_stok }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-check-circle text-4xl mb-2 text-green-400"></i>
                        <p>Semua stok produk aman</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-clock mr-2 text-primary-600"></i>Pesanan Terbaru
                </h3>
                <a href="{{ route('admin.pembelian.index') }}" class="text-sm text-primary-600 hover:text-primary-800">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No. Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pesananTerbaru as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">{{ $order->order_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'User') }}&size=32&background=3b82f6&color=fff" 
                                        alt="Avatar" class="w-8 h-8 rounded-full mr-2">
                                    <span class="text-sm text-gray-900">{{ $order->user->name ?? 'Guest' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @switch($order->status)
                                    @case('pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                        @break
                                    @case('paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-credit-card mr-1"></i>Paid
                                        </span>
                                        @break
                                    @case('processing')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                            <i class="fas fa-cog mr-1"></i>Processing
                                        </span>
                                        @break
                                    @case('shipped')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-truck mr-1"></i>Shipped
                                        </span>
                                        @break
                                    @case('delivered')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Delivered
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Cancelled
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $order->status }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.pembelian.show', $order->id) }}" 
                                    class="text-primary-600 hover:text-primary-800" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada pesanan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
@if($penjualan7Hari->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($penjualan7Hari->pluck('tanggal')->map(function($d) { return \Carbon\Carbon::parse($d)->format('d M'); })) !!},
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: {!! json_encode($penjualan7Hari->pluck('total')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endpush