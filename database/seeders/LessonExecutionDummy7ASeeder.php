<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\LessonSession;
use Illuminate\Database\Seeder;

class LessonExecutionDummy7ASeeder extends Seeder
{
    public function run(): void
    {
        // Get the first 4 sessions for class 7A (Bahasa Indonesia)
        $sessions = LessonSession::query()
            ->where('school_class_id', 1) // 7A
            ->where('material_category_id', 4) // Bahasa Indonesia
            ->orderBy('session_date')
            ->take(4)
            ->get();

        if ($sessions->isEmpty()) {
            $this->command?->warn('Tidak ada sesi untuk kelas 7A. Pastikan CurriculumPlanDummy2026Seeder sudah dijalankan.');
            return;
        }

        $executionData = [
            [
                'achievement_percent' => 85,
                'execution_notes'     => 'Pembelajaran berjalan lancar. Siswa aktif berdiskusi tentang ciri-ciri teks deskripsi. Semua siswa dapat mengidentifikasi struktur teks dengan baik.',
                'homework_notes'      => 'Tugas: Baca teks deskripsi halaman 15-20 dan identifikasi bagian deskripsi umum dan deskripsi bagian.',
                'issues_notes'        => 'Beberapa siswa masih kesulitan membedakan deskripsi umum dan deskripsi bagian.',
            ],
            [
                'achievement_percent' => 80,
                'execution_notes'     => 'Siswa diminta menulis teks deskripsi tentang benda atau tempat favorit mereka. Hasil cukup baik, meskipun ada beberapa yang masih perlu perbaikan struktur.',
                'homework_notes'      => 'Tugas: Revisi teks deskripsi yang telah ditulis dan kumpulkan minggu depan.',
                'issues_notes'        => 'Waktu kurang untuk menyelesaikan semua siswa. Perlu alokasi waktu lebih untuk praktik menulis.',
            ],
            [
                'achievement_percent' => 90,
                'execution_notes'     => 'Pembelajaran teks narasi dimulai dengan membaca cerita fantasi bersama. Siswa sangat antusias dan banyak bertanya tentang unsur-unsur cerita.',
                'homework_notes'      => 'Tugas: Baca cerita fantasi "Legenda Nusantara" dan catat tokoh, latar, dan konflik.',
                'issues_notes'        => 'Tidak ada kendala berarti. Kelas sangat kondusif dan siswa sangat responsif.',
            ],
            [
                'achievement_percent' => 78,
                'execution_notes'     => 'Diskusi kelompok tentang unsur-unsur cerita narasi. Setiap kelompok mempresentasikan analisis mereka. Beberapa kelompok masih kurang detail dalam analisis.',
                'homework_notes'      => 'Tugas: Lengkapi tabel analisis unsur cerita untuk semua cerita yang telah dibaca.',
                'issues_notes'        => 'Beberapa siswa kurang aktif dalam diskusi kelompok. Perlu perhatian khusus untuk meningkatkan partisipasi.',
            ],
        ];

        foreach ($sessions as $index => $session) {
            $session->update(array_merge($executionData[$index], [
                'status' => 'completed',
            ]));
        }

        $this->command?->info('Selesai! 4 sesi kelas 7A sudah diisi data realisasi mengajar.');
        $this->command?->info('Sesi 5-8 masih kosong (published) untuk simulasi proses pembelajaran yang sedang berjalan.');
    }
}
