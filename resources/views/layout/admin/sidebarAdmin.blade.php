<!-- Sidebar Desktop -->
<aside x-show="!sidebarOpen || window.innerWidth >= 1024"
    class="hidden lg:block bg-white shadow-lg border-r border-gray-200 transition-all duration-300"
    :class="{ 'lg:w-64': !sidebarCollapsed, 'lg:w-20': sidebarCollapsed }">

    <!-- Header Sidebar -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 bg-primary-600">
        <div class="flex items-center space-x-3" :class="{ 'hidden': sidebarCollapsed }">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <i class="fas fa-cog text-primary-600 text-xl"></i>
            </div>
            <div>
                <h1 class="text-white font-bold text-lg">Admin Panel</h1>
                <p class="text-primary-200 text-xs">Bearing Shop</p>
            </div>
        </div>
        <button @click="sidebarCollapsed = !sidebarCollapsed"
            class="p-2 rounded-lg hover:bg-primary-700 text-white transition-colors">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Navigasi Sidebar -->
    <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto" style="max-height: calc(100vh - 8rem);">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard.index') }}"
            :title="sidebarCollapsed ? 'Dashboard' : ''"
            class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.dashboard.*') ? 'bg-primary-50 text-primary-600' : '' }}">
            <i class="fas fa-home w-6 text-center"></i>
            <span :class="{ 'hidden': sidebarCollapsed }" class="ml-3">Dashboard</span>
        </a>

        <!-- Produk -->
        <div x-data="sidebarFlyout({ open: {{ request()->routeIs('admin.produk.*') ? 'true' : 'false' }} })">
            <button x-ref="trigger" @click="toggle()"
                :title="sidebarCollapsed ? 'Produk' : ''"
                class="flex items-center justify-between w-full px-3 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.produk.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                <div class="flex items-center">
                    <i class="fas fa-box w-6 text-center"></i>
                    <span :class="{ 'hidden': sidebarCollapsed }" class="ml-3">Produk</span>
                </div>
                <i :class="{ 'hidden': sidebarCollapsed }" class="fas fa-chevron-down text-xs transition-transform"
                    :class="{ 'rotate-180': open }"></i>
            </button>

            <!-- Inline submenu (sidebar expanded) -->
            <div x-show="open && !sidebarCollapsed" x-collapse class="ml-9 mt-1 space-y-1">
                <a href="{{ route('admin.produk.index') }}"
                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.produk.index') ? 'bg-primary-100 text-primary-600' : '' }}">
                    <i class="fas fa-list mr-2"></i>Daftar Produk
                </a>
                <a href="{{ route('admin.produk.create') }}"
                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.produk.create') ? 'bg-primary-100 text-primary-600' : '' }}">
                    <i class="fas fa-plus mr-2"></i>Tambah Produk
                </a>
            </div>

            <!-- Flyout (teleport ke body biar tidak ke-clip sidebar) -->
            <template x-teleport="body">
                <div x-show="open && sidebarCollapsed" x-cloak
                    @click.outside="open = false"
                    @keydown.escape.window="open = false"
                    x-transition.opacity.duration.150ms
                    :style="`top:${pos.top}px; left:${pos.left}px;`"
                    class="fixed w-56 bg-white rounded-lg shadow-2xl border border-gray-200 py-2 z-[9999]">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase border-b border-gray-100 mb-1">Produk</div>
                    <a href="{{ route('admin.produk.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.produk.index') ? 'bg-primary-100 text-primary-600' : '' }}">
                        <i class="fas fa-list mr-2 w-4"></i>Daftar Produk
                    </a>
                    <a href="{{ route('admin.produk.create') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.produk.create') ? 'bg-primary-100 text-primary-600' : '' }}">
                        <i class="fas fa-plus mr-2 w-4"></i>Tambah Produk
                    </a>
                </div>
            </template>
        </div>

        <!-- Kategori -->
        <div x-data="sidebarFlyout({ open: {{ request()->routeIs('admin.kategori.*') ? 'true' : 'false' }} })">
            <button x-ref="trigger" @click="toggle()"
                :title="sidebarCollapsed ? 'Kategori' : ''"
                class="flex items-center justify-between w-full px-3 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.kategori.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                <div class="flex items-center">
                    <i class="fas fa-tags w-6 text-center"></i>
                    <span :class="{ 'hidden': sidebarCollapsed }" class="ml-3">Kategori</span>
                </div>
                <i :class="{ 'hidden': sidebarCollapsed }" class="fas fa-chevron-down text-xs transition-transform"
                    :class="{ 'rotate-180': open }"></i>
            </button>

            <div x-show="open && !sidebarCollapsed" x-collapse class="ml-9 mt-1 space-y-1">
                <a href="{{ route('admin.kategori.index') }}"
                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.kategori.index') ? 'bg-primary-100 text-primary-600' : '' }}">
                    <i class="fas fa-list mr-2"></i>Daftar Kategori
                </a>
                <a href="{{ route('admin.kategori.create') }}"
                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.kategori.create') ? 'bg-primary-100 text-primary-600' : '' }}">
                    <i class="fas fa-plus mr-2"></i>Tambah Kategori
                </a>
            </div>

            <template x-teleport="body">
                <div x-show="open && sidebarCollapsed" x-cloak
                    @click.outside="open = false"
                    @keydown.escape.window="open = false"
                    x-transition.opacity.duration.150ms
                    :style="`top:${pos.top}px; left:${pos.left}px;`"
                    class="fixed w-56 bg-white rounded-lg shadow-2xl border border-gray-200 py-2 z-[9999]">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase border-b border-gray-100 mb-1">Kategori</div>
                    <a href="{{ route('admin.kategori.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.kategori.index') ? 'bg-primary-100 text-primary-600' : '' }}">
                        <i class="fas fa-list mr-2 w-4"></i>Daftar Kategori
                    </a>
                    <a href="{{ route('admin.kategori.create') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.kategori.create') ? 'bg-primary-100 text-primary-600' : '' }}">
                        <i class="fas fa-plus mr-2 w-4"></i>Tambah Kategori
                    </a>
                </div>
            </template>
        </div>

        <!-- Merk -->
        <div x-data="sidebarFlyout({ open: {{ request()->routeIs('admin.merk.*') ? 'true' : 'false' }} })">
            <button x-ref="trigger" @click="toggle()"
                :title="sidebarCollapsed ? 'Merk' : ''"
                class="flex items-center justify-between w-full px-3 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.merk.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                <div class="flex items-center">
                    <i class="fas fa-tag w-6 text-center"></i>
                    <span :class="{ 'hidden': sidebarCollapsed }" class="ml-3">Merk</span>
                </div>
                <i :class="{ 'hidden': sidebarCollapsed }" class="fas fa-chevron-down text-xs transition-transform"
                    :class="{ 'rotate-180': open }"></i>
            </button>

            <div x-show="open && !sidebarCollapsed" x-collapse class="ml-9 mt-1 space-y-1">
                <a href="{{ route('admin.merk.index') }}"
                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.merk.index') ? 'bg-primary-100 text-primary-600' : '' }}">
                    <i class="fas fa-list mr-2"></i>Daftar Merk
                </a>
                <a href="{{ route('admin.merk.create') }}"
                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.merk.create') ? 'bg-primary-100 text-primary-600' : '' }}">
                    <i class="fas fa-plus mr-2"></i>Tambah Merk
                </a>
            </div>

            <template x-teleport="body">
                <div x-show="open && sidebarCollapsed" x-cloak
                    @click.outside="open = false"
                    @keydown.escape.window="open = false"
                    x-transition.opacity.duration.150ms
                    :style="`top:${pos.top}px; left:${pos.left}px;`"
                    class="fixed w-56 bg-white rounded-lg shadow-2xl border border-gray-200 py-2 z-[9999]">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase border-b border-gray-100 mb-1">Merk</div>
                    <a href="{{ route('admin.merk.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.merk.index') ? 'bg-primary-100 text-primary-600' : '' }}">
                        <i class="fas fa-list mr-2 w-4"></i>Daftar Merk
                    </a>
                    <a href="{{ route('admin.merk.create') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.merk.create') ? 'bg-primary-100 text-primary-600' : '' }}">
                        <i class="fas fa-plus mr-2 w-4"></i>Tambah Merk
                    </a>
                </div>
            </template>
        </div>

        <!-- Pembelian -->
        <a href="{{ route('admin.pembelian.index') }}"
            :title="sidebarCollapsed ? 'Pembelian' : ''"
            class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.pembelian.*') ? 'bg-primary-50 text-primary-600' : '' }}">
            <i class="fas fa-shopping-cart w-6 text-center"></i>
            <span :class="{ 'hidden': sidebarCollapsed }" class="ml-3">Pembelian</span>
        </a>

        <!-- Akun Pelanggan -->
        <a href="{{ route('admin.akunpelanggan.index') }}"
            :title="sidebarCollapsed ? 'Pelanggan' : ''"
            class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.akunpelanggan.*') ? 'bg-primary-50 text-primary-600' : '' }}">
            <i class="fas fa-users w-6 text-center"></i>
            <span :class="{ 'hidden': sidebarCollapsed }" class="ml-3">Pelanggan</span>
        </a>

        <!-- Pembatas -->
        <div :class="{ 'hidden': sidebarCollapsed }" class="border-t border-gray-200 my-4"></div>

        <!-- Label Section Pengaturan -->
        <div :class="{ 'hidden': sidebarCollapsed }" class="px-3 mb-2">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</span>
        </div>

        <!-- Halaman Statis -->
        <div x-data="sidebarFlyout({ open: {{ request()->routeIs('admin.tentang-kami.*') || request()->routeIs('admin.kontak.*') || request()->routeIs('admin.kebijakan-privasi.*') ? 'true' : 'false' }} })">
            <button x-ref="trigger" @click="toggle()"
                :title="sidebarCollapsed ? 'Halaman' : ''"
                class="flex items-center justify-between w-full px-3 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.tentang-kami.*') || request()->routeIs('admin.kontak.*') || request()->routeIs('admin.kebijakan-privasi.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                <div class="flex items-center">
                    <i class="fas fa-file-alt w-6 text-center"></i>
                    <span :class="{ 'hidden': sidebarCollapsed }" class="ml-3">Halaman</span>
                </div>
                <i :class="{ 'hidden': sidebarCollapsed }" class="fas fa-chevron-down text-xs transition-transform"
                    :class="{ 'rotate-180': open }"></i>
            </button>

            <div x-show="open && !sidebarCollapsed" x-collapse class="ml-9 mt-1 space-y-1">
                <a href="{{ route('admin.kontak.index') }}"
                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.kontak.*') ? 'bg-primary-100 text-primary-600' : '' }}">
                    <i class="fas fa-phone mr-2"></i>Kontak
                </a>
            </div>

            <template x-teleport="body">
                <div x-show="open && sidebarCollapsed" x-cloak
                    @click.outside="open = false"
                    @keydown.escape.window="open = false"
                    x-transition.opacity.duration.150ms
                    :style="`top:${pos.top}px; left:${pos.left}px;`"
                    class="fixed w-56 bg-white rounded-lg shadow-2xl border border-gray-200 py-2 z-[9999]">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase border-b border-gray-100 mb-1">Halaman</div>
                    <a href="{{ route('admin.kontak.index') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('admin.kontak.*') ? 'bg-primary-100 text-primary-600' : '' }}">
                        <i class="fas fa-phone mr-2 w-4"></i>Kontak
                    </a>
                </div>
            </template>
        </div>

        <!-- Metode Pembayaran -->
        <a href="{{ route('admin.metode-pembayaran.index') }}"
            :title="sidebarCollapsed ? 'Metode Pembayaran' : ''"
            class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.metode-pembayaran.*') ? 'bg-primary-50 text-primary-600' : '' }}">
            <i class="fas fa-credit-card w-6 text-center"></i>
            <span :class="{ 'hidden': sidebarCollapsed }" class="ml-3">Metode Pembayaran</span>
        </a>

        <!-- Profil -->
        <a href="{{ route('admin.profil.index') }}"
            :title="sidebarCollapsed ? 'Profil' : ''"
            class="flex items-center px-3 py-3 text-gray-700 rounded-lg hover:bg-primary-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.profil.*') ? 'bg-primary-50 text-primary-600' : '' }}">
            <i class="fas fa-user w-6 text-center"></i>
            <span :class="{ 'hidden': sidebarCollapsed }" class="ml-3">Profil</span>
        </a>
    </nav>

    <!-- Footer Sidebar -->
    <div class="border-t border-gray-200 p-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                :title="sidebarCollapsed ? 'Logout' : ''"
                class="flex items-center w-full px-3 py-3 text-gray-700 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                <i class="fas fa-sign-out-alt w-6 text-center"></i>
                <span :class="{ 'hidden': sidebarCollapsed }" class="ml-3">Logout</span>
            </button>
        </form>
    </div>
</aside>

<script>
    // Reusable Alpine component untuk dropdown sidebar dengan flyout saat collapsed
    function sidebarFlyout(initial = {}) {
        return {
            // open hanya untuk inline mode (sidebar expanded). Flyout (collapsed) selalu mulai tutup.
            open: initial.open ?? false,
            pos: { top: 0, left: 0 },
            toggle() {
                // Saat collapsed: flyout. Hanya boleh open lewat klik user, hitung posisi dulu.
                if (this.sidebarCollapsed) {
                    this.updatePosition();
                }
                this.open = !this.open;
            },
            updatePosition() {
                const trigger = this.$refs.trigger;
                if (!trigger) return;
                const rect = trigger.getBoundingClientRect();
                this.pos = {
                    top: rect.top,
                    left: rect.right + 8,
                };
            },
            init() {
                // Jika init dengan sidebar collapsed, paksa tutup (state pre-active dari route hanya untuk inline mode)
                if (this.sidebarCollapsed) {
                    this.open = false;
                }
                // Reposition saat scroll/resize agar flyout tetap menempel ke trigger
                const handler = () => { if (this.open && this.sidebarCollapsed) this.updatePosition(); };
                window.addEventListener('scroll', handler, true);
                window.addEventListener('resize', handler);
                // Tutup flyout/dropdown saat sidebar berubah mode agar state bersih
                this.$watch('sidebarCollapsed', () => { this.open = false; });
            },
        };
    }
</script>
