<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get()->map(function ($cat) {
            $cat->products_count = Product::where('category', $cat->name)->count();
            return $cat;
        });

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori ini sudah ada.',
            'name.max' => 'Nama kategori maksimal 100 karakter.',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', "Kategori produk \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $oldName = $category->name;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori ini sudah digunakan oleh kategori lain.',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        // If category name changed, update all products referencing the old name
        if ($oldName !== $validated['name']) {
            Product::where('category', $oldName)->update([
                'category' => $validated['name']
            ]);
        }

        return back()->with('success', "Kategori produk \"{$category->name}\" berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $productCount = Product::where('category', $category->name)->count();
        if ($productCount > 0) {
            return back()->with('error', "Gagal menghapus! Masih ada {$productCount} produk yang menggunakan kategori \"{$category->name}\". Pindahkan atau hapus produk terlebih dahulu.");
        }

        $category->delete();

        return back()->with('success', "Kategori produk \"{$category->name}\" berhasil dihapus.");
    }
}
