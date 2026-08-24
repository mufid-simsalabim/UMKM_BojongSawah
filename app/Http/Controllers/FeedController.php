<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use App\Models\UmkmProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if (!Auth::check() || !Auth::user()->isUmkm() || !Auth::user()->isApproved()) {
            return back()->with('error', 'Hanya Pelaku UMKM terverifikasi yang dapat memposting.');
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
}
