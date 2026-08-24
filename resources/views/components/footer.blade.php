<footer class="bg-slate-900 text-slate-300 mt-16 border-t-4 border-emerald-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            <!-- Col 1: Village Identity & Logo -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo-bojongsawah.png') }}" alt="Logo Desa Bojongsawah" class="h-12 w-auto bg-white p-1 rounded-lg">
                    <div>
                        <h3 class="text-white font-extrabold text-lg">Desa Bojongsawah</h3>
                        <p class="text-emerald-400 text-xs font-semibold">See Kasep</p>
                    </div>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Platform Social Commerce resmi untuk memajukan UMKM lokal Desa Bojongsawah. Menghubungkan produk unggulan desa langsung ke pembeli via WhatsApp.
                </p>
                <div class="inline-block bg-slate-800 border border-slate-700 px-3 py-1.5 rounded-xl text-[11px] text-emerald-300 font-semibold">
                    <i class="fa-solid fa-star text-amber-400 mr-1"></i> KASEP: Kreatif, Agamis, Sehat, Edukatif, Produktif
                </div>
            </div>

            <!-- Col 2: Navigation Links -->
            <div class="space-y-3">
                <h4 class="text-white font-bold text-sm uppercase tracking-wider border-b border-slate-800 pb-2">Navigasi Utama</h4>
                <ul class="space-y-2 text-xs font-medium">
                    <li><a href="{{ route('feed.index') }}" class="hover:text-emerald-400 transition-colors flex items-center"><i class="fa-solid fa-angle-right mr-2 text-emerald-500"></i>Beranda Feed Social</a></li>
                    <li><a href="{{ route('catalog.index') }}" class="hover:text-emerald-400 transition-colors flex items-center"><i class="fa-solid fa-angle-right mr-2 text-emerald-500"></i>Katalog Produk UMKM</a></li>
                    <li><a href="{{ route('register.umkm') }}" class="hover:text-emerald-400 transition-colors flex items-center"><i class="fa-solid fa-angle-right mr-2 text-emerald-500"></i>Pendaftaran UMKM Baru</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-emerald-400 transition-colors flex items-center"><i class="fa-solid fa-angle-right mr-2 text-emerald-500"></i>Masuk Akun UMKM / Admin</a></li>
                </ul>
            </div>

            <!-- Col 3: Village Office Image & Info -->
            <div class="space-y-3">
                <h4 class="text-white font-bold text-sm uppercase tracking-wider border-b border-slate-800 pb-2">Kantor Desa</h4>
                <div class="overflow-hidden rounded-xl border border-slate-700 group shadow-md">
                    <img src="{{ asset('images/kantor-desa.jpg') }}" alt="Kantor Desa Bojongsawah" class="w-full h-28 object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <p class="text-[11px] text-slate-400">
                    <i class="fa-solid fa-location-dot text-emerald-500 mr-1"></i> Jalan Raya Desa Bojongsawah, Kec. Kebonpedes, Kab. Sukabumi, Jawa Barat.
                </p>
            </div>

            <!-- Col 4: Contact & WhatsApp Support -->
            <div class="space-y-3">
                <h4 class="text-white font-bold text-sm uppercase tracking-wider border-b border-slate-800 pb-2">Kontak & Layanan</h4>
                <p class="text-xs text-slate-400">Punya pertanyaan seputar pendaftaran UMKM atau produk desa?</p>
                <a href="https://wa.me/{{ \App\Helpers\WhatsappHelper::getAdminPhone() }}?text=Halo%20Admin%20Desa%20Bojong%20Sawah,%20saya%20ingin%20bertanya%20seputar%20website%20UMKM." 
                   target="_blank"
                   class="inline-flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow transition-all w-full justify-center">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    <span>Hubungi Admin Desa</span>
                </a>
            </div>

        </div>

        <div class="mt-12 pt-6 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 space-y-3 md:space-y-0">
            <p>© {{ date('Y') }} Pemerintah Desa Bojongsawah. Hak Cipta Dilindungi.</p>
            <p class="flex items-center">
                Dibuat dengan <i class="fa-solid fa-heart text-rose-500 mx-1"></i> untuk Memajukan Ekonomi Warga Desa Bojongsawah
            </p>
        </div>
    </div>
</footer>
