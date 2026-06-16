<!-- Navigasi Atas -->
<header class="fixed top-0 inset-x-0 z-50 bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between h-16 px-4 lg:px-8">
        <div class="flex items-center gap-4">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-bars text-gray-600"></i>
            </button>

            <a href="{{ route('pelanggan.home.index') }}" class="flex items-center">
                <img src="{{ asset('images/logo_bearindo.png') }}" alt="Bearing Shop" class="h-16 w-auto">
            </a>

            <nav class="hidden lg:flex items-center gap-2">
                <a href="{{ route('pelanggan.home.index') }}"
                    class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pelanggan.home.index') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50' }}">
                    Beranda
                </a>
                <a href="{{ route('pelanggan.produk.index') }}"
                    class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pelanggan.produk.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50' }}">
                    Produk
                </a>
                <a href="{{ route('pelanggan.keranjang.index') }}"
                    class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pelanggan.keranjang.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50' }}">
                    Keranjang
                </a>
                <!-- <a href="{{ route('pelanggan.pembelian.index') }}"
                    class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pelanggan.pembelian.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50' }}">
                    Pesanan
                </a> -->
                <a href="{{ route('pelanggan.kontak') }}"
                    class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pelanggan.kontak') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50' }}">
                    Kontak
                </a>
            </nav>
        </div>

        <div class="hidden lg:block flex-1 max-w-2xl mx-6">
            <form action="{{ route('pelanggan.produk.index') }}" method="GET">
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari produk bearing..." value="{{ request('search') }}"
                        class="w-full px-4 py-2 pl-10 pr-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    <button type="submit" class="absolute left-3 top-3 text-gray-400 hover:text-primary-600">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Ikon Sisi Kanan -->
        <div class="flex items-center space-x-1">
            @auth
                <!-- Tombol Keranjang -->
                @php
                    $cartCount = \App\Models\Keranjang::where('user_id', auth()->id())->sum('quantity');
                @endphp
                <a href="{{ route('pelanggan.keranjang.index') }}"
                    class="relative p-2 ml-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-shopping-cart text-gray-600 text-lg"></i>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-orange-500 text-white text-xs rounded-full flex items-center justify-center font-medium">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- Profil Pengguna -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="w-9 h-9 bg-primary-600 rounded-full flex items-center justify-center overflow-hidden">
                            @php
                                $navInitials = collect(explode(' ', auth()->user()->name))->map(fn($s) => strtoupper(substr($s, 0, 1)))->take(2)->join('');
                            @endphp
                            <span class="text-white text-sm font-semibold">{{ $navInitials }}</span>
                        </div>
                        <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </button>

                    <!-- Dropdown Profil -->
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 z-50 border border-gray-200">

                        <div class="px-4 py-3 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('pelanggan.profil.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2 text-gray-400"></i>Profil Saya
                        </a>
                        <a href="{{ route('pelanggan.pembelian.index') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-box mr-2 text-gray-400"></i>Pesanan Saya
                        </a>
                        <div class="border-t border-gray-200 my-2"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Guest: Login & Register -->
                <a href="{{ route('login') }}"
                    class="px-4 py-2 text-sm font-medium text-primary-600 hover:text-primary-700 border border-primary-600 rounded-lg hover:bg-primary-50 transition-all">
                    <i class="fas fa-sign-in-alt mr-1"></i>Login
                </a>
                <a href="{{ route('register') }}"
                    class="hidden sm:inline-flex px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-all">
                    <i class="fas fa-user-plus mr-1"></i>Daftar
                </a>
            @endauth
        </div>
    </div>

    <div class="px-4 pb-4 lg:hidden">
        <form action="{{ route('pelanggan.produk.index') }}" method="GET">
            <div class="relative">
                <input type="text" name="search" placeholder="Cari produk bearing..." value="{{ request('search') }}"
                    class="w-full px-4 py-2 pl-10 pr-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                <button type="submit" class="absolute left-3 top-3 text-gray-400 hover:text-primary-600">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden border-t border-gray-200 bg-white">
        <nav class="px-4 py-3 space-y-1">
            <a href="{{ route('pelanggan.home.index') }}"
                class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('pelanggan.home.index') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600' }}">
                Beranda
            </a>
            <a href="{{ route('pelanggan.produk.index') }}"
                class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('pelanggan.produk.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600' }}">
                Produk
            </a>
            <a href="{{ route('pelanggan.keranjang.index') }}"
                class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('pelanggan.keranjang.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600' }}">
                Keranjang
            </a>
            <a href="{{ route('pelanggan.pembelian.index') }}"
                class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('pelanggan.pembelian.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600' }}">
                Pesanan
            </a>
            <a href="{{ route('pelanggan.kontak') }}"
                class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('pelanggan.kontak') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600' }}">
                Kontak
            </a>
        </nav>
    </div>
</header>