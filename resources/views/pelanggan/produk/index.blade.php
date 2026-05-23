@extends('layout.pelanggan.app')

@section('title', 'Katalog Produk - Bearing Shop')

@section('content')
    <!-- Header Halaman -->
    <div class="bg-linear-to-r from-primary-600 to-primary-800 rounded-2xl shadow-xl p-8 mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Katalog Produk Bearing</h1>
        <p class="text-primary-100">Temukan bearing berkualitas tinggi dari berbagai brand ternama</p>
    </div>

    <!-- Mobile Filter Button -->
    <div class="lg:hidden mb-4">
        <button type="button" id="openFilterModal" 
            class="w-full bg-white border border-gray-300 text-gray-700 py-3 px-4 rounded-xl font-medium hover:bg-gray-50 transition-all flex items-center justify-center shadow-md">
            <i class="fas fa-filter mr-2 text-primary-600"></i>
            Filter Produk
            @if(request('search') || request('kategori_id') || request('merk_id') || request('sort'))
                <span class="ml-2 bg-primary-600 text-white text-xs px-2 py-0.5 rounded-full">Aktif</span>
            @endif
        </button>
    </div>

    <!-- Mobile Filter Modal -->
    <div id="filterModal" class="fixed inset-0 z-50 hidden lg:hidden">
        <!-- Backdrop -->
        <div id="filterModalBackdrop" class="absolute inset-0 bg-black/60 transition-opacity"></div>
        
        <!-- Modal Content -->
        <div id="filterModalContent" class="absolute inset-x-0 bottom-0 bg-white rounded-t-3xl max-h-[85vh] overflow-y-auto transform translate-y-full transition-transform duration-300">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between rounded-t-3xl">
                <h3 class="font-bold text-gray-900 text-lg flex items-center">
                    <i class="fas fa-filter mr-2 text-primary-600"></i>Filter Produk
                </h3>
                <button type="button" id="closeFilterModal" class="text-gray-500 hover:text-gray-700 p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <form action="{{ route('pelanggan.produk.index') }}" method="GET" id="filterFormMobile" class="p-6">
                <!-- Pencarian -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
                    </div>
                </div>

                <!-- Filter Kategori -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Kategori</label>
                    <select name="kategori_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Brand -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Brand</label>
                    <select name="merk_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500">
                        <option value="">Semua Brand</option>
                        @foreach ($merks as $merk)
                            <option value="{{ $merk->id }}" {{ request('merk_id') == $merk->id ? 'selected' : '' }}>
                                {{ $merk->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Urutkan -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Urutkan</label>
                    <select name="sort" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-500">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                    </select>
                </div>

                <!-- Tombol Filter -->
                <div class="space-y-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="w-full bg-primary-600 text-white py-3 rounded-xl font-semibold hover:bg-primary-700 transition-all">
                        <i class="fas fa-check mr-2"></i>Terapkan Filter
                    </button>
                    <a href="{{ route('pelanggan.produk.index') }}" 
                        class="block w-full bg-gray-100 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-200 transition-all text-center">
                        <i class="fas fa-redo mr-2"></i>Reset Filter
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="grid lg:grid-cols-4 gap-6">
        <!-- Sidebar Filter (Desktop Only) -->
        <div class="hidden lg:block lg:col-span-1">
            <form action="{{ route('pelanggan.produk.index') }}" method="GET" id="filterForm">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-filter mr-2 text-primary-600"></i>Filter Produk
                    </h3>

                    <!-- Pencarian -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Filter Kategori -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Kategori</label>
                        <select name="kategori_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Brand -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Brand</label>
                        <select name="merk_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                            <option value="">Semua Brand</option>
                            @foreach ($merks as $merk)
                                <option value="{{ $merk->id }}" {{ request('merk_id') == $merk->id ? 'selected' : '' }}>
                                    {{ $merk->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Urutkan -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Urutkan</label>
                        <select name="sort" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary-500">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                        </select>
                    </div>

                    <!-- Tombol Filter -->
                    <div class="space-y-2">
                        <button type="submit" class="w-full bg-primary-600 text-white py-2 rounded-lg font-medium hover:bg-primary-700 transition-all">
                            <i class="fas fa-filter mr-2"></i>Terapkan Filter
                        </button>
                        <a href="{{ route('pelanggan.produk.index') }}" 
                            class="block w-full bg-gray-100 text-gray-700 py-2 rounded-lg font-medium hover:bg-gray-200 transition-all text-center">
                            <i class="fas fa-redo mr-2"></i>Reset Filter
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            <!-- Toolbar -->
            <div class="bg-white rounded-xl shadow-md p-4 mb-6 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-gray-600 text-sm">Menampilkan</span>
                    <span class="font-semibold text-primary-600">{{ $produks->total() }}</span>
                    <span class="text-gray-600 text-sm">produk</span>
                </div>
                <div class="text-sm text-gray-500">
                    Halaman {{ $produks->currentPage() }} dari {{ $produks->lastPage() }}
                </div>
            </div>

            <!-- Products Grid -->
            @if ($produks->count() > 0)
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
                    @foreach ($produks as $produk)
                        <div class="bg-white rounded-xl border border-gray-200 hover:border-primary-300 transition-colors overflow-hidden flex flex-col">
                            <div class="relative bg-gray-100">
                                @if ($produk->images->first())
                                    <img src="{{ asset('storage/' . $produk->images->first()->image_path) }}" alt="{{ $produk->nama }}"
                                        class="w-full aspect-square object-cover">
                                @else
                                    <div class="w-full aspect-square bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                                    </div>
                                @endif

                                @if ($produk->is_featured)
                                    <div class="absolute top-3 left-3">
                                        <span class="bg-primary-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                            Unggulan
                                        </span>
                                    </div>
                                @endif

                                @if ($produk->harga_diskon)
                                    <div class="absolute top-3 right-3">
                                        <span class="bg-primary-600 text-white text-xs font-bold px-2 py-1 rounded">
                                            -{{ round((($produk->harga - $produk->harga_diskon) / $produk->harga) * 100) }}%
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs text-gray-500 font-medium uppercase tracking-wide">{{ $produk->merk->nama ?? '-' }}</span>
                                    <span class="text-xs {{ $produk->stok > $produk->min_stok ? 'text-primary-600' : 'text-gray-500' }} font-medium">
                                        Stok: {{ $produk->stok }}
                                    </span>
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 min-h-12 leading-snug">{{ $produk->nama }}</h3>
                                <div class="flex items-center gap-2 mb-3 text-xs">
                                    <span class="text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                        {{ $produk->kategori->nama ?? '-' }}
                                    </span>
                                    <span class="text-gray-500">{{ $produk->sold_count }} terjual</span>
                                </div>
                                <div class="mb-4">
                                    @if ($produk->harga_diskon)
                                        <div class="text-xs text-gray-400 line-through">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                        <div class="text-xl font-bold text-primary-600 leading-tight">Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}</div>
                                    @else
                                        <div class="text-xl font-bold text-primary-600 leading-tight">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                    @endif
                                </div>
                                {{-- Tombol selalu di paling bawah card agar align rata antar produk --}}
                                <div class="mt-auto">
                                    @auth
                                        <form action="{{ route('pelanggan.keranjang.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit"
                                                class="w-full cursor-pointer bg-primary-600 text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-primary-700 transition-colors flex items-center justify-center">
                                                <i class="fas fa-shopping-cart mr-2"></i>Tambah ke Keranjang
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}"
                                            class="w-full bg-primary-600 text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-primary-700 transition-colors flex items-center justify-center">
                                            <i class="fas fa-sign-in-alt mr-2"></i>Login untuk Beli
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $produks->withQueryString()->links() }}
                </div>
            @else
                <!-- Status Kosong -->
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-600 mb-4">Coba ubah filter pencarian Anda</p>
                    <a href="{{ route('pelanggan.produk.index') }}"
                        class="bg-primary-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-primary-700 transition-all inline-block">
                        Reset Filter
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterModal = document.getElementById('filterModal');
        const filterModalBackdrop = document.getElementById('filterModalBackdrop');
        const filterModalContent = document.getElementById('filterModalContent');
        const openFilterModal = document.getElementById('openFilterModal');
        const closeFilterModal = document.getElementById('closeFilterModal');

        // Open modal
        openFilterModal.addEventListener('click', function() {
            filterModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Trigger animation
            setTimeout(() => {
                filterModalBackdrop.classList.add('opacity-100');
                filterModalContent.classList.remove('translate-y-full');
            }, 10);
        });

        // Close modal function
        function closeModal() {
            filterModalBackdrop.classList.remove('opacity-100');
            filterModalContent.classList.add('translate-y-full');
            
            setTimeout(() => {
                filterModal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        // Close modal on button click
        closeFilterModal.addEventListener('click', closeModal);

        // Close modal on backdrop click
        filterModalBackdrop.addEventListener('click', closeModal);

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !filterModal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>
@endpush