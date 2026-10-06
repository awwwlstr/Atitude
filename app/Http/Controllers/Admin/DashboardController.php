<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use App\Models\Progress;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalPembuat = User::where('role', 'pembuat_materi')->count();
        $totalMaterials = Material::count();
        $pendingMaterials = Material::where('status', 'pending')->count();
        $approvedMaterials = Material::whereIn('status', ['approved', 'published'])->count();
        $revisionMaterials = Material::where('status', 'revision')->count();
        $draftMaterials = Material::where('status', 'draft')->count();

        $totalStudied = Progress::count();
        $avgProgress = Progress::avg('progress_percentage') ?? 0;

        // Categories with material counts for charts
        $categoriesChart = Category::withCount('materials')->get();

        // Recent users progress
        $recentProgresses = Progress::with(['user', 'material.category'])
            ->latest('updated_at')
            ->take(6)
            ->get();

        // Pending review materials
        $pendingList = Material::where('status', 'pending')
            ->with(['category', 'creator'])
            ->latest()
            ->take(5)
            ->get();

        // Stats summary object
        $stats = [
            'total_users' => $totalUsers,
            'total_pembuat' => $totalPembuat,
            'total_materials' => $totalMaterials,
            'pending_materials' => $pendingMaterials,
            'approved_materials' => $approvedMaterials,
            'revision_materials' => $revisionMaterials,
            'draft_materials' => $draftMaterials,
            'total_studied' => $totalStudied,
            'avg_progress' => round($avgProgress, 1),
        ];

        return view('admin.dashboard', compact('stats', 'categoriesChart', 'recentProgresses', 'pendingList'));
    }
}
