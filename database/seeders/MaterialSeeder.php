<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Material;
use App\Models\MaterialContent;
use App\Models\Progress;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pembuat = User::where('role', 'pembuat_materi')->first();
        $user1 = User::where('email', 'ahmad@attitude.com')->first();
        $user2 = User::where('email', 'siti@attitude.com')->first();

        $catJujur = Category::where('name', 'Kejujuran')->first();
        $catDisiplin = Category::where('name', 'Disiplin')->first();
        $catTanggungJawab = Category::where('name', 'Tanggung Jawab')->first();
        $catKerjaSama = Category::where('name', 'Kerja Sama')->first();
        $catSopan = Category::where('name', 'Sopan Santun')->first();

        // Materi 1: Belajar Jujur dalam Kehidupan Sehari-hari (Published)
        $m1 = Material::updateOrCreate(
            ['slug' => 'belajar-jujur-dalam-kehidupan-sehari-hari'],
            [
                'creator_id' => $pembuat->id,
                'category_id' => $catJujur->id,
                'title' => 'Belajar Jujur dalam Kehidupan Sehari-hari',
                'description' => 'Memahami hakikat kejujuran, mengapa berbohong merugikan diri sendiri dan orang lain, serta cara mempraktikkan kejujuran di sekolah dan rumah.',
                'content' => 'Kejujuran adalah pondasi utama dari seluruh karakter mulia. Seseorang yang jujur akan dihargai dan dipercaya dalam perkataan maupun tindakannya. Dalam modul ini, kita akan mempelajari prinsip-prinsip kejujuran.',
                'status' => 'published',
                'approved_at' => now()->subDays(5),
            ]
        );

        // Konten Materi 1
        MaterialContent::updateOrCreate(
            ['material_id' => $m1->id, 'order' => 1],
            [
                'type' => 'text',
                'title' => 'Pengertian dan Nilai Luhur Kejujuran',
                'content' => "Kejujuran berasal dari kata dasar 'jujur' yang bermakna lurus hati, tidak berbohong, tidak curang, dan tulus. Berperilaku jujur berarti menyelaraskan antara apa yang ada di dalam hati, apa yang diucapkan oleh lisan, dan apa yang diperbuat dalam tindakan nyata.\n\nManfaat bersikap jujur:\n1. Memperoleh ketenangan batin dan terbebas dari rasa cemas.\n2. Mendapatkan kepercayaan tinggi dari keluarga, guru, dan teman-teman.\n3. Menjadi pribadi yang berintegritas dan dihormati di masyarakat.\n4. Mencegah konflik dan kesalahpahaman dalam interaksi sosial.",
            ]
        );

        MaterialContent::updateOrCreate(
            ['material_id' => $m1->id, 'order' => 2],
            [
                'type' => 'video',
                'title' => 'Video Edukasi: Kekuatan Sebuah Kejujuran',
                'content' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', // Embed URL
            ]
        );

        MaterialContent::updateOrCreate(
            ['material_id' => $m1->id, 'order' => 3],
            [
                'type' => 'text',
                'title' => 'Penerapan Kejujuran di Lingkungan Sekolah',
                'content' => "Di lingkungan sekolah, bentuk kejujuran nyata antara lain:\n- Tidak menyontek saat ujian atau tugas mandiri.\n- Mengembalikan barang temuan kepada pemiliknya atau ke ruang BK / piket.\n- Mengakui kesalahan secara berani jika melakukan pelanggaran tata tertib.\n- Berkata apa adanya ketika izin tidak masuk sekolah.",
            ]
        );

        // Soal untuk Materi 1
        $q1 = Question::updateOrCreate(
            ['material_id' => $m1->id, 'order' => 1],
            [
                'question' => 'Apa yang dimaksud dengan sikap jujur?',
                'type' => 'multiple_choice',
                'points' => 25,
            ]
        );
        QuestionOption::updateOrCreate(['question_id' => $q1->id, 'option_text' => 'Kesesuaian antara ucapan, tindakan, dan kenyataan yang sebenarnya'], ['is_correct' => true]);
        QuestionOption::updateOrCreate(['question_id' => $q1->id, 'option_text' => 'Mengatakan hal yang menyenangkan orang lain meski tidak benar'], ['is_correct' => false]);
        QuestionOption::updateOrCreate(['question_id' => $q1->id, 'option_text' => 'Menyembunyikan kebenaran demi menjaga perasaan teman'], ['is_correct' => false]);
        QuestionOption::updateOrCreate(['question_id' => $q1->id, 'option_text' => 'Mengikuti perkataan mayoritas orang tanpa memeriksa fakta'], ['is_correct' => false]);

        $q2 = Question::updateOrCreate(
            ['material_id' => $m1->id, 'order' => 2],
            [
                'question' => 'Ketika menemukan dompet berisi uang di perpustakaan sekolah, tindakan jujur yang paling tepat adalah...',
                'type' => 'multiple_choice',
                'points' => 25,
            ]
        );
        QuestionOption::updateOrCreate(['question_id' => $q2->id, 'option_text' => 'Menyerahkannya kepada petugas perpustakaan atau guru piket untuk diumumkan'], ['is_correct' => true]);
        QuestionOption::updateOrCreate(['question_id' => $q2->id, 'option_text' => 'Mengambil uangnya lalu membuang dompetnya'], ['is_correct' => false]);
        QuestionOption::updateOrCreate(['question_id' => $q2->id, 'option_text' => 'Menyimpannya diam-diam sampai ada yang menanyakan'], ['is_correct' => false]);
        QuestionOption::updateOrCreate(['question_id' => $q2->id, 'option_text' => 'Membagikan uangnya kepada teman sekelas'], ['is_correct' => false]);

        $q3 = Question::updateOrCreate(
            ['material_id' => $m1->id, 'order' => 3],
            [
                'question' => 'Menyontek saat ujian merupakan tindakan yang dapat merugikan diri sendiri karena merusak rasa percaya diri dan menghambat proses belajar sejati.',
                'type' => 'true_false',
                'points' => 25,
            ]
        );
        QuestionOption::updateOrCreate(['question_id' => $q3->id, 'option_text' => 'Benar'], ['is_correct' => true]);
        QuestionOption::updateOrCreate(['question_id' => $q3->id, 'option_text' => 'Salah'], ['is_correct' => false]);

        $q4 = Question::updateOrCreate(
            ['material_id' => $m1->id, 'order' => 4],
            [
                'question' => 'Salah satu dampak positif dari selalu bersikap jujur adalah hidup menjadi tenang dan dipercaya banyak orang.',
                'type' => 'true_false',
                'points' => 25,
            ]
        );
        QuestionOption::updateOrCreate(['question_id' => $q4->id, 'option_text' => 'Benar'], ['is_correct' => true]);
        QuestionOption::updateOrCreate(['question_id' => $q4->id, 'option_text' => 'Salah'], ['is_correct' => false]);


        // Materi 2: Manajemen Waktu dan Disiplin Diri (Published)
        $m2 = Material::updateOrCreate(
            ['slug' => 'manajemen-waktu-dan-disiplin-diri'],
            [
                'creator_id' => $pembuat->id,
                'category_id' => $catDisiplin->id,
                'title' => 'Manajemen Waktu dan Disiplin Diri',
                'description' => 'Strategi mengelola waktu belajar, memprioritaskan hal penting, dan membangun kebiasaan disiplin tanpa perlu disuruh.',
                'content' => 'Disiplin bukan tentang kekangan, melainkan jembatan antara tujuan dan pencapaian. Dengan disiplin, kita menguasai diri untuk meraih cita-cita.',
                'status' => 'published',
                'approved_at' => now()->subDays(3),
            ]
        );

        MaterialContent::updateOrCreate(
            ['material_id' => $m2->id, 'order' => 1],
            [
                'type' => 'text',
                'title' => 'Pentingnya Disiplin Waktu',
                'content' => "Waktu adalah aset yang tidak dapat diulang. Disiplin waktu melatih kita untuk hadir tepat waktu, menyelesaikan tugas sebelum tenggat waktu, dan tidak menunda pekerjaan.\n\nTips membiasakan disiplin:\n1. Buat jadwal harian terstruktur.\n2. Terapkan aturan 'Kerjakan yang sulit terlebih dahulu'.\n3. Batasi gangguan saat jam belajar (misal: notifikasi media sosial).\n4. Beri penghargaan pada diri sendiri saat berhasil memenuhi target harian.",
            ]
        );

        $q2_1 = Question::updateOrCreate(
            ['material_id' => $m2->id, 'order' => 1],
            [
                'question' => 'Sikap yang menunjukkan disiplin dalam belajar adalah...',
                'type' => 'multiple_choice',
                'points' => 50,
            ]
        );
        QuestionOption::updateOrCreate(['question_id' => $q2_1->id, 'option_text' => 'Belajar secara teratur sesuai jadwal yang telah dibuat'], ['is_correct' => true]);
        QuestionOption::updateOrCreate(['question_id' => $q2_1->id, 'option_text' => 'Hanya belajar saat malam sebelum ujian (sistem kebut semalam)'], ['is_correct' => false]);
        QuestionOption::updateOrCreate(['question_id' => $q2_1->id, 'option_text' => 'Menunda pengerjaan tugas hingga batas akhir pengumpulan'], ['is_correct' => false]);
        QuestionOption::updateOrCreate(['question_id' => $q2_1->id, 'option_text' => 'Belajar hanya ketika diawasi orang tua'], ['is_correct' => false]);

        $q2_2 = Question::updateOrCreate(
            ['material_id' => $m2->id, 'order' => 2],
            [
                'question' => 'Disiplin diri hanya diperlukan di lingkungan sekolah dan tidak berpengaruh di masa depan.',
                'type' => 'true_false',
                'points' => 50,
            ]
        );
        QuestionOption::updateOrCreate(['question_id' => $q2_2->id, 'option_text' => 'Benar'], ['is_correct' => false]);
        QuestionOption::updateOrCreate(['question_id' => $q2_2->id, 'option_text' => 'Salah'], ['is_correct' => true]);


        // Materi 3: Tanggung Jawab Terhadap Tugas dan Janji (Published)
        $m3 = Material::updateOrCreate(
            ['slug' => 'tanggung-jawab-terhadap-tugas-dan-janji'],
            [
                'creator_id' => $pembuat->id,
                'category_id' => $catTanggungJawab->id,
                'title' => 'Tanggung Jawab Terhadap Tugas dan Janji',
                'description' => 'Membangun komitmen atas setiap amanah yang diemban dan berani menanggung konsekuensi atas tindakan yang diambil.',
                'content' => 'Tanggung jawab adalah tanda kedewasaan mental. Pribadi yang bertanggung jawab tidak mencari kambing hitam atas kegagalan, melainkan mencari solusi.',
                'status' => 'published',
                'approved_at' => now()->subDays(2),
            ]
        );

        MaterialContent::updateOrCreate(
            ['material_id' => $m3->id, 'order' => 1],
            [
                'type' => 'text',
                'title' => 'Menjadi Pribadi yang Amanah',
                'content' => "Setiap tugas yang kita terima adalah sebuah amanah. Memenuhi janji dan menyelesaikan tugas dengan tuntas adalah wujud nyata dari sikap bertanggung jawab.",
            ]
        );

        $q3_1 = Question::updateOrCreate(
            ['material_id' => $m3->id, 'order' => 1],
            [
                'question' => 'Jika Anda melakukan kesalahan yang menyebabkan tugas kelompok terlambat, sikap tanggung jawab yang benar adalah...',
                'type' => 'multiple_choice',
                'points' => 100,
            ]
        );
        QuestionOption::updateOrCreate(['question_id' => $q3_1->id, 'option_text' => 'Mengakui kesalahan, meminta maaf kepada tim, dan segera membantu menyelesaikannya'], ['is_correct' => true]);
        QuestionOption::updateOrCreate(['question_id' => $q3_1->id, 'option_text' => 'Menyalahkan anggota kelompok lain'], ['is_correct' => false]);
        QuestionOption::updateOrCreate(['question_id' => $q3_1->id, 'option_text' => 'Pura-pura tidak tahu dan keluar dari kelompok'], ['is_correct' => false]);
        QuestionOption::updateOrCreate(['question_id' => $q3_1->id, 'option_text' => 'Mencari alasan palsu agar tidak disalahkan'], ['is_correct' => false]);


        // Materi 4: Seni Berkolaborasi dan Kerja Sama Tim (Pending Review)
        Material::updateOrCreate(
            ['slug' => 'seni-berkolaborasi-dan-kerja-sama-tim'],
            [
                'creator_id' => $pembuat->id,
                'category_id' => $catKerjaSama->id,
                'title' => 'Seni Berkolaborasi dan Kerja Sama Tim',
                'description' => 'Menghilangkan ego pribadi, aktif mendengarkan rekan kerja kelompok, dan menyatukan kekuatan demi keberhasilan bersama.',
                'content' => 'Kerja sama yang baik melipatgandakan hasil dan mempererat persaudaraan.',
                'status' => 'pending',
            ]
        );

        // Materi 5: Etika Berkomunikasi dan Sopan Santun Digital (Draft)
        Material::updateOrCreate(
            ['slug' => 'etika-berkomunikasi-dan-sopan-santun-digital'],
            [
                'creator_id' => $pembuat->id,
                'category_id' => $catSopan->id,
                'title' => 'Etika Berkomunikasi dan Sopan Santun Digital',
                'description' => 'Bagaimana menjaga kesantunan dalam bertutur kata baik di dunia nyata maupun di media sosial.',
                'content' => 'Draf materi mengenai netiket dan kesopanan berkomunikasi.',
                'status' => 'draft',
            ]
        );

        // Materi 6: Membangun Sikap Mandiri Sejak Dini (Revision requested)
        Material::updateOrCreate(
            ['slug' => 'membangun-sikap-mandiri-sejak-dini'],
            [
                'creator_id' => $pembuat->id,
                'category_id' => Category::where('name', 'Kemandirian')->first()->id,
                'title' => 'Membangun Sikap Mandiri Sejak Dini',
                'description' => 'Panduan melatih kemandirian emosional dan praktis dalam kehidupan sehari-hari.',
                'content' => 'Materi tentang kemandirian.',
                'status' => 'revision',
                'revision_note' => 'Mohon tambahkan contoh studi kasus yang lebih dekat dengan kehidupan siswa SMP/SMA serta tambahkan minimal 3 soal latihan evaluasi.',
            ]
        );

        // Initial Progress for User 1 (Ahmad Fauzi)
        // User 1 has completed Material 1 with score 100
        Progress::updateOrCreate(
            ['user_id' => $user1->id, 'material_id' => $m1->id],
            [
                'status' => 'completed',
                'progress_percentage' => 100,
                'score' => 100,
                'attempts_count' => 1,
                'started_at' => now()->subDays(2),
                'completed_at' => now()->subDays(2)->addMinutes(25),
            ]
        );

        // User 1 has completed Material 2 with score 100
        Progress::updateOrCreate(
            ['user_id' => $user1->id, 'material_id' => $m2->id],
            [
                'status' => 'completed',
                'progress_percentage' => 100,
                'score' => 100,
                'attempts_count' => 1,
                'started_at' => now()->subDays(1),
                'completed_at' => now()->subDays(1)->addMinutes(15),
            ]
        );

        // Initial Progress for User 2 (Siti)
        // User 2 in progress with Material 1
        Progress::updateOrCreate(
            ['user_id' => $user2->id, 'material_id' => $m1->id],
            [
                'status' => 'in_progress',
                'progress_percentage' => 50,
                'score' => 75,
                'attempts_count' => 1,
                'started_at' => now()->subHours(5),
            ]
        );
    }
}
