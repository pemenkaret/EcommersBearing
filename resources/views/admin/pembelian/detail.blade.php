@extends('layout.admin.app')

@section('title', 'Detail Pembelian - Admin')

@section('content')
    <!-- Header -->
    <div class="bg-linear-to-r from-primary-700 to-primary-900 rounded-2xl shadow-xl p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.pembelian.index') }}"
                    class="inline-flex items-center text-white hover:text-white mb-4 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h1 class="text-3xl font-bold text-white mb-2">Detail Pembelian</h1>
                <p class="text-primary-100">{{ $order->order_number }}</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-receipt text-primary-900 text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Info Order & Actions -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status Order</h3>
                
                <div class="text-center mb-4">
                    @switch($order->status)
                        @case('pending')
                            <div class="w-20 h-20 mx-auto bg-yellow-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-clock text-yellow-600 text-3xl"></i>
                            </div>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                            @break
                        @case('paid')
                            <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-credit-card text-green-600 text-3xl"></i>
                            </div>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                Paid
                            </span>
                            @break
                        @case('processing')
                            <div class="w-20 h-20 mx-auto bg-primary-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-cog text-primary-600 text-3xl"></i>
                            </div>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-primary-100 text-primary-800">
                                Processing
                            </span>
                            @break
                        @case('shipped')
                            <div class="w-20 h-20 mx-auto bg-purple-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-truck text-purple-600 text-3xl"></i>
                            </div>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                Shipped
                            </span>
                            @break
                        @case('delivered')
                            <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                            </div>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                Delivered
                            </span>
                            @break
                        @case('cancelled')
                            <div class="w-20 h-20 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-times-circle text-red-600 text-3xl"></i>
                            </div>
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                Cancelled
                            </span>
                            @break
                    @endswitch
                </div>

                <!-- Update Status Form -->
                @if (!in_array($order->status, ['delivered', 'cancelled']))
                    <form action="{{ route('admin.pembelian.update-status', $order->id) }}" method="POST" class="mt-4 pt-4 border-t">
                        @csrf
                        @method('PATCH')
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ubah Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 mb-2">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <input type="text" name="keterangan" placeholder="Keterangan (opsional)" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 mb-2">
                        <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-all">
                            <i class="fas fa-save mr-2"></i>Update Status
                        </button>
                    </form>
                @endif
            </div>

            <!-- Info Pengiriman -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Pengiriman</h3>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kurir:</span>
                        <span class="font-medium">{{ $order->kurir ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">No. Resi:</span>
                        <span class="font-medium">{{ $order->resi ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Ongkir:</span>
                        <span class="font-medium">Rp {{ number_format($order->ongkir ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Estimasi Sampai:</span>
                        <span class="font-medium">{{ $order->estimasi_sampai ? \Carbon\Carbon::parse($order->estimasi_sampai)->format('d M Y') : '-' }}</span>
                    </div>
                </div>

                <!-- Update Resi Form -->
                @if (in_array($order->status, ['paid', 'processing', 'shipped']))
                    <form action="{{ route('admin.pembelian.update-resi', $order->id) }}" method="POST" class="mt-4 pt-4 border-t">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-2">
                            <input type="text" name="kurir" value="{{ $order->kurir }}" placeholder="Kurir (JNE, JNT, dll)" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                            <input type="text" name="resi" value="{{ $order->resi }}" placeholder="Nomor Resi" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                            <input type="date" name="estimasi_sampai" value="{{ $order->estimasi_sampai }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                            <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition-all">
                                <i class="fas fa-truck mr-2"></i>Update Resi
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <!-- Info Pelanggan -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Pelanggan</h3>
                
                <div class="flex items-center mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name ?? 'User') }}&size=60&background=3b82f6&color=fff" 
                        alt="Avatar" class="w-14 h-14 rounded-full mr-4">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $order->user->name ?? 'Guest' }}</p>
                        <p class="text-sm text-gray-500">{{ $order->user->email ?? '-' }}</p>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Telepon:</span>
                        <span class="font-medium">{{ $order->user->telepon ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-receipt mr-2 text-green-600"></i>Bukti Pembayaran
                </h3>
                
                @if ($order->bukti_pembayaran)
                    <div class="space-y-4">
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $order->bukti_pembayaran) }}" 
                                alt="Bukti Pembayaran" 
                                class="w-full rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition-all"
                                onclick="openImageModal('{{ asset('storage/' . $order->bukti_pembayaran) }}')">
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black bg-opacity-30 rounded-lg">
                                <span class="text-white text-sm font-medium">
                                    <i class="fas fa-search-plus mr-1"></i>Lihat Besar
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">
                                <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                Bukti sudah diupload
                            </span>
                            <a href="{{ asset('storage/' . $order->bukti_pembayaran) }}" 
                                target="_blank" 
                                class="text-primary-600 hover:text-primary-800 font-medium">
                                <i class="fas fa-external-link-alt mr-1"></i>Buka Tab Baru
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">Pelanggan belum mengunggah bukti pembayaran</p>
                        @if ($order->status === 'pending')
                            <p class="text-xs text-gray-400 mt-1">Menunggu pembayaran dari pelanggan</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Detail Order -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Order -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Order</h3>
                
                <div class="grid md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <label class="text-gray-500">No. Order</label>
                        <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500">Tanggal Order</label>
                        <p class="font-semibold text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500">Metode Pembayaran</label>
                        <p class="font-semibold text-gray-900">{{ ucfirst($order->metode_pembayaran ?? 'Transfer') }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500">Catatan</label>
                        <p class="font-semibold text-gray-900">{{ $order->catatan ?? '-' }}</p>
                    </div>
                </div>

                <!-- Alamat Pengiriman -->
                <div class="mt-4 pt-4 border-t">
                    <label class="text-gray-500 text-sm">Alamat Pengiriman</label>
                    <p class="font-semibold text-gray-900 mt-1">
                        {{ $order->alamat_pengiriman ?? '-' }}
                    </p>
                </div>
            </div>

            <!-- Items -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-900">Produk Dipesan</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if ($item->produk && $item->produk->images->first())
                                                <img src="{{ asset('storage/' . $item->produk->images->first()->image_path) }}" 
                                                    alt="{{ $item->produk->nama }}" class="w-12 h-12 rounded-lg object-cover mr-3">
                                            @else
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg mr-3 flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->produk->nama ?? $item->produk_nama ?? 'Produk' }}</p>
                                                <p class="text-xs text-gray-500">SKU: {{ $item->produk->sku ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="bg-gray-50 p-6">
                    <div class="flex justify-end">
                        <div class="w-64 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Subtotal:</span>
                                <span class="font-medium">Rp {{ number_format($order->subtotal ?? $order->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Ongkir:</span>
                                <span class="font-medium">Rp {{ number_format($order->ongkir ?? 0, 0, ',', '.') }}</span>
                            </div>
                            @if ($order->diskon)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Diskon:</span>
                                    <span>- Rp {{ number_format($order->diskon, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-lg font-bold pt-2 border-t">
                                <span>Total:</span>
                                <span class="text-primary-600">Rp {{ number_format($order->total + ($order->ongkir ?? 0), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Status -->
            @if ($order->statuses && $order->statuses->count() > 0)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Riwayat Status</h3>
                    
                    <div class="space-y-4">
                        @foreach ($order->statuses->sortByDesc('created_at') as $status)
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center mr-4 shrink-0">
                                    <i class="fas fa-history text-primary-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ ucfirst($status->status) }}</p>
                                    <p class="text-sm text-gray-500">{{ $status->keterangan ?? '-' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $status->created_at->format('d M Y H:i') }} 
                                        @if ($status->createdBy)
                                            oleh {{ $status->createdBy->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal for Image Preview -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center p-4" onclick="closeImageModal()">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" src="" alt="Bukti Pembayaran" class="max-w-full max-h-[80vh] rounded-lg shadow-2xl">
        </div>
    </div>

    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection