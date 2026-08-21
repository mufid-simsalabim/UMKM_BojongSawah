<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    private function getCategories(): array
    {
        return Category::orderBy('name')->pluck('name')->toArray();
    }

    public function index(Request $request)
    {
        $query = Product::with('user.umkmProfile')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = $this->getCategories();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = $this->getCategories();
        $users = User::whereIn('role', ['umkm', 'admin'])->with('umkmProfile')->get();

        return view('admin.products.create', compact('categories', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'user_id.required' => 'Pemilik produk / UMKM penjual wajib dipilih.',
            'name.required' => 'Nama produk wajib diisi.',
            'category.required' => 'Kategori produk wajib dipilih.',
            'price.required' => 'Harga produk wajib diisi.',
            'unit.required' => 'Satuan produk wajib diisi.',
            'description.required' => 'Deskripsi produk wajib diisi.',
            'image.required' => 'Foto produk wajib diunggah.',
        ]);

        $imagePath = $request->file('image')->store('product_images', 'public');

        $product = Product::create([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . time(),
            'category' => $validated['category'],
            'price' => $validated['price'],
            'unit' => $validated['unit'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.products.index')->with('success', "Produk \"{$product->name}\" berhasil ditambahkan ke Katalog.");
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = $this->getCategories();
        $users = User::whereIn('role', ['umkm', 'admin'])->with('umkmProfile')->get();

        return view('admin.products.edit', compact('product', 'categories', 'users'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('product_images', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']) . '-' . time();
        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', "Produk \"{$product->name}\" berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus dari Katalog.');
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'is_active' => !$product->is_active
        ]);

        $statusLabel = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Status produk \"{$product->name}\" berhasil {$statusLabel}.");
    }
}
