<header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            
            <!-- Brand Logo & Title -->
            <a href="{{ route('feed.index') }}" class="flex items-center space-x-3 group">
                <img src="{{ asset('images/logo-bojongsawah.png') }}" alt="Logo Desa Bojong Sawah" class="h-12 w-auto object-contain transition-transform group-hover:scale-105">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="font-extrabold text-lg md:text-xl text-slate-900 tracking-tight">UMKM Bojong Sawah</span>
                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Social Commerce</span>
                    </div>
                    <p class="text-xs text-slate-500 font-medium hidden sm:block">Desa Bojong Sawah • See Kasep</p>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                <a href="{{ route('feed.index') }}" 
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('feed.index') ? 'bg-primary-800 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-newspaper mr-2"></i>Beranda Feed
                </a>
                <a href="{{ route('catalog.index') }}" 
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('catalog.*') ? 'bg-primary-800 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-store mr-2"></i>Katalog Produk
                </a>
            </nav>

            <!-- User Auth Controls -->
            <div class="hidden md:flex items-center space-x-3">
                @guest
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-primary-800 hover:text-primary-900 transition-colors">
                        <i class="fa-solid fa-right-to-bracket mr-1"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-3.5 py-2 text-xs font-bold text-emerald-800 bg-emerald-100 hover:bg-emerald-200 rounded-xl transition-all flex items-center">
                        <i class="fa-solid fa-user-plus mr-1.5"></i> Daftar Warga
                    </a>
                    <a href="{{ route('register.umkm') }}" class="px-3.5 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-sm hover:shadow transition-all flex items-center">
                        <i class="fa-solid fa-store mr-1.5"></i> Daftar UMKM
                    </a>
                @endguest

                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg bg-purple-100 text-purple-900 text-xs font-bold hover:bg-purple-200 transition-colors">
                            <i class="fa-solid fa-user-shield mr-1"></i> Admin Panel
                        </a>
                    @elseif(Auth::user()->isUmkm())
                        <a href="{{ route('umkm.dashboard') }}" class="px-3 py-2 rounded-lg bg-emerald-100 text-emerald-900 text-xs font-bold hover:bg-emerald-200 transition-colors">
                            <i class="fa-solid fa-shop mr-1"></i> Dashboard Toko
                        </a>
                        <a href="{{ route('umkm.products.create') }}" class="px-3 py-2 rounded-lg bg-amber-500 text-white text-xs font-bold hover:bg-amber-600 shadow-sm transition-all">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Produk
                        </a>
                    @endif

                    <!-- Profile Dropdown -->
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 p-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-primary-800 text-white flex items-center justify-center font-bold text-sm overflow-hidden">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <span class="text-sm font-semibold text-slate-700 max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                        </button>

                        <div x-show="dropdownOpen" 
                             @click.outside="dropdownOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                            
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400 font-medium">Masuk sebagai</p>
                                <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold rounded-full uppercase {{ Auth::user()->isAdmin() ? 'bg-purple-100 text-purple-800' : (Auth::user()->isUmkm() ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>

                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 font-medium">
                                    <i class="fa-solid fa-gauge-high mr-2 text-slate-400"></i> Dashboard Admin
                                </a>
                                <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 font-medium">
                                    <i class="fa-solid fa-tags mr-2 text-slate-400"></i> Kategori Produk Admin
                                </a>
                                <a href="{{ route('admin.products.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 font-medium">
                                    <i class="fa-solid fa-boxes-stacked mr-2 text-slate-400"></i> Katalog Produk Admin
                                </a>
                            @elseif(Auth::user()->isUmkm())
                                <a href="{{ route('umkm.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 font-medium">
                                    <i class="fa-solid fa-store mr-2 text-slate-400"></i> Dashboard UMKM
                                </a>
                            @endif

                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 font-medium">
                                <i class="fa-solid fa-user-gear mr-2 text-slate-400"></i> Pengaturan Profil
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium flex items-center">
                                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none">
                    <i x-show="!mobileMenuOpen" class="fa-solid fa-bars text-xl"></i>
                    <i x-show="mobileMenuOpen" class="fa-solid fa-xmark text-xl" x-cloak></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div x-show="mobileMenuOpen" x-cloak x-transition class="md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-3">
        <a href="{{ route('feed.index') }}" class="block px-3 py-2 rounded-xl text-base font-semibold {{ request()->routeIs('feed.index') ? 'bg-primary-800 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
            <i class="fa-solid fa-newspaper mr-2"></i> Beranda Feed
        </a>
        <a href="{{ route('catalog.index') }}" class="block px-3 py-2 rounded-xl text-base font-semibold {{ request()->routeIs('catalog.*') ? 'bg-primary-800 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
            <i class="fa-solid fa-store mr-2"></i> Katalog Produk
        </a>

        @guest
            <div class="pt-3 border-t border-slate-100 space-y-2">
                <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 rounded-xl border border-slate-300 font-bold text-slate-700">
                    <i class="fa-solid fa-right-to-bracket mr-1"></i> Masuk Akun
                </a>
                <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2.5 rounded-xl bg-emerald-100 text-emerald-800 font-bold">
                    <i class="fa-solid fa-user-plus mr-1"></i> Daftar Akun Warga
                </a>
                <a href="{{ route('register.umkm') }}" class="block w-full text-center px-4 py-2.5 rounded-xl bg-amber-600 text-white font-bold shadow-sm">
                    <i class="fa-solid fa-store mr-1"></i> Daftar UMKM Sekarang
                </a>
            </div>
        @endguest

        @auth
            <div class="pt-3 border-t border-slate-100 space-y-2">
                <div class="px-3 py-2 bg-slate-50 rounded-xl">
                    <p class="text-xs text-slate-400">Masuk sebagai</p>
                    <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }} ({{ strtoupper(Auth::user()->role) }})</p>
                </div>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-xl bg-purple-100 text-purple-900 font-bold">
                        <i class="fa-solid fa-user-shield mr-2"></i> Dashboard Admin
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded-xl bg-slate-100 text-slate-800 font-bold">
                        <i class="fa-solid fa-tags mr-2"></i> Kategori Produk Admin
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="block px-3 py-2 rounded-xl bg-slate-100 text-slate-800 font-bold">
                        <i class="fa-solid fa-boxes-stacked mr-2"></i> Katalog Produk Admin
                    </a>
                @elseif(Auth::user()->isUmkm())
                    <a href="{{ route('umkm.dashboard') }}" class="block px-3 py-2 rounded-xl bg-emerald-100 text-emerald-900 font-bold">
                        <i class="fa-solid fa-shop mr-2"></i> Dashboard UMKM
                    </a>
                    <a href="{{ route('umkm.products.create') }}" class="block px-3 py-2 rounded-xl bg-amber-500 text-white font-bold">
                        <i class="fa-solid fa-plus mr-2"></i> Tambah Produk Baru
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-xl bg-slate-100 text-slate-800 font-bold">
                    <i class="fa-solid fa-user-gear mr-2"></i> Pengaturan Profil
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-xl text-rose-600 font-bold hover:bg-rose-50">
                        <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar
                    </button>
                </form>
            </div>
        @endauth
    </div>
</header>
