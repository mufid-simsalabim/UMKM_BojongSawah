@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru - Admin Desa Bojongsawah')

@section('content')
<div x-data="{ selectedRole: 'user' }" class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    
    <!-- Page Header -->
    <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl flex items-center justify-between border-b-4 border-emerald-500">
        <div>
            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-bold">
                <i class="fa-solid fa-user-plus mr-1"></i> Tambah Pengguna Baru
            </span>
            <h1 class="text-2xl font-black mt-2">Registrasikan Akun Baru</h1>
            <p class="text-xs text-slate-300 mt-1">Buat akun untuk Warga biasa, Pelaku UMKM, atau Pengelola Admin Desa.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition-all border border-slate-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke List
        </a>
    </div>

    <!-- Alert Errors -->
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold space-y-1">
            <p class="font-extrabold text-sm"><i class="fa-solid fa-circle-xmark mr-1"></i> Gagal menyimpan akun baru:</p>
            <ul class="list-disc list-inside space-y-0.5 font-semibold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Create User -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-100 space-y-6">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Role Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Pilih Role / Hak Akses Akun <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-3 gap-3">
                    <label @click="selectedRole = 'user'" class="p-3 border-2 rounded-2xl cursor-pointer flex flex-col items-center justify-center space-y-1 transition-all"
                           :class="selectedRole === 'user' ? 'border-sky-500 bg-sky-50 text-sky-900 font-bold' : 'border-slate-200 text-slate-600'">
                        <input type="radio" name="role" value="user" x-model="selectedRole" class="sr-only">
                        <i class="fa-solid fa-user text-lg"></i>
                        <span class="text-xs">Warga (User)</span>
                    </label>

                    <label @click="selectedRole = 'umkm'" class="p-3 border-2 rounded-2xl cursor-pointer flex flex-col items-center justify-center space-y-1 transition-all"
                           :class="selectedRole === 'umkm' ? 'border-emerald-500 bg-emerald-50 text-emerald-900 font-bold' : 'border-slate-200 text-slate-600'">
                        <input type="radio" name="role" value="umkm" x-model="selectedRole" class="sr-only">
                        <i class="fa-solid fa-store text-lg"></i>
                        <span class="text-xs">Pelaku UMKM</span>
                    </label>

                    <label @click="selectedRole = 'admin'" class="p-3 border-2 rounded-2xl cursor-pointer flex flex-col items-center justify-center space-y-1 transition-all"
                           :class="selectedRole === 'admin' ? 'border-purple-500 bg-purple-50 text-purple-900 font-bold' : 'border-slate-200 text-slate-600'">
                        <input type="radio" name="role" value="admin" x-model="selectedRole" class="sr-only">
                        <i class="fa-solid fa-user-shield text-lg"></i>
                        <span class="text-xs">Admin Desa</span>
                    </label>
                </div>
            </div>

            <!-- General Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                           placeholder="Contoh: M Abdul Hadi Mufid">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                           placeholder="contoh@gmail.com">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">No. Telepon / WA <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                           placeholder="08123456789">
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Awal Akun <span class="text-rose-500">*</span></label>
                    <select name="status" id="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-emerald-500">
                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Aktif (Approved)</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Ditangguhkan (Suspended)</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kata Sandi (Password) <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                           placeholder="Minimal 8 karakter">
                </div>
            </div>

            <!-- Additional UMKM Fields (If UMKM Role selected) -->
            <div x-show="selectedRole === 'umkm'" x-cloak class="pt-4 border-t border-slate-100 space-y-4">
                <h3 class="font-extrabold text-sm text-emerald-800 flex items-center">
                    <i class="fa-solid fa-store mr-2"></i> Detail Profil Toko UMKM
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="store_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Toko UMKM <span class="text-rose-500">*</span></label>
                        <input type="text" name="store_name" id="store_name" value="{{ old('store_name') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold"
                               placeholder="Contoh: Keripik Singkong Mufid">
                    </div>

                    <div>
                        <label for="nik" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">NIK Pemilik (16 Digit) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik') }}" maxlength="16"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold font-mono"
                               placeholder="3202xxxxxxxxxxxx">
                    </div>

                    <div>
                        <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori Usaha <span class="text-rose-500">*</span></label>
                        <select name="category" id="category" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Usaha / RT RW <span class="text-rose-500">*</span></label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold"
                               placeholder="Kp. Bojongsawah RT 01/RW 02">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md transition-all">
                    <i class="fa-solid fa-user-plus mr-1"></i> Buat Akun Pengguna
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
