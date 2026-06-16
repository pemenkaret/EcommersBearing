@extends('layout.pelanggan.app')

@section('title', 'Profil Saya - Bearing Shop')

@section('content')
    <!-- Header Halaman -->
    <div class="bg-linear-to-r from-primary-700 to-primary-900 rounded-2xl shadow-xl p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Profil Saya</h1>
                <p class="text-primary-100">Kelola informasi profil Anda</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-primary-800 text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages menggunakan komponen -->
    @if (session('success'))
        @include('pelanggan.component.alert', ['type' => 'success', 'slot' => session('success')])
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
        <!-- Sidebar Menu Profil -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md">
                <!-- Avatar -->
                <div class="relative bg-linear-to-br from-primary-700 to-primary-900 p-8 text-center rounded-t-xl overflow-hidden">
                    <div class="w-32 h-32 mx-auto mb-4 relative">
                        @php
                            $initials = collect(explode(' ', $user->name))->map(fn($s) => strtoupper(substr($s, 0, 1)))->take(2)->join('');
                        @endphp
                        <div class="w-full h-full rounded-full border-4 border-white shadow-lg flex items-center justify-center bg-primary-500 text-white font-bold text-5xl">
                            {{ $initials }}
                        </div>
                    </div>
                    <h2 class="text-xl font-bold text-white mb-1">{{ $user->name }}</h2>
                    <p class="text-primary-100 text-sm">{{ $user->email }}</p>
                </div>

                <!-- Menu -->
                <div class="p-2">
                    <button onclick="showSection('info')" data-section="info"
                        class="profile-menu-item w-full flex items-center px-4 py-3 rounded-lg text-left font-medium text-white bg-primary-600 mb-1 transition-all">
                        <i class="fas fa-user w-5 mr-3"></i>
                        <span>Informasi Pribadi</span>
                    </button>
                    <button onclick="showSection('alamat')" data-section="alamat"
                        class="profile-menu-item w-full flex items-center px-4 py-3 rounded-lg text-left font-medium text-gray-700 hover:bg-gray-100 mb-1 transition-all">
                        <i class="fas fa-map-marker-alt w-5 mr-3"></i>
                        <span>Alamat Pengiriman</span>
                    </button>
                    <button onclick="showSection('keamanan')" data-section="keamanan"
                        class="profile-menu-item w-full flex items-center px-4 py-3 rounded-lg text-left font-medium text-gray-700 hover:bg-gray-100 mb-1 transition-all">
                        <i class="fas fa-lock w-5 mr-3"></i>
                        <span>Keamanan</span>
                    </button>
                    <button onclick="showSection('notifikasi')" data-section="notifikasi"
                        class="profile-menu-item w-full flex items-center px-4 py-3 rounded-lg text-left font-medium text-gray-700 hover:bg-gray-100 mb-1 transition-all">
                        <i class="fas fa-bell w-5 mr-3"></i>
                        <span>Notifikasi</span>
                    </button>
                </div>
            </div>

            <!-- Info Singkat -->
            <div class="bg-white rounded-xl shadow-md p-6 mt-6">
                <h3 class="font-bold text-gray-900 mb-4">Info Akun</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Telepon:</span>
                        <span class="font-medium">{{ $user->telepon ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Bergabung:</span>
                        <span class="font-medium">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total Alamat:</span>
                        <span class="font-medium">{{ $alamats->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Profil -->
        <div class="lg:col-span-2">
            <!-- Section Informasi Pribadi -->
            <div id="section-info" class="profile-section bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-user mr-2 text-primary-600"></i>Informasi Pribadi
                </h2>

                <form action="{{ route('pelanggan.profil.update-pribadi') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" name="telepon" value="{{ old('telepon', $user->telepon) }}"
                                class="w-full px-4 py-2 border {{ $errors->has('telepon') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="08xx-xxxx-xxxx"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            @error('telepon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-6 py-2 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-all">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>

                <!-- Upload Avatar Dihapus karena menggunakan inisial nama -->
            </div>

            <!-- Section Alamat Pengiriman -->
            <div id="section-alamat" class="profile-section hidden bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-map-marker-alt mr-2 text-primary-600"></i>Alamat Pengiriman
                    </h2>
                    <button type="button" onclick="document.getElementById('formAlamatBaru').classList.toggle('hidden')"
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-all">
                        <i class="fas fa-plus mr-2"></i>Tambah Alamat
                    </button>
                </div>

                <!-- Form Tambah Alamat -->
                <div id="formAlamatBaru" class="hidden mb-6 p-4 bg-gray-50 rounded-lg border">
                    <h3 class="font-bold text-gray-900 mb-4">Tambah Alamat Baru</h3>
                    <form action="{{ route('pelanggan.alamat.store') }}" method="POST">
                        @csrf
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Label Alamat <span class="text-red-500">*</span></label>
                                <input type="text" name="label" value="{{ old('label') }}" placeholder="Contoh: Rumah, Kantor" required
                                    class="w-full px-4 py-2 border {{ $errors->has('label') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500">
                                @error('label')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penerima <span class="text-red-500">*</span></label>
                                <input type="text" name="penerima" value="{{ old('penerima') }}" required
                                    class="w-full px-4 py-2 border {{ $errors->has('penerima') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500">
                                @error('penerima')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telepon <span class="text-red-500">*</span></label>
                                <input type="tel" name="telepon" value="{{ old('telepon') }}" required placeholder="08xx-xxxx-xxxx"
                                    class="w-full px-4 py-2 border {{ $errors->has('telepon') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                @error('telepon')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                                <select name="provinsi" id="provinsi-tambah" required
                                    class="w-full px-4 py-2 border {{ $errors->has('provinsi') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                @error('provinsi')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten <span class="text-red-500">*</span></label>
                                <select name="kota" id="kota-tambah" required disabled
                                    class="w-full px-4 py-2 border {{ $errors->has('kota') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500">
                                    <option value="">Pilih Provinsi Terlebih Dahulu</option>
                                </select>
                                @error('kota')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                                <select name="kecamatan" id="kecamatan-tambah" required disabled
                                    class="w-full px-4 py-2 border {{ $errors->has('kecamatan') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500">
                                    <option value="">Pilih Kota Terlebih Dahulu</option>
                                </select>
                                @error('kecamatan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos <span class="text-red-500">*</span></label>
                                <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" required placeholder="Contoh: 40132"
                                    class="w-full px-4 py-2 border {{ $errors->has('kode_pos') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                @error('kode_pos')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="alamat_lengkap" rows="3" required placeholder="Nama jalan, nomor rumah, RT/RW, dll"
                                    class="w-full px-4 py-2 border {{ $errors->has('alamat_lengkap') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500">{{ old('alamat_lengkap') }}</textarea>
                                @error('alamat_lengkap')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="document.getElementById('formAlamatBaru').classList.add('hidden')"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                                <i class="fas fa-save mr-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Daftar Alamat -->
                <div class="space-y-4">
                    @forelse ($alamats as $alamat)
                        <div class="border rounded-lg p-4 {{ $alamat->is_default ? 'border-primary-500 bg-primary-50' : 'border-gray-200' }}">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full mr-2">
                                        {{ $alamat->label ?? 'Alamat' }}
                                    </span>
                                    @if ($alamat->is_default)
                                        <span class="px-3 py-1 bg-primary-600 text-white text-xs font-semibold rounded-full">Utama</span>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <button type="button" 
                                        onclick="editAlamat('{{ $alamat->id }}', '{{ $alamat->label }}', '{{ $alamat->penerima }}', '{{ $alamat->telepon }}', '{{ addslashes($alamat->alamat_lengkap) }}', '{{ $alamat->provinsi }}', '{{ $alamat->kota }}', '{{ $alamat->kecamatan }}', '{{ $alamat->kode_pos }}')"
                                        class="text-primary-600 hover:text-primary-700 text-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    @if (!$alamat->is_default)
                                        <form action="{{ route('pelanggan.alamat.set-default', $alamat->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-700 text-sm">
                                                <i class="fas fa-check-circle"></i> Jadikan Utama
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('pelanggan.alamat.destroy', $alamat->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus alamat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="space-y-1 text-sm">
                                <p class="font-bold text-gray-900">{{ $alamat->penerima }}</p>
                                <p class="text-gray-600">{{ $alamat->telepon }}</p>
                                <p class="text-gray-700">{{ $alamat->alamat_lengkap }}</p>
                                <p class="text-gray-600">{{ $alamat->kecamatan }}, {{ $alamat->kota }}</p>
                                <p class="text-gray-500">{{ $alamat->provinsi }} {{ $alamat->kode_pos }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-map-marker-alt text-4xl mb-2"></i>
                            <p>Belum ada alamat tersimpan</p>
                        </div>
                    @endforelse
                </div>

                <!-- Modal Edit Alamat -->
                <div id="modalEditAlamat" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 p-4" style="display: none; align-items: center; justify-content: center;">
                    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                        <div class="sticky top-0 bg-white border-b px-6 py-4 flex items-center justify-between">
                            <h3 class="text-xl font-bold text-gray-900">Edit Alamat</h3>
                            <button type="button" onclick="closeModalEdit()"
                                class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <form id="formEditAlamat" action="" method="POST" class="p-6">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="edit-alamat-id" name="alamat_id">
                            
                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Label Alamat <span class="text-red-500">*</span></label>
                                    <input type="text" id="edit-label" name="label" required placeholder="Contoh: Rumah, Kantor"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Penerima <span class="text-red-500">*</span></label>
                                    <input type="text" id="edit-penerima" name="penerima" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Telepon <span class="text-red-500">*</span></label>
                                    <input type="tel" id="edit-telepon" name="telepon" required placeholder="08xx-xxxx-xxxx"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                                    <select id="edit-provinsi" name="provinsi" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten <span class="text-red-500">*</span></label>
                                    <select id="edit-kota" name="kota" required disabled
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                        <option value="">Pilih Provinsi Terlebih Dahulu</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                                    <select id="edit-kecamatan" name="kecamatan" required disabled
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                        <option value="">Pilih Kota Terlebih Dahulu</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos <span class="text-red-500">*</span></label>
                                    <input type="text" id="edit-kode-pos" name="kode_pos" required placeholder="Contoh: 40132"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                                    <textarea id="edit-alamat-lengkap" name="alamat_lengkap" rows="3" required
                                        placeholder="Jalan, RT/RW, Nomor Rumah, Patokan"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500"></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-2 pt-4 border-t">
                                <button type="button" onclick="closeModalEdit()"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-all">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-all">
                                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Section Keamanan -->
            <div id="section-keamanan" class="profile-section hidden bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-lock mr-2 text-primary-600"></i>Keamanan Akun
                </h2>

                <!-- Ubah Password -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Ubah Password</h3>
                    <form action="{{ route('pelanggan.profil.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama <span class="text-red-500">*</span></label>
                                <input type="password" name="current_password" required
                                    class="w-full px-4 py-2 border {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500">
                                @error('current_password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required
                                    class="w-full px-4 py-2 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500"
                                    placeholder="Minimal 8 karakter">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                            </div>
                        </div>
                        <button type="submit"
                            class="px-6 py-2 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-all">
                            <i class="fas fa-key mr-2"></i>Ubah Password
                        </button>
                    </form>
                </div>

                <!-- Perangkat yang Diingat -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Perangkat yang Diingat</h3>
                        @if($rememberTokens->count() > 0)
                            <form action="{{ route('pelanggan.profil.delete-all-remember') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus semua perangkat?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition-all">
                                    <i class="fas fa-trash mr-1"></i>Hapus Semua
                                </button>
                            </form>
                        @endif
                    </div>

                    @if($rememberTokens->count() > 0)
                        <div class="space-y-3">
                            @foreach($rememberTokens as $token)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="flex items-center">
                                                <i class="fas fa-{{ (str_contains($token->device_name, 'iOS') || str_contains($token->device_name, 'Android')) ? 'mobile-alt' : 'desktop' }} text-primary-500 mr-2"></i>
                                                <div>
                                                    <p class="font-medium text-gray-900 text-sm">{{ $token->device_name ?? 'Perangkat Tidak Dikenal' }}</p>
                                                    <p class="text-xs text-gray-500">{{ $token->ip_address }} • {{ $token->created_at->diffForHumans() }}</p>
                                                    <p class="text-xs text-gray-400">Kadaluarsa: {{ $token->expires_at->translatedFormat('d M Y, H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <form action="{{ route('pelanggan.profil.delete-remember', $token->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus perangkat ini?')">
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
                        <div class="text-center py-6 text-gray-500">
                            <i class="fas fa-mobile-alt text-3xl mb-2"></i>
                            <p class="text-sm">Tidak ada perangkat yang diingat</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section Notifikasi -->
            <div id="section-notifikasi" class="profile-section hidden bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-bell mr-2 text-primary-600"></i>Pengaturan Notifikasi
                </h2>

                <form action="{{ route('pelanggan.profil.update-notifikasi') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4 mb-6">
                        <label class="flex items-center justify-between cursor-pointer p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Notifikasi Email</p>
                                <p class="text-sm text-gray-500">Terima pemberitahuan via email</p>
                            </div>
                            <input type="checkbox" name="notifikasi_email" value="1" 
                                {{ $user->notifikasi_email ? 'checked' : '' }}
                                class="w-5 h-5 text-primary-600 rounded focus:ring-primary-500">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Update Pesanan</p>
                                <p class="text-sm text-gray-500">Notifikasi status pesanan dan pengiriman</p>
                            </div>
                            <input type="checkbox" name="notifikasi_order" value="1"
                                {{ $user->notifikasi_order ? 'checked' : '' }}
                                class="w-5 h-5 text-primary-600 rounded focus:ring-primary-500">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">Promosi & Penawaran</p>
                                <p class="text-sm text-gray-500">Info promo dan diskon spesial</p>
                            </div>
                            <input type="checkbox" name="notifikasi_promo" value="1"
                                {{ $user->notifikasi_promo ? 'checked' : '' }}
                                class="w-5 h-5 text-primary-600 rounded focus:ring-primary-500">
                        </label>
                    </div>
                    <button type="submit"
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-all">
                        <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Tampilkan Section
        function showSection(section) {
            // Sembunyikan semua section
            document.querySelectorAll('.profile-section').forEach(el => el.classList.add('hidden'));

            // Tampilkan section yang dipilih
            document.getElementById('section-' + section).classList.remove('hidden');

            // Update menu aktif
            document.querySelectorAll('.profile-menu-item').forEach(item => {
                if (item.dataset.section === section) {
                    item.classList.remove('text-gray-700', 'hover:bg-gray-100');
                    item.classList.add('text-white', 'bg-primary-600');
                } else {
                    item.classList.remove('text-white', 'bg-primary-600');
                    item.classList.add('text-gray-700', 'hover:bg-gray-100');
                }
            });
        }

        // API Wilayah Indonesia (Local Proxy)
        const API_BASE = '/api/wilayah';

        // Load Provinsi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            loadProvinsi();
        });

        async function loadProvinsi() {
            try {
                const response = await fetch(`${API_BASE}/provinsi`);
                const data = await response.json();
                
                const selectProvinsi = document.getElementById('provinsi-tambah');
                selectProvinsi.innerHTML = '<option value="">Pilih Provinsi</option>';
                
                data.forEach(prov => {
                    const option = document.createElement('option');
                    option.value = prov.nama;
                    option.dataset.kode = prov.kode;
                    option.textContent = prov.nama;
                    selectProvinsi.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading provinsi:', error);
            }
        }

        // Event listener untuk perubahan provinsi
        document.getElementById('provinsi-tambah')?.addEventListener('change', async function() {
            const selectedOption = this.options[this.selectedIndex];
            const kodeProvinsi = selectedOption.dataset.kode;
            
            const selectKota = document.getElementById('kota-tambah');
            const selectKecamatan = document.getElementById('kecamatan-tambah');
            
            // Reset kota dan kecamatan
            selectKota.innerHTML = '<option value="">Loading...</option>';
            selectKota.disabled = true;
            selectKecamatan.innerHTML = '<option value="">Pilih Kota Terlebih Dahulu</option>';
            selectKecamatan.disabled = true;
            
            if (kodeProvinsi) {
                try {
                    const response = await fetch(`${API_BASE}/kota?pro=${kodeProvinsi}`);
                    const data = await response.json();
                    
                    selectKota.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    data.forEach(kab => {
                        const option = document.createElement('option');
                        option.value = kab.nama;
                        option.dataset.kode = kab.kode;
                        option.textContent = kab.nama;
                        selectKota.appendChild(option);
                    });
                    selectKota.disabled = false;
                } catch (error) {
                    console.error('Error loading kabupaten:', error);
                    selectKota.innerHTML = '<option value="">Error loading data</option>';
                }
            }
        });

        // Event listener untuk perubahan kota
        document.getElementById('kota-tambah')?.addEventListener('change', async function() {
            const selectedProvOption = document.getElementById('provinsi-tambah').options[document.getElementById('provinsi-tambah').selectedIndex];
            const selectedKabOption = this.options[this.selectedIndex];
            const kodeProvinsi = selectedProvOption.dataset.kode;
            const kodeKabupaten = selectedKabOption.dataset.kode;
            
            const selectKecamatan = document.getElementById('kecamatan-tambah');
            selectKecamatan.innerHTML = '<option value="">Loading...</option>';
            selectKecamatan.disabled = true;
            
            if (kodeProvinsi && kodeKabupaten) {
                try {
                    const response = await fetch(`${API_BASE}/kecamatan?pro=${kodeProvinsi}&kab=${kodeKabupaten}`);
                    const data = await response.json();
                    
                    selectKecamatan.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    data.forEach(kec => {
                        const option = document.createElement('option');
                        option.value = kec.nama;
                        option.dataset.kode = kec.kode;
                        option.textContent = kec.nama;
                        selectKecamatan.appendChild(option);
                    });
                    selectKecamatan.disabled = false;
                } catch (error) {
                    console.error('Error loading kecamatan:', error);
                    selectKecamatan.innerHTML = '<option value="">Error loading data</option>';
                }
            }
        });

        // Fungsi untuk edit alamat
        function editAlamat(alamatId, label, penerima, telepon, alamatLengkap, provinsi, kota, kecamatan, kodePos) {
            // Set form action URL
            const formEdit = document.getElementById('formEditAlamat');
            formEdit.action = `/pelanggan/alamat/${alamatId}`;
            
            // Isi form modal edit
            document.getElementById('edit-alamat-id').value = alamatId;
            document.getElementById('edit-label').value = label;
            document.getElementById('edit-penerima').value = penerima;
            document.getElementById('edit-telepon').value = telepon;
            document.getElementById('edit-alamat-lengkap').value = alamatLengkap;
            document.getElementById('edit-kode-pos').value = kodePos;
            
            // Load provinsi untuk form edit
            loadProvinsiEdit(provinsi, kota, kecamatan);
            
            // Tampilkan modal
            const modal = document.getElementById('modalEditAlamat');
            modal.style.display = 'flex';
            modal.classList.remove('hidden');
        }

        // Fungsi untuk menutup modal
        function closeModalEdit() {
            const modal = document.getElementById('modalEditAlamat');
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }

        async function loadProvinsiEdit(selectedProv = '', selectedKota = '', selectedKec = '') {
            try {
                const response = await fetch(`${API_BASE}/provinsi`);
                const data = await response.json();
                
                const selectProvinsi = document.getElementById('edit-provinsi');
                selectProvinsi.innerHTML = '<option value="">Pilih Provinsi</option>';
                
                let kodeProvSelected = '';
                data.forEach(prov => {
                    const option = document.createElement('option');
                    option.value = prov.nama;
                    option.dataset.kode = prov.kode;
                    option.textContent = prov.nama;
                    if (prov.nama === selectedProv) {
                        option.selected = true;
                        kodeProvSelected = prov.kode;
                    }
                    selectProvinsi.appendChild(option);
                });

                // Load kota jika ada provinsi terpilih
                if (kodeProvSelected) {
                    await loadKotaEdit(kodeProvSelected, selectedKota, selectedKec);
                }
            } catch (error) {
                console.error('Error loading provinsi for edit:', error);
            }
        }

        async function loadKotaEdit(kodeProvinsi, selectedKota = '', selectedKec = '') {
            try {
                const response = await fetch(`${API_BASE}/kota?pro=${kodeProvinsi}`);
                const data = await response.json();
                
                const selectKota = document.getElementById('edit-kota');
                selectKota.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                
                let kodeKabSelected = '';
                data.forEach(kab => {
                    const option = document.createElement('option');
                    option.value = kab.nama;
                    option.dataset.kode = kab.kode;
                    option.textContent = kab.nama;
                    if (kab.nama === selectedKota) {
                        option.selected = true;
                        kodeKabSelected = kab.kode;
                    }
                    selectKota.appendChild(option);
                });
                selectKota.disabled = false;

                // Load kecamatan jika ada kota terpilih
                if (kodeKabSelected) {
                    await loadKecamatanEdit(kodeProvinsi, kodeKabSelected, selectedKec);
                }
            } catch (error) {
                console.error('Error loading kota for edit:', error);
            }
        }

        async function loadKecamatanEdit(kodeProvinsi, kodeKabupaten, selectedKec = '') {
            try {
                const response = await fetch(`${API_BASE}/kecamatan?pro=${kodeProvinsi}&kab=${kodeKabupaten}`);
                const data = await response.json();
                
                const selectKecamatan = document.getElementById('edit-kecamatan');
                selectKecamatan.innerHTML = '<option value="">Pilih Kecamatan</option>';
                
                data.forEach(kec => {
                    const option = document.createElement('option');
                    option.value = kec.nama;
                    option.dataset.kode = kec.kode;
                    option.textContent = kec.nama;
                    if (kec.nama === selectedKec) {
                        option.selected = true;
                    }
                    selectKecamatan.appendChild(option);
                });
                selectKecamatan.disabled = false;
            } catch (error) {
                console.error('Error loading kecamatan for edit:', error);
            }
        }

        // Event listeners untuk form edit
        document.getElementById('edit-provinsi')?.addEventListener('change', async function() {
            const selectedOption = this.options[this.selectedIndex];
            const kodeProvinsi = selectedOption.dataset.kode;
            
            const selectKota = document.getElementById('edit-kota');
            const selectKecamatan = document.getElementById('edit-kecamatan');
            
            selectKota.innerHTML = '<option value="">Loading...</option>';
            selectKota.disabled = true;
            selectKecamatan.innerHTML = '<option value="">Pilih Kota Terlebih Dahulu</option>';
            selectKecamatan.disabled = true;
            
            if (kodeProvinsi) {
                await loadKotaEdit(kodeProvinsi);
            }
        });

        document.getElementById('edit-kota')?.addEventListener('change', async function() {
            const selectedProvOption = document.getElementById('edit-provinsi').options[document.getElementById('edit-provinsi').selectedIndex];
            const selectedKabOption = this.options[this.selectedIndex];
            const kodeProvinsi = selectedProvOption.dataset.kode;
            const kodeKabupaten = selectedKabOption.dataset.kode;
            
            const selectKecamatan = document.getElementById('edit-kecamatan');
            selectKecamatan.innerHTML = '<option value="">Loading...</option>';
            selectKecamatan.disabled = true;
            
            if (kodeProvinsi && kodeKabupaten) {
                await loadKecamatanEdit(kodeProvinsi, kodeKabupaten);
            }
        });
    </script>
@endsection