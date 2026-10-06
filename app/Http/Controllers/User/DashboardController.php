<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Published materials count
        $totalPublishedMaterials = Material::where('status', 'published')->count();

        // User's progresses
        $userProgresses = Progress::where('user_id', $user->id)->with('material.category')->get();

        $completedCount = $userProgresses->where('status', 'completed')->count();
        $inProgressCount = $userProgresses->where('status', 'in_progress')->count();
        $unstartedCount = max(0, $totalPublishedMaterials - ($completedCount + $inProgressCount));

        // Overall progress percentage
        $overallPercentage = $totalPublishedMaterials > 0
            ? round(($completedCount / $totalPublishedMaterials) * 100)
            : 0;

        // Average score
        $completedWithScore = $userProgresses->where('status', 'completed')->whereNotNull('score');
        $averageScore = $completedWithScore->count() > 0
            ? round($completedWithScore->avg('score'), 1)
            : 0;

        // Currently learning materials (in_progress)
        $inProgressMaterials = Material::where('status', 'published')
            ->whereHas('progress', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'in_progress');
            })
            ->with(['category', 'progress' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->latest()
            ->take(3)
            ->get();

        // Recommended materials (published materials user hasn't completed)
        $completedMaterialIds = $userProgresses->where('status', 'completed')->pluck('material_id')->toArray();
        $recommendedMaterials = Material::where('status', 'published')
            ->whereNotIn('id', $completedMaterialIds)
            ->with(['category', 'creator'])
            ->latest('approved_at')
            ->take(4)
            ->get();

        // Recent completed materials
        $recentCompleted = Progress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('material.category')
            ->latest('completed_at')
            ->take(3)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'totalPublishedMaterials',
            'completedCount',
            'inProgressCount',
            'unstartedCount',
            'overallPercentage',
            'averageScore',
            'inProgressMaterials',
            'recommendedMaterials',
            'recentCompleted'
        ));
    }
}
