@extends('layouts.app')

@section('title', 'Edit Postingan Beranda - UMKM Desa Bojongsawah')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-emerald-900 via-primary-800 to-slate-900 text-white rounded-3xl p-6 shadow-xl flex items-center justify-between">
        <div>
            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold text-emerald-300">
                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Postingan Beranda
            </span>
            <h1 class="text-2xl font-black mt-2">Perbarui Postingan Anda</h1>
            <p class="text-xs text-emerald-100 mt-1">Ubah deskripsi, foto produk, atau tautan produk terkait.</p>
        </div>
        <a href="{{ route('feed.index') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold backdrop-blur-md transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <!-- Alert Notifications -->
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

    <!-- Form Edit Post -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-100 space-y-6">
        <form action="{{ route('umkm.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Post Content -->
            <div>
                <label for="content" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Isi Deskripsi Postingan <span class="text-rose-500">*</span>
                </label>
                <textarea name="content" id="content" rows="5" required
                          class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white leading-relaxed"
                          placeholder="Tuliskan deskripsi produk, promosi, atau update terbaru usaha Anda...">{{ old('content', $post->content) }}</textarea>
            </div>

            <!-- Tagged Product -->
            <div>
                <label for="product_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Hubungkan Produk Katalog (Opsional)
                </label>
                <select name="product_id" id="product_id" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    <option value="">-- Tanpa Tautan Produk --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ old('product_id', $post->product_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} (Rp {{ number_format($p->price, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Current Image & Upload New Image -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Foto Postingan</label>
                
                @if($post->image)
                    <div class="mb-3 p-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('storage/' . $post->image) }}" class="h-16 w-16 object-cover rounded-xl shadow-sm" onerror="this.src='{{ asset('images/sawah-hero.jpg') }}';">
                            <div>
                                <p class="text-xs font-bold text-slate-800">Foto Saat Ini</p>
                                <p class="text-[10px] text-slate-400">Pilih "Hapus Foto" di bawah jika ingin menghilangkan foto.</p>
                            </div>
                        </div>
                        <label class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-rose-100 text-rose-700 hover:bg-rose-200 rounded-xl text-xs font-bold cursor-pointer transition-colors">
                            <input type="checkbox" name="remove_image" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                            <span>Hapus Foto</span>
                        </label>
                    </div>
                @endif

                <div x-data="{ imagePreview: null }">
                    <input type="file" name="image" accept="image/*"
                           @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file); }"
                           class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
                    <p class="text-[10px] text-slate-400 font-medium mt-1">Format: JPG, PNG, WEBP. Maks 3MB.</p>

                    <template x-if="imagePreview">
                        <div class="mt-3 relative">
                            <p class="text-xs font-bold text-slate-700 mb-1">Pratinjau Foto Baru:</p>
                            <img :src="imagePreview" class="h-44 w-full object-cover rounded-2xl border border-slate-200">
                        </div>
                    </template>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('feed.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md transition-all">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Perubahan Postingan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
