<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('materials')->latest()->paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:30'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? 'bi-bookmark-star',
            'color' => $validated['color'] ?? 'primary',
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Topik/Kategori attitude berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:30'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? 'bi-bookmark-star',
            'color' => $validated['color'] ?? 'primary',
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori attitude berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = Category::withCount('materials')->findOrFail($id);

        if ($category->materials_count > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh materi.');
        }

        $category->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori attitude berhasil dihapus.');
    }
}
