<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CurriculumPlan;
use App\Models\CurriculumPlanTopic;
use App\Models\LessonSession;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use Illuminate\Database\Seeder;

class CurriculumDemoSeeder extends Seeder
{
    public function run(): void
    {
        $class = SchoolClass::active()->first();
        $subject = MaterialCategory::first();
        $teacher = StaffMember::active()->first();

        if (! $class || ! $subject || ! $teacher) {
            $this->command?->warn('Seeder dilewati: butuh minimal 1 kelas, 1 mapel, 1 guru aktif.');
            return;
        }

        $plan = CurriculumPlan::create([
            'school_class_id' => $class->id,
            'material_category_id' => $subject->id,
            'staff_member_id' => $teacher->id,
            'academic_year' => '2025/2026',
            'semester' => 'ganjil',
            'title' => "{$subject->name} {$class->name} — Semester Ganjil 2025/2026",
            'description' => 'Kurikulum demo untuk testing modul Manajemen Materi Pelajaran Harian.',
            'default_methods' => 'Ceramah, Diskusi, Praktik',
            'default_media' => 'LCD, Papan Tulis',
            'is_active' => true,
            'created_by' => 1,
        ]);

        $topics = [
            ['week' => 1, 'topic' => 'Bab 1: Pengenalan', 'objectives' => 'Siswa memahami konsep dasar'],
            ['week' => 2, 'topic' => 'Bab 2: Pendalaman Materi', 'objectives' => 'Siswa mampu menerapkan konsep'],
            ['week' => 3, 'topic' => 'Bab 3: Latihan Soal', 'objectives' => 'Siswa mampu menyelesaikan soal'],
            ['week' => 4, 'topic' => 'Bab 4: Praktik Mandiri', 'objectives' => 'Siswa mampu bekerja mandiri'],
            ['week' => 5, 'topic' => 'Bab 5: Review & Evaluasi', 'objectives' => 'Evaluasi pemahaman siswa'],
        ];

        foreach ($topics as $t) {
            CurriculumPlanTopic::create([
                'curriculum_plan_id' => $plan->id,
                'week_number' => $t['week'],
                'order' => 0,
                'topic' => $t['topic'],
                'learning_objectives' => $t['objectives'],
                'methods' => 'Ceramah, Tanya Jawab',
                'media' => 'LCD, Buku Paket',
                'default_duration_minutes' => 90,
            ]);
        }

        // Create 3 sample sessions for today
        $today = today();
        $times = [
            ['07:30', '09:00', 'Jam ke-1'],
            ['09:15', '10:45', 'Jam ke-2'],
            ['11:00', '12:30', 'Jam ke-3'],
        ];

        foreach ($times as $i => [$start, $end, $period]) {
            $topic = $topics[$i % count($topics)];
            LessonSession::create([
                'school_class_id' => $class->id,
                'material_category_id' => $subject->id,
                'staff_member_id' => $teacher->id,
                'curriculum_plan_id' => $plan->id,
                'session_date' => $today,
                'start_time' => $start,
                'end_time' => $end,
                'period' => $period,
                'topic' => $topic['topic'],
                'learning_objectives' => $topic['objectives'],
                'methods' => 'Ceramah, Tanya Jawab',
                'media' => 'LCD, Buku Paket',
                'status' => 'published',
            ]);
        }

        $this->command?->info('Seeder demo kurikulum selesai.');
        $this->command?->info("Plan: {$plan->title}");
        $this->command?->info("5 topik + 3 sesi hari ini untuk guru {$teacher->name}");
    }
}
