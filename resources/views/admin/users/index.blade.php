@extends('layouts.app')

@section('title', 'Manajemen Pengguna Warga & UMKM - Admin Desa Bojongsawah')

@section('content')
<div x-data="{ 
    suspendModalOpen: false, 
    suspendActionUrl: '', 
    userNameToSuspend: '' 
}" class="py-10 bg-slate-50 min-h-screen">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 border-b-4 border-emerald-500">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-3 py-1 rounded-full text-xs font-bold">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Manajemen Pengguna & Penangguhan Akun</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Kelola Akun Warga & UMKM</h1>
                <p class="text-xs sm:text-sm text-slate-300">
                    Kelola data pengguna, perbarui hak akses, buat akun baru, serta lakukan penangguhan (*suspend*) akun warga & UMKM.
                </p>
            </div>

            <div class="flex items-center space-x-3 shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-2xl text-xs font-bold transition-all border border-slate-700 flex items-center">
                    <i class="fa-solid fa-gauge-high mr-2"></i> Dashboard Admin
                </a>
                <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl text-xs shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-user-plus mr-2"></i> Tambah Pengguna Baru
                </a>
            </div>
        </div>

        <!-- Summary Statistics Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <a href="{{ route('admin.users.index', ['role' => 'all']) }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm text-center hover:bg-slate-50 transition-colors">
                <p class="text-xs text-slate-400 font-bold uppercase">Total Pengguna</p>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_users'] }}</p>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'warga']) }}" class="bg-sky-50 p-4 rounded-2xl border border-sky-200 shadow-sm text-center hover:bg-sky-100/80 transition-colors">
                <p class="text-xs text-sky-800 font-bold uppercase">Warga (User)</p>
                <p class="text-2xl font-black text-sky-700 mt-1">{{ $stats['total_warga'] }}</p>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'umkm']) }}" class="bg-emerald-50 p-4 rounded-2xl border border-emerald-200 shadow-sm text-center hover:bg-emerald-100/80 transition-colors">
                <p class="text-xs text-emerald-800 font-bold uppercase">Mitra UMKM</p>
                <p class="text-2xl font-black text-emerald-700 mt-1">{{ $stats['total_umkm'] }}</p>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'suspended']) }}" class="bg-rose-50 p-4 rounded-2xl border border-rose-200 shadow-sm text-center hover:bg-rose-100/80 transition-colors">
                <p class="text-xs text-rose-800 font-bold uppercase">Ditangguhkan</p>
                <p class="text-2xl font-black text-rose-600 mt-1">{{ $stats['total_suspended'] }}</p>
            </a>
            <div class="bg-amber-50 p-4 rounded-2xl border border-amber-200 shadow-sm text-center">
                <p class="text-xs text-amber-800 font-bold uppercase">Pending UMKM</p>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ $stats['total_pending'] }}</p>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Filter Tabs -->
            <div class="flex items-center space-x-2 overflow-x-auto w-full sm:w-auto pb-2 sm:pb-0">
                <a href="{{ route('admin.users.index', ['role' => 'all', 'search' => request('search')]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $roleFilter == 'all' ? 'bg-slate-900 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                    Semua Role
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'warga', 'search' => request('search')]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $roleFilter == 'warga' ? 'bg-sky-600 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-user mr-1"></i> Warga
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'umkm', 'search' => request('search')]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $roleFilter == 'umkm' ? 'bg-emerald-600 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-store mr-1"></i> UMKM
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'suspended', 'search' => request('search')]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $roleFilter == 'suspended' ? 'bg-rose-600 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-ban mr-1"></i> Ditangguhkan
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'admin', 'search' => request('search')]) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $roleFilter == 'admin' ? 'bg-purple-600 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                    <i class="fa-solid fa-user-shield mr-1"></i> Admin
                </a>
            </div>

            <!-- Search Input -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="w-full sm:w-80 flex items-center bg-white p-1.5 rounded-2xl border border-slate-200 shadow-sm">
                <input type="hidden" name="role" value="{{ $roleFilter }}">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama, email, HP, toko, NIK..." 
                       class="w-full px-3.5 py-1.5 text-slate-800 text-xs font-medium focus:outline-none rounded-xl">
                <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-colors">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>

        <!-- Table of Users -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-black text-slate-600 uppercase tracking-wider">
                            <th class="py-4 px-6">Pengguna & Kontak</th>
                            <th class="py-4 px-6">Usaha / NIK (Jika UMKM)</th>
                            <th class="py-4 px-4 text-center">Role Hak Akses</th>
                            <th class="py-4 px-4 text-center">Status Akun</th>
                            <th class="py-4 px-4 text-center">Tanggal Daftar</th>
                            <th class="py-4 px-6 text-center">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($users as $u)
                            @php
                                $umkm = $u->umkmProfile;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                
                                <!-- User Info -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-2xl bg-slate-800 text-white flex items-center justify-center font-bold text-sm shrink-0 overflow-hidden shadow-sm">
                                            @if($u->avatar)
                                                <img src="{{ $u->avatar_url }}" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm flex items-center">
                                                {{ $u->name }}
                                                @if($u->id === Auth::id())
                                                    <span class="ml-2 px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-md">(Saya)</span>
                                                @endif
                                            </p>
                                            <p class="text-[11px] text-slate-500">{{ $u->email }}</p>
                                            <p class="text-[10px] text-slate-400 font-semibold"><i class="fa-solid fa-phone mr-1"></i>{{ $u->phone }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- UMKM Info -->
                                <td class="py-4 px-6">
                                    @if($umkm)
                                        <p class="font-bold text-emerald-800 text-sm">{{ $umkm->store_name }}</p>
                                        <p class="text-[11px] text-slate-500">NIK: <span class="font-mono font-bold">{{ $umkm->nik }}</span></p>
                                        <span class="inline-block mt-0.5 px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md">
                                            {{ $umkm->category }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic text-xs">Akun Pengguna Warga</span>
                                    @endif
                                </td>

                                <!-- Role Badge -->
                                <td class="py-4 px-4 text-center">
                                    @if($u->isAdmin())
                                        <span class="px-2.5 py-1 bg-purple-100 text-purple-900 text-[10px] font-black rounded-full uppercase">
                                            <i class="fa-solid fa-user-shield mr-1"></i> Admin
                                        </span>
                                    @elseif($u->isUmkm())
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-900 text-[10px] font-black rounded-full uppercase">
                                            <i class="fa-solid fa-store mr-1"></i> UMKM
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-sky-100 text-sky-900 text-[10px] font-black rounded-full uppercase">
                                            <i class="fa-solid fa-user mr-1"></i> Warga
                                        </span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-4 text-center">
                                    @if($u->status === 'suspended')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-100 text-rose-800 uppercase shadow-sm">
                                            <i class="fa-solid fa-ban mr-1"></i> Ditangguhkan
                                        </span>
                                    @elseif($u->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 uppercase">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Aktif
                                        </span>
                                    @elseif($u->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 uppercase">
                                            <i class="fa-solid fa-clock mr-1"></i> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-slate-200 text-slate-700 uppercase">
                                            {{ $u->status }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Date -->
                                <td class="py-4 px-4 text-center text-slate-500 text-[11px]">
                                    {{ $u->created_at->format('d M Y') }}
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        
                                        <!-- Suspend / Unsuspend Button -->
                                        @if($u->id !== Auth::id())
                                            @if($u->status === 'suspended')
                                                <form action="{{ route('admin.users.unsuspend', $u->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold rounded-xl text-xs transition-colors" title="Pulihkan Akses Akun">
                                                        <i class="fa-solid fa-rotate-left mr-1"></i> Pulihkan
                                                    </button>
                                                </form>
                                            @else
                                                <button @click="suspendActionUrl = '{{ route('admin.users.suspend', $u->id) }}'; userNameToSuspend = '{{ $u->name }}'; suspendModalOpen = true"
                                                        class="px-2.5 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold rounded-xl text-xs transition-colors" title="Tangguhkan Akun (Suspend)">
                                                    <i class="fa-solid fa-ban mr-1"></i> Tangguhkan
                                                </button>
                                            @endif
                                        @endif

                                        <!-- Edit -->
                                        <a href="{{ route('admin.users.edit', $u->id) }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-colors" title="Edit Akun">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <!-- Delete -->
                                        @if($u->id !== Auth::id())
                                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini secara permanen beserta seluruh datanya?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-rose-700 font-bold rounded-xl text-xs transition-colors" title="Hapus Akun">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-users-slash text-4xl mb-2"></i>
                                    <p class="font-bold">Tidak ada pengguna pada kriteria ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>

    </div>

    <!-- Suspension Reason Dialog Modal -->
    <div x-show="suspendModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.outside="suspendModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-base text-rose-700"><i class="fa-solid fa-ban mr-2"></i>Tangguhkan Akun Pengguna</h3>
                <button @click="suspendModalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form :action="suspendActionUrl" method="POST" class="space-y-4">
                @csrf
                <p class="text-xs text-slate-600">
                    Akun <span class="font-bold text-slate-900" x-text="userNameToSuspend"></span> akan ditangguhkan (*suspended*) dan tidak dapat login ke sistem.
                </p>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alasan Penangguhan (Opsional)</label>
                    <textarea name="reason" rows="3"
                              class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-rose-500"
                              placeholder="Contoh: Pelanggaran etika publikasi, laporan penipuan, dll..."></textarea>
                </div>

                <div class="flex items-center justify-end space-x-2">
                    <button type="button" @click="suspendModalOpen = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow">
                        Konfirmasi Penangguhan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
