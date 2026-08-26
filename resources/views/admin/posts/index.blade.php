@extends('layouts.app')

@section('title', 'Manajemen Postingan Beranda - Admin Desa Bojongsawah')

@section('content')
<div x-data="{ createModalOpen: false }" class="py-10 bg-slate-50 min-h-screen">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 border-b-4 border-emerald-500">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-3 py-1 rounded-full text-xs font-bold">
                    <i class="fa-solid fa-newspaper"></i>
                    <span>Manajemen Postingan Admin Desa</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Kelola Postingan Beranda</h1>
                <p class="text-xs sm:text-sm text-slate-300">
                    Pantau seluruh kabar usaha warga, edit atau hapus postingan, serta terbitkan pengumuman resmi Desa Bojongsawah.
                </p>
            </div>

            <div class="flex items-center space-x-3 shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-2xl text-xs font-bold transition-all border border-slate-700 flex items-center">
                    <i class="fa-solid fa-gauge-high mr-2"></i> Dashboard Admin
                </a>
                <button @click="createModalOpen = true" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl text-xs shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-bullhorn mr-2"></i> Buat Pengumuman Desa
                </button>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <form action="{{ route('admin.posts.index') }}" method="GET" class="w-full sm:w-96 flex items-center bg-white p-1.5 rounded-2xl border border-slate-200 shadow-sm">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari isi postingan / nama penulis..." 
                       class="w-full px-4 py-2 text-slate-800 text-xs font-medium focus:outline-none rounded-xl">
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-colors">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
            <p class="text-xs text-slate-500 font-semibold">Total {{ $posts->total() }} postingan terdaftar</p>
        </div>

        <!-- Table of Posts -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-black text-slate-600 uppercase tracking-wider">
                            <th class="py-4 px-6">Penulis / UMKM</th>
                            <th class="py-4 px-6">Isi Postingan</th>
                            <th class="py-4 px-4 text-center">Foto / Produk</th>
                            <th class="py-4 px-4 text-center">Komentar</th>
                            <th class="py-4 px-4 text-center">Tanggal</th>
                            <th class="py-4 px-6 text-center">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($posts as $post)
                            @php
                                $umkm = optional($post->user)->umkmProfile;
                                $authorName = $umkm ? $umkm->store_name : (optional($post->user)->name ?? 'Admin Desa');
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                
                                <!-- Author Info -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-800 text-white flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden">
                                            @if(optional($post->user)->avatar)
                                                <img src="{{ asset('storage/' . $post->user->avatar) }}" class="w-full h-full object-cover" onerror="this.style.display='none'">
                                            @else
                                                {{ strtoupper(substr($authorName, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ $authorName }}</p>
                                            <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-md uppercase {{ optional($post->user)->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800' }}">
                                                {{ optional($post->user)->role ?? 'User' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Content snippet -->
                                <td class="py-4 px-6 max-w-xs sm:max-w-md">
                                    <p class="text-xs text-slate-800 leading-relaxed line-clamp-3 font-medium">
                                        {{ $post->content }}
                                    </p>
                                </td>

                                <!-- Foto / Product Tag -->
                                <td class="py-4 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-1">
                                        @if($post->image)
                                            <img src="{{ $post->image_url }}" class="w-10 h-10 object-cover rounded-lg shadow-sm" onerror="this.onerror=null; this.src='{{ asset('images/sawah-hero.jpg') }}';">
                                        @endif
                                        @if($post->product)
                                            <span class="px-2 py-0.5 bg-amber-100 text-amber-900 text-[10px] font-bold rounded-md truncate max-w-[120px]" title="{{ $post->product->name }}">
                                                {{ $post->product->name }}
                                            </span>
                                        @endif
                                        @if(!$post->image && !$post->product)
                                            <span class="text-[10px] text-slate-400 italic">Teks saja</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Comment count -->
                                <td class="py-4 px-4 text-center font-bold text-slate-700">
                                    <span class="px-2.5 py-1 bg-slate-100 rounded-full text-xs">
                                        <i class="fa-regular fa-comment mr-1 text-slate-400"></i>{{ $post->comments->count() }}
                                    </span>
                                </td>

                                <!-- Date -->
                                <td class="py-4 px-4 text-center text-slate-500 text-[11px]">
                                    {{ $post->created_at->format('d M Y, H:i') }}
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- Edit -->
                                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 font-bold rounded-xl text-xs transition-colors">
                                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus postingan ini secara permanen?')">
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
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-folder-open text-4xl mb-2"></i>
                                    <p class="font-bold">Belum ada postingan di beranda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $posts->links() }}
            </div>
        </div>

    </div>

    <!-- Create Announcement Modal for Admin -->
    <div x-show="createModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.outside="createModalOpen = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-base text-slate-900"><i class="fa-solid fa-bullhorn text-emerald-600 mr-2"></i>Buat Pengumuman Resmi Admin Desa</h3>
                <button @click="createModalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Isi Pengumuman / Kabar Desa <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="content" rows="4" required placeholder="Tuliskan isi pengumuman atau informasi resmi dari Pemerintah Desa Bojongsawah..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Hubungkan Produk Katalog (Opsional)
                    </label>
                    <select name="product_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Tanpa Tautan Produk --</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} (Rp {{ number_format($p->price, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Unggah Foto Pengumuman (Opsional)</label>
                    <input type="file" name="image" accept="image/*"
                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
                </div>

                <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow">
                        Terbitkan Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
