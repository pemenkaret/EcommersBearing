@extends('layout.admin.app')

@section('title', 'Edit Produk - Admin')

@section('content')
    <!-- Header -->
    <div class="bg-linear-to-r from-primary-700 to-primary-900 rounded-2xl shadow-xl p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.produk.index') }}"
                    class="inline-flex items-center text-white hover:text-white mb-4 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h1 class="text-3xl font-bold text-white mb-2">Edit Produk</h1>
                <p class="text-primary-100">{{ $produk->nama }}</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-edit text-primary-900 text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <strong>Terjadi kesalahan:</strong>
            </div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid lg:grid-cols-4 gap-6">
        <!-- Sidebar Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="text-center mb-4">
                    @if ($produk->images->first())
                        <img src="{{ asset('storage/' . $produk->images->first()->image_path) }}" 
                            alt="{{ $produk->nama }}" class="w-full h-40 object-cover rounded-lg mb-4">
                    @else
                        <div class="w-full h-40 bg-gray-100 rounded-lg mb-4 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                    <p class="text-sm text-gray-500">SKU: {{ $produk->sku }}</p>
                </div>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Stok:</span>
                        <span class="font-medium">{{ $produk->stok }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dibuat:</span>
                        <span class="font-medium">{{ $produk->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Diupdate:</span>
                        <span class="font-medium">{{ $produk->updated_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Existing Images -->
                @if ($produk->images->count() > 0)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm font-medium text-gray-700 mb-2">Gambar Produk:</p>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach ($produk->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                    alt="Gambar" class="w-full h-16 object-cover rounded">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Form -->
        <div class="lg:col-span-3">
            <form id="produk-edit-form" action="{{ route('admin.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Informasi Dasar -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-info-circle mr-2 text-primary-600"></i>Informasi Dasar
                    </h2>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Produk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" value="{{ old('nama', $produk->nama) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('nama') border-red-500 @enderror">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="kategori_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('kategori_id') border-red-500 @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $produk->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Merk <span class="text-red-500">*</span>
                            </label>
                            <select name="merk_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('merk_id') border-red-500 @enderror">
                                <option value="">Pilih Merk</option>
                                @foreach ($merks as $merk)
                                    <option value="{{ $merk->id }}" {{ old('merk_id', $produk->merk_id) == $merk->id ? 'selected' : '' }}>
                                        {{ $merk->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('merk_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                SKU
                            </label>
                            <input type="text" name="sku" value="{{ old('sku', $produk->sku) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('sku') border-red-500 @enderror">
                            @error('sku')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="deskripsi" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Harga & Stok -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-tag mr-2 text-primary-600"></i>Harga & Stok
                    </h2>

                    <div class="grid md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Harga <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                <input type="text" name="harga" id="harga" value="{{ old('harga', number_format($produk->harga, 0, ',', '.')) }}" required
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('harga') border-red-500 @enderror"
                                    oninput="formatRupiah(this)">
                            </div>
                            @error('harga')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Harga jual normal produk</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Harga Diskon
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                <input type="text" name="harga_diskon" id="harga_diskon" value="{{ old('harga_diskon', $produk->harga_diskon ? number_format($produk->harga_diskon, 0, ',', '.') : '') }}"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('harga_diskon') border-red-500 @enderror"
                                    oninput="formatRupiah(this)">
                            </div>
                            @error('harga_diskon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Kosongkan jika tidak ada diskon</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Stok <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}" required min="0"
                                    inputmode="numeric"
                                    oninput="stripLeadingZeros(this)"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('stok') border-red-500 @enderror">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">pcs</span>
                            </div>
                            @error('stok')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Jumlah stok tersedia</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Min Stok <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="min_stok" value="{{ old('min_stok', $produk->min_stok) }}" required min="0"
                                    inputmode="numeric"
                                    oninput="stripLeadingZeros(this)"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('min_stok') border-red-500 @enderror">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">pcs</span>
                            </div>
                            @error('min_stok')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Batas peringatan stok rendah</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Berat <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" step="0.01" name="berat" value="{{ old('berat', $produk->berat) }}" required min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('berat') border-red-500 @enderror"
                                    placeholder="0">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">gram</span>
                            </div>
                            @error('berat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Untuk kalkulasi ongkir</p>
                        </div>
                    </div>
                </div>

                <script>
                    function formatRupiah(input) {
                        let value = input.value.replace(/[^\d]/g, '');
                        if (value === '') {
                            input.value = '';
                            return;
                        }
                        input.value = new Intl.NumberFormat('id-ID').format(value);
                    }

                    // Hapus leading zero pada input integer; "007" -> "7", "0" tetap "0", "" tetap ""
                    function stripLeadingZeros(input) {
                        const cleaned = input.value.replace(/[^\d]/g, '');
                        if (cleaned === '') {
                            input.value = '';
                            return;
                        }
                        input.value = cleaned.replace(/^0+(?=\d)/, '');
                    }
                    
                    // Before form submit, convert formatted price back to plain digits
                    const produkEditForm = document.getElementById('produk-edit-form');
                    if (produkEditForm) {
                        produkEditForm.addEventListener('submit', function() {
                            const harga = document.getElementById('harga');
                            const hargaDiskon = document.getElementById('harga_diskon');
                            if (harga) harga.value = harga.value.replace(/[^\d]/g, '');
                            if (hargaDiskon) hargaDiskon.value = hargaDiskon.value.replace(/[^\d]/g, '');
                        });
                    }
                </script>

                <!-- Spesifikasi Teknis -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-cog mr-2 text-primary-600"></i>Spesifikasi Teknis
                    </h2>

                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Inner Diameter (mm)
                            </label>
                            <input type="number" step="0.01" name="inner_diameter" value="{{ old('inner_diameter', $produk->inner_diameter) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Outer Diameter (mm)
                            </label>
                            <input type="number" step="0.01" name="outer_diameter" value="{{ old('outer_diameter', $produk->outer_diameter) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Width (mm)
                            </label>
                            <input type="number" step="0.01" name="width" value="{{ old('width', $produk->width) }}" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Material
                            </label>
                            <input type="text" name="material" value="{{ old('material', $produk->material) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Seal Type
                            </label>
                            <input type="text" name="seal_type" value="{{ old('seal_type', $produk->seal_type) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Cage Type
                            </label>
                            <input type="text" name="cage_type" value="{{ old('cage_type', $produk->cage_type) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Gambar & Status -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-image mr-2 text-primary-600"></i>Gambar & Status
                    </h2>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tambah Gambar Baru
                            </label>
                            <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <p class="text-gray-500 text-xs mt-1">Format: jpeg, png, jpg, webp. Maksimal 2MB per gambar.</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $produk->is_active) ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <label class="ml-2 text-sm font-medium text-gray-700">Produk Aktif</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $produk->is_featured) ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <label class="ml-2 text-sm font-medium text-gray-700">Produk Unggulan (Featured)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.produk.index') }}"
                        class="px-6 py-2.5 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-all">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
