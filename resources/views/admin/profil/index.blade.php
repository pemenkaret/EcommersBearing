@extends('layout.admin.app')

@section('title', 'Profil Admin - Bearing Shop')

@section('content')
    <!-- Header Halaman -->
    <div class="bg-linear-to-r from-primary-700 to-primary-900 rounded-2xl shadow-xl p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Profil Admin</h1>
                <p class="text-primary-100">Kelola informasi profil administrator</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield text-primary-800 text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
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
        <!-- Sidebar Profil -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md">
                <!-- Avatar -->
                <div class="bg-linear-to-br from-primary-700 to-primary-900 p-8 text-center rounded-t-xl">
                    <div class="w-32 h-32 mx-auto mb-4 relative">
                        @if (auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar"
                                class="w-full h-full rounded-full border-4 border-white shadow-lg object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=128&background=3b82f6&color=fff"
                                alt="Avatar" class="w-full h-full rounded-full border-4 border-white shadow-lg object-cover">
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-white">{{ auth()->user()->name }}</h3>
                    <p class="text-primary-100 text-sm">{{ auth()->user()->email }}</p>
                    <span
                        class="inline-block mt-2 px-3 py-1 bg-white bg-opacity-20 text-white text-xs font-semibold rounded-full">
                        {{ auth()->user()->role->name ?? 'Admin' }}
                    </span>
                </div>

                <!-- Info Singkat -->
                <div class="p-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Telepon:</span>
                            <span class="font-medium">{{ auth()->user()->telepon ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Bergabung:</span>
                            <span class="font-medium">{{ auth()->user()->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Terakhir Update:</span>
                            <span class="font-medium">{{ auth()->user()->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit Profil -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Informasi Pribadi -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-user mr-2 text-primary-600"></i>Informasi Pribadi
                    </h2>

                    @php
                        $nameBorderClass = $errors->has('name') ? 'border-red-500' : 'border-gray-300';
                        $emailBorderClass = $errors->has('email') ? 'border-red-500' : 'border-gray-300';
                        $teleponBorderClass = $errors->has('telepon') ? 'border-red-500' : 'border-gray-300';
                        $avatarBorderClass = $errors->has('avatar') ? 'border-red-500' : 'border-gray-300';
                    @endphp

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                                class="w-full px-4 py-2 border {{ $nameBorderClass }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Nama lengkap Anda">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Nama akan
                                ditampilkan di header admin</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                class="w-full px-4 py-2 border {{ $emailBorderClass }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="admin@example.com">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Email untuk login
                                dan notifikasi</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon
                            </label>
                            <input type="tel" name="telepon" value="{{ old('telepon', auth()->user()->telepon) }}"
                                class="w-full px-4 py-2 border {{ $teleponBorderClass }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                placeholder="08xx-xxxx-xxxx">
                            @error('telepon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Format:
                                08xxxxxxxxxx (opsional)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Avatar
                            </label>
                            <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg"
                                class="w-full px-4 py-2 border {{ $avatarBorderClass }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            @error('avatar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1">Format: jpeg, png, jpg. Maksimal 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- Ubah Password -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-lock mr-2 text-primary-600"></i>Ubah Password
                    </h2>
                    <p class="text-gray-500 text-sm mb-4">Kosongkan jika tidak ingin mengubah password</p>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password Baru
                            </label>
                            @php
                                $passwordBorderClass = $errors->has('password') ? 'border-red-500' : 'border-gray-300';
                            @endphp
                            <input type="password" name="password"
                                class="w-full px-4 py-2 border {{ $passwordBorderClass }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Minimal 8 karakter">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Minimal 8 karakter
                                dengan kombinasi huruf dan angka</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Ulangi password baru">
                            <p class="text-gray-500 text-xs mt-1"><i class="fas fa-info-circle mr-1"></i>Harus sama dengan
                                password baru</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-2.5 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-all">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Perangkat yang Diingat -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-laptop-code mr-2 text-primary-600"></i>Perangkat yang Diingat
                    </h2>
                    @if($rememberTokens->count() > 0)
                        <form action="{{ route('admin.profil.delete-all-remember') }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus semua perangkat?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-all">
                                <i class="fas fa-trash mr-1"></i>Hapus Semua
                            </button>
                        </form>
                    @endif
                </div>

                @if($rememberTokens->count() > 0)
                    <div class="space-y-4">
                        @foreach($rememberTokens as $token)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center">
                                            <i
                                                class="fas fa-{{ (str_contains($token->device_name, 'iOS') || str_contains($token->device_name, 'Android')) ? 'mobile-alt' : 'desktop' }} text-primary-500 mr-3 text-xl"></i>
                                            <div>
                                                <p class="font-semibold text-gray-900">
                                                    {{ $token->device_name ?? 'Perangkat Tidak Dikenal' }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $token->ip_address }} •
                                                    {{ $token->created_at->diffForHumans() }}
                                                </p>
                                                <p class="text-xs text-gray-400">Kadaluarsa:
                                                    {{ $token->expires_at->translatedFormat('d M Y, H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.profil.delete-remember', $token->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus perangkat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-mobile-alt text-4xl mb-2"></i>
                        <p>Tidak ada perangkat yang diingat</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection