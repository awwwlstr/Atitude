<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use App\Models\Progress;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $totalPublishedMaterials = Material::where('status', 'published')->count();

        $query = User::where('role', 'user')->with(['progress.material']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        // Calculate user specific summary stats
        $users->getCollection()->transform(function ($u) use ($totalPublishedMaterials) {
            $completed = $u->progress->where('status', 'completed')->count();
            $percentage = $totalPublishedMaterials > 0 ? round(($completed / $totalPublishedMaterials) * 100) : 0;
            $avgScore = $u->progress->where('status', 'completed')->whereNotNull('score')->avg('score') ?? 0;

            $u->completed_count = $completed;
            $u->progress_percentage = $percentage;
            $u->avg_score = round($avgScore, 1);
            return $u;
        });

        return view('admin.progress.index', compact('users', 'totalPublishedMaterials'));
    }

    public function showUser($id)
    {
        $user = User::where('role', 'user')->with(['progress.material.category'])->findOrFail($id);

        $totalPublishedMaterials = Material::where('status', 'published')->count();
        $completedCount = $user->progress->where('status', 'completed')->count();
        $overallPercentage = $totalPublishedMaterials > 0 ? round(($completedCount / $totalPublishedMaterials) * 100) : 0;
        $avgScore = $user->progress->where('status', 'completed')->avg('score') ?? 0;

        $recentAnswers = UserAnswer::where('user_id', $user->id)
            ->with(['question.material'])
            ->latest()
            ->take(15)
            ->get();

        return view('admin.progress.show', compact('user', 'totalPublishedMaterials', 'completedCount', 'overallPercentage', 'avgScore', 'recentAnswers'));
    }

    public function reports()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalMaterials = Material::where('status', 'published')->count();
        $totalCompletions = Progress::where('status', 'completed')->count();
        $avgSystemScore = Progress::where('status', 'completed')->avg('score') ?? 0;

        $categories = Category::withCount('publishedMaterials')->get()->map(function ($cat) {
            $materialIds = Material::where('category_id', $cat->id)->where('status', 'published')->pluck('id');
            $completionsInCat = Progress::whereIn('material_id', $materialIds)->where('status', 'completed')->count();
            $avgScoreInCat = Progress::whereIn('material_id', $materialIds)->where('status', 'completed')->avg('score') ?? 0;

            $cat->completions_count = $completionsInCat;
            $cat->avg_score = round($avgScoreInCat, 1);
            return $cat;
        });

        $topStudents = User::where('role', 'user')->get()->map(function ($u) {
            $completed = $u->progress->where('status', 'completed')->count();
            $avgScore = $u->progress->where('status', 'completed')->avg('score') ?? 0;

            $u->completed_count = $completed;
            $u->avg_score = round($avgScore, 1);
            return $u;
        })->sortByDesc('completed_count')->take(5);

        return view('admin.reports.index', compact('totalUsers', 'totalMaterials', 'totalCompletions', 'avgSystemScore', 'categories', 'topStudents'));
    }
}
