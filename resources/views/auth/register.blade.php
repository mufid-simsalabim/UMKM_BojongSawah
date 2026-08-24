@extends('layouts.app')

@section('title', 'Registrasi Pengguna - UMKM Desa Bojongsawah')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-xl border border-slate-100 space-y-6">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <img src="{{ asset('images/logo-bojongsawah.png') }}" class="h-16 w-auto mx-auto mb-2">
            <h2 class="text-2xl font-black text-slate-900">Daftar Akun Pengguna</h2>
            <p class="text-xs text-slate-500 font-medium">Buat akun warga untuk memesan produk, menyukai postingan, dan berinteraksi di feed Desa Bojongsawah.</p>
        </div>

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white @error('name') border-rose-500 @enderror"
                       placeholder="Contoh: Ahmad Subagja">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email (Wajib Verifikasi)</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white @error('email') border-rose-500 @enderror"
                       placeholder="nama@email.com">
                @error('email')
                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white @error('phone') border-rose-500 @enderror"
                       placeholder="081234567890">
                @error('phone')
                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white @error('password') border-rose-500 @enderror"
                       placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="text-xs text-rose-500 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                       placeholder="Ketik ulang kata sandi">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm rounded-2xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center">
                <i class="fa-solid fa-user-plus mr-2"></i> Daftar Akun Pengguna
            </button>

            <!-- Links -->
            <div class="pt-4 border-t border-slate-100 text-center space-y-2">
                <p class="text-xs text-slate-600 font-medium">
                    Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:underline">Masuk disini</a>
                </p>
                <p class="text-xs text-slate-500 font-medium">
                    Ingin mendaftarkan toko UMKM? <a href="{{ route('register.umkm') }}" class="font-bold text-amber-600 hover:underline">Daftar sebagai UMKM</a>
                </p>
            </div>
        </form>

    </div>
</div>
@endsection
