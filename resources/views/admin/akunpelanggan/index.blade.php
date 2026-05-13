@extends('layout.admin.app')

@section('title', 'Manajemen Akun Pelanggan - Admin')

@section('content')
    <!-- Header Halaman -->
    <div class="bg-linear-to-r from-primary-700 to-primary-900 rounded-2xl shadow-xl p-8 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Manajemen Akun Pelanggan</h1>
                <p class="text-primary-100">Kelola data pelanggan terdaftar</p>
            </div>
            <div class="md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-primary-900 text-4xl"></i>
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

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Pelanggan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pelanggans->total() }}</p>
                </div>
                <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-primary-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Pelanggan Aktif</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pelanggans->where('is_active', 1)->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tidak Aktif</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pelanggans->where('is_active', 0)->count() }}</p>
                </div>
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-slash text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form action="{{ route('admin.akunpelanggan.index') }}" method="GET" class="grid md:grid-cols-3 gap-4">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau telepon..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
            </div>
            <select name="is_active"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2.5 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-all">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.akunpelanggan.index') }}"
                    class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition-all">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Pelanggan -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Kontak
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Terdaftar
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pelanggans as $index => $pelanggan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $pelanggans->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($pelanggan->name) }}&size=40&background=3b82f6&color=fff" 
                                        alt="{{ $pelanggan->name }}" class="w-10 h-10 rounded-full mr-3">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $pelanggan->name }}</p>
                                        <p class="text-sm text-gray-500">ID: #{{ $pelanggan->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $pelanggan->email }}</p>
                                <p class="text-sm text-gray-500">{{ $pelanggan->telepon ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if ($pelanggan->is_active)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold flex items-center w-fit">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold flex items-center w-fit">
                                        <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $pelanggan->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">Tidak ada data pelanggan</p>
                                    <p class="text-gray-400 text-sm">Belum ada pelanggan yang terdaftar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($pelanggans->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $pelanggans->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
