@extends('layouts.app')

@section('title', 'Beranda Social Feed - UMKM Desa Bojongsawah')

@section('content')
<!-- Hero Village Banner Section -->
<div class="relative bg-slate-900 text-white overflow-hidden">
    <!-- Hero Rice Field Background Image Overlay -->
    <img src="{{ asset('images/sawah-hero.jpg') }}" alt="Sawah Desa Bojongsawah" class="absolute inset-0 w-full h-full object-cover opacity-35 filter brightness-90">
    <div class="absolute inset-0 bg-gradient-to-r from-emerald-950/90 via-emerald-900/80 to-slate-950/90"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <div class="lg:col-span-8 space-y-4">
                <div class="inline-flex items-center space-x-2 bg-emerald-500/20 border border-emerald-400/30 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-emerald-300">
                    <img src="{{ asset('images/logo-bojongsawah.png') }}" class="h-4 w-auto">
                    <span>Social Commerce Desa Bojongsawah</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                    Maju Bersama UMKM <br><span class="text-emerald-400">Desa Bojongsawah</span>
                </h1>
                <p class="text-sm sm:text-base text-slate-200 max-w-2xl font-medium leading-relaxed">
                    Jelajahi karya, hasil tani, dan sajian kuliner khas warga Desa Bojongsawah. Pesan produk berkualitas langsung ke kontak WhatsApp pelaku usaha tanpa perantara.
                </p>
                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="{{ route('catalog.index') }}" class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-sm shadow-lg hover:shadow-xl transition-all flex items-center">
                        <i class="fa-solid fa-store mr-2"></i> Lihat Katalog Produk
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="px-5 py-3 rounded-2xl bg-emerald-700/80 hover:bg-emerald-600 text-white font-bold text-sm backdrop-blur-md border border-emerald-500/30 transition-all flex items-center">
                            <i class="fa-solid fa-user-plus mr-2"></i> Daftar Sebagai Warga
                        </a>
                        <a href="{{ route('register.umkm') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm backdrop-blur-md border border-white/20 transition-all flex items-center">
                            <i class="fa-solid fa-store mr-2"></i> Daftar UMKM
                        </a>
                    @endguest
                </div>
            </div>

            <!-- Slogan Card Badge -->
            <div class="lg:col-span-4 hidden lg:block">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-3xl space-y-3">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo-bojongsawah.png') }}" class="h-12 w-auto">
                        <div>
                            <p class="text-xs text-amber-400 font-bold uppercase">Semboyan Desa</p>
                            <p class="text-lg font-black text-white">See Kasep</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-300">
                        <span class="font-bold text-emerald-300">KASEP:</span> Kreatif, Agamis, Sehat, Edukatif, Produktif. Memperkuat ekonomi desa melalui digitalisasi UMKM.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Main Feed & Sidebar Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Category Filter Bar -->
    <div class="mb-8 overflow-x-auto pb-2 scrollbar-none">
        <div class="flex items-center space-x-2 min-w-max">
            <a href="{{ route('feed.index') }}" 
               class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ !request('category') ? 'bg-primary-800 text-white shadow-md' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                <i class="fa-solid fa-border-all mr-1.5"></i> Semua Kategori
            </a>
            @foreach(['Kuliner & Olahan', 'Pertanian & Peternakan', 'Kerajinan & Kriya', 'Jasa & Perdagangan'] as $cat)
                <a href="{{ route('feed.index', ['category' => $cat]) }}" 
                   class="px-4 py-2 rounded-2xl text-xs font-bold transition-all {{ request('category') == $cat ? 'bg-primary-800 text-white shadow-md' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Feed Timeline (Cols 8) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- UMKM Post Creator Widget (If Logged-in & Approved UMKM) -->
            @auth
                @if(Auth::user()->isUmkm() && Auth::user()->isApproved())
                    <div x-data="{ openComposer: false, imagePreview: null }" class="bg-white rounded-3xl shadow-md border border-slate-100 p-5 space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-sm overflow-hidden">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <button @click="openComposer = true" class="flex-1 text-left px-4 py-3 bg-slate-100 hover:bg-slate-200/80 rounded-2xl text-slate-500 text-sm font-medium transition-colors">
                                Apa produk atau kabar UMKM yang ingin Anda bagikan hari ini, {{ Auth::user()->name }}?
                            </button>
                        </div>

                        <!-- Expanded Composer Form -->
                        <div x-show="openComposer" x-cloak x-transition class="pt-4 border-t border-slate-100 space-y-4">
                            <form action="{{ route('feed.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <textarea name="content" rows="3" required
                                          class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:bg-white"
                                          placeholder="Tuliskan deskripsi produk, promosi, atau update terbaru usaha Anda..."></textarea>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                                    <!-- Tagged Product (Optional) -->
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1">Hubungkan Produk Katalog (Opsional)</label>
                                        <select name="product_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold">
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach(Auth::user()->products as $p)
                                                <option value="{{ $p->id }}">{{ $p->name }} (Rp {{ number_format($p->price, 0, ',', '.') }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Upload Image -->
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1">Unggah Foto Produk/Postingan</label>
                                        <input type="file" name="image" accept="image/*"
                                               @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file); }"
                                               class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
                                    </div>
                                </div>

                                <template x-if="imagePreview">
                                    <div class="mt-3 relative">
                                        <img :src="imagePreview" class="h-44 w-full object-cover rounded-2xl">
                                    </div>
                                </template>

                                <div class="mt-4 flex items-center justify-end space-x-2">
                                    <button type="button" @click="openComposer = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-100">Batal</button>
                                    <button type="submit" class="px-5 py-2 rounded-xl bg-primary-800 hover:bg-primary-900 text-white font-extrabold text-xs shadow-md">
                                        <i class="fa-solid fa-paper-plane mr-1"></i> Terbitkan Postingan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Posts List Feed Timeline -->
            @forelse($posts as $post)
                @php
                    $umkm = optional($post->user)->umkmProfile;
                    $storeName = $umkm ? $umkm->store_name : (optional($post->user)->name ?? 'UMKM Bojongsawah');
                    $phoneWA = ($umkm->phone_wa ?? null) ?: (optional($post->user)->phone ?: \App\Helpers\WhatsappHelper::getAdminPhone());
                    $waLink = \App\Helpers\WhatsappHelper::makePostInquiryUrl($phoneWA, $storeName, $post->content);
                @endphp

                <article x-data="{ showComments: false }" class="bg-white rounded-3xl shadow-md border border-slate-100 overflow-hidden transition-all hover:shadow-lg">
                    
                    <!-- Post Header -->
                    <div class="p-5 flex items-center justify-between border-b border-slate-50">
                        <div class="flex items-center space-x-3">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-600 to-primary-800 text-white flex items-center justify-center font-black text-base shadow-sm overflow-hidden">
                                @if(optional($post->user)->avatar)
                                    <img src="{{ asset('storage/' . $post->user->avatar) }}" class="w-full h-full object-cover" onerror="this.style.display='none'">
                                @else
                                    {{ strtoupper(substr($storeName, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="font-bold text-slate-900 text-base leading-tight">{{ $storeName }}</h3>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-circle-check text-[9px] mr-1"></i> Verified UMKM
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">
                                    <i class="fa-regular fa-clock mr-1"></i> {{ $post->created_at->diffForHumans() }}
                                    @if($umkm)
                                        • <span class="text-slate-600 font-semibold">{{ $umkm->category }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Admin Moderation Action -->
                        @auth
                            @if(Auth::user()->isAdmin())
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus postingan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 p-2 rounded-xl transition-colors" title="Hapus Postingan">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>

                    <!-- Post Body Text -->
                    <div class="p-5 text-sm text-slate-800 leading-relaxed space-y-3">
                        <p class="whitespace-pre-line">{{ $post->content }}</p>

                        <!-- Tagged Product Details (If linked) -->
                        @if($post->product)
                            <div class="mt-3 p-3 bg-emerald-50/70 border border-emerald-100 rounded-2xl flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    @if($post->product->image)
                                        <img src="{{ asset('storage/' . $post->product->image) }}" class="h-12 w-12 object-cover rounded-xl shadow-sm" onerror="this.style.display='none'">
                                    @endif
                                    <div>
                                        <p class="text-xs text-slate-500 font-bold uppercase">Produk Terkait</p>
                                        <p class="text-sm font-bold text-slate-900">{{ $post->product->name }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 rounded-xl bg-emerald-600 text-white font-black text-xs">
                                    {{ $post->product->formatted_price }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Post Image Display -->
                    @if($post->image)
                        <div class="bg-slate-100 max-h-[450px] overflow-hidden">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Foto Post" class="w-full h-auto object-cover max-h-[450px] hover:scale-[1.01] transition-transform duration-300" onerror="this.onerror=null; this.src='{{ asset('images/sawah-hero.jpg') }}';">
                        </div>
                    @endif

                    <!-- Interactive Action Bar (Comment, WhatsApp Order) -->
                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                        
                        <div class="flex items-center space-x-2">
                            <!-- Comment Button Toggle -->
                            <button @click="showComments = !showComments" 
                                    class="inline-flex items-center space-x-1.5 px-3 py-2 bg-white hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all">
                                <i class="fa-regular fa-comment text-sm text-slate-400"></i>
                                <span>{{ $post->comments->count() }} Komentar</span>
                            </button>
                        </div>

                        <!-- WhatsApp Click-to-Chat Button -->
                        <a href="{{ $waLink }}" 
                           target="_blank"
                           class="inline-flex items-center space-x-2 px-4 py-2 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                            <span>Pesan via WhatsApp</span>
                        </a>

                    </div>

                    <!-- Comment Section (Collapsible) -->
                    <div x-show="showComments" x-cloak x-transition x-data="{ replyToId: null, replyToName: '' }" class="p-5 bg-slate-100/70 border-t border-slate-200 space-y-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                            Komentar Warga ({{ $post->comments->count() }})
                        </h4>

                        <!-- Existing Comments List -->
                        <div class="space-y-4">
                            @forelse($post->comments->whereNull('parent_id') as $comment)
                                <div class="space-y-2">
                                    <!-- Main Comment Box -->
                                    <div class="p-3.5 bg-white rounded-2xl border border-slate-200/80 shadow-2xs space-y-2">
                                        <div class="flex items-start justify-between space-x-3">
                                            <div class="flex items-start space-x-3">
                                                <div class="w-8 h-8 rounded-full bg-slate-700 text-white flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden mt-0.5">
                                                    @if(optional($comment->user)->avatar)
                                                        <img src="{{ asset('storage/' . $comment->user->avatar) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ strtoupper(substr(optional($comment->user)->name ?? 'W', 0, 1)) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-xs font-bold text-slate-900">{{ optional($comment->user)->name }}</span>
                                                        <span class="text-[10px] text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-xs text-slate-700 mt-1 whitespace-pre-line">{{ $comment->content }}</p>
                                                </div>
                                            </div>

                                            <!-- Delete Main Comment Button -->
                                            @auth
                                                @if(Auth::id() === $comment->user_id || Auth::user()->isAdmin())
                                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-slate-400 hover:text-rose-500 text-xs p-1" title="Hapus komentar">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>

                                        <!-- Reply Action Button -->
                                        @auth
                                            <div class="pl-11 pt-1 flex items-center space-x-3 text-[11px]">
                                                <button @click="replyToId = (replyToId === {{ $comment->id }} ? null : {{ $comment->id }}); replyToName = '{{ addslashes(optional($comment->user)->name ?? 'User') }}'" 
                                                        class="text-emerald-700 hover:text-emerald-900 font-bold flex items-center space-x-1">
                                                    <i class="fa-solid fa-reply text-[10px]"></i>
                                                    <span>Balas</span>
                                                </button>
                                                @if($comment->replies->count() > 0)
                                                    <span class="text-slate-400">• {{ $comment->replies->count() }} balasan</span>
                                                @endif
                                            </div>
                                        @endauth
                                    </div>

                                    <!-- Nested Replies List (Facebook Style Indented Threads) -->
                                    @if($comment->replies->count() > 0)
                                        <div class="ml-6 sm:ml-9 space-y-2 border-l-2 border-slate-300 pl-3">
                                            @foreach($comment->replies as $reply)
                                                <div class="p-3 bg-white/90 rounded-2xl border border-slate-200/60 shadow-2xs flex items-start justify-between space-x-3">
                                                    <div class="flex items-start space-x-2.5">
                                                        <div class="w-7 h-7 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-[11px] shrink-0 overflow-hidden mt-0.5">
                                                            @if(optional($reply->user)->avatar)
                                                                <img src="{{ asset('storage/' . $reply->user->avatar) }}" class="w-full h-full object-cover">
                                                            @else
                                                                {{ strtoupper(substr(optional($reply->user)->name ?? 'W', 0, 1)) }}
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="flex items-center space-x-2">
                                                                <span class="text-xs font-bold text-slate-900">{{ optional($reply->user)->name }}</span>
                                                                <span class="text-[10px] text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <p class="text-xs text-slate-700 mt-0.5 whitespace-pre-line">{{ $reply->content }}</p>
                                                        </div>
                                                    </div>

                                                    <!-- Delete Reply Button -->
                                                    @auth
                                                        @if(Auth::id() === $reply->user_id || Auth::user()->isAdmin())
                                                            <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" onsubmit="return confirm('Hapus balasan ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-slate-400 hover:text-rose-500 text-xs p-1" title="Hapus balasan">
                                                                    <i class="fa-solid fa-xmark"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endauth
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Inline Reply Form (Shown when Balas is clicked) -->
                                    @auth
                                        <div x-show="replyToId === {{ $comment->id }}" x-cloak x-transition class="ml-6 sm:ml-9 mt-2 p-3 bg-emerald-50/80 rounded-2xl border border-emerald-200 space-y-2">
                                            <div class="flex items-center justify-between text-xs text-emerald-800 font-bold">
                                                <span><i class="fa-solid fa-reply mr-1"></i> Membalas <span x-text="replyToName"></span></span>
                                                <button type="button" @click="replyToId = null" class="text-slate-400 hover:text-rose-600">
                                                    <i class="fa-solid fa-xmark"></i> Batal
                                                </button>
                                            </div>
                                            <form action="{{ route('posts.comment', $post->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                <div class="flex items-center space-x-2">
                                                    <input type="text" name="content" required :placeholder="'Tulis balasan untuk ' + replyToName + '...'" 
                                                           class="flex-1 px-3 py-2 bg-white border border-emerald-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                                                    <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm">
                                                        Balas
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endauth
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 font-medium italic text-center py-2">Belum ada komentar. Jadilah yang pertama memberikan masukan!</p>
                            @endforelse
                        </div>

                        <!-- General Comment Input Form (Top level) -->
                        @auth
                            <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="pt-2 border-t border-slate-200">
                                @csrf
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="content" required placeholder="Tuliskan komentar utama Anda..." 
                                           class="flex-1 px-4 py-2.5 bg-white border border-slate-300 rounded-2xl text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                                    <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-2xl shadow-sm">
                                        Kirim
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="p-3 bg-slate-200/60 rounded-2xl text-center text-xs text-slate-600 font-medium">
                                Silakan <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:underline">Masuk</a> atau <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:underline">Daftar Akun Warga</a> untuk menuliskan komentar.
                            </div>
                        @endauth
                    </div>

                </article>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 space-y-3">
                    <i class="fa-solid fa-newspaper text-4xl text-slate-300"></i>
                    <h3 class="text-lg font-bold text-slate-700">Belum Ada Postingan Beranda</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto">
                        Jadilah yang pertama untuk membagikan produk atau informasi UMKM Desa Bojongsawah.
                    </p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="pt-4">
                {{ $posts->links() }}
            </div>

        </div>

        <!-- Right Sidebar (Cols 4) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Widget 1: Featured Products Mini Marketplace -->
            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-sm text-slate-900 flex items-center">
                        <i class="fa-solid fa-fire text-amber-500 mr-2"></i> Produk Unggulan Desa
                    </h3>
                    <a href="{{ route('catalog.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">Lihat Semua</a>
                </div>

                <div class="space-y-3">
                    @foreach($featuredProducts as $fp)
                        @php
                            $fpStore = optional($fp->user)->umkmProfile ? optional($fp->user)->umkmProfile->store_name : 'UMKM Bojongsawah';
                            $fpPhone = optional(optional($fp->user)->umkmProfile)->phone_wa ?: (optional($fp->user)->phone ?: \App\Helpers\WhatsappHelper::getAdminPhone());
                            $fpWa = \App\Helpers\WhatsappHelper::makeProductOrderUrl($fpPhone, $fpStore, $fp->name, $fp->price);
                        @endphp
                        <div class="flex items-center space-x-3 p-2 hover:bg-slate-50 rounded-2xl transition-colors">
                            <img src="{{ $fp->image ? asset('storage/' . $fp->image) : asset('images/sawah-hero.jpg') }}" onerror="this.onerror=null; this.src='{{ asset('images/sawah-hero.jpg') }}';" class="w-14 h-14 object-cover rounded-xl shadow-sm">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-slate-800 truncate">{{ $fp->name }}</h4>
                                <p class="text-[11px] text-slate-400 truncate">{{ $fpStore }}</p>
                                <p class="text-xs font-black text-emerald-600">{{ $fp->formatted_price }}</p>
                            </div>
                            <a href="{{ $fpWa }}" target="_blank" class="p-2 rounded-xl bg-emerald-100 text-emerald-800 hover:bg-emerald-600 hover:text-white transition-colors" title="Pesan via WA">
                                <i class="fa-brands fa-whatsapp text-base"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Widget 2: List of Verified UMKM Stores -->
            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-sm text-slate-900 flex items-center">
                        <i class="fa-solid fa-store text-emerald-600 mr-2"></i> Mitra UMKM Terverifikasi
                    </h3>
                </div>

                <div class="space-y-3">
                    @foreach($verifiedUmkms as $vUmkm)
                        <div class="flex items-center space-x-3 p-2 bg-slate-50/70 rounded-2xl">
                            <div class="w-10 h-10 rounded-xl bg-emerald-800 text-white flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($vUmkm->store_name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-slate-800 truncate">{{ $vUmkm->store_name }}</h4>
                                <p class="text-[10px] text-slate-400 uppercase font-semibold">{{ $vUmkm->category }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Widget 3: Village Office Banner & Call -->
            <div class="bg-gradient-to-br from-slate-900 to-emerald-950 text-white rounded-3xl p-6 space-y-4 shadow-lg">
                <img src="{{ asset('images/kantor-desa.jpg') }}" class="w-full h-32 object-cover rounded-2xl opacity-90">
                <div>
                    <h4 class="font-bold text-sm text-white">Ingin Mendaftarkan Usaha Anda?</h4>
                    <p class="text-xs text-slate-300 mt-1">Dapatkan fasilitas promosi gratis dan langsung terhubung dengan pembeli.</p>
                </div>
                <a href="{{ route('register.umkm') }}" class="block w-full text-center py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-extrabold rounded-xl shadow transition-all">
                    Daftar UMKM Bojongsawah
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
