@extends('layouts.app')

@section('title', 'Pemberitahuan Saya - UMKM Desa Bojongsawah')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    
    <div class="bg-gradient-to-r from-emerald-900 via-primary-800 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold text-emerald-300">
                <i class="fa-solid fa-bell mr-1"></i> Kotak Masuk Pemberitahuan
            </span>
            <h1 class="text-2xl sm:text-3xl font-black mt-2">Pemberitahuan Saya</h1>
            <p class="text-xs text-emerald-100 font-medium mt-1">
                Dapatkan informasi update komentar, balasan, dan status verifikasi UMKM Anda.
            </p>
        </div>

        @if(Auth::user()->unreadNotificationsCount() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all flex items-center shrink-0">
                    <i class="fa-solid fa-check-double mr-2"></i> Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-3xl shadow-md border border-slate-100 overflow-hidden divide-y divide-slate-100">
        @forelse($notifications as $notif)
            <div class="p-5 flex items-start justify-between space-x-4 transition-colors {{ $notif->is_read ? 'bg-white' : 'bg-emerald-50/50' }}">
                <div class="flex items-start space-x-3.5">
                    <div class="w-10 h-10 rounded-2xl {{ $notif->is_read ? 'bg-slate-100 text-slate-500' : 'bg-emerald-600 text-white shadow-md' }} flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                        <i class="fa-solid {{ str_contains($notif->title, 'Disetujui') ? 'fa-circle-check text-amber-300' : (str_contains($notif->title, 'Ditolak') ? 'fa-circle-xmark text-rose-300' : 'fa-comment-dots') }}"></i>
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <h3 class="font-bold text-slate-900 text-sm leading-tight">{{ $notif->title }}</h3>
                            @if(!$notif->is_read)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-500 text-white uppercase">Baru</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed font-medium">{{ $notif->message }}</p>
                        <p class="text-[11px] text-slate-400"><i class="fa-regular fa-clock mr-1"></i>{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2 shrink-0">
                    @if($notif->url)
                        <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3.5 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold rounded-xl text-xs transition-colors">
                                Lihat <i class="fa-solid fa-arrow-right ml-1"></i>
                            </button>
                        </form>
                    @elseif(!$notif->is_read)
                        <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs transition-colors">
                                Tandai Dibaca
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-slate-400 space-y-3">
                <i class="fa-regular fa-bell-slash text-4xl"></i>
                <p class="font-bold text-sm">Belum ada pemberitahuan.</p>
                <p class="text-xs text-slate-400">Semua aktivitas dan informasi terbaru akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div>
        {{ $notifications->links() }}
    </div>

</div>
@endsection
