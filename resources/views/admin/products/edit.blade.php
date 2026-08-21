@extends('layouts.app')

@section('title', 'Edit Produk Katalog (Admin) - Desa Bojong Sawah')

@section('content')
<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 space-y-6">
            
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div>
                    <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-700">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Katalog Admin
                    </a>
                    <h1 class="text-2xl font-black text-slate-900 mt-2">Edit Produk Katalog</h1>
                    <p class="text-xs text-slate-500">Perbarui detail produk "{{ $product->name }}"</p>
                </div>
                <span class="px-3 py-1 bg-amber-100 text-amber-900 text-xs font-bold rounded-xl">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Produk
                </span>
            </div>

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
                  x-data="{ imagePreview: '{{ $product->image ? asset('storage/' . $product->image) : '' }}' }"
                  class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Select Seller / Owner -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Pilih Pemilik Toko / Penjual UMKM <span class="text-rose-500">*</span>
                    </label>
                    <select name="user_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                        @foreach($users as $u)
                            @php
                                $store = $u->umkmProfile ? $u->umkmProfile->store_name : null;
                            @endphp
                            <option value="{{ $u->id }}" {{ old('user_id', $product->user_id) == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} {{ $store ? "({$store})" : "({$u->role})" }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Product Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Produk <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" required value="{{ old('name', $product->name) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Category & Unit -->
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
                        @error('category') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Satuan Penjualan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="unit" required value="{{ old('unit', $product->unit) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                        @error('unit') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Harga Produk (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 font-bold text-sm">Rp</span>
                        <input type="number" name="price" required min="0" value="{{ old('price', $product->price) }}"
                               class="pl-10 block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                    </div>
                    @error('price') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Deskripsi Lengkap Produk <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" rows="4" required
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Foto Produk (Kosongkan jika tidak ingin merubah)
                    </label>
                    <div class="border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl p-4 text-center bg-slate-50 cursor-pointer relative"
                         @click="$refs.imgInput.click()">
                        
                        <template x-if="!imagePreview">
                            <div class="py-4 space-y-1">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400"></i>
                                <p class="text-xs font-bold text-slate-600">Pilih Foto Produk Baru</p>
                            </div>
                        </template>

                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="h-44 w-full object-cover rounded-xl shadow-sm">
                        </template>

                        <input type="file" name="image" x-ref="imgInput" accept="image/*" class="hidden"
                               @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file); }">
                    </div>
                    @error('image') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Is Active Checkbox -->
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center space-x-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                    <label for="is_active" class="text-xs font-bold text-slate-700 cursor-pointer">
                        Publikasikan produk di Katalog Publik
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-200 text-slate-700 text-xs font-bold">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs shadow-md">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>
@endsection
