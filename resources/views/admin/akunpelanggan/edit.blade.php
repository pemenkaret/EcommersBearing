@extends('layout.admin.app')

@section('title', 'Edit Akun Pelanggan - Admin')

@section('content')
    <!-- Header Halaman -->
    <div class="bg-linear-to-r from-primary-700 to-primary-900 rounded-2xl shadow-xl p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.akunpelanggan.index') }}"
                    class="inline-flex items-center text-white hover:text-white mb-4 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <h1 class="text-3xl font-bold text-white mb-2">Edit Akun Pelanggan</h1>
                <p class="text-primary-100">Ubah informasi pelanggan</p>
            </div>
            <div class="hidden md:block">
                <div class="w-18 h-18 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-edit text-primary-900 text-4xl"></i>
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

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Informasi Singkat -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <div class="text-center mb-6">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($pelanggan->name) }}&size=128&background=3b82f6&color=fff" alt="Avatar"
                        class="w-32 h-32 rounded-full mx-auto mb-4 border-4 border-primary-100">
                    <h3 class="text-xl font-bold text-gray-900">{{ $pelanggan->name }}</h3>
                    <p class="text-sm text-gray-500">ID: #{{ $pelanggan->id }}</p>
                    @if ($pelanggan->is_active)
                        <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                            <i class="fas fa-check-circle mr-1"></i>Aktif
                        </span>
                    @else
                        <span class="inline-block mt-2 px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                            <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                        </span>
                    @endif
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Terdaftar:</span>
                        <span class="font-medium">{{ $pelanggan->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Pesanan:</span>
                        <span class="font-medium">{{ $pelanggan->orders()->count() ?? 0 }} pesanan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Terakhir Diupdate:</span>
                        <span class="font-medium">{{ $pelanggan->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.akunpelanggan.update', $pelanggan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Informasi Pribadi -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-user mr-2 text-primary-600"></i>Informasi Pribadi
                    </h2>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $pelanggan->name) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                placeholder="Contoh: John Doe">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Nama lengkap pelanggan</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $pelanggan->email) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                placeholder="contoh@email.com">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Email harus unik untuk setiap pelanggan</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon
                            </label>
                            <input type="tel" name="telepon" value="{{ old('telepon', $pelanggan->telepon) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('telepon') border-red-500 @enderror"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                placeholder="081234567890">
                            @error('telepon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Format: 08xxxxxxxxxx (opsional)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status Akun <span class="text-red-500">*</span>
                            </label>
                            <select name="is_active" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('is_active') border-red-500 @enderror">
                                <option value="1" {{ old('is_active', $pelanggan->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', $pelanggan->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('is_active')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Akun tidak aktif tidak bisa login</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.akunpelanggan.index') }}"
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