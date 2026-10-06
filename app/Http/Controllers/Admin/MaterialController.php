<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::with(['category', 'creator']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('category_id', $request->kategori);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('title', 'like', "%{$search}%");
        }

        $materials = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.material.index', compact('materials', 'categories'));
    }

    public function pending()
    {
        $materials = Material::where('status', 'pending')
            ->with(['category', 'creator'])
            ->latest()
            ->paginate(10);

        return view('admin.material.pending', compact('materials'));
    }

    public function show($id)
    {
        $material = Material::with(['category', 'creator', 'contents', 'questions.options'])->findOrFail($id);
        return view('admin.material.show', compact('material'));
    }

    public function approve($id)
    {
        $material = Material::findOrFail($id);

        $material->update([
            'status' => 'published',
            'approved_at' => now(),
            'revision_note' => null,
        ]);

        return redirect()->route('admin.materi.show', $material->id)
            ->with('success', "Materi '{$material->title}' berhasil disetujui dan telah dipublikasikan untuk User!");
    }

    public function revision(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $validated = $request->validate([
            'revision_note' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        $material->update([
            'status' => 'revision',
            'revision_note' => $validated['revision_note'],
        ]);

        return redirect()->route('admin.materi.show', $material->id)
            ->with('warning', "Catatan revisi berhasil dikirimkan kepada Pembuat Materi.");
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);

        if ($material->cover_image && Storage::disk('public')->exists($material->cover_image)) {
            Storage::disk('public')->delete($material->cover_image);
        }

        foreach ($material->contents as $content) {
            if ($content->file_path && Storage::disk('public')->exists($content->file_path)) {
                Storage::disk('public')->delete($content->file_path);
            }
        }

        $material->delete();

        return redirect()->route('admin.materi.index')->with('success', 'Materi berhasil dihapus.');
    }
}
