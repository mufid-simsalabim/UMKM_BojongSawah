@extends('layouts.app')

@section('title', 'Masuk Akun - UMKM Desa Bojongsawah')

@section('content')
<div class="py-12 bg-slate-50 min-h-[calc(100vh-200px)] flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl shadow-xl border border-slate-100">
        
        <!-- Header -->
        <div class="text-center">
            <img src="{{ asset('images/logo-bojongsawah.png') }}" alt="Logo Desa Bojongsawah" class="h-16 w-auto mx-auto mb-3">
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Masuk ke Sistem</h2>
            <p class="text-xs text-slate-500 mt-1">Social Commerce UMKM Desa Bojongsawah</p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('login.submit') }}" method="POST">
            @csrf
            
            <div class="space-y-4 rounded-md">
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               value="{{ old('email') }}"
                               class="pl-10 block w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all" 
                               placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Kata Sandi / Password
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                               class="pl-10 block w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white transition-all" 
                               placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="text-rose-600 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                    <label for="remember" class="ml-2 block text-xs text-slate-600 font-medium">
                        Ingat Saya
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-primary-800 hover:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk Sekarang
                </button>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-100 text-center space-y-3">
            <p class="text-xs text-slate-600 font-medium">
                Belum memiliki akun warga?
                <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:underline">
                    Daftar Akun Pengguna
                </a>
            </p>
            <p class="text-xs text-slate-600 font-medium">
                Ingin mendaftarkan toko UMKM?
                <a href="{{ route('register.umkm') }}" class="font-bold text-amber-600 hover:underline">
                    Daftar UMKM Baru
                </a>
            </p>
        </div>

    </div>
</div>
@endsection
