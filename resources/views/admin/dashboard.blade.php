@extends('layouts.app')

@section('title', 'Dashboard Admin - Verifikasi UMKM Desa Bojongsawah')

@section('content')
<div x-data="{ 
    modalOpen: false, 
    modalTitle: '', 
    modalImage: '', 
    rejectModalOpen: false, 
    rejectActionUrl: '', 
    umkmStoreName: '' 
}" class="py-10 bg-slate-50 min-h-screen">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Dashboard Header -->
        <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 border-b-4 border-emerald-500">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-3 py-1 rounded-full text-xs font-bold">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>Panel Verifikasi Admin Desa</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Manajemen Verifikasi UMKM</h1>
                <p class="text-xs sm:text-sm text-slate-300">
                    Kelola pendaftaran pelaku UMKM Desa Bojongsawah, periksa keabsahan KTP, dan berikan akses publikasi.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.posts.index') }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs rounded-2xl shadow-lg transition-all flex items-center shrink-0">
                    <i class="fa-solid fa-newspaper mr-2"></i> Kelola Postingan
                </a>
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-2xl shadow-lg transition-all flex items-center shrink-0">
                    <i class="fa-solid fa-tags mr-2"></i> Kategori Produk
                </a>
                <img src="{{ asset('images/logo-bojongsawah.png') }}" class="h-16 w-auto bg-white p-1 rounded-2xl shadow hidden sm:block">
            </div>
        </div>

        <!-- Summary Statistics Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm text-center">
                <p class="text-xs text-slate-400 font-bold uppercase">Total UMKM</p>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_umkm'] }}</p>
            </div>
            <div class="bg-amber-50 p-4 rounded-2xl border border-amber-200 shadow-sm text-center">
                <p class="text-xs text-amber-800 font-bold uppercase">Pending</p>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-200 shadow-sm text-center">
                <p class="text-xs text-emerald-800 font-bold uppercase">Disetujui</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['approved'] }}</p>
            </div>
            <div class="bg-rose-50 p-4 rounded-2xl border border-rose-200 shadow-sm text-center">
                <p class="text-xs text-rose-800 font-bold uppercase">Ditolak</p>
                <p class="text-2xl font-black text-rose-600 mt-1">{{ $stats['rejected'] }}</p>
            </div>
            <a href="{{ route('admin.posts.index') }}" class="bg-white hover:bg-slate-50 p-4 rounded-2xl border border-slate-100 shadow-sm text-center transition-colors block">
                <p class="text-xs text-slate-400 font-bold uppercase">Post Beranda</p>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_posts'] }}</p>
            </a>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center space-x-2 border-b border-slate-200 pb-3 overflow-x-auto">
            <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $statusFilter == 'pending' ? 'bg-amber-500 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-clock mr-1"></i> Perlu Verifikasi (Pending)
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'approved']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $statusFilter == 'approved' ? 'bg-emerald-600 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-circle-check mr-1"></i> Terverifikasi (Approved)
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'rejected']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $statusFilter == 'rejected' ? 'bg-rose-600 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-circle-xmark mr-1"></i> Ditolak (Rejected)
            </a>
            <a href="{{ route('admin.dashboard', ['status' => 'all']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $statusFilter == 'all' ? 'bg-slate-900 text-white shadow' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                Semua Pendaftar
            </a>
        </div>

        <!-- Table of UMKM Applicants -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 border-b border-slate-200 text-[11px] font-black text-slate-600 uppercase tracking-wider">
                            <th class="py-4 px-4">Nama Pemilik & NIK</th>
                            <th class="py-4 px-4">Nama Usaha & Kategori</th>
                            <th class="py-4 px-4">No. WhatsApp</th>
                            <th class="py-4 px-4 text-center">Dokumen Verifikasi</th>
                            <th class="py-4 px-4 text-center">Status</th>
                            <th class="py-4 px-4 text-center">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                        @forelse($umkms as $umkm)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                
                                <!-- Owner Name & NIK -->
                                <td class="py-4 px-4">
                                    <p class="font-bold text-slate-900 text-sm">{{ $umkm->owner_name }}</p>
                                    <p class="text-[11px] text-slate-500">NIK: <span class="font-mono font-bold text-slate-700">{{ $umkm->nik }}</span></p>
                                    <p class="text-[10px] text-slate-400 mt-0.5"><i class="fa-regular fa-calendar mr-1"></i>{{ $umkm->created_at->format('d M Y, H:i') }}</p>
                                </td>

                                <!-- Store Name & Category -->
                                <td class="py-4 px-4">
                                    <p class="font-bold text-emerald-800 text-sm">{{ $umkm->store_name }}</p>
                                    <span class="inline-block mt-0.5 px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md">
                                        {{ $umkm->category }}
                                    </span>
                                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-1" title="{{ $umkm->address }}">{{ $umkm->address }}</p>
                                </td>

                                <!-- Phone WA -->
                                <td class="py-4 px-4">
                                    <a href="https://wa.me/{{ \App\Helpers\WhatsappHelper::formatPhone($umkm->phone_wa) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center space-x-1.5 text-emerald-700 hover:text-emerald-900 font-bold">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                        <span>{{ $umkm->phone_wa }}</span>
                                    </a>
                                </td>

                                <!-- Document Verification Lightbox Buttons -->
                                <td class="py-4 px-4 text-center">
                                    <div class="flex flex-col sm:flex-row items-center justify-center gap-1.5">
                                        <button @click="modalTitle = 'Foto KTP - {{ $umkm->owner_name }} (NIK: {{ $umkm->nik }})'; modalImage = '{{ asset('storage/' . $umkm->ktp_image) }}'; modalOpen = true"
                                                class="px-2.5 py-1.5 bg-sky-100 hover:bg-sky-200 text-sky-800 text-[11px] font-bold rounded-xl transition-colors">
                                            <i class="fa-solid fa-id-card mr-1"></i> Lihat KTP
                                        </button>
                                        
                                        <button @click="modalTitle = 'Foto Tempat Usaha - {{ $umkm->store_name }}'; modalImage = '{{ asset('storage/' . $umkm->business_image) }}'; modalOpen = true"
                                                class="px-2.5 py-1.5 bg-purple-100 hover:bg-purple-200 text-purple-800 text-[11px] font-bold rounded-xl transition-colors">
                                            <i class="fa-solid fa-shop mr-1"></i> Foto Usaha
                                        </button>
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-4 text-center">
                                    @if($umkm->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 uppercase">
                                            <i class="fa-solid fa-clock mr-1"></i> Pending
                                        </span>
                                    @elseif($umkm->status == 'approved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 uppercase">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-100 text-rose-800 uppercase" title="Alasan: {{ $umkm->rejection_reason }}">
                                            <i class="fa-solid fa-circle-xmark mr-1"></i> Rejected
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        @if($umkm->status != 'approved')
                                            <form action="{{ route('admin.umkm.approve', $umkm->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow text-xs transition-all">
                                                    <i class="fa-solid fa-check mr-1"></i> Setujui
                                                </button>
                                            </form>
                                        @endif

                                        @if($umkm->status != 'rejected')
                                            <button @click="rejectActionUrl = '{{ route('admin.umkm.reject', $umkm->id) }}'; umkmStoreName = '{{ $umkm->store_name }}'; rejectModalOpen = true" 
                                                    class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold rounded-xl shadow text-xs transition-all">
                                                <i class="fa-solid fa-xmark mr-1"></i> Tolak
                                            </button>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-folder-open text-4xl mb-2"></i>
                                    <p class="font-bold">Tidak ada pendaftar UMKM pada status ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $umkms->links() }}
            </div>
        </div>

    </div>

    <!-- Interactive Lightbox Modal for KTP & Business Photo View -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.outside="modalOpen = false" class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-base text-slate-900" x-text="modalTitle"></h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="bg-slate-900 rounded-2xl p-2 max-h-[500px] overflow-hidden flex items-center justify-center">
                <img :src="modalImage" class="max-h-[460px] w-auto object-contain rounded-xl">
            </div>
            <div class="text-right">
                <button @click="modalOpen = false" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold rounded-xl">
                    Tutup Preview
                </button>
            </div>
        </div>
    </div>

    <!-- Rejection Dialog Modal -->
    <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.outside="rejectModalOpen = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-base text-rose-700">Tolak Pendaftaran UMKM</h3>
                <button @click="rejectModalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl font-bold">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form :action="rejectActionUrl" method="POST" class="space-y-4">
                @csrf
                <p class="text-xs text-slate-600">
                    Berikan alasan penolakan untuk pendaftaran <span class="font-bold text-slate-900" x-text="umkmStoreName"></span>:
                </p>
                <textarea name="rejection_reason" rows="3" required
                          class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-rose-500"
                          placeholder="Contoh: Foto KTP kurang jelas / NIK tidak dapat diverifikasi..."></textarea>

                <div class="flex items-center justify-end space-x-2">
                    <button type="button" @click="rejectModalOpen = false" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-bold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow">
                        Konfirmasi Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
