<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use App\Models\Progress;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $allPublishedCount = Material::where('status', 'published')->count();
        $progresses = Progress::where('user_id', $user->id)
            ->with(['material.category'])
            ->latest('updated_at')
            ->paginate(10);

        $completedCount = Progress::where('user_id', $user->id)->where('status', 'completed')->count();
        $inProgressCount = Progress::where('user_id', $user->id)->where('status', 'in_progress')->count();
        $unstartedCount = max(0, $allPublishedCount - ($completedCount + $inProgressCount));

        $overallPercentage = $allPublishedCount > 0
            ? round(($completedCount / $allPublishedCount) * 100)
            : 0;

        $avgScore = Progress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->avg('score') ?? 0;

        $categories = Category::withCount(['publishedMaterials'])->get()->map(function ($cat) use ($user) {
            $catMaterialIds = Material::where('category_id', $cat->id)->where('status', 'published')->pluck('id');
            $userCompletedInCat = Progress::where('user_id', $user->id)
                ->whereIn('material_id', $catMaterialIds)
                ->where('status', 'completed')
                ->count();

            $cat->completed_count = $userCompletedInCat;
            $cat->percentage = $cat->published_materials_count > 0
                ? round(($userCompletedInCat / $cat->published_materials_count) * 100)
                : 0;

            return $cat;
        });

        return view('user.progress.index', compact(
            'progresses',
            'allPublishedCount',
            'completedCount',
            'inProgressCount',
            'unstartedCount',
            'overallPercentage',
            'avgScore',
            'categories'
        ));
    }

    public function results(Request $request)
    {
        $user = Auth::user();

        $selectedMaterialId = $request->input('material_id');
        $selectedMaterial = null;
        $detailedAnswers = [];

        if ($selectedMaterialId) {
            $selectedMaterial = Material::with(['questions.options'])->find($selectedMaterialId);
            if ($selectedMaterial) {
                // Get latest attempt user answers
                $latestAttempt = UserAnswer::where('user_id', $user->id)
                    ->whereIn('question_id', $selectedMaterial->questions->pluck('id'))
                    ->max('attempt');

                if ($latestAttempt) {
                    $detailedAnswers = UserAnswer::where('user_id', $user->id)
                        ->where('attempt', $latestAttempt)
                        ->whereIn('question_id', $selectedMaterial->questions->pluck('id'))
                        ->get()
                        ->keyBy('question_id');
                }
            }
        }

        $completedProgresses = Progress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('material.category')
            ->latest('completed_at')
            ->get();

        return view('user.progress.results', compact('completedProgresses', 'selectedMaterial', 'detailedAnswers'));
    }
}
