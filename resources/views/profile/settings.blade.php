@extends('layouts.app')

@section('title', 'Pengaturan Profil - UMKM Desa Bojongsawah')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    
    <!-- Page Title Header -->
    <div class="bg-gradient-to-r from-emerald-900 via-primary-800 to-slate-900 text-white rounded-3xl p-8 shadow-xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center font-black text-2xl border border-white/30 overflow-hidden shadow-inner">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black">{{ $user->name }}</h1>
                    <p class="text-xs text-emerald-200 font-medium mt-1">
                        <i class="fa-solid fa-envelope mr-1"></i> {{ $user->email }}
                        • <span class="uppercase font-bold text-amber-300">{{ $user->role }}</span>
                    </p>
                </div>
            </div>
            <a href="{{ route('feed.index') }}" class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold backdrop-blur-md transition-all self-start md:self-auto">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center">
            <i class="fa-solid fa-circle-check text-base mr-2 text-emerald-600"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl text-xs font-bold flex items-center">
            <i class="fa-solid fa-triangle-exclamation text-base mr-2 text-amber-600"></i>
            {{ session('warning') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold space-y-1">
            <p class="font-extrabold text-sm"><i class="fa-solid fa-circle-xmark mr-1"></i> Terdapat kesalahan pada input Anda:</p>
            <ul class="list-disc list-inside space-y-0.5 font-semibold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Profile Details & Avatar Form (Cols 7) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-100 space-y-6">
            <div class="border-b border-slate-100 pb-4 flex items-center space-x-2">
                <i class="fa-solid fa-user-gear text-emerald-600 text-lg"></i>
                <h2 class="font-extrabold text-lg text-slate-900">Ubah Detail Profil</h2>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Avatar Upload Field -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Foto Profil (Avatar)</label>
                    <div class="flex items-center space-x-4" x-data="{ avatarPreview: null }">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-xl overflow-hidden border border-slate-200">
                            <template x-if="avatarPreview">
                                <img :src="avatarPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!avatarPreview">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                @endif
                            </template>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="avatar" accept="image/*"
                                   @change="const file = $event.target.files[0]; if (file) { avatarPreview = URL.createObjectURL(file); }"
                                   class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
                            <p class="text-[10px] text-slate-400 font-medium mt-1">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                        </div>
                    </div>
                </div>

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                </div>

                <!-- Phone Field -->
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">Nomor Handphone / WhatsApp</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                           placeholder="081234567890">
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    <p class="text-[10px] text-slate-400 font-medium mt-1">*Jika Anda mengubah email, akun akan memerlukan verifikasi ulang.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all flex items-center justify-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Form (Cols 5) -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-100 space-y-6">
            <div class="border-b border-slate-100 pb-4 flex items-center space-x-2">
                <i class="fa-solid fa-key text-amber-500 text-lg"></i>
                <h2 class="font-extrabold text-lg text-slate-900">Ubah Kata Sandi</h2>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" required
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:bg-white"
                           placeholder="Ketik kata sandi lama">
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:bg-white"
                           placeholder="Minimal 8 karakter">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-amber-500 focus:bg-white"
                           placeholder="Ketik ulang kata sandi baru">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved mr-2"></i> Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
