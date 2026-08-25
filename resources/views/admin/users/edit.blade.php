@extends('layouts.app')

@section('title', 'Edit Pengguna - Admin Desa Bojongsawah')

@section('content')
<div x-data="{ selectedRole: '{{ old('role', $user->role) }}' }" class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    
    <!-- Page Header -->
    <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl flex items-center justify-between border-b-4 border-emerald-500">
        <div>
            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-bold">
                <i class="fa-solid fa-user-gear mr-1"></i> Edit Akun Pengguna
            </span>
            <h1 class="text-2xl font-black mt-2">Edit Detail: {{ $user->name }}</h1>
            <p class="text-xs text-slate-300 mt-1">Perbarui role, nomor HP, email, status akun (termasuk penangguhan), atau reset password.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition-all border border-slate-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke List
        </a>
    </div>

    <!-- Alert Errors -->
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold space-y-1">
            <p class="font-extrabold text-sm"><i class="fa-solid fa-circle-xmark mr-1"></i> Gagal memperbarui akun:</p>
            <ul class="list-disc list-inside space-y-0.5 font-semibold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Edit User -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-100 space-y-6">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Role Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Role / Hak Akses Akun <span class="text-rose-500">*</span>
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
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">No. Telepon / WA <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Akun <span class="text-rose-500">*</span></label>
                    <select name="status" id="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-emerald-500">
                        <option value="approved" {{ old('status', $user->status) == 'approved' ? 'selected' : '' }}>Aktif (Approved)</option>
                        <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ old('status', $user->status) == 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                        <option value="suspended" {{ old('status', $user->status) == 'suspended' ? 'selected' : '' }}>Ditangguhkan (Suspended)</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Reset Kata Sandi / Password Baru (Biarkan kosong jika tidak diubah)
                    </label>
                    <input type="password" name="password" id="password"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                           placeholder="Kosongkan jika tidak ingin mengganti password">
                </div>
            </div>

            <!-- Additional UMKM Fields (If UMKM Role selected or User has UMKM profile) -->
            @php
                $umkm = $user->umkmProfile;
            @endphp
            <div x-show="selectedRole === 'umkm'" x-cloak class="pt-4 border-t border-slate-100 space-y-4">
                <h3 class="font-extrabold text-sm text-emerald-800 flex items-center">
                    <i class="fa-solid fa-store mr-2"></i> Detail Toko UMKM
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="store_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Toko UMKM</label>
                        <input type="text" name="store_name" id="store_name" value="{{ old('store_name', optional($umkm)->store_name) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                    </div>

                    <div>
                        <label for="category" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori Usaha</label>
                        <select name="category" id="category" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', optional($umkm)->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Usaha / RT RW</label>
                        <input type="text" name="address" id="address" value="{{ old('address', optional($umkm)->address) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs shadow-md transition-all">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Perubahan Pengguna
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
