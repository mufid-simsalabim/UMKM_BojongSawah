<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\User;
use App\Models\UmkmProfile;
use App\Models\Product;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $statusFilter = $request->get('status', 'pending');

        $stats = [
            'total_umkm' => UmkmProfile::count(),
            'pending' => UmkmProfile::where('status', 'pending')->count(),
            'approved' => UmkmProfile::where('status', 'approved')->count(),
            'rejected' => UmkmProfile::where('status', 'rejected')->count(),
            'total_posts' => Post::count(),
        ];

        $query = UmkmProfile::with('user')->orderBy('created_at', 'desc');

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $umkms = $query->paginate(10)->withQueryString();

        return view('admin.dashboard', compact('stats', 'umkms', 'statusFilter'));
    }

    public function approve($id)
    {
        $umkm = UmkmProfile::findOrFail($id);
        $umkm->update([
            'status' => 'approved',
            'rejection_reason' => null
        ]);

        $umkm->user->update([
            'status' => 'approved'
        ]);

        Notification::create([
            'user_id' => $umkm->user_id,
            'title' => 'Pendaftaran UMKM Disetujui!',
            'message' => 'Selamat! Pendaftaran UMKM "' . $umkm->store_name . '" telah disetujui Admin. Anda kini dapat memposting produk & kabar usaha di beranda.',
            'url' => route('umkm.dashboard'),
        ]);

        return back()->with('success', "Akun UMKM \"{$umkm->store_name}\" ({$umkm->owner_name}) BERHASIL DISETUJUI. Pelaku UMKM kini sudah dapat login dan memposting produk.");
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi untuk memberikan feedback pada pendaftar.'
        ]);

        $umkm = UmkmProfile::findOrFail($id);
        $umkm->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        $umkm->user->update([
            'status' => 'rejected'
        ]);

        Notification::create([
            'user_id' => $umkm->user_id,
            'title' => 'Pendaftaran UMKM Ditolak',
            'message' => 'Pendaftaran UMKM "' . $umkm->store_name . '" belum disetujui. Alasan: ' . $request->rejection_reason,
            'url' => route('feed.index'),
        ]);

        return back()->with('info', "Pendaftaran UMKM \"{$umkm->store_name}\" telah DITOLAK.");
    }

    // Admin Post Management (CRUD)
    public function postsIndex(Request $request)
    {
        $query = Post::with(['user.umkmProfile', 'product', 'comments'])->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $posts = $query->paginate(15)->withQueryString();
        $products = Product::where('is_active', true)->get();

        return view('admin.posts.index', compact('posts', 'products'));
    }

    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
            'product_id' => ['nullable', 'exists:products,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = ImageHelper::store($request->file('image'));
        }

        Post::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'] ?? null,
            'content' => $validated['content'],
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Pengumuman / Postingan Admin berhasil diterbitkan ke Beranda!');
    }

    public function editPost($id)
    {
        $post = Post::findOrFail($id);
        $products = Product::where('is_active', true)->get();
        return view('admin.posts.edit', compact('post', 'products'));
    }

    public function updatePost(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
            'product_id' => ['nullable', 'exists:products,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $imagePath = $post->image;

        if ($request->boolean('remove_image')) {
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            $imagePath = ImageHelper::store($request->file('image'));
        }

        $post->update([
            'content' => $validated['content'],
            'product_id' => $validated['product_id'] ?? null,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Postingan beranda berhasil diperbarui oleh Admin.');
    }

    public function deletePost($id)
    {
        $post = Post::findOrFail($id);

        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('success', 'Postingan beranda berhasil dihapus oleh Admin.');
    }
}
