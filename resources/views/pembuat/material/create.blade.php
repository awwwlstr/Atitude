@extends('layouts.app')

@section('title', 'Buat Materi Baru - E-Attitude')

@section('content')
<div class="row">
    <!-- Sidebar Pembuat -->
    <div class="col-lg-3">
        @include('layouts.sidebar-pembuat')
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-plus-circle me-2"></i>Buat Materi Pembelajaran Baru</h5>
                <a href="{{ route('pembuat.materi.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('pembuat.materi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Informasi Pokok Materi -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                            <i class="bi bi-info-circle text-primary me-2"></i>1. Informasi Pokok Materi
                        </h6>

                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label for="title" class="form-label fw-semibold">Judul Materi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Belajar Menepati Janji dan Amanah" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="category_id" class="form-label fw-semibold">Topik / Nilai Sikap <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">-- Pilih Nilai Sikap --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Deskripsi Singkat / Ringkasan Materi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Tuliskan tujuan pembelajaran dan pengantar singkat mengenai materi ini..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label fw-semibold">Uraian Konsep Materi (Teks Pokok)</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5" placeholder="Tuliskan materi pembelajaran pokok yang ingin disampaikan kepada siswa...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label fw-semibold">Gambar Sampul / Cover Materi (Opsional)</label>
                            <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*">
                            <div class="form-text">Format: JPG, PNG, WEBP (Maksimal 2MB).</div>
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Section 2: Upload Media & File Tambahan (Video, PDF, Gambar) -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                            <i class="bi bi-collection-play text-danger me-2"></i>2. Media Pembelajaran Tambahan (Video, PDF / Dokumen, Gambar)
                        </h6>

                        <!-- Form Video -->
                        <div class="p-3 bg-light rounded-4 mb-3 border">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-youtube text-danger fs-4 me-2"></i>
                                <h6 class="fw-bold mb-0">Tautan Video / Unggah Video</h6>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Judul Video</label>
                                    <input type="text" name="video_title" class="form-control form-control-sm" placeholder="Contoh: Video Edukasi Perilaku Jujur">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Tautan / Link Video (YouTube Embed)</label>
                                    <input type="text" name="video_url" class="form-control form-control-sm" placeholder="https://www.youtube.com/watch?v=...">
                                </div>
                            </div>
                            <div>
                                <label class="form-label small fw-semibold">Atau Unggah File Video Langsung (MP4 / WebM)</label>
                                <input type="file" name="video_file" class="form-control form-control-sm" accept="video/mp4,video/webm">
                                <div class="form-text" style="font-size: 0.75rem;">Maksimal 20MB.</div>
                            </div>
                        </div>

                        <!-- Form PDF / Dokumen Pendukung -->
                        <div class="p-3 bg-light rounded-4 mb-3 border">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-file-earmark-pdf-fill text-danger fs-4 me-2"></i>
                                <h6 class="fw-bold mb-0">File Dokumen Pendukung (PDF / Docx / PPT)</h6>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Nama Dokumen</label>
                                    <input type="text" name="doc_title" class="form-control form-control-sm" placeholder="Contoh: Modul Bacaan Karakter PDF">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Pilih File Dokumen</label>
                                    <input type="file" name="doc_file" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.ppt,.pptx">
                                </div>
                            </div>
                        </div>

                        <!-- Form Gambar Tambahan -->
                        <div class="p-3 bg-light rounded-4 mb-3 border">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-image-fill text-info fs-4 me-2"></i>
                                <h6 class="fw-bold mb-0">Gambar Ilustrasi Tambahan</h6>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Judul Gambar</label>
                                    <input type="text" name="img_title" class="form-control form-control-sm" placeholder="Contoh: Infografis Penerapan Sikap">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Pilih Gambar</label>
                                    <input type="file" name="img_file" class="form-control form-control-sm" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Latihan Soal Evaluasi -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                            <i class="bi bi-patch-question text-warning me-2"></i>3. Latihan Soal Evaluasi Sikap (Opsional, dapat ditambah lagi nanti)
                        </h6>

                        <div class="p-3 bg-light rounded-4 border">
                            <div class="row g-3 mb-2">
                                <div class="col-md-8">
                                    <label class="form-label small fw-semibold">Tipe Soal</label>
                                    <select name="quiz_type" id="create_quiz_type" class="form-select form-select-sm" onchange="toggleCreateQuiz(this.value)">
                                        <option value="multiple_choice">Pilihan Ganda (A, B, C, D)</option>
                                        <option value="true_false">Benar / Salah</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Poin Soal</label>
                                    <input type="number" name="quiz_points" class="form-control form-control-sm" value="25" min="1" max="100">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Pertanyaan Soal</label>
                                <textarea name="quiz_question" class="form-control form-control-sm" rows="2" placeholder="Tuliskan pertanyaan evaluasi sikap..."></textarea>
                            </div>

                            <div id="create_mc_box">
                                <label class="form-label small fw-semibold">Pilihan Jawaban (Pilih radio untuk jawaban benar):</label>
                                @for($i = 0; $i < 4; $i++)
                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="radio" name="quiz_correct_option" value="{{ $i }}" {{ $i === 0 ? 'checked' : '' }}>
                                            <span class="ms-2 fw-bold">{{ chr(65 + $i) }}</span>
                                        </div>
                                        <input type="text" name="quiz_options[{{ $i }}]" class="form-control" placeholder="Pilihan jawaban {{ chr(65 + $i) }}">
                                    </div>
                                @endfor
                            </div>

                            <div id="create_tf_box" style="display: none;">
                                <label class="form-label small fw-semibold">Kunci Jawaban Benar:</label>
                                <select name="quiz_tf_answer" class="form-select form-select-sm">
                                    <option value="Benar">Benar</option>
                                    <option value="Salah">Salah</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="small text-muted">
                            <i class="bi bi-info-circle me-1"></i> Anda dapat menambah/mengedit modul video, file, dan bank soal lebih banyak di halaman detail materi.
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" name="action_type" value="save_draft" class="btn btn-outline-primary px-4 rounded-pill fw-semibold">
                                <i class="bi bi-save me-1"></i> Simpan Sebagai Draft
                            </button>
                            <button type="submit" name="action_type" value="submit_review" class="btn btn-success px-4 rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-send-check me-1"></i> Simpan & Ajukan Review
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCreateQuiz(type) {
    document.getElementById('create_mc_box').style.display = (type === 'multiple_choice') ? 'block' : 'none';
    document.getElementById('create_tf_box').style.display = (type === 'true_false') ? 'block' : 'none';
}
</script>
@endsection
