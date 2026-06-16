 @extends('layout.pelanggan.app')

@section('title', 'Keranjang Belanja - Bearing Shop')

@section('content')
    <!-- Header Halaman -->
    <div class="bg-linear-to-r from-primary-600 to-primary-800 rounded-2xl shadow-xl p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Keranjang Belanja</h1>
                <p class="text-primary-100">Kelola produk yang ingin Anda beli</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-cart-shopping text-white text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages menggunakan komponen -->
    @if (session('success'))
        @include('pelanggan.component.alert', ['type' => 'success', 'slot' => session('success')])
    @endif

    @if (session('error'))
        @include('pelanggan.component.alert', ['type' => 'error', 'slot' => session('error')])
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Daftar Produk Keranjang -->
        <div class="lg:col-span-2 space-y-4">
            @if ($keranjangs->count() > 0)
                <!-- Header Keranjang -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-shopping-bag text-primary-600"></i>
                            <span class="font-semibold text-gray-900">{{ $keranjangs->count() }} Produk di Keranjang</span>
                        </div>
                        <form action="{{ route('pelanggan.keranjang.clear') }}" method="POST" 
                            onsubmit="return confirm('Kosongkan semua keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm flex items-center">
                                <i class="fas fa-trash mr-2"></i>Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Item Keranjang -->
                @foreach ($keranjangs as $keranjang)
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-all">
                        <div class="flex items-start space-x-4">
                            <!-- Gambar Produk -->
                            <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                @if ($keranjang->produk->images->first())
                                    <img src="{{ asset('storage/' . $keranjang->produk->images->first()->image_path) }}" 
                                        alt="{{ $keranjang->produk->nama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Info Produk -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1 pr-4">
                                        <p class="text-xs text-gray-500 mb-1">{{ $keranjang->produk->merk->nama ?? '-' }}</p>
                                        <h4 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                            <a href="{{ route('pelanggan.produk.show', $keranjang->produk->slug) }}" class="hover:text-primary-600">
                                                {{ $keranjang->produk->nama }}
                                            </a>
                                        </h4>
                                        <div class="flex items-center space-x-2 mb-3">
                                            <span class="text-lg font-bold text-primary-600">Rp {{ number_format($keranjang->harga, 0, ',', '.') }}</span>
                                            @if ($keranjang->produk->stok < 10)
                                                <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded">Stok tinggal {{ $keranjang->produk->stok }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <form action="{{ route('pelanggan.keranjang.destroy', $keranjang->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Kontrol Jumlah -->
                                <div class="flex items-center justify-between">
                                    <form action="{{ route('pelanggan.keranjang.update', $keranjang->id) }}" method="POST" class="flex items-center space-x-3">
                                        @csrf
                                        @method('PUT')
                                        <button type="button" onclick="decreaseQty(this)" 
                                            class="w-8 h-8 rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <input type="number" name="quantity" value="{{ $keranjang->quantity }}" min="1" max="{{ $keranjang->produk->stok }}"
                                            class="w-16 text-center border border-gray-300 rounded-lg py-1 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                            onchange="this.form.submit()">
                                        <button type="button" onclick="increaseQty(this, {{ $keranjang->produk->stok }})" 
                                            class="w-8 h-8 rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                        <span class="text-sm text-gray-500">Maks. {{ $keranjang->produk->stok }}</span>
                                    </form>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500 mb-1">Subtotal</p>
                                        <p class="text-lg font-bold text-gray-900">Rp {{ number_format($keranjang->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Status Kosong -->
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shopping-cart text-gray-400 text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Keranjang Belanja Kosong</h3>
                    <p class="text-gray-600 mb-6">Belum ada produk di keranjang Anda. Yuk, mulai belanja sekarang!</p>
                    <a href="{{ route('pelanggan.produk.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-shopping-bag mr-2"></i>Belanja Sekarang
                    </a>
                </div>
            @endif
        </div>

        <!-- Ringkasan Belanja -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Ringkasan Belanja</h3>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-gray-700">
                        <span>Total Harga ({{ $keranjangs->sum('quantity') }} barang)</span>
                        <span class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Biaya Pengiriman</span>
                        <span class="font-semibold text-gray-500">Dihitung saat checkout</span>
                    </div>
                </div>

                <!-- Total Pembayaran -->
                <div class="border-t border-gray-200 pt-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Subtotal</span>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-primary-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Checkout -->
                @if ($keranjangs->count() > 0)
                    <a href="{{ route('pelanggan.checkout.form') }}"
                        class="w-full bg-primary-600 text-white py-3 rounded-lg font-semibold hover:bg-primary-700 transition-all shadow-md hover:shadow-lg flex items-center justify-center">
                        <i class="fas fa-lock mr-2"></i>Lanjut ke Pembayaran
                    </a>
                @else
                    <button disabled
                        class="w-full bg-gray-300 text-gray-500 py-3 rounded-lg font-semibold cursor-not-allowed flex items-center justify-center">
                        <i class="fas fa-lock mr-2"></i>Lanjut ke Pembayaran
                    </button>
                @endif

                <div class="mt-4 text-center">
                    <a href="{{ route('pelanggan.produk.index') }}"
                        class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>Lanjut Belanja
                    </a>
                </div>

                <!-- Info Keamanan -->
                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-shield-alt text-green-600 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Transaksi Aman</p>
                            <p class="text-xs text-gray-600">Data Anda dilindungi dengan enkripsi SSL</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-undo text-primary-600 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Garansi Uang Kembali</p>
                            <p class="text-xs text-gray-600">Jika produk tidak sesuai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function decreaseQty(btn) {
            const form = btn.closest('form');
            const input = form.querySelector('input[name="quantity"]');
            const min = parseInt(input.min);
            const value = parseInt(input.value);

            if (value > min) {
                input.value = value - 1;
                form.submit();
            }
        }

        function increaseQty(btn, max) {
            const form = btn.closest('form');
            const input = form.querySelector('input[name="quantity"]');
            const value = parseInt(input.value);

            if (value < max) {
                input.value = value + 1;
                form.submit();
            }
        }
    </script>
@endsection