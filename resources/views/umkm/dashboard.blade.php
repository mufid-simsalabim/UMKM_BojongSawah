@extends('layouts.app')

@section('title', 'Dashboard Toko - ' . $profile->store_name)

@section('content')
<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Store Header Banner -->
        <div class="bg-gradient-to-r from-emerald-800 to-primary-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 border-b-4 border-amber-500">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-white text-emerald-900 flex items-center justify-center font-black text-2xl shadow">
                    {{ strtoupper(substr($profile->store_name, 0, 1)) }}
                </div>
                <div class="space-y-1">
                    <div class="flex items-center space-x-2">
                        <h1 class="text-2xl font-black">{{ $profile->store_name }}</h1>
                        <span class="bg-emerald-500/20 text-emerald-300 text-xs font-bold px-2.5 py-0.5 rounded-full border border-emerald-400/30">
                            <i class="fa-solid fa-circle-check text-emerald-400 mr-1"></i> Terverifikasi
                        </span>
                    </div>
                    <p class="text-xs text-emerald-100 font-medium">Pemilik: {{ $profile->owner_name }} • WA: {{ $profile->phone_wa }}</p>
                    <p class="text-xs text-emerald-200"><i class="fa-solid fa-location-dot mr-1"></i>{{ $profile->address }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('umkm.products.create') }}" class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Produk Baru
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-box"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase">Total Produk</p>
                    <p class="text-2xl font-black text-slate-800">{{ $stats['total_products'] }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-sky-100 text-sky-800 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase">Produk Aktif Tampil</p>
                    <p class="text-2xl font-black text-sky-700">{{ $stats['active_products'] }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-800 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase">Postingan Beranda</p>
                    <p class="text-2xl font-black text-purple-700">{{ $stats['total_posts'] }}</p>
                </div>
            </div>
        </div>

        <!-- Section 1: Daftar Produk Toko -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden space-y-4">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-extrabold text-lg text-slate-900">Kelola Produk Katalog Toko</h2>
                    <p class="text-xs text-slate-500">Daftar produk yang saat ini dapat diorder oleh pembeli via WhatsApp</p>
                </div>
                <a href="{{ route('umkm.products.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow">
                    <i class="fa-solid fa-plus mr-1"></i> Tambah Produk
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-black text-slate-600 uppercase">
                            <th class="py-3.5 px-6">Foto & Nama Produk</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Harga / Satuan</th>
                            <th class="py-3.5 px-4 text-center">Status Tampil</th>
                            <th class="py-3.5 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($products as $p)
                            <tr class="hover:bg-slate-50">
                                <td class="py-4 px-6 flex items-center space-x-3">
                                    <img src="{{ $p->image ? asset('storage/' . $p->image) : asset('images/sawah-hero.jpg') }}" class="w-12 h-12 object-cover rounded-xl shadow-sm">
                                    <div>
                                        <p class="font-bold text-slate-900 text-sm">{{ $p->name }}</p>
                                        <p class="text-[11px] text-slate-400 line-clamp-1">{{ Str::limit($p->description, 50) }}</p>
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[10px] font-bold rounded-full">
                                        {{ $p->category }}
                                    </span>
                                </td>

                                <td class="py-4 px-4 font-extrabold text-emerald-700 text-sm">
                                    {{ $p->formatted_price }} <span class="text-xs font-normal text-slate-400">/ {{ $p->unit }}</span>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if($p->is_active)
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full">Aktif</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-200 text-slate-600 text-[10px] font-bold rounded-full">Non-Aktif</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('umkm.products.edit', $p->id) }}" class="p-2 bg-sky-100 hover:bg-sky-200 text-sky-800 rounded-xl transition-colors" title="Edit Produk">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('umkm.products.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-100 hover:bg-rose-200 text-rose-800 rounded-xl transition-colors" title="Hapus Produk">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-400">
                                    Belum ada produk. Klik "Tambah Produk" untuk memasukkan produk jualan Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 2: Postingan Beranda Toko -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="font-extrabold text-lg text-slate-900">Postingan Beranda Toko Anda</h2>
                <a href="{{ route('feed.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">
                    Buat Postingan Baru di Beranda Feed <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($posts as $post)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex items-start space-x-3">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="w-16 h-16 object-cover rounded-xl shadow-sm">
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-slate-400 font-bold"><i class="fa-regular fa-clock mr-1"></i>{{ $post->created_at->diffForHumans() }}</p>
                            <p class="text-xs text-slate-800 line-clamp-2 mt-1">{{ $post->content }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic">Belum ada postingan di beranda feed.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
