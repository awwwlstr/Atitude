<?php

namespace App\Http\Controllers\Pembuat;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use App\Models\MaterialContent;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $creator = Auth::user();

        $query = Material::where('creator_id', $creator->id)
            ->with(['category', 'contents', 'questions']);

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

        return view('pembuat.material.index', compact('materials', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('pembuat.material.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'action_type' => ['required', 'in:save_draft,submit_review'],
            
            // Optional Video
            'video_title' => ['nullable', 'string', 'max:255'],
            'video_url' => ['nullable', 'string', 'max:1000'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:20480'],
            
            // Optional PDF Document
            'doc_title' => ['nullable', 'string', 'max:255'],
            'doc_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,zip', 'max:10240'],

            // Optional Image
            'img_title' => ['nullable', 'string', 'max:255'],
            'img_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],

            // Optional Question
            'quiz_question' => ['nullable', 'string'],
            'quiz_type' => ['nullable', 'in:multiple_choice,true_false,short_answer'],
            'quiz_points' => ['nullable', 'integer', 'min:1', 'max:100'],
            'quiz_options' => ['nullable', 'array'],
            'quiz_correct_option' => ['nullable', 'numeric'],
            'quiz_tf_answer' => ['nullable', 'in:Benar,Salah'],
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        $status = ($validated['action_type'] === 'submit_review') ? 'pending' : 'draft';

        $material = Material::create([
            'creator_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(6),
            'description' => $validated['description'],
            'content' => $validated['content'] ?? null,
            'cover_image' => $coverPath,
            'status' => $status,
        ]);

        $orderIndex = 1;

        // 1. Process Video if given
        if (!empty($request->video_url) || $request->hasFile('video_file')) {
            $videoPath = null;
            if ($request->hasFile('video_file')) {
                $videoPath = $request->file('video_file')->store('materials/videos', 'public');
            }
            MaterialContent::create([
                'material_id' => $material->id,
                'type' => 'video',
                'title' => $request->video_title ?: 'Video Pembelajaran: ' . $material->title,
                'content' => $request->video_url ?: null,
                'file_path' => $videoPath,
                'order' => $orderIndex++,
            ]);
        }

        // 2. Process PDF Document if given
        if ($request->hasFile('doc_file')) {
            $docPath = $request->file('doc_file')->store('materials/documents', 'public');
            MaterialContent::create([
                'material_id' => $material->id,
                'type' => 'file',
                'title' => $request->doc_title ?: 'Dokumen / Modul PDF Pendukung',
                'content' => 'Dokumen lampiran materi pembelajaran.',
                'file_path' => $docPath,
                'order' => $orderIndex++,
            ]);
        }

        // 3. Process Image if given
        if ($request->hasFile('img_file')) {
            $imgPath = $request->file('img_file')->store('materials/images', 'public');
            MaterialContent::create([
                'material_id' => $material->id,
                'type' => 'image',
                'title' => $request->img_title ?: 'Gambar Ilustrasi Karakter',
                'content' => 'Ilustrasi pendukung materi.',
                'file_path' => $imgPath,
                'order' => $orderIndex++,
            ]);
        }

        // 4. Process Question if given
        if (!empty($request->quiz_question)) {
            $qType = $request->quiz_type ?: 'multiple_choice';
            $points = $request->quiz_points ?: 25;

            $question = Question::create([
                'material_id' => $material->id,
                'question' => $request->quiz_question,
                'type' => $qType,
                'points' => $points,
                'order' => 1,
            ]);

            if ($qType === 'multiple_choice') {
                $options = $request->input('quiz_options', []);
                $correctIdx = (int)$request->input('quiz_correct_option', 0);
                foreach ($options as $idx => $optText) {
                    if (!empty($optText)) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $optText,
                            'is_correct' => ($idx === $correctIdx),
                        ]);
                    }
                }
            } elseif ($qType === 'true_false') {
                $tfAns = $request->input('quiz_tf_answer', 'Benar');
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => 'Benar',
                    'is_correct' => ($tfAns === 'Benar'),
                ]);
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => 'Salah',
                    'is_correct' => ($tfAns === 'Salah'),
                ]);
            }
        }

        $message = ($status === 'pending')
            ? 'Materi beserta video, file, dan soal latihan berhasil dibuat dan diajukan untuk review Admin!'
            : 'Materi berhasil disimpan sebagai draft. Anda dapat terus melengkapi modul dan latihan soal.';

        return redirect()->route('pembuat.materi.show', $material->id)->with('success', $message);
    }

    public function show($id)
    {
        $material = Material::where('creator_id', Auth::id())
            ->with(['category', 'contents', 'questions.options'])
            ->findOrFail($id);

        return view('pembuat.material.show', compact('material'));
    }

    public function edit($id)
    {
        $material = Material::where('creator_id', Auth::id())->findOrFail($id);

        if ($material->status === 'approved' || $material->status === 'published') {
            return redirect()->route('pembuat.materi.show', $material->id)
                ->with('info', 'Materi yang sudah disetujui / dipublikasikan tidak dapat diedit secara langsung.');
        }

        $categories = Category::all();
        return view('pembuat.material.edit', compact('material', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::where('creator_id', Auth::id())->findOrFail($id);

        if ($material->status === 'approved' || $material->status === 'published') {
            return redirect()->route('pembuat.materi.show', $material->id)
                ->with('error', 'Materi yang sudah disetujui tidak dapat diubah.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($material->cover_image && Storage::disk('public')->exists($material->cover_image)) {
                Storage::disk('public')->delete($material->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $material->update($validated);

        return redirect()->route('pembuat.materi.show', $material->id)
            ->with('success', 'Informasi materi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $material = Material::where('creator_id', Auth::id())->findOrFail($id);

        if (in_array($material->status, ['approved', 'published'])) {
            return back()->with('error', 'Materi yang sudah disetujui atau dipublikasikan tidak dapat dihapus.');
        }

        if ($material->cover_image && Storage::disk('public')->exists($material->cover_image)) {
            Storage::disk('public')->delete($material->cover_image);
        }

        foreach ($material->contents as $content) {
            if ($content->file_path && Storage::disk('public')->exists($content->file_path)) {
                Storage::disk('public')->delete($content->file_path);
            }
        }

        $material->delete();

        return redirect()->route('pembuat.materi.index')->with('success', 'Materi berhasil dihapus.');
    }

    public function uploadContents(Request $request, $id)
    {
        $material = Material::where('creator_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'type' => ['required', 'in:text,image,video,file'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:20480'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            if ($validated['type'] === 'image') {
                $request->validate(['file' => 'mimes:jpeg,png,jpg,webp,gif|max:5120']);
                $filePath = $request->file('file')->store('materials/images', 'public');
            } elseif ($validated['type'] === 'video') {
                $request->validate(['file' => 'mimes:mp4,webm,mov|max:20480']);
                $filePath = $request->file('file')->store('materials/videos', 'public');
            } elseif ($validated['type'] === 'file') {
                $request->validate(['file' => 'mimes:pdf,doc,docx,ppt,pptx,zip|max:10240']);
                $filePath = $request->file('file')->store('materials/documents', 'public');
            }
        }

        $order = $validated['order'] ?? (($material->contents()->max('order') ?? 0) + 1);

        MaterialContent::create([
            'material_id' => $material->id,
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'file_path' => $filePath,
            'order' => $order,
        ]);

        return back()->with('success', 'Konten materi berhasil ditambahkan!');
    }

    public function deleteContent($materialId, $contentId)
    {
        $material = Material::where('creator_id', Auth::id())->findOrFail($materialId);
        $content = MaterialContent::where('material_id', $material->id)->findOrFail($contentId);

        if ($content->file_path && Storage::disk('public')->exists($content->file_path)) {
            Storage::disk('public')->delete($content->file_path);
        }

        $content->delete();

        return back()->with('success', 'Konten materi berhasil dihapus.');
    }

    public function storeQuestion(Request $request, $id)
    {
        $material = Material::where('creator_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'question' => ['required', 'string'],
            'type' => ['required', 'in:multiple_choice,true_false,short_answer'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'string'],
            'correct_option' => ['nullable', 'numeric'],
            'true_false_answer' => ['nullable', 'in:Benar,Salah'],
            'short_answer_key' => ['nullable', 'string'],
        ]);

        $order = ($material->questions()->max('order') ?? 0) + 1;

        $question = Question::create([
            'material_id' => $material->id,
            'question' => $validated['question'],
            'type' => $validated['type'],
            'points' => $validated['points'],
            'order' => $order,
        ]);

        if ($validated['type'] === 'multiple_choice') {
            $options = $request->input('options', []);
            $correctIdx = (int)$request->input('correct_option', 0);

            foreach ($options as $idx => $optText) {
                if (!empty($optText)) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optText,
                        'is_correct' => ($idx === $correctIdx),
                    ]);
                }
            }
        } elseif ($validated['type'] === 'true_false') {
            $correctTf = $request->input('true_false_answer', 'Benar');
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => 'Benar',
                'is_correct' => ($correctTf === 'Benar'),
            ]);
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => 'Salah',
                'is_correct' => ($correctTf === 'Salah'),
            ]);
        } elseif ($validated['type'] === 'short_answer') {
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => $request->input('short_answer_key', ''),
                'is_correct' => true,
            ]);
        }

        return back()->with('success', 'Soal latihan berhasil ditambahkan!');
    }

    public function deleteQuestion($materialId, $questionId)
    {
        $material = Material::where('creator_id', Auth::id())->findOrFail($materialId);
        $question = Question::where('material_id', $material->id)->findOrFail($questionId);

        $question->delete();

        return back()->with('success', 'Soal latihan berhasil dihapus.');
    }

    public function submitForReview($id)
    {
        $material = Material::where('creator_id', Auth::id())->findOrFail($id);

        if (!in_array($material->status, ['draft', 'revision'])) {
            return back()->with('error', 'Hanya materi berstatus Draft atau Revisi yang dapat diajukan untuk review.');
        }

        $material->update([
            'status' => 'pending',
        ]);

        return redirect()->route('pembuat.materi.show', $material->id)
            ->with('success', 'Materi berhasil diajukan kepada Admin untuk direview.');
    }

    public function revision($id)
    {
        $material = Material::where('creator_id', Auth::id())->findOrFail($id);

        return view('pembuat.material.revision', compact('material'));
    }
}
