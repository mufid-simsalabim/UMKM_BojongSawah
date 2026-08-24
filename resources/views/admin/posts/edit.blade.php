@extends('layouts.app')

@section('title', 'Edit Postingan Admin - Desa Bojongsawah')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    
    <!-- Page Header -->
    <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl flex items-center justify-between border-b-4 border-emerald-500">
        <div>
            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-bold">
                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Postingan Admin
            </span>
            <h1 class="text-2xl font-black mt-2">Edit Postingan / Moderasi</h1>
            <p class="text-xs text-slate-300 mt-1">Perbarui isi pesan, produk terkait, atau foto postingan ini.</p>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition-all border border-slate-700">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke List
        </a>
    </div>

    <!-- Form Edit Post Admin -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-md border border-slate-100 space-y-6">
        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Post Content -->
            <div>
                <label for="content" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Isi Deskripsi Postingan <span class="text-rose-500">*</span>
                </label>
                <textarea name="content" id="content" rows="5" required
                          class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white leading-relaxed">{{ old('content', $post->content) }}</textarea>
            </div>

            <!-- Tagged Product -->
            <div>
                <label for="product_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Hubungkan Produk Katalog (Opsional)
                </label>
                <select name="product_id" id="product_id" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-500">
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
                                <p class="text-xs font-bold text-slate-800">Foto saat ini</p>
                                <p class="text-[10px] text-slate-400">Centang opsi hapus jika ingin membuang foto.</p>
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
                    
                    <template x-if="imagePreview">
                        <div class="mt-3">
                            <p class="text-xs font-bold text-slate-700 mb-1">Pratinjau Foto Baru:</p>
                            <img :src="imagePreview" class="h-44 w-full object-cover rounded-2xl border border-slate-200">
                        </div>
                    </template>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.posts.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs shadow-md transition-all">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Perubahan Admin
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
