<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UmkmController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $profile = $user->umkmProfile;

        $products = Product::where('user_id', $user->id)->latest()->get();
        $posts = Post::where('user_id', $user->id)->latest()->get();

        $stats = [
            'total_products' => $products->count(),
            'active_products' => $products->where('is_active', true)->count(),
            'total_posts' => $posts->count(),
        ];

        return view('umkm.dashboard', compact('user', 'profile', 'products', 'posts', 'stats'));
    }
}
