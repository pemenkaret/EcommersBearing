@extends('layout.pelanggan.app')

@section('title', $produk->nama . ' - Bearing Shop')

@section('content')
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-600 mb-6 flex-wrap">
        <a href="{{ route('pelanggan.home.index') }}" class="hover:text-primary-600 transition-colors">
            <i class="fas fa-home"></i>
        </a>
        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
        <a href="{{ route('pelanggan.produk.index') }}" class="hover:text-primary-600 transition-colors">Produk</a>
        @if ($produk->kategori)
            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            <a href="{{ route('pelanggan.produk.index', ['kategori_id' => $produk->kategori_id]) }}"
                class="hover:text-primary-600 transition-colors">{{ $produk->kategori->nama }}</a>
        @endif
        <i class="fas fa-chevron-right text-xs text-gray-400"></i>
        <span class="text-gray-900 font-medium">{{ Str::limit($produk->nama, 40) }}</span>
    </nav>

    {{-- Detail Produk --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 lg:p-8 mb-6">
        <div class="grid lg:grid-cols-12 gap-8">
            {{-- Galeri Gambar --}}
            <div class="lg:col-span-5">
                <div class="bg-gray-50 rounded-lg overflow-hidden mb-3 border border-gray-200">
                    <div class="aspect-square">
                        @if ($produk->images->first())
                            <img src="{{ asset('storage/' . $produk->images->first()->image_path) }}"
                                alt="{{ $produk->nama }}" id="mainImage"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-image text-gray-300 text-6xl"></i>
                            </div>
                        @endif
                    </div>
                </div>
                @if ($produk->images->count() > 1)
                    <div class="grid grid-cols-5 gap-2">
                        @foreach ($produk->images as $index => $image)
                            <button type="button"
                                onclick="changeImage('{{ asset('storage/' . $image->image_path) }}', this)"
                                class="aspect-square rounded-md overflow-hidden border-2 {{ $index === 0 ? 'border-primary-600' : 'border-gray-200' }} hover:border-primary-400 transition-colors">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                    alt="Thumbnail {{ $index + 1 }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Info Produk --}}
            <div class="lg:col-span-7">
                {{-- Brand & badges --}}
                <div class="flex items-center gap-2 mb-3 flex-wrap">
                    <span class="text-xs text-gray-500 uppercase tracking-wide font-medium">{{ $produk->merk->nama ?? '-' }}</span>
                    @if ($produk->is_featured)
                        <span class="bg-primary-50 text-primary-700 text-xs font-semibold px-2 py-0.5 rounded border border-primary-200">
                            Unggulan
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-3 leading-tight">{{ $produk->nama }}</h1>

                {{-- Meta info --}}
                <div class="flex items-center gap-4 text-sm text-gray-500 mb-6 pb-6 border-b border-gray-200 flex-wrap">
                    <span><i class="fas fa-tag mr-1.5"></i>SKU: <span class="font-mono text-gray-700">{{ $produk->sku }}</span></span>
                    <span class="text-gray-300">|</span>
                    <span><i class="fas fa-shopping-bag mr-1.5"></i>{{ $produk->sold_count }} terjual</span>
                    <span class="text-gray-300">|</span>
                    <span><i class="fas fa-eye mr-1.5"></i>{{ $produk->views ?? 0 }} dilihat</span>
                </div>

                {{-- Harga --}}
                <div class="mb-6">
                    @if ($produk->harga_diskon)
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-base text-gray-400 line-through">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                            <span class="bg-primary-50 text-primary-700 text-xs font-bold px-2 py-0.5 rounded border border-primary-200">
                                Hemat {{ round((($produk->harga - $produk->harga_diskon) / $produk->harga) * 100) }}%
                            </span>
                        </div>
                        <div class="text-3xl lg:text-4xl font-bold text-primary-600">
                            Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}
                        </div>
                    @else
                        <div class="text-3xl lg:text-4xl font-bold text-primary-600">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </div>
                    @endif
                </div>

                {{-- Info kunci ringkas --}}
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Stok</p>
                        <p class="font-semibold {{ $produk->stok > $produk->min_stok ? 'text-gray-900' : ($produk->stok > 0 ? 'text-primary-600' : 'text-gray-400') }}">
                            {{ $produk->stok > 0 ? $produk->stok . ' pcs' : 'Habis' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Berat</p>
                        <p class="font-semibold text-gray-900">{{ number_format($produk->berat) }} gram</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Kategori</p>
                        <p class="font-semibold text-gray-900 truncate">{{ $produk->kategori->nama ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Brand</p>
                        <p class="font-semibold text-gray-900 truncate">{{ $produk->merk->nama ?? '-' }}</p>
                    </div>
                </div>

                {{-- Aksi: tambah keranjang / beli sekarang --}}
                @auth
                    @if ($produk->stok > 0)
                        <form action="{{ route('pelanggan.keranjang.store') }}" method="POST" id="addToCartForm">
                            @csrf
                            <input type="hidden" name="produk_id" value="{{ $produk->id }}">

                            {{-- Quantity --}}
                            <div class="mb-5">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                                <div class="flex items-center gap-3">
                                    <div class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                        <button type="button" onclick="decreaseQty()"
                                            class="px-4 py-2 text-gray-600 hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <input type="number" name="quantity" id="quantity" value="1" min="1"
                                            max="{{ $produk->stok }}"
                                            class="w-16 text-center border-x border-gray-300 py-2 focus:outline-none">
                                        <button type="button" onclick="increaseQty()"
                                            class="px-4 py-2 text-gray-600 hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                    <span class="text-sm text-gray-500">Tersedia {{ $produk->stok }} pcs</span>
                                </div>
                            </div>

                            {{-- Tombol aksi --}}
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <button type="submit"
                                    class="bg-white border-2 border-primary-600 text-primary-600 py-3 rounded-lg font-semibold hover:bg-primary-50 transition-colors flex items-center justify-center">
                                    <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                                </button>
                                <button type="button" onclick="buyNow()"
                                    class="bg-primary-600 text-white py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors flex items-center justify-center">
                                    <i class="fas fa-bolt mr-2"></i>Beli Sekarang
                                </button>
                            </div>
                        </form>

                        <script>
                            function buyNow() {
                                const quantity = document.getElementById('quantity').value;
                                window.location.href = "{{ route('pelanggan.buy-now.form', $produk->id) }}?quantity=" + quantity;
                            }
                        </script>
                    @else
                        <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                            <i class="fas fa-times-circle text-gray-400 text-2xl mb-2"></i>
                            <p class="text-gray-700 font-semibold">Produk Tidak Tersedia</p>
                            <p class="text-gray-500 text-sm">Stok habis sementara waktu</p>
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="block w-full bg-primary-600 text-white py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors text-center mb-4">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login untuk Membeli
                    </a>
                @endauth

                {{-- Aksi sekunder --}}
                <div class="flex items-center gap-4 pt-4 border-t border-gray-200 text-sm">
                    <button onclick="shareProduct()"
                        class="flex items-center gap-2 text-gray-600 hover:text-primary-600 transition-colors">
                        <i class="fas fa-share-alt"></i>
                        <span>Bagikan</span>
                    </button>
                    <span class="text-gray-300">|</span>
                    <span class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-shield-alt"></i>
                        <span>Garansi Original</span>
                    </span>
                    <span class="text-gray-300">|</span>
                    <span class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-truck"></i>
                        <span>Pengiriman Cepat</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs: Deskripsi & Spesifikasi --}}
    <div class="bg-white rounded-xl border border-gray-200 mb-6 overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex">
                <button onclick="showTab('description')"
                    class="tab-button py-4 px-6 border-b-2 font-semibold text-sm transition-colors border-primary-600 text-primary-600"
                    data-tab="description">
                    <i class="fas fa-align-left mr-2"></i>Deskripsi Produk
                </button>
                <button onclick="showTab('specifications')"
                    class="tab-button py-4 px-6 border-b-2 font-semibold text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700"
                    data-tab="specifications">
                    <i class="fas fa-cogs mr-2"></i>Spesifikasi Teknis
                </button>
            </nav>
        </div>

        <div class="p-6 lg:p-8">
            {{-- Tab Deskripsi --}}
            <div id="description-content" class="tab-content">
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    @if (!empty($produk->deskripsi))
                        {!! $produk->deskripsi !!}
                    @else
                        <p class="text-gray-400 italic">Tidak ada deskripsi produk.</p>
                    @endif
                </div>
            </div>

            {{-- Tab Spesifikasi --}}
            <div id="specifications-content" class="tab-content hidden">
                <div class="grid md:grid-cols-2 gap-x-8 gap-y-1">
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-500">SKU</span>
                        <span class="font-medium text-gray-900 font-mono">{{ $produk->sku }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-500">Kategori</span>
                        <span class="font-medium text-gray-900">{{ $produk->kategori->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-500">Brand</span>
                        <span class="font-medium text-gray-900">{{ $produk->merk->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-500">Berat</span>
                        <span class="font-medium text-gray-900">{{ number_format($produk->berat) }} gram</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-500">Stok</span>
                        <span class="font-medium text-gray-900">{{ $produk->stok }} pcs</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-500">Terjual</span>
                        <span class="font-medium text-gray-900">{{ $produk->sold_count }} pcs</span>
                    </div>
                    @if ($produk->inner_diameter)
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Inner Diameter</span>
                            <span class="font-medium text-gray-900">{{ $produk->inner_diameter }} mm</span>
                        </div>
                    @endif
                    @if ($produk->outer_diameter)
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Outer Diameter</span>
                            <span class="font-medium text-gray-900">{{ $produk->outer_diameter }} mm</span>
                        </div>
                    @endif
                    @if ($produk->width)
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Width</span>
                            <span class="font-medium text-gray-900">{{ $produk->width }} mm</span>
                        </div>
                    @endif
                    @if ($produk->material)
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Material</span>
                            <span class="font-medium text-gray-900">{{ $produk->material }}</span>
                        </div>
                    @endif
                    @if ($produk->seal_type)
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Seal Type</span>
                            <span class="font-medium text-gray-900">{{ $produk->seal_type }}</span>
                        </div>
                    @endif
                    @if ($produk->cage_type)
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-500">Cage Type</span>
                            <span class="font-medium text-gray-900">{{ $produk->cage_type }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Produk Terkait --}}
    @if ($produkTerkait->count() > 0)
        <div class="bg-white rounded-xl border border-gray-200 p-6 lg:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <span class="block w-1 h-8 bg-primary-600 rounded-full"></span>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Produk Terkait</h2>
                        <p class="text-gray-500 text-sm">Produk lain dari kategori yang sama</p>
                    </div>
                </div>
                <a href="{{ route('pelanggan.produk.index', ['kategori_id' => $produk->kategori_id]) }}"
                    class="text-primary-600 font-medium text-sm flex items-center">
                    Lihat Semua <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($produkTerkait as $related)
                    <a href="{{ route('pelanggan.produk.show', $related->slug) }}"
                        class="bg-gray-50 rounded-lg border border-gray-200 hover:border-primary-300 transition-colors overflow-hidden flex flex-col">
                        <div class="relative">
                            @if ($related->images->first())
                                <img src="{{ asset('storage/' . $related->images->first()->image_path) }}"
                                    alt="{{ $related->nama }}"
                                    class="w-full aspect-square object-cover bg-white">
                            @else
                                <div class="w-full aspect-square bg-white flex items-center justify-center">
                                    <i class="fas fa-image text-gray-300 text-4xl"></i>
                                </div>
                            @endif
                            @if ($related->harga_diskon)
                                <div class="absolute top-2 right-2 bg-primary-600 text-white text-xs px-2 py-0.5 rounded font-semibold">
                                    -{{ round((($related->harga - $related->harga_diskon) / $related->harga) * 100) }}%
                                </div>
                            @endif
                        </div>
                        <div class="p-3 flex flex-col flex-1">
                            <p class="text-xs text-gray-400 mb-1 uppercase tracking-wide">{{ $related->merk->nama ?? '-' }}</p>
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 text-sm leading-snug min-h-10">{{ $related->nama }}</h3>
                            <div class="mt-auto">
                                @if ($related->harga_diskon)
                                    <p class="text-xs text-gray-400 line-through">Rp {{ number_format($related->harga, 0, ',', '.') }}</p>
                                    <p class="text-base font-bold text-primary-600 leading-tight">Rp {{ number_format($related->harga_diskon, 0, ',', '.') }}</p>
                                @else
                                    <p class="text-base font-bold text-primary-600 leading-tight">Rp {{ number_format($related->harga, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <script>
        // Ganti gambar utama saat klik thumbnail
        function changeImage(src, element) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.grid.grid-cols-5 > button').forEach(el => {
                el.classList.remove('border-primary-600');
                el.classList.add('border-gray-200');
            });
            element.classList.remove('border-gray-200');
            element.classList.add('border-primary-600');
        }

        // Tab switcher
        function showTab(tabName) {
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('border-primary-600', 'text-primary-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
            activeBtn.classList.remove('border-transparent', 'text-gray-500');
            activeBtn.classList.add('border-primary-600', 'text-primary-600');
            document.getElementById(`${tabName}-content`).classList.remove('hidden');
        }

        // Quantity stepper
        function increaseQty() {
            const qty = document.getElementById('quantity');
            const max = parseInt(qty.max);
            const value = parseInt(qty.value) || 1;
            if (value < max) qty.value = value + 1;
        }
        function decreaseQty() {
            const qty = document.getElementById('quantity');
            const min = parseInt(qty.min);
            const value = parseInt(qty.value) || 1;
            if (value > min) qty.value = value - 1;
        }

        // Share produk via Web Share API atau fallback clipboard
        function shareProduct() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $produk->nama }}',
                    text: 'Lihat produk {{ $produk->nama }} di Bearing Shop',
                    url: window.location.href,
                });
            } else {
                navigator.clipboard.writeText(window.location.href);
                alert('Link produk berhasil disalin!');
            }
        }
    </script>
@endsection
