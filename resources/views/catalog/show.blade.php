@extends('layouts.app')

@section('title', $product->name . ' - UMKM Desa Bojongsawah')

@section('content')
@php
    $umkm = optional($product->user)->umkmProfile;
    $storeName = $umkm ? $umkm->store_name : (optional($product->user)->name ?? 'UMKM Bojongsawah');
    $phoneWA = ($umkm->phone_wa ?? null) ?: (optional($product->user)->phone ?: \App\Helpers\WhatsappHelper::getAdminPhone());
    $storeAddress = $umkm ? $umkm->address : 'Desa Bojongsawah';
    $waOrderUrl = \App\Helpers\WhatsappHelper::makeProductOrderUrl($phoneWA, $storeName, $product->name, $product->price);
@endphp

<div class="py-10 bg-slate-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Breadcrumb Navigation -->
        <nav class="flex text-xs font-semibold text-slate-500 space-x-2">
            <a href="{{ route('feed.index') }}" class="hover:text-emerald-700">Beranda</a>
            <span>/</span>
            <a href="{{ route('catalog.index') }}" class="hover:text-emerald-700">Katalog Produk</a>
            <span>/</span>
            <span class="text-slate-800 font-bold truncate max-w-xs">{{ $product->name }}</span>
        </nav>

        <!-- Main Product Card -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-12 gap-0">
            
            <!-- Left: Image Section (Cols 6) -->
            <div class="md:col-span-6 bg-slate-100 p-6 flex items-center justify-center min-h-[350px]">
                <img src="{{ $product->image_url ?: asset('images/sawah-hero.jpg') }}" 
                     alt="{{ $product->name }}" 
                     onerror="this.onerror=null; this.src='{{ asset('images/sawah-hero.jpg') }}';"
                     class="max-h-[420px] w-full object-contain rounded-2xl shadow-md">
            </div>

            <!-- Right: Product Details (Cols 6) -->
            <div class="md:col-span-6 p-8 flex flex-col justify-between space-y-6">
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full uppercase">
                            {{ $product->category }}
                        </span>
                        <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">
                            Tersedia
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Price Box -->
                    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-baseline justify-between">
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase">Harga per {{ $product->unit }}</p>
                            <p class="text-3xl font-black text-emerald-700 mt-0.5">{{ $product->formatted_price }}</p>
                        </div>
                        <span class="text-xs text-emerald-800 font-bold">
                            <i class="fa-solid fa-shield-halved mr-1"></i> Langsung dari Pembuat
                        </span>
                    </div>

                    <!-- Description -->
                    <div class="space-y-1">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Deskripsi Produk</h3>
                        <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line font-medium">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- UMKM Store Info Box -->
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-800 text-white flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($storeName, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">{{ $storeName }}</h4>
                                <p class="text-xs text-slate-500"><i class="fa-solid fa-location-dot text-emerald-600 mr-1"></i>{{ $storeAddress }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button: Order via WhatsApp -->
                <div>
                    <a href="{{ $waOrderUrl }}" 
                       target="_blank"
                       class="w-full flex items-center justify-center space-x-3 py-4 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-base shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-0.5">
                        <i class="fa-brands fa-whatsapp text-2xl"></i>
                        <span>Pesan Produk Ini via WhatsApp</span>
                    </a>
                </div>

            </div>

        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->count() > 0)
            <div class="space-y-4 pt-6">
                <h3 class="text-xl font-bold text-slate-900">Produk Serupa di Kategori {{ $product->category }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $rp)
                        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow space-y-3">
                            <img src="{{ $rp->image ? asset('storage/' . $rp->image) : asset('images/sawah-hero.jpg') }}" onerror="this.onerror=null; this.src='{{ asset('images/sawah-hero.jpg') }}';" class="h-36 w-full object-cover rounded-xl">
                            <h4 class="font-bold text-sm text-slate-800 truncate">{{ $rp->name }}</h4>
                            <p class="text-sm font-black text-emerald-600">{{ $rp->formatted_price }}</p>
                            <a href="{{ route('catalog.show', $rp->id) }}" class="block text-center py-2 bg-slate-100 hover:bg-emerald-100 hover:text-emerald-800 text-xs font-bold rounded-xl transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
