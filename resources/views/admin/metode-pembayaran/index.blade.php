@extends('layout.admin.app')

@section('title', 'Kelola Metode Pembayaran')

@section('content')
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Metode Pembayaran</h1>
            <p class="text-gray-600 mt-1">Kelola metode pembayaran untuk pelanggan</p>
        </div>
        <a href="{{ route('admin.metode-pembayaran.create') }}"
            class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg font-semibold hover:bg-primary-700 transition-all">
            <i class="fas fa-plus mr-2"></i>Tambah Metode
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Urutan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Info Bank</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($metodePembayarans as $metode)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-gray-900 font-medium">{{ $metode->urutan }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if ($metode->logo)
                                        <img src="{{ asset('storage/' . $metode->logo) }}" alt="{{ $metode->nama }}"
                                            class="w-10 h-10 rounded object-contain mr-3 bg-gray-100 p-1">
                                    @else
                                        <div class="w-10 h-10 rounded bg-gray-100 flex items-center justify-center mr-3">
                                            @if ($metode->tipe == 'transfer')
                                                <i class="fas fa-university text-gray-400"></i>
                                            @elseif ($metode->tipe == 'cod')
                                                <i class="fas fa-hand-holding-usd text-gray-400"></i>
                                            @else
                                                <i class="fas fa-wallet text-gray-400"></i>
                                            @endif
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $metode->nama }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($metode->tipe == 'transfer')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-primary-100 text-primary-800">Transfer Bank</span>
                                @elseif ($metode->tipe == 'cod')
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">COD</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">E-Wallet</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($metode->tipe == 'transfer')
                                    <div class="text-sm">
                                        <p class="font-medium text-gray-900">{{ $metode->bank_nama }}</p>
                                        <p class="text-gray-500">{{ $metode->bank_rekening }}</p>
                                        <p class="text-gray-500">a.n {{ $metode->bank_atas_nama }}</p>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Tidak berlaku (bukan transfer bank)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.metode-pembayaran.toggle-status', $metode->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="focus:outline-none">
                                        @if ($metode->is_active)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 cursor-pointer hover:bg-green-200">
                                                <i class="fas fa-check-circle mr-1"></i>Aktif
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 cursor-pointer hover:bg-red-200">
                                                <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.metode-pembayaran.edit', $metode->id) }}"
                                        class="text-primary-600 hover:text-primary-800" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.metode-pembayaran.destroy', $metode->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus metode pembayaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-credit-card text-4xl mb-3"></i>
                                <p>Belum ada metode pembayaran</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($metodePembayarans->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $metodePembayarans->links() }}
            </div>
        @endif
    </div>
@endsection
