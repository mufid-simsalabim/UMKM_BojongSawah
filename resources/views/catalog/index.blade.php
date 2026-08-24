@extends('layouts.app')

@section('title', 'Katalog Produk UMKM Desa Bojongsawah')

@section('content')
<!-- Header Banner Section -->
<div class="bg-gradient-to-r from-emerald-800 to-primary-900 text-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center md:text-left">
            <span class="bg-white/20 text-emerald-200 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                <i class="fa-solid fa-store mr-1"></i> Toko Desa Online
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Katalog Produk UMKM Bojongsawah</h1>
            <p class="text-sm text-emerald-100 max-w-xl">
                Temukan aneka olahan makanan, hasil pertanian segar, dan produk kerajinan tangan berkualitas asli buatan warga desa.
            </p>
        </div>
        
        <!-- Quick Search Bar -->
        <form action="{{ route('catalog.index') }}" method="GET" class="w-full md:w-96 flex items-center bg-white p-1.5 rounded-2xl shadow-lg">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama produk / beras / keripik..." 
                   class="w-full px-4 py-2 text-slate-800 text-sm font-medium focus:outline-none rounded-xl">
            <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow text-sm transition-colors">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Category Tabs Filter -->
    <div class="flex items-center justify-between mb-8 overflow-x-auto pb-2 scrollbar-none">
        <div class="flex items-center space-x-2 min-w-max">
            <a href="{{ route('catalog.index') }}" 
               class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ !request('category') ? 'bg-primary-800 text-white shadow-md' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                Semua Produk
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('catalog.index', array_merge(request()->query(), ['category' => $cat])) }}" 
                   class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ request('category') == $cat ? 'bg-primary-800 text-white shadow-md' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Product Grid Marketplace Tokopedia Style -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            @php
                $umkm = optional($product->user)->umkmProfile;
                $storeName = $umkm ? $umkm->store_name : (optional($product->user)->name ?? 'UMKM Bojongsawah');
                $phoneWA = ($umkm->phone_wa ?? null) ?: (optional($product->user)->phone ?: \App\Helpers\WhatsappHelper::getAdminPhone());
                $waOrderUrl = \App\Helpers\WhatsappHelper::makeProductOrderUrl($phoneWA, $storeName, $product->name, $product->price);
            @endphp

            <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col group">
                
                <!-- Product Image & Badges -->
                <div class="relative bg-slate-100 h-52 overflow-hidden">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/sawah-hero.jpg') }}" 
                         alt="{{ $product->name }}" 
                         onerror="this.onerror=null; this.src='{{ asset('images/sawah-hero.jpg') }}';"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    
                    <span class="absolute top-3 left-3 bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">
                        {{ $product->category }}
                    </span>

                    <span class="absolute bottom-3 right-3 bg-amber-500 text-white text-xs font-black px-2.5 py-1 rounded-xl shadow-md">
                        / {{ $product->unit }}
                    </span>
                </div>

                <!-- Product Info Body -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                    
                    <div>
                        <!-- Store Info -->
                        <div class="flex items-center space-x-1.5 mb-1.5 text-xs text-slate-500 font-semibold">
                            <i class="fa-solid fa-store text-emerald-600"></i>
                            <span class="truncate">{{ $storeName }}</span>
                        </div>

                        <!-- Product Title -->
                        <a href="{{ route('catalog.show', $product->id) }}" class="font-extrabold text-slate-900 text-base leading-snug hover:text-emerald-700 transition-colors line-clamp-2">
                            {{ $product->name }}
                        </a>

                        <!-- Product Description snippet -->
                        <p class="text-xs text-slate-500 line-clamp-2 mt-1 font-medium">
                            {{ Str::limit($product->description, 70) }}
                        </p>
                    </div>

                    <!-- Price & WhatsApp Order Action -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Harga Produk</p>
                            <p class="text-lg font-black text-emerald-600">{{ $product->formatted_price }}</p>
                        </div>

                        <a href="{{ $waOrderUrl }}" 
                           target="_blank"
                           class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5"
                           title="Pesan via WhatsApp">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            <span>Pesan</span>
                        </a>
                    </div>

                </div>

            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-100 space-y-3">
                <i class="fa-solid fa-box-open text-5xl text-slate-300"></i>
                <h3 class="text-xl font-bold text-slate-700">Produk Tidak Ditemukan</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">
                    Tidak ada produk yang cocok dengan pencarian atau kategori ini.
                </p>
                <a href="{{ route('catalog.index') }}" class="inline-block px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl shadow">
                    Tampilkan Semua Produk
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $products->links() }}
    </div>

</div>
@endsection
