<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->withCount('progress');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);

        $totalPublishedMaterials = Material::where('status', 'published')->count();
        $progresses = Progress::where('user_id', $user->id)
            ->with(['material.category'])
            ->latest('updated_at')
            ->get();

        $completedCount = $progresses->where('status', 'completed')->count();
        $avgScore = $progresses->where('status', 'completed')->avg('score') ?? 0;

        return view('admin.users.show', compact('user', 'progresses', 'totalPublishedMaterials', 'completedCount', 'avgScore'));
    }

    public function toggleStatus($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $newStatus = ($user->status === 'active') ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        $statusLabel = ($newStatus === 'active') ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun user {$user->name} berhasil {$statusLabel}.");
    }
}
