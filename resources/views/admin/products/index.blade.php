@extends('layouts.app')

@section('title', 'Manajemen Katalog Produk - Admin Desa Bojong Sawah')

@section('content')
<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 border-b-4 border-emerald-500">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-3 py-1 rounded-full text-xs font-bold">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Manajemen Katalog Admin</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Katalog Produk UMKM Desa</h1>
                <p class="text-xs sm:text-sm text-slate-300">
                    Kelola seluruh katalog produk lokal Desa Bojong Sawah, tambah produk baru, edit harga/informasi, dan atur status publikasi.
                </p>
            </div>

            <div class="flex items-center space-x-3 shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-2xl text-xs font-bold transition-all border border-slate-700 flex items-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Verifikasi UMKM
                </a>
                <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl text-xs shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Produk Baru
                </a>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <form action="{{ route('admin.products.index') }}" method="GET" class="w-full flex flex-col md:flex-row items-center gap-3">
                
                <!-- Search Input -->
                <div class="relative flex-1 w-full">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama produk atau deskripsi..."
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                </div>

                <!-- Category Filter -->
                <select name="category" class="w-full md:w-48 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                    <option value="all">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <!-- Status Filter -->
                <select name="status" class="w-full md:w-36 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-aktif</option>
                </select>

                <!-- Filter Button -->
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-all shrink-0">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>

                @if(request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('admin.products.index') }}" class="w-full md:w-auto text-center px-3 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-xs font-bold transition-all shrink-0">
                        <i class="fa-solid fa-xmark mr-1"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Products List Table -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-black text-slate-600 uppercase tracking-wider">
                            <th class="py-4 px-4">Produk</th>
                            <th class="py-4 px-4">Penjual / Toko UMKM</th>
                            <th class="py-4 px-4">Harga & Satuan</th>
                            <th class="py-4 px-4 text-center">Status</th>
                            <th class="py-4 px-4 text-center">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($products as $product)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                
                                <!-- Product Info -->
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/sawah-hero.jpg') }}" 
                                             class="w-14 h-14 object-cover rounded-2xl border border-slate-200 shrink-0">
                                        <div>
                                            <a href="{{ route('catalog.show', $product->id) }}" target="_blank" class="font-bold text-slate-900 text-sm hover:text-emerald-700 transition-colors line-clamp-1">
                                                {{ $product->name }}
                                            </a>
                                            <span class="inline-block mt-0.5 px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md">
                                                {{ $product->category }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Store / Seller Info -->
                                <td class="py-4 px-4">
                                    @php
                                        $umkm = optional($product->user)->umkmProfile;
                                    @endphp
                                    <p class="font-bold text-emerald-800 text-xs">{{ $umkm ? $umkm->store_name : optional($product->user)->name }}</p>
                                    <p class="text-[11px] text-slate-400">Pemilik: {{ optional($product->user)->name }}</p>
                                </td>

                                <!-- Price & Unit -->
                                <td class="py-4 px-4">
                                    <p class="font-extrabold text-slate-900 text-sm">{{ $product->formatted_price }}</p>
                                    <p class="text-[11px] text-slate-400">per {{ $product->unit }}</p>
                                </td>

                                <!-- Status Badge & Toggle -->
                                <td class="py-4 px-4 text-center">
                                    <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                title="Klik untuk mengubah status produk"
                                                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase transition-all shadow-2xs {{ $product->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-200 text-slate-600 hover:bg-slate-300' }}">
                                            <i class="fa-solid fa-circle {{ $product->is_active ? 'text-emerald-500 mr-1.5' : 'text-slate-400 mr-1.5' }} text-[8px]"></i>
                                            <span>{{ $product->is_active ? 'Aktif' : 'Non-aktif' }}</span>
                                        </button>
                                    </form>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                           class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 font-bold rounded-xl text-xs transition-colors">
                                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                        </a>

                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini dari Katalog?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 text-rose-700 font-bold rounded-xl text-xs transition-colors">
                                                <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-boxes-packing text-4xl mb-2"></i>
                                    <p class="font-bold text-sm">Tidak ada produk katalog yang sesuai dengan filter.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        </div>

    </div>
</div>
@endsection
