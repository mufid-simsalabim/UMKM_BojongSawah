@extends('layouts.app')

@section('title', 'Verifikasi Email - UMKM Desa Bojongsawah')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-xl border border-slate-100 text-center space-y-6">
        
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-2xl shadow-inner">
            <i class="fa-solid fa-envelope-circle-check"></i>
        </div>

        <div class="space-y-2">
            <h2 class="text-2xl font-black text-slate-900">Verifikasi Alamat Email Anda</h2>
            <p class="text-xs text-slate-600 font-medium leading-relaxed">
                Terima kasih telah mendaftar di Portal UMKM Desa Bojongsawah! Sebelum melanjutkan, mohon verifikasi email Anda (<span class="font-bold text-slate-800">{{ Auth::user()->email }}</span>).
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold">
                <i class="fa-solid fa-check-circle mr-1"></i> Tautan verifikasi email baru telah dikirimkan / dicatat ke sistem.
            </div>
        @endif

        <!-- Quick Instant Verification Action for Effortless Local Testing -->
        <div class="p-4 bg-amber-50/80 border border-amber-200/80 rounded-2xl space-y-3">
            <div class="text-xs text-amber-900 font-bold flex items-center justify-center space-x-1.5">
                <i class="fa-solid fa-bolt text-amber-500 text-sm"></i>
                <span>Verifikasi Praktis & Cepat (Lokal)</span>
            </div>
            <p class="text-[11px] text-amber-800 leading-snug">
                Agar pengujian mudah tanpa perlu menyiapkan SMTP, Anda bisa langsung mengklik tombol verifikasi instan di bawah ini:
            </p>
            <form method="POST" action="{{ route('verification.instant') }}">
                @csrf
                <button type="submit" class="w-full py-3 px-4 bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs rounded-xl shadow-md transition-all flex items-center justify-center">
                    <i class="fa-solid fa-circle-check mr-2"></i> Verifikasi Akun Sekarang (Instan)
                </button>
            </form>
        </div>

        <div class="space-y-3 pt-1 border-t border-slate-100">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Ulang Notifikasi Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2 px-4 text-rose-600 hover:bg-rose-50 font-bold text-xs rounded-xl transition-all">
                    <i class="fa-solid fa-right-from-bracket mr-1"></i> Keluar / Ganti Akun
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
