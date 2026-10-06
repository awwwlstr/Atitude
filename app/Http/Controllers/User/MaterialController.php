<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use App\Models\Progress;
use App\Models\Question;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Material::with(['category', 'creator', 'progress' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])->where('status', 'published');

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

        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->whereHas('progress', function ($q) use ($user) {
                    $q->where('user_id', $user->id)->where('status', 'completed');
                });
            } elseif ($request->status === 'in_progress') {
                $query->whereHas('progress', function ($q) use ($user) {
                    $q->where('user_id', $user->id)->where('status', 'in_progress');
                });
            } elseif ($request->status === 'unstarted') {
                $query->whereDoesntHave('progress', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
        }

        $materials = $query->latest('approved_at')->paginate(9)->withQueryString();
        $categories = Category::withCount(['publishedMaterials'])->get();

        return view('user.material.index', compact('materials', 'categories'));
    }

    public function show($id)
    {
        $user = Auth::user();

        $material = Material::with(['category', 'creator', 'contents', 'questions.options'])
            ->where('status', 'published')
            ->findOrFail($id);

        $progress = Progress::where('user_id', $user->id)
            ->where('material_id', $material->id)
            ->first();

        return view('user.material.show', compact('material', 'progress'));
    }

    public function learn($id)
    {
        $user = Auth::user();

        $material = Material::with(['category', 'creator', 'contents', 'questions'])
            ->where('status', 'published')
            ->findOrFail($id);

        // Update or create progress as in_progress
        $progress = Progress::firstOrCreate(
            ['user_id' => $user->id, 'material_id' => $material->id],
            [
                'status' => 'in_progress',
                'progress_percentage' => 40,
                'started_at' => now(),
            ]
        );

        if ($progress->status === 'not_started') {
            $progress->update([
                'status' => 'in_progress',
                'progress_percentage' => max($progress->progress_percentage, 40),
                'started_at' => $progress->started_at ?? now(),
            ]);
        }

        return view('user.material.learn', compact('material', 'progress'));
    }

    public function quiz($id)
    {
        $user = Auth::user();

        $material = Material::with(['category', 'questions.options'])
            ->where('status', 'published')
            ->findOrFail($id);

        if ($material->questions->isEmpty()) {
            // If no questions, automatically complete the material
            $progress = Progress::firstOrCreate(
                ['user_id' => $user->id, 'material_id' => $material->id],
                ['started_at' => now()]
            );

            $progress->update([
                'status' => 'completed',
                'progress_percentage' => 100,
                'score' => 100,
                'completed_at' => now(),
            ]);

            return redirect()->route('user.materi.show', $material->id)
                ->with('success', 'Materi telah selesai dipelajari!');
        }

        $progress = Progress::where('user_id', $user->id)
            ->where('material_id', $material->id)
            ->first();

        // Previous answers if any
        $lastAttemptAnswers = UserAnswer::where('user_id', $user->id)
            ->whereIn('question_id', $material->questions->pluck('id'))
            ->get()
            ->keyBy('question_id');

        return view('user.material.quiz', compact('material', 'progress', 'lastAttemptAnswers'));
    }

    public function submitQuiz(Request $request, $id)
    {
        $user = Auth::user();

        $material = Material::with(['questions.options'])
            ->where('status', 'published')
            ->findOrFail($id);

        $questions = $material->questions;
        $totalQuestions = $questions->count();

        if ($totalQuestions === 0) {
            return redirect()->route('user.materi.show', $material->id);
        }

        $answers = $request->input('answers', []);
        $correctCount = 0;
        $totalEarnedPoints = 0;
        $maxPossiblePoints = $questions->sum('points') ?: ($totalQuestions * 10);

        // Track current attempt number
        $currentAttempt = (Progress::where('user_id', $user->id)->where('material_id', $material->id)->value('attempts_count') ?? 0) + 1;

        foreach ($questions as $question) {
            $userAns = $answers[$question->id] ?? null;
            $isCorrect = false;
            $score = 0;

            if ($question->type === 'multiple_choice' || $question->type === 'true_false') {
                $correctOpt = $question->options->firstWhere('is_correct', true);
                if ($correctOpt && (string)$correctOpt->id === (string)$userAns) {
                    $isCorrect = true;
                    $score = $question->points ?: 10;
                    $correctCount++;
                    $totalEarnedPoints += $score;
                }
            } elseif ($question->type === 'short_answer') {
                // Check if user answer matches option text roughly
                $correctOpt = $question->options->firstWhere('is_correct', true);
                if ($correctOpt && strcasecmp(trim($userAns ?? ''), trim($correctOpt->option_text)) === 0) {
                    $isCorrect = true;
                    $score = $question->points ?: 10;
                    $correctCount++;
                    $totalEarnedPoints += $score;
                }
            }

            // Save user answer
            UserAnswer::create([
                'user_id' => $user->id,
                'question_id' => $question->id,
                'answer' => is_numeric($userAns) ? optional($question->options->find($userAns))->option_text : $userAns,
                'is_correct' => $isCorrect,
                'score' => $score,
                'attempt' => $currentAttempt,
            ]);
        }

        // Final score on scale 0 - 100
        $finalScore = $maxPossiblePoints > 0
            ? round(($totalEarnedPoints / $maxPossiblePoints) * 100)
            : round(($correctCount / $totalQuestions) * 100);

        // Update progress
        $progress = Progress::updateOrCreate(
            ['user_id' => $user->id, 'material_id' => $material->id],
            [
                'status' => 'completed',
                'progress_percentage' => 100,
                'score' => $finalScore,
                'attempts_count' => $currentAttempt,
                'completed_at' => now(),
            ]
        );

        return redirect()->route('user.hasil', ['material_id' => $material->id])
            ->with('success', "Latihan soal selesai! Anda memperoleh nilai {$finalScore}.");
    }
}
