<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('user.umkmProfile')->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->pluck('name')->toArray();

        return view('catalog.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::with('user.umkmProfile', 'posts')->findOrFail($id);
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }

    public function create()
    {
        if (!Auth::check() || !Auth::user()->isUmkm()) {
            return redirect()->route('login');
        }

        $categories = Category::orderBy('name')->pluck('name')->toArray();
        return view('umkm.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isUmkm()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $imagePath = $request->file('image')->store('product_images', 'public');

        $product = Product::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . time(),
            'category' => $validated['category'],
            'price' => $validated['price'],
            'unit' => $validated['unit'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'is_active' => true,
        ]);

        return redirect()->route('umkm.dashboard')->with('success', "Produk \"{$product->name}\" berhasil ditambahkan ke Katalog UMKM.");
    }

    public function edit($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        $categories = Category::orderBy('name')->pluck('name')->toArray();

        return view('umkm.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('product_images', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']) . '-' . time();
        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('umkm.dashboard')->with('success', "Produk \"{$product->name}\" berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('umkm.dashboard')->with('success', 'Produk berhasil dihapus.');
    }
}
