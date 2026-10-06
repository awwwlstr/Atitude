@extends('layouts.app')

@section('title', 'Kelola Materi: ' . $material->title . ' - E-Attitude')

@section('content')
<div class="row">
    <!-- Sidebar Pembuat -->
    <div class="col-lg-3">
        @include('layouts.sidebar-pembuat')
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <!-- Status & Action Banner -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-{{ $material->category->color }} fs-6">
                            {{ $material->category->name }}
                        </span>
                        <span class="badge {{ $material->status_badge_class }} fs-6">
                            Status: {{ $material->status_label }}
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1 text-dark">{{ $material->title }}</h3>
                    <div class="small text-muted">Dibuat: {{ $material->created_at->format('d M Y H:i') }} • Terakhir diubah: {{ $material->updated_at->format('d M Y H:i') }}</div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    @if(in_array($material->status, ['draft', 'revision']))
                        <a href="{{ route('pembuat.materi.edit', $material->id) }}" class="btn btn-outline-primary rounded-pill px-3">
                            <i class="bi bi-pencil me-1"></i> Edit Info
                        </a>
                        <form action="{{ route('pembuat.materi.submit', $material->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ajukan materi ini kepada Admin untuk direview?')">
                            @csrf
                            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                                <i class="bi bi-send-check-fill me-1"></i> Ajukan Review Admin
                            </button>
                        </form>
                    @elseif($material->status === 'pending')
                        <span class="btn btn-warning text-dark disabled rounded-pill px-3">
                            <i class="bi bi-hourglass-split me-1"></i> Sedang Ditinjau Admin
                        </span>
                    @elseif($material->status === 'published')
                        <span class="btn btn-success disabled rounded-pill px-3">
                            <i class="bi bi-check-all me-1"></i> Telah Dipublikasikan
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Revision Note from Admin if status is revision -->
        @if($material->status === 'revision' && $material->revision_note)
            <div class="alert alert-danger border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-exclamation-triangle-fill fs-4 text-danger me-2"></i>
                    <h5 class="fw-bold mb-0 text-danger">Catatan Revisi dari Administrator:</h5>
                </div>
                <div class="p-3 bg-white rounded-3 border text-dark mt-2" style="white-space: pre-line;">
                    {{ $material->revision_note }}
                </div>
                <div class="mt-3 small text-muted">
                    Silakan perbaiki uraian modul atau soal latihan di bawah ini, lalu klik tombol <strong>"Ajukan Review Admin"</strong> di atas.
                </div>
            </div>
        @endif

        <!-- General Overview -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-2"></i>Ringkasan Materi Pokok</h5>
            <p class="lead fs-6 text-secondary">{{ $material->description }}</p>
            @if($material->content)
                <div class="p-3 bg-light rounded-3 text-secondary" style="white-space: pre-line;">{{ $material->content }}</div>
            @endif
        </div>

            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-collection-play text-primary me-2"></i>Konten & Modul Pembelajaran ({{ $material->contents->count() }})
                </h5>
                @if(in_array($material->status, ['draft', 'revision']))
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#addContentModal" onclick="setMediaType('video')">
                            <i class="bi bi-youtube me-1"></i> + Tambah Video
                        </button>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#addContentModal" onclick="setMediaType('file')">
                            <i class="bi bi-file-earmark-pdf me-1"></i> + Upload PDF
                        </button>
                        <button type="button" class="btn btn-info text-white btn-sm rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#addContentModal" onclick="setMediaType('image')">
                            <i class="bi bi-image me-1"></i> + Upload Gambar
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#addContentModal" onclick="setMediaType('text')">
                            <i class="bi bi-file-text me-1"></i> + Teks Modul
                        </button>
                    </div>
                @endif
            </div>
            <div class="card-body p-4">
                @if($material->contents->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-file-earmark-plus fs-1"></i>
                        <p class="mt-2 mb-0">Belum ada lampiran modul khusus (Teks, Video, Gambar, atau File PDF).</p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($material->contents as $index => $content)
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm bg-light rounded-4 p-3 h-100 position-relative">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary rounded-circle p-2 me-2" style="width: 26px; height: 26px; display: flex; align-items: center; justify-content: center;">
                                                {{ $index + 1 }}
                                            </span>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">{{ $content->title }}</h6>
                                                <span class="badge bg-secondary-subtle text-secondary text-uppercase" style="font-size: 0.65rem;">{{ $content->type }}</span>
                                            </div>
                                        </div>
                                        @if(in_array($material->status, ['draft', 'revision']))
                                            <form action="{{ route('pembuat.materi.content.delete', ['id' => $material->id, 'content_id' => $content->id]) }}" method="POST" onsubmit="return confirm('Hapus konten ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" title="Hapus Konten">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    @if($content->type === 'text')
                                        <p class="small text-secondary mb-0 line-clamp-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ $content->content }}
                                        </p>
                                    @elseif($content->type === 'image')
                                        <div class="text-center my-2">
                                            <img src="{{ $content->file_url }}" alt="{{ $content->title }}" class="img-fluid rounded-3" style="max-height: 120px;">
                                        </div>
                                    @elseif($content->type === 'video')
                                        <div class="small text-muted">
                                            <i class="bi bi-play-circle text-danger me-1"></i> {{ $content->content ?? 'Video File' }}
                                        </div>
                                    @elseif($content->type === 'file')
                                        <div class="small text-muted">
                                            <i class="bi bi-file-earmark-pdf text-warning me-1"></i> File Lampiran
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Latihan Soal Evaluasi Section -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-patch-question text-warning me-2"></i>Latihan Soal & Evaluasi Sikap ({{ $material->questions->count() }})
                </h5>
                @if(in_array($material->status, ['draft', 'revision']))
                    <button type="button" class="btn btn-warning text-dark btn-sm rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Soal
                    </button>
                @endif
            </div>
            <div class="card-body p-4">
                @if($material->questions->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-question-circle fs-1"></i>
                        <p class="mt-2 mb-0">Belum ada latihan soal evaluasi pada materi ini.</p>
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($material->questions as $index => $q)
                            <div class="p-3 bg-light rounded-4 border">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning text-dark me-2">Soal No. {{ $index + 1 }}</span>
                                        <span class="badge bg-secondary-subtle text-secondary me-2">{{ $q->type }}</span>
                                        <span class="badge bg-success-subtle text-success">{{ $q->points }} Poin</span>
                                    </div>
                                    @if(in_array($material->status, ['draft', 'revision']))
                                        <form action="{{ route('pembuat.materi.question.delete', ['id' => $material->id, 'question_id' => $q->id]) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" title="Hapus Soal">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <h6 class="fw-bold text-dark mb-2">{{ $q->question }}</h6>

                                <div class="ps-3 border-start border-3 border-warning">
                                    <div class="small fw-semibold text-muted mb-1">Pilihan / Kunci Jawaban:</div>
                                    @foreach($q->options as $opt)
                                        <div class="small {{ $opt->is_correct ? 'text-success fw-bold' : 'text-secondary' }}">
                                            @if($opt->is_correct)
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                            @else
                                                <i class="bi bi-dash-circle me-1 text-muted"></i>
                                            @endif
                                            {{ $opt->option_text }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Konten -->
<div class="modal fade" id="addContentModal" tabindex="-1" aria-labelledby="addContentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="addContentModalLabel"><i class="bi bi-plus-circle me-2"></i>Tambah Konten Modul</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pembuat.materi.upload', $material->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipe Konten <span class="text-danger">*</span></label>
                            <select name="type" id="content_type" class="form-select" required onchange="toggleContentFields(this.value)">
                                <option value="text">Teks Penjelasan / Uraian</option>
                                <option value="video">Video (YouTube Embed / Upload Video)</option>
                                <option value="image">Gambar Ilustrasi / Infografis</option>
                                <option value="file">Dokumen Pendukung (PDF / Doc)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Judul Konten <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Video Studi Kasus Kejujuran" required>
                        </div>
                    </div>

                    <div class="mb-3" id="field_content_text">
                        <label class="form-label fw-semibold">Isi Teks / Tautan Video (URL Embed)</label>
                        <textarea name="content" class="form-control" rows="5" placeholder="Tuliskan uraian materi atau tempelkan URL embed video YouTube..."></textarea>
                    </div>

                    <div class="mb-3" id="field_content_file">
                        <label class="form-label fw-semibold">Unggah File (Gambar / Dokumen / Video)</label>
                        <input type="file" name="file" class="form-control">
                        <div class="form-text">Maksimal 20MB. Format didukung: JPG, PNG, WEBP, MP4, PDF, DOCX, PPTX, ZIP.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                        <i class="bi bi-save me-1"></i> Simpan Konten
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Soal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold" id="addQuestionModalLabel"><i class="bi bi-patch-question me-2"></i>Tambah Soal Evaluasi Sikap</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pembuat.materi.soal', $material->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipe Soal <span class="text-danger">*</span></label>
                            <select name="type" id="question_type" class="form-select" required onchange="toggleQuestionOptions(this.value)">
                                <option value="multiple_choice">Pilihan Ganda (A, B, C, D)</option>
                                <option value="true_false">Benar / Salah</option>
                                <option value="short_answer">Pertanyaan Singkat</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Bobot Poin <span class="text-danger">*</span></label>
                            <input type="number" name="points" class="form-control" value="25" min="1" max="100" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pertanyaan Soal <span class="text-danger">*</span></label>
                        <textarea name="question" class="form-control" rows="3" placeholder="Tuliskan pertanyaan evaluasi..." required></textarea>
                    </div>

                    <!-- Multiple Choice Options -->
                    <div id="mc_options_box">
                        <label class="form-label fw-semibold">Pilihan Jawaban (Pilih radio untuk jawaban benar):</label>
                        @for($i = 0; $i < 4; $i++)
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="correct_option" value="{{ $i }}" {{ $i === 0 ? 'checked' : '' }}>
                                    <span class="ms-2 fw-bold">{{ chr(65 + $i) }}</span>
                                </div>
                                <input type="text" name="options[{{ $i }}]" class="form-control" placeholder="Pilihan jawaban {{ chr(65 + $i) }}">
                            </div>
                        @endfor
                    </div>

                    <!-- True False Options -->
                    <div id="tf_options_box" style="display: none;">
                        <label class="form-label fw-semibold">Kunci Jawaban yang Benar:</label>
                        <select name="true_false_answer" class="form-select">
                            <option value="Benar">Benar</option>
                            <option value="Salah">Salah</option>
                        </select>
                    </div>

                    <!-- Short Answer Options -->
                    <div id="sa_options_box" style="display: none;">
                        <label class="form-label fw-semibold">Kunci Jawaban Singkat:</label>
                        <input type="text" name="short_answer_key" class="form-control" placeholder="Kata kunci jawaban yang benar...">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill px-4">
                        <i class="bi bi-save me-1"></i> Simpan Soal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setMediaType(type) {
    const sel = document.getElementById('content_type');
    if (sel) {
        sel.value = type;
        toggleContentFields(type);
    }
}

function toggleContentFields(type) {
    const textField = document.getElementById('field_content_text');
    const fileField = document.getElementById('field_content_file');
    
    if (type === 'text') {
        textField.style.display = 'block';
        fileField.style.display = 'none';
    } else if (type === 'video') {
        textField.style.display = 'block';
        fileField.style.display = 'block';
    } else {
        textField.style.display = 'none';
        fileField.style.display = 'block';
    }
}

function toggleQuestionOptions(type) {
    document.getElementById('mc_options_box').style.display = (type === 'multiple_choice') ? 'block' : 'none';
    document.getElementById('tf_options_box').style.display = (type === 'true_false') ? 'block' : 'none';
    document.getElementById('sa_options_box').style.display = (type === 'short_answer') ? 'block' : 'none';
}
</script>
@endsection
