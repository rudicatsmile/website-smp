<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CurriculumPlan;
use App\Models\CurriculumPlanTopic;
use App\Models\LessonSession;
use Illuminate\Database\Seeder;

class CurriculumPlanDummy2026Seeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'school_class_id'      => 1, // 7A
                'material_category_id' => 4, // Bahasa Indonesia
                'staff_member_id'      => 1, // Abdul Ghofar
                'title'                => 'Bahasa Indonesia Kelas 7A — Semester Ganjil 2026/2027',
                'learning_model_ids'   => [1], // Discovering
                'default_methods'      => [1, 2], // Ceramah, Diskusi
                'default_media'        => [1, 2], // LCD, Papan Tulis
                'session_day'          => 'monday',
                'session_start_week'   => '2026-06-02',
                'topics'               => [
                    ['week' => 1, 'order' => 1, 'topic' => 'Teks Deskripsi: Ciri-Ciri dan Struktur',          'objectives' => [1],    'methods' => [1, 2], 'media' => [1, 2], 'assessment_plan' => 'Observasi dan tanya jawab lisan'],
                    ['week' => 2, 'order' => 1, 'topic' => 'Teks Deskripsi: Menulis Teks Deskripsi',           'objectives' => [1, 2], 'methods' => [2, 3], 'media' => [1, 2], 'assessment_plan' => 'Tugas menulis teks deskripsi'],
                    ['week' => 3, 'order' => 1, 'topic' => 'Teks Narasi: Mengenal Cerita Fantasi',             'objectives' => [1],    'methods' => [1, 2], 'media' => [1, 3], 'assessment_plan' => 'Kuis identifikasi cerita'],
                    ['week' => 4, 'order' => 1, 'topic' => 'Teks Narasi: Mengidentifikasi Unsur Cerita',       'objectives' => [1, 2], 'methods' => [2],    'media' => [1, 2], 'assessment_plan' => 'Diskusi kelompok'],
                    ['week' => 5, 'order' => 1, 'topic' => 'Teks Narasi: Menceritakan Kembali Isi Cerita',     'objectives' => [2],    'methods' => [2, 3], 'media' => [1],    'assessment_plan' => 'Presentasi lisan'],
                    ['week' => 6, 'order' => 1, 'topic' => 'Teks Prosedur: Struktur dan Ciri Kebahasaan',      'objectives' => [1],    'methods' => [1, 4], 'media' => [1, 2], 'assessment_plan' => 'Identifikasi teks prosedur'],
                    ['week' => 7, 'order' => 1, 'topic' => 'Teks Prosedur: Menulis Teks Prosedur',             'objectives' => [1, 2], 'methods' => [2],    'media' => [1, 3], 'assessment_plan' => 'Tugas menulis prosedur'],
                    ['week' => 8, 'order' => 1, 'topic' => 'Review & Ulangan Harian Semester Ganjil',          'objectives' => [1, 2], 'methods' => [1],    'media' => [1, 2], 'assessment_plan' => 'Ulangan harian tertulis'],
                ],
            ],
            [
                'school_class_id'      => 2, // 7B
                'material_category_id' => 2, // IPA
                'staff_member_id'      => 2, // Abdul Hafidz
                'title'                => 'IPA Kelas 7B — Semester Ganjil 2026/2027',
                'learning_model_ids'   => [2], // PBL
                'default_methods'      => [2, 3], // Diskusi, Presentasi
                'default_media'        => [1, 3], // LCD, Buku Tulis
                'session_day'          => 'tuesday',
                'session_start_week'   => '2026-06-03',
                'topics'               => [
                    ['week' => 1, 'order' => 1, 'topic' => 'Hakikat Ilmu Sains dan Metode Ilmiah',             'objectives' => [1],    'methods' => [1, 2], 'media' => [1, 2], 'assessment_plan' => 'Tanya jawab dan diskusi'],
                    ['week' => 2, 'order' => 1, 'topic' => 'Keselamatan Kerja di Laboratorium',                'objectives' => [1, 2], 'methods' => [1, 4], 'media' => [1, 2], 'assessment_plan' => 'Observasi praktik keselamatan'],
                    ['week' => 3, 'order' => 1, 'topic' => 'Pengukuran: Besaran dan Satuan',                   'objectives' => [1],    'methods' => [1, 2], 'media' => [1, 3], 'assessment_plan' => 'Latihan soal besaran'],
                    ['week' => 4, 'order' => 1, 'topic' => 'Pengukuran: Alat Ukur dan Ketelitian',             'objectives' => [1, 2], 'methods' => [4, 5], 'media' => [1, 2], 'assessment_plan' => 'Praktik pengukuran'],
                    ['week' => 5, 'order' => 1, 'topic' => 'Klasifikasi Makhluk Hidup: Pengantar',             'objectives' => [1],    'methods' => [1, 2], 'media' => [1, 3], 'assessment_plan' => 'Identifikasi ciri makhluk hidup'],
                    ['week' => 6, 'order' => 1, 'topic' => 'Klasifikasi Makhluk Hidup: Kunci Dikotomis',       'objectives' => [1, 2], 'methods' => [2, 3], 'media' => [1, 2], 'assessment_plan' => 'Tugas kunci dikotomis'],
                    ['week' => 7, 'order' => 1, 'topic' => 'Klasifikasi Makhluk Hidup: Lima Kingdom',          'objectives' => [1, 2], 'methods' => [2, 3], 'media' => [1, 3], 'assessment_plan' => 'Presentasi kingdom'],
                    ['week' => 8, 'order' => 1, 'topic' => 'Review & Ulangan Harian IPA Semester Ganjil',      'objectives' => [1, 2], 'methods' => [1],    'media' => [1, 2], 'assessment_plan' => 'Ulangan harian tertulis'],
                ],
            ],
            [
                'school_class_id'      => 3, // 8A
                'material_category_id' => 3, // IPS
                'staff_member_id'      => 3, // ANISSA
                'title'                => 'IPS Kelas 8A — Semester Ganjil 2026/2027',
                'learning_model_ids'   => [3], // PJBL
                'default_methods'      => [1, 4], // Ceramah, Demonstrasi
                'default_media'        => [1, 2, 3], // LCD, Papan Tulis, Buku Tulis
                'session_day'          => 'wednesday',
                'session_start_week'   => '2026-06-04',
                'topics'               => [
                    ['week' => 1, 'order' => 1, 'topic' => 'Kondisi Geografis Indonesia',                      'objectives' => [1],    'methods' => [1, 2], 'media' => [1, 2], 'assessment_plan' => 'Tanya jawab peta Indonesia'],
                    ['week' => 2, 'order' => 1, 'topic' => 'Sumber Daya Alam Indonesia',                       'objectives' => [1, 2], 'methods' => [1, 2], 'media' => [1, 3], 'assessment_plan' => 'Diskusi SDA setempat'],
                    ['week' => 3, 'order' => 1, 'topic' => 'Keunggulan dan Keterbatasan Antarruang',            'objectives' => [1],    'methods' => [2, 3], 'media' => [1, 2], 'assessment_plan' => 'Analisis studi kasus'],
                    ['week' => 4, 'order' => 1, 'topic' => 'Perdagangan Antardaerah dan Antarpulau',            'objectives' => [1, 2], 'methods' => [2, 3], 'media' => [1, 2], 'assessment_plan' => 'Presentasi kelompok'],
                    ['week' => 5, 'order' => 1, 'topic' => 'Perdagangan Internasional',                        'objectives' => [1, 2], 'methods' => [1, 4], 'media' => [1, 3], 'assessment_plan' => 'Latihan soal perdagangan'],
                    ['week' => 6, 'order' => 1, 'topic' => 'Pengaruh Interaksi Sosial terhadap Pembangunan',   'objectives' => [1],    'methods' => [2, 3], 'media' => [1, 2], 'assessment_plan' => 'Essay singkat'],
                    ['week' => 7, 'order' => 1, 'topic' => 'Mobilitas Sosial: Bentuk dan Dampaknya',           'objectives' => [1, 2], 'methods' => [2],    'media' => [1, 3], 'assessment_plan' => 'Diskusi studi kasus'],
                    ['week' => 8, 'order' => 1, 'topic' => 'Review & Ulangan Harian IPS Semester Ganjil',      'objectives' => [1, 2], 'methods' => [1],    'media' => [1, 2], 'assessment_plan' => 'Ulangan harian tertulis'],
                ],
            ],
        ];

        $sessionTimes = [
            ['07:30', '09:00', 'Jam ke-1'],
            ['09:15', '10:45', 'Jam ke-2'],
            ['11:00', '12:30', 'Jam ke-3'],
            ['12:45', '14:15', 'Jam ke-4'],
            ['07:30', '09:00', 'Jam ke-1'],
            ['09:15', '10:45', 'Jam ke-2'],
            ['11:00', '12:30', 'Jam ke-3'],
            ['12:45', '14:15', 'Jam ke-4'],
        ];

        foreach ($plans as $planData) {
            $plan = CurriculumPlan::create([
                'school_class_id'      => $planData['school_class_id'],
                'material_category_id' => $planData['material_category_id'],
                'staff_member_id'      => $planData['staff_member_id'],
                'academic_year'        => '2026/2027',
                'semester'             => 'ganjil',
                'title'                => $planData['title'],
                'time_allocation'      => '2 x 40 menit',
                'learning_objective_ids' => [1, 2],
                'learning_model_ids'   => $planData['learning_model_ids'],
                'default_methods'      => $planData['default_methods'],
                'default_media'        => $planData['default_media'],
                'is_active'            => true,
                'created_by'           => 1,
            ]);

            $this->command?->info("Plan dibuat: {$plan->title}");

            $topicModels = [];
            foreach ($planData['topics'] as $topicData) {
                $topic = CurriculumPlanTopic::create([
                    'curriculum_plan_id'       => $plan->id,
                    'week_number'              => $topicData['week'],
                    'order'                    => $topicData['order'],
                    'topic'                    => $topicData['topic'],
                    'learning_objectives'      => $topicData['objectives'],
                    'methods'                  => $topicData['methods'],
                    'media'                    => $topicData['media'],
                    'assessment_plan'          => $topicData['assessment_plan'],
                    'default_duration_minutes' => 80,
                ]);
                $topicModels[$topicData['week']] = $topic;
            }

            // Generate 8 session dates (weekly from start)
            $startDate = \Carbon\Carbon::parse($planData['session_start_week']);
            for ($week = 0; $week < 8; $week++) {
                $sessionDate = $startDate->copy()->addWeeks($week);
                [$start, $end, $period] = $sessionTimes[$week];
                $topicIndex  = $week + 1;
                $topicModel  = $topicModels[$topicIndex] ?? null;
                $topicData   = $planData['topics'][$week];

                LessonSession::create([
                    'school_class_id'          => $planData['school_class_id'],
                    'material_category_id'     => $planData['material_category_id'],
                    'staff_member_id'          => $planData['staff_member_id'],
                    'curriculum_plan_id'       => $plan->id,
                    'curriculum_plan_topic_id' => $topicModel?->id,
                    'session_date'             => $sessionDate->toDateString(),
                    'start_time'               => $start,
                    'end_time'                 => $end,
                    'period'                   => $period,
                    'topic'                    => $topicData['topic'],
                    'learning_objectives'      => $topicData['objectives'],
                    'methods'                  => $topicData['methods'],
                    'media'                    => $topicData['media'],
                    'assessment_plan'          => $topicData['assessment_plan'],
                    'status'                   => 'published',
                    'notes'                    => "Pertemuan minggu ke-{$topicIndex}",
                ]);
            }

            $this->command?->info("  → 8 topik + 8 sesi dibuat.");
        }

        $this->command?->info('Selesai! Total: 3 curriculum plan, 24 topik, 24 sesi mengajar.');
    }
}
