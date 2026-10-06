<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['publishedMaterials'])->get();
        $recentMaterials = Material::with(['category', 'creator'])
            ->where('status', 'published')
            ->latest('approved_at')
            ->take(6)
            ->get();

        $stats = [
            'total_materials' => Material::where('status', 'published')->count(),
            'total_categories' => Category::count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_creators' => User::where('role', 'pembuat_materi')->count(),
        ];

        return view('public.home', compact('categories', 'recentMaterials', 'stats'));
    }

    public function materials(Request $request)
    {
        $query = Material::with(['category', 'creator'])
            ->where('status', 'published');

        if ($request->filled('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $materials = $query->latest('approved_at')->paginate(9)->withQueryString();
        $categories = Category::withCount(['publishedMaterials'])->get();

        return view('public.materials', compact('materials', 'categories'));
    }

    public function preview($slug)
    {
        $material = Material::with(['category', 'creator', 'contents', 'questions'])
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedMaterials = Material::where('category_id', $material->category_id)
            ->where('id', '!=', $material->id)
            ->where('status', 'published')
            ->take(3)
            ->get();

        return view('public.preview', compact('material', 'relatedMaterials'));
    }

    public function categories()
    {
        $categories = Category::withCount(['publishedMaterials'])->get();
        return view('public.categories', compact('categories'));
    }

    public function about()
    {
        return view('public.about');
    }
}
