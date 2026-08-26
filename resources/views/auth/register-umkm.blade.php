@extends('layouts.app')

@section('title', 'Pendaftaran UMKM Desa Bojongsawah')

@section('content')
<div class="py-10 bg-slate-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Registration Card Header -->
        <div class="bg-gradient-to-r from-emerald-800 to-primary-900 rounded-3xl p-8 text-white shadow-xl mb-8 relative overflow-hidden">
            <div class="relative z-10">
                <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold mb-3">
                    <i class="fa-solid fa-store text-amber-400"></i>
                    <span>Formulir Registrasi Mitra UMKM</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Daftarkan UMKM Anda Sekarang</h1>
                <p class="mt-2 text-sm text-emerald-100 max-w-xl">
                    Bergabunglah bersama puluhan pelaku UMKM Desa Bojongsawah. Pasarkan produk Anda di beranda sosial dan katalog marketplace dengan pesanan langsung ke WhatsApp.
                </p>
            </div>
            <!-- Decorative Shield Logo -->
            <img src="{{ asset('images/logo-bojongsawah.png') }}" class="absolute right-4 bottom-0 h-40 opacity-15 pointer-events-none transform translate-y-4">
        </div>

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 mb-6 flex items-center space-x-3 text-xs font-bold shadow-sm">
                <i class="fa-solid fa-circle-xmark text-rose-600 text-lg"></i>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Info Note Box -->
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-8 flex items-start space-x-3">
            <i class="fa-solid fa-circle-info text-amber-600 text-xl mt-0.5"></i>
            <div class="text-xs text-amber-900 space-y-1">
                <p class="font-bold">Prosedur Verifikasi Keamanan Data:</p>
                <p>Setelah mendaftar, akun UMKM Anda akan berada dalam status <span class="font-bold underline">PENDING</span>. Tim Admin Desa Bojongsawah akan memeriksa NIK dan data usaha Anda sebelum menyetujui akun.</p>
            </div>
        </div>

        <!-- Registration Form -->
        <form action="{{ route('register.umkm.submit') }}" method="POST"
              class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 sm:p-10 space-y-8">
            @csrf

            <!-- Section 1: Data Pemilik UMKM -->
            <div class="space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs">1</div>
                    <h2 class="font-bold text-base text-slate-900">Data Diri Pemilik Usaha</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Nama Lengkap (Sesuai KTP) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                               placeholder="Contoh: Budi Santoso">
                        @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            NIK (Nomor Induk Kependudukan - 16 Digit) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nik" maxlength="16" required value="{{ old('nik') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                               placeholder="3202xxxxxxxxxxxx">
                        @error('nik') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            No. WhatsApp Aktif <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="phone" required value="{{ old('phone') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                               placeholder="081234567890">
                        @error('phone') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Alamat Email (Untuk Login) <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                               placeholder="nama@gmail.com">
                        @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Kata Sandi / Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                               placeholder="Minimal 8 karakter">
                        @error('password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                               placeholder="Ulangi password">
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Usaha UMKM -->
            <div class="space-y-4">
                <div class="border-b border-slate-100 pb-3 flex items-center space-x-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs">2</div>
                    <h2 class="font-bold text-base text-slate-900">Informasi Usaha & Produk</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Nama Usaha / Toko <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="store_name" required value="{{ old('store_name') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                               placeholder="Contoh: Keripik Singkong Bojongsawah">
                        @error('store_name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Kategori Usaha <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Alamat Usaha di Desa Bojongsawah <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="address" rows="2" required
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                              placeholder="RT/RW, Kampung, Desa Bojongsawah">{{ old('address') }}</textarea>
                    @error('address') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Deskripsi Usaha & Produk Unggulan
                    </label>
                    <textarea name="description" rows="3"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                              placeholder="Jelaskan mengenai usaha Anda, keunggulan produk, dll...">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="w-full flex items-center justify-center space-x-2 py-3.5 px-6 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-base shadow-lg hover:shadow-xl transition-all">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Kirim Pendaftaran UMKM</span>
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
