@extends('layouts.app')

@section('title', 'Edit Produk - ' . $product->name)

@section('content')
<div class="py-10 bg-slate-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 space-y-6">
            
            <div class="border-b border-slate-100 pb-4">
                <a href="{{ route('umkm.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-700">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Dashboard
                </a>
                <h1 class="text-2xl font-black text-slate-900 mt-2">Edit Detail Produk</h1>
                <p class="text-xs text-slate-500">Perbarui harga, deskripsi, atau foto produk jualan Anda</p>
            </div>

            <form action="{{ route('umkm.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
                  x-data="{ imagePreview: '{{ $product->image ? asset('storage/' . $product->image) : null }}' }"
                  class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Produk <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" required value="{{ old('name', $product->name) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Kategori Produk <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $product->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Satuan Penjualan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="unit" required value="{{ old('unit', $product->unit) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Harga Produk (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 font-bold text-sm">Rp</span>
                        <input type="number" name="price" required min="0" value="{{ old('price', $product->price) }}"
                               class="pl-10 block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Deskripsi Lengkap Produk <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" rows="4" required
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="flex items-center space-x-2 bg-slate-50 p-3 rounded-xl border border-slate-200">
                    <input type="checkbox" name="is_active" value="1" id="is_active" {{ $product->is_active ? 'checked' : '' }} class="h-4 w-4 text-emerald-600 rounded">
                    <label for="is_active" class="text-xs font-bold text-slate-700">Tampilkan Produk Ini di Katalog Publik</label>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Ganti Foto Produk (Opsional)
                    </label>
                    <div class="border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl p-4 text-center bg-slate-50 cursor-pointer relative"
                         @click="$refs.imgInput.click()">
                        
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="h-44 w-full object-cover rounded-xl shadow-sm">
                        </template>

                        <input type="file" name="image" x-ref="imgInput" accept="image/*" class="hidden"
                               @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file); }">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('umkm.dashboard') }}" class="px-5 py-2.5 rounded-xl bg-slate-200 text-slate-700 text-xs font-bold">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>
@endsection
