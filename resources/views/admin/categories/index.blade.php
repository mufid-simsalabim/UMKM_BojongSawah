@extends('layouts.app')

@section('title', 'Manajemen Kategori Produk - Admin Desa Bojong Sawah')

@section('content')
<div x-data="{ 
    createModalOpen: false, 
    editModalOpen: false, 
    editCategory: { id: null, name: '', description: '' },
    editActionUrl: '' 
}" class="py-10 bg-slate-50 min-h-screen">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 border-b-4 border-emerald-500">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-3 py-1 rounded-full text-xs font-bold">
                    <i class="fa-solid fa-tags"></i>
                    <span>Manajemen Kategori Admin</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Kategori Produk UMKM</h1>
                <p class="text-xs sm:text-sm text-slate-300">
                    Kelola daftar kategori produk untuk memudahkan pencarian warga dan pengelompokan produk UMKM Desa Bojong Sawah.
                </p>
            </div>

            <div class="flex items-center space-x-3 shrink-0">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-2xl text-xs font-bold transition-all border border-slate-700 flex items-center">
                    <i class="fa-solid fa-boxes-stacked mr-2"></i> Katalog Produk
                </a>
                <button @click="createModalOpen = true" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-2xl text-xs shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Kategori Baru
                </button>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Daftar Kategori Produk</h3>
                    <p class="text-xs text-slate-400">Total {{ $categories->count() }} kategori produk terdaftar</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-black text-slate-600 uppercase tracking-wider">
                            <th class="py-4 px-6">Nama Kategori & Slug</th>
                            <th class="py-4 px-6">Deskripsi Kategori</th>
                            <th class="py-4 px-6 text-center">Jumlah Produk</th>
                            <th class="py-4 px-6 text-center">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                
                                <!-- Name & Slug -->
                                <td class="py-4 px-6">
                                    <p class="font-bold text-slate-900 text-sm flex items-center">
                                        <i class="fa-solid fa-tag text-emerald-600 mr-2"></i>
                                        {{ $cat->name }}
                                    </p>
                                    <p class="text-[11px] text-slate-400 font-mono mt-0.5">slug: {{ $cat->slug }}</p>
                                </td>

                                <!-- Description -->
                                <td class="py-4 px-6">
                                    <p class="text-xs text-slate-600 max-w-md leading-relaxed">
                                        {{ $cat->description ?: '-' }}
                                    </p>
                                </td>

                                <!-- Product Count -->
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-box mr-1.5 text-[10px]"></i>
                                        {{ $cat->products_count }} Produk
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- Edit Button -->
                                        <button @click="editCategory = { id: {{ $cat->id }}, name: '{{ addslashes($cat->name) }}', description: '{{ addslashes($cat->description ?? '') }}' }; editActionUrl = '{{ route('admin.categories.update', $cat->id) }}'; editModalOpen = true"
                                                class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 font-bold rounded-xl text-xs transition-colors">
                                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \"{{ $cat->name }}\"?')">
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
                                <td colspan="4" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-folder-open text-4xl mb-2"></i>
                                    <p class="font-bold">Belum ada kategori produk. Klik tombol di atas untuk menambah.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Create Category Modal -->
    <div x-show="createModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.outside="createModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-base text-slate-900"><i class="fa-solid fa-plus-circle text-emerald-600 mr-2"></i>Tambah Kategori Produk Baru</h3>
                <button @click="createModalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Kategori <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="Contoh: Olahan Kayu & Bambu"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Deskripsi Singkat (Opsional)
                    </label>
                    <textarea name="description" rows="3" placeholder="Jelaskan mengenai jenis produk dalam kategori ini..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="createModalOpen = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.outside="editModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-base text-slate-900"><i class="fa-solid fa-pen-to-square text-amber-600 mr-2"></i>Edit Kategori Produk</h3>
                <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form :action="editActionUrl" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Kategori <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" x-model="editCategory.name" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Deskripsi Singkat (Opsional)
                    </label>
                    <textarea name="description" rows="3" x-model="editCategory.description"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
