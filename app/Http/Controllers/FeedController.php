<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use App\Models\UmkmProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user.umkmProfile', 'product', 'comments.user', 'comments.replies.user'])->orderBy('created_at', 'desc');

        if ($request->has('category') && $request->category != '') {
            $category = $request->category;
            $query->whereHas('user.umkmProfile', function($q) use ($category) {
                $q->where('category', $category);
            });
        }

        $posts = $query->paginate(10);
        $featuredProducts = Product::where('is_active', true)->with('user.umkmProfile')->latest()->take(5)->get();
        $verifiedUmkms = UmkmProfile::where('status', 'approved')->latest()->take(6)->get();

        return view('feed.index', compact('posts', 'featuredProducts', 'verifiedUmkms'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || (!($user->isUmkm() && $user->isApproved()) && !$user->isAdmin())) {
            return back()->with('error', 'Hanya Pelaku UMKM terverifikasi dan Admin yang dapat memposting.');
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
            'product_id' => ['nullable', 'exists:products,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post_images', 'public');
        }

        Post::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'] ?? null,
            'content' => $validated['content'],
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Postingan baru berhasil diterbitkan di Beranda!');
    }

    public function editPost($id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user();

        if ($user->id !== $post->user_id && !$user->isAdmin()) {
            return redirect()->route('feed.index')->with('error', 'Anda tidak memiliki hak akses untuk mengedit postingan ini.');
        }

        $products = $user->isAdmin() ? Product::where('is_active', true)->get() : $user->products;

        if ($user->isAdmin()) {
            return view('admin.posts.edit', compact('post', 'products'));
        }

        return view('umkm.posts.edit', compact('post', 'products'));
    }

    public function updatePost(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user();

        if ($user->id !== $post->user_id && !$user->isAdmin()) {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk mengedit postingan ini.');
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
            'product_id' => ['nullable', 'exists:products,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $imagePath = $post->image;

        if ($request->boolean('remove_image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $imagePath = $request->file('image')->store('post_images', 'public');
        }

        $post->update([
            'content' => $validated['content'],
            'product_id' => $validated['product_id'] ?? null,
            'image' => $imagePath,
        ]);

        if ($user->isAdmin()) {
            return redirect()->route('admin.posts.index')->with('success', 'Postingan beranda berhasil diperbarui!');
        }

        return redirect()->route('feed.index')->with('success', 'Postingan Anda berhasil diperbarui!');
    }

    public function destroyPost($id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user();

        if ($user->id !== $post->user_id && !$user->isAdmin()) {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk menghapus postingan ini.');
        }

        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('success', 'Postingan berhasil dihapus.');
    }
}
