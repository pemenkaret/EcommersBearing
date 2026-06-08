@extends('layout.pelanggan.app')

@section('title', 'Home - Bearing Shop')

@section('content')

    <div class="relative rounded-2xl shadow-xl mb-8 bg-linear-to-r from-primary-600 via-primary-700 to-primary-900">

        <!-- Pembungkus khusus SVG agar overflow-nya tidak mengganggu dropdown -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <svg class="w-full h-full opacity-[0.18]" xmlns="http://www.w3.org/2000/svg">
                <path d="M-50 100 C 200 0, 600 220, 1100 60" stroke="white" stroke-width="2.5" fill="none"
                    stroke-linecap="round" />
                <path d="M-50 340 C 200 240, 600 380, 1100 300" stroke="white" stroke-width="3" fill="none"
                    stroke-linecap="round" />
                <path d="M-50 420 C 250 310, 650 450, 1100 360" stroke="white" stroke-width="2.5" fill="none"
                    stroke-linecap="round" />
                <path d="M-50 500 C 240 390, 640 520, 1100 440" stroke="white" stroke-width="3" fill="none"
                    stroke-linecap="round" />
            </svg>
        </div>

        <div class="px-8 py-12 lg:px-12 lg:py-16 relative z-0">
            <div class="grid lg:grid-cols-2 gap-8 items-center">

                <div class="text-white">
                    <h1 class="text-4xl lg:text-5xl font-bold mb-4">
                        Bearing Berkualitas <br>untuk Industri Anda
                    </h1>
                    <p class="text-primary-100 text-lg mb-6">
                        Temukan berbagai jenis bearing dari brand ternama dengan harga kompetitif.
                        Pengiriman cepat ke seluruh Indonesia.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('pelanggan.produk.index') }}"
                            class="bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-primary-50 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-shopping-bag mr-2"></i>Belanja Sekarang
                        </a>

                        <a href="#categories"
                            class="bg-primary-500 bg-opacity-30 backdrop-blur-sm text-white px-8 py-3 rounded-lg font-semibold hover:bg-opacity-40 transition-all border-2 border-white border-opacity-30">
                            <i class="fas fa-th-large mr-2"></i>Lihat Kategori
                        </a>
                    </div>
                </div>

                <div class="hidden lg:block">
                    <div class="relative">
                        <div class="absolute inset-0 bg-white opacity-10 rounded-full blur-3xl"></div>
                        <img src="{{ asset('assets/semua bearing.jpg') }}" alt="Bearing Products"
                            class="relative rounded-2xl shadow-xl">
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Bagian Fitur -->
    <div class="grid md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all">
            <div class="w-14 h-14 bg-primary-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-shipping-fast text-primary-600 text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-900 mb-2">Pengiriman Cepat</h3>
            <p class="text-gray-600 text-sm">Gratis ongkir untuk pembelian di atas 1 juta</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all">
            <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-shield-alt text-green-600 text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-900 mb-2">Produk Original</h3>
            <p class="text-gray-600 text-sm">100% bearing original bergaransi resmi</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all">
            <div class="w-14 h-14 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-headset text-orange-600 text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-900 mb-2">Support 24/7</h3>
            <p class="text-gray-600 text-sm">Tim support siap membantu kapan saja</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all">
            <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                <i class="fas fa-tag text-purple-600 text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-900 mb-2">Harga Terbaik</h3>
            <p class="text-gray-600 text-sm">Dapatkan penawaran harga terbaik</p>
        </div>
    </div>

    <!-- Bagian Kategori -->
    <div id="categories" class="mb-8 bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <span class="block w-1 h-8 bg-primary-600 rounded-full"></span>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Kategori Bearing</h2>
                    <p class="text-gray-500 text-sm">Pilih kategori sesuai kebutuhan Anda</p>
                </div>
            </div>
        </div>

        <!-- Mobile: Horizontal Scroll -->
        <div class="md:hidden overflow-x-auto pb-2 -mx-2 px-2 scrollbar-hide">
            <div class="flex gap-3" style="width: max-content;">
                @forelse($kategoris as $kategori)
                    <a href="{{ route('pelanggan.produk.index', ['kategori_id' => $kategori->id]) }}"
                        class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-primary-300 transition-colors flex-shrink-0 w-28">
                        @if(!empty(trim($kategori->icon)) && str_contains($kategori->icon, '/'))
                            <img src="{{ asset('storage/' . ltrim($kategori->icon, '/')) }}" alt="{{ $kategori->nama }}" class="w-12 h-12 rounded-lg object-cover mb-3 mx-auto">
                        @elseif(!empty(trim($kategori->icon)))
                            <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center mb-3 mx-auto">
                                <i class="{{ str_starts_with($kategori->icon, 'bi-') || str_starts_with($kategori->icon, 'fas ') || str_starts_with($kategori->icon, 'fab ') || str_starts_with($kategori->icon, 'far ') ? $kategori->icon : 'fas ' . $kategori->icon }} text-primary-600 text-xl"></i>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center mb-3 mx-auto">
                                <i class="fas fa-layer-group text-primary-600 text-xl"></i>
                            </div>
                        @endif
                        <h3 class="font-semibold text-gray-900 text-center mb-0.5 text-xs line-clamp-2">{{ $kategori->nama }}</h3>
                        <p class="text-gray-400 text-[10px] text-center">{{ $kategori->produks_count ?? $kategori->produks->count() }} produk</p>
                    </a>
                @empty
                    <div class="text-center py-8 text-gray-500 w-full">
                        <i class="fas fa-box-open text-4xl mb-2"></i>
                        <p>Belum ada kategori</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Desktop: Grid -->
        <div class="hidden md:grid md:grid-cols-3 lg:grid-cols-6 gap-3">
            @forelse($kategoris as $kategori)
                <a href="{{ route('pelanggan.produk.index', ['kategori_id' => $kategori->id]) }}"
                    class="bg-gray-50 rounded-lg p-5 border border-gray-200 hover:border-primary-300 transition-colors">
                    @if(!empty(trim($kategori->icon)) && str_contains($kategori->icon, '/'))
                        <img src="{{ asset('storage/' . ltrim($kategori->icon, '/')) }}" alt="{{ $kategori->nama }}" class="w-14 h-14 rounded-lg object-cover mb-3 mx-auto">
                    @elseif(!empty(trim($kategori->icon)))
                        <div class="w-14 h-14 bg-primary-50 rounded-lg flex items-center justify-center mb-3 mx-auto">
                            <i class="{{ str_starts_with($kategori->icon, 'bi-') || str_starts_with($kategori->icon, 'fas ') || str_starts_with($kategori->icon, 'fab ') || str_starts_with($kategori->icon, 'far ') ? $kategori->icon : 'fas ' . $kategori->icon }} text-primary-600 text-2xl"></i>
                        </div>
                    @else
                        <div class="w-14 h-14 bg-primary-50 rounded-lg flex items-center justify-center mb-3 mx-auto">
                            <i class="fas fa-layer-group text-primary-600 text-2xl"></i>
                        </div>
                    @endif
                    <h3 class="font-semibold text-gray-900 text-center mb-1 text-sm line-clamp-2 min-h-10">{{ $kategori->nama }}</h3>
                    <p class="text-gray-400 text-xs text-center">{{ $kategori->produks_count ?? $kategori->produks->count() }} produk</p>
                </a>
            @empty
                <div class="col-span-6 text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2"></i>
                    <p>Belum ada kategori</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Bagian Brand Premium -->
    @if($merksPremium->count() > 0)
        <div class="mb-8 bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <span class="block w-1 h-8 bg-primary-600 rounded-full"></span>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Brand Premium</h2>
                        <p class="text-gray-500 text-sm">Bearing dari brand ternama dunia</p>
                    </div>
                </div>
                <a href="{{ route('pelanggan.produk.index') }}"
                    class="text-primary-600 font-medium text-sm flex items-center">
                    Lihat Semua <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>

            <!-- Mobile: Horizontal Scroll -->
            <div class="md:hidden overflow-x-auto pb-2 -mx-2 px-2 scrollbar-hide">
                <div class="flex gap-3" style="width: max-content;">
                    @foreach($merksPremium as $merk)
                        <a href="{{ route('pelanggan.produk.index', ['merk_id' => $merk->id]) }}"
                            class="relative rounded-lg p-4 border border-gray-200 hover:border-primary-300 transition-colors text-center flex-shrink-0 w-28 flex flex-col justify-center min-h-[88px] overflow-hidden {{ empty(trim($merk->logo)) ? 'bg-gray-50/50' : 'bg-transparent' }}"
                            @if(!empty(trim($merk->logo)))
                                style="background-image: url('{{ asset('storage/' . ltrim($merk->logo, '/')) }}'); background-size: contain; background-position: center; background-repeat: no-repeat;"
                            @endif
                        >
                            @if(!empty(trim($merk->logo)))
                                <div class="absolute inset-0 bg-white/70"></div>
                                <h3 class="relative z-10 font-bold text-gray-900 text-sm line-clamp-1">{{ $merk->nama }}</h3>
                            @else
                                <span class="text-sm font-bold text-gray-700">{{ $merk->nama }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Desktop: Grid -->
            <div class="hidden md:grid md:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach($merksPremium as $merk)
                    <a href="{{ route('pelanggan.produk.index', ['merk_id' => $merk->id]) }}"
                        class="relative bg-transparent rounded-lg p-5 border border-gray-200 hover:border-primary-300 transition-colors text-center flex flex-col justify-center min-h-[120px] overflow-hidden"
                        @if(!empty(trim($merk->logo)))
                            style="background-image: url('{{ asset('storage/' . ltrim($merk->logo, '/')) }}'); background-size: contain; background-position: center; background-repeat: no-repeat;"
                        @endif
                    >
                        @if(!empty(trim($merk->logo)))
                            <div class="absolute inset-0 bg-white/70"></div>
                            <h3 class="relative z-10 font-bold text-gray-900 text-base">{{ $merk->nama }}</h3>
                        @else
                            <span class="text-xl font-bold text-gray-700">{{ $merk->nama }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Bagian Produk Unggulan -->
    <div class="mb-8 bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <span class="block w-1 h-8 bg-primary-600 rounded-full"></span>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Produk Unggulan</h2>
                    <p class="text-gray-500 text-sm">Produk bearing yang paling banyak diminati</p>
                </div>
            </div>
            <a href="{{ route('pelanggan.produk.index', ['sort' => 'popular']) }}"
                class="text-primary-600 font-medium text-sm flex items-center">
                Lihat Semua <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($featuredProducts as $produk)
                <div class="bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-300 transition-colors overflow-hidden flex flex-col">
                    <div class="relative">
                        @if($produk->images->first())
                            <img src="{{ asset('storage/' . $produk->images->first()->image_path) }}"
                                alt="{{ $produk->nama }}"
                                class="w-full aspect-square object-cover bg-white">
                        @else
                            <div class="w-full aspect-square bg-white flex items-center justify-center">
                                <i class="fas fa-image text-gray-300 text-4xl"></i>
                            </div>
                        @endif
                        @if($produk->harga_diskon)
                            <div class="absolute top-2 right-2 bg-primary-600 text-white text-xs px-2 py-0.5 rounded font-semibold">
                                -{{ round((($produk->harga - $produk->harga_diskon) / $produk->harga) * 100) }}%
                            </div>
                        @endif
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <p class="text-xs text-gray-400 mb-1 uppercase tracking-wide">{{ $produk->merk->nama ?? '-' }}</p>
                        <h3 class="font-semibold text-gray-900 mb-3 line-clamp-2 min-h-12 leading-snug">{{ $produk->nama }}</h3>
                        <div class="mb-3">
                            @if($produk->harga_diskon)
                                <p class="text-lg font-bold text-primary-600 leading-none">Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400 line-through mt-0.5">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            @else
                                <p class="text-lg font-bold text-primary-600 leading-none">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            @endif
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4 mt-auto">
                            <span>Stok: {{ $produk->stok }}</span>
                            <span>{{ $produk->sold_count ?? 0 }} terjual</span>
                        </div>
                        <a href="{{ route('pelanggan.produk.show', $produk->slug) }}"
                            class="block w-full bg-primary-600 text-white text-center py-2 rounded-lg font-semibold text-sm hover:bg-primary-700 transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2"></i>
                    <p>Belum ada produk unggulan</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Bagian Produk Terbaru -->
    <div class="mb-8 bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <span class="block w-1 h-8 bg-primary-600 rounded-full"></span>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Produk Terbaru</h2>
                    <p class="text-gray-500 text-sm">Produk bearing terbaru di katalog kami</p>
                </div>
            </div>
            <a href="{{ route('pelanggan.produk.index', ['sort' => 'latest']) }}"
                class="text-primary-600 font-medium text-sm flex items-center">
                Lihat Semua <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($produkTerbaru as $produk)
                <div class="bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-300 transition-colors overflow-hidden flex flex-col">
                    <div class="relative">
                        @if($produk->images->first())
                            <img src="{{ asset('storage/' . $produk->images->first()->image_path) }}"
                                alt="{{ $produk->nama }}"
                                class="w-full aspect-square object-cover bg-white">
                        @else
                            <div class="w-full aspect-square bg-white flex items-center justify-center">
                                <i class="fas fa-image text-gray-300 text-4xl"></i>
                            </div>
                        @endif
                        <div class="absolute top-2 left-2 bg-white border border-gray-200 text-gray-700 text-xs px-2 py-0.5 rounded font-medium">
                            Baru
                        </div>
                        @if($produk->harga_diskon)
                            <div class="absolute top-2 right-2 bg-primary-600 text-white text-xs px-2 py-0.5 rounded font-semibold">
                                -{{ round((($produk->harga - $produk->harga_diskon) / $produk->harga) * 100) }}%
                            </div>
                        @endif
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <p class="text-xs text-gray-400 mb-1 uppercase tracking-wide">{{ $produk->merk->nama ?? '-' }}</p>
                        <h3 class="font-semibold text-gray-900 mb-3 line-clamp-2 min-h-12 leading-snug">{{ $produk->nama }}</h3>
                        <div class="mb-3">
                            @if($produk->harga_diskon)
                                <p class="text-lg font-bold text-primary-600 leading-none">Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400 line-through mt-0.5">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            @else
                                <p class="text-lg font-bold text-primary-600 leading-none">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            @endif
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4 mt-auto">
                            <span>Stok: {{ $produk->stok }}</span>
                            <span class="text-primary-600">Tersedia</span>
                        </div>
                        <a href="{{ route('pelanggan.produk.show', $produk->slug) }}"
                            class="block w-full bg-primary-600 text-white text-center py-2 rounded-lg font-semibold text-sm hover:bg-primary-700 transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-2"></i>
                    <p>Belum ada produk terbaru</p>
                </div>
            @endforelse
        </div>
    </div>

@endsection