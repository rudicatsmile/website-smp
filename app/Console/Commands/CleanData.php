<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanData extends Command
{
    protected $signature = 'app:clean-data {--force : Lewati konfirmasi}';

    protected $description = 'Truncate semua tabel data transaksional, pertahankan konfigurasi & referensi';

    /**
     * Tabel yang akan di-TRUNCATE.
     * Urutan tidak penting karena FK checks dinonaktifkan sementara.
     */
    private const TRUNCATE_TABLES = [
        // Siswa & Orang Tua
        'students',
        'parent_student',
        'parent_notes',
        'parent_note_messages',

        // Guru & Staff
        'staff_members',
        'staff_schedules',
        'leave_requests',

        // Akademik & KBM
        'lesson_sessions',
        'lesson_session_assignments',
        'lesson_session_materials',
        'curriculum_plans',
        'curriculum_plan_topics',
        'class_materials',
        'class_assignments',
        'class_announcements',
        'assignment_submissions',
        'grades',
        'materials',

        // Soal & Kuis
        'bank_questions',
        'bank_question_options',
        'question_banks',
        'quizzes',
        'quiz_questions',
        'quiz_question_options',
        'quiz_attempts',
        'quiz_answers',

        // Absensi & Pelanggaran
        'student_attendances',
        'student_violations',
        'student_payments',

        // Tahfidz
        'tahfidz_participants',
        'tahfidz_grades',

        // Ekstrakurikuler
        'extracurriculars',
        'extracurricular_members',
        'extracurricular_schedules',
        'extracurricular_gallery_items',
        'extracurricular_achievements',

        // BK (Bimbingan Konseling)
        'counseling_tickets',
        'counseling_messages',

        // Chat & Notifikasi
        'chat_logs',
        'notifications',
        'notification_logs',
        'internal_announcements',
        'internal_announcement_acknowledgements',

        // Konten Website (akan diisi ulang)
        'news',
        'news_tag',
        'sliders',
        'galleries',
        'gallery_items',
        'downloads',
        'school_events',
        'announcements',
        'contact_messages',

        // PPDB (kecuali spmb_periods)
        'spmb_registrations',
        'spmb_documents',

        // Alumni & Tracer
        'alumni',
        'tracer_studies',

        // Activity Log
        'activity_log',
    ];

    /**
     * Tabel yang DIPERTAHANKAN (dokumentasi saja, tidak diproses).
     */
    private const KEEP_TABLES = [
        // Sistem
        'users', 'roles', 'permissions', 'model_has_roles', 'model_has_permissions',
        'role_has_permissions', 'settings', 'media',
        'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs',
        'password_reset_tokens', 'migrations',

        // Referensi
        'school_classes', 'staff_categories', 'news_categories', 'material_categories',
        'tags', 'download_categories',

        // Konfigurasi UI & PPDB
        'page_heroes', 'popups', 'spmb_periods',

        // Konten tetap
        'faqs', 'programs', 'academics', 'facilities', 'achievements',
    ];

    public function handle(): int
    {
        $this->warn('=================================================');
        $this->warn('  PERINGATAN: Operasi ini tidak dapat diurungkan!');
        $this->warn('=================================================');
        $this->newLine();
        $this->info('Tabel yang akan di-truncate (' . count(self::TRUNCATE_TABLES) . ' tabel):');
        $this->line(implode(', ', self::TRUNCATE_TABLES));
        $this->newLine();
        $this->info('Tabel yang DIPERTAHANKAN: ' . implode(', ', self::KEEP_TABLES));
        $this->newLine();

        if (! $this->option('force') && ! $this->confirm('Lanjutkan? Semua data di tabel di atas akan dihapus permanen.', false)) {
            $this->info('Dibatalkan.');
            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('Memulai proses clean...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $truncated = 0;
        $skipped   = 0;

        foreach (self::TRUNCATE_TABLES as $table) {
            try {
                $count = DB::table($table)->count();
                DB::table($table)->truncate();
                $this->line("  <fg=green>✓</> {$table} ({$count} baris dihapus)");
                $truncated++;
            } catch (\Throwable $e) {
                $this->line("  <fg=yellow>⚠</> {$table} — dilewati: " . $e->getMessage());
                $skipped++;
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->newLine();
        $this->info("Selesai: {$truncated} tabel di-truncate, {$skipped} dilewati.");
        $this->newLine();
        $this->comment('Langkah berikutnya:');
        $this->line('  php artisan db:seed --class=ImportStaffFromExcelSeeder');
        $this->line('  php artisan db:seed --class=ImportStudentsFromExcelSeeder');

        return self::SUCCESS;
    }
}
