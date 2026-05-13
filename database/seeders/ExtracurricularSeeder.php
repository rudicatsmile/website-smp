<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Extracurricular;
use App\Models\ExtracurricularAchievement;
use App\Models\ExtracurricularSchedule;
use Illuminate\Database\Seeder;

class ExtracurricularSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'slug'        => 'futsal',
                'name'        => 'Futsal',
                'category'    => 'olahraga',
                'description' => '<p>Ekskul futsal melatih kemampuan sepak bola lapangan kecil, kerjasama tim, dan kebugaran jasmani siswa.</p>',
                'location'    => 'Lapangan Olahraga',
                'order'       => 1,
                'schedules'   => [
                    ['day_of_week' => 2, 'start_time' => '14:00', 'end_time' => '16:00'],
                    ['day_of_week' => 5, 'start_time' => '14:00', 'end_time' => '16:00'],
                ],
                'achievements' => [
                    ['title' => 'Juara 2 Turnamen Futsal Antar SMP', 'level' => 'kabupaten', 'rank' => 'Juara 2', 'achieved_at' => '2024-11-10'],
                ],
            ],
            [
                'slug'        => 'pramuka',
                'name'        => 'Pramuka',
                'category'    => 'lainnya',
                'description' => '<p>Gerakan Pramuka membina karakter, kemandirian, dan jiwa kepemimpinan siswa melalui kegiatan alam dan sosial.</p>',
                'location'    => 'Aula & Lapangan',
                'order'       => 2,
                'schedules'   => [
                    ['day_of_week' => 6, 'start_time' => '07:30', 'end_time' => '10:00'],
                ],
                'achievements' => [
                    ['title' => 'Juara 1 Lomba Pionering', 'level' => 'kecamatan', 'rank' => 'Juara 1', 'achieved_at' => '2024-08-17'],
                    ['title' => 'Kontingen Jambore Daerah', 'level' => 'provinsi', 'rank' => 'Partisipan', 'achieved_at' => '2024-10-05'],
                ],
            ],
            [
                'slug'        => 'tahfidz-club',
                'name'        => 'Tahfidz Club',
                'category'    => 'keagamaan',
                'description' => "<p>Program hafalan Al-Qur'an dengan bimbingan intensif, target hafalan bertahap, dan metode muraja'ah rutin.</p>",
                'location'    => 'Musala Sekolah',
                'order'       => 3,
                'schedules'   => [
                    ['day_of_week' => 1, 'start_time' => '06:30', 'end_time' => '07:15'],
                    ['day_of_week' => 3, 'start_time' => '06:30', 'end_time' => '07:15'],
                    ['day_of_week' => 4, 'start_time' => '06:30', 'end_time' => '07:15'],
                ],
                'achievements' => [],
            ],
            [
                'slug'        => 'robotik',
                'name'        => 'Robotik',
                'category'    => 'akademik',
                'description' => '<p>Ekskul robotik mengembangkan kemampuan coding, elektronika, dan problem solving melalui pembuatan robot sederhana.</p>',
                'location'    => 'Lab Komputer',
                'quota'       => 20,
                'order'       => 4,
                'schedules'   => [
                    ['day_of_week' => 4, 'start_time' => '14:00', 'end_time' => '16:30'],
                ],
                'achievements' => [
                    ['title' => 'Juara 3 Kompetisi Robotik Pelajar', 'level' => 'kabupaten', 'rank' => 'Juara 3', 'achieved_at' => '2025-02-20'],
                ],
            ],
        ];

        foreach ($data as $item) {
            $schedules    = $item['schedules'] ?? [];
            $achievements = $item['achievements'] ?? [];
            $quota        = $item['quota'] ?? null;
            unset($item['schedules'], $item['achievements'], $item['quota']);

            $ekskul = Extracurricular::updateOrCreate(
                ['slug' => $item['slug']],
                array_merge($item, ['is_active' => true, 'quota' => $quota])
            );

            foreach ($schedules as $s) {
                ExtracurricularSchedule::firstOrCreate(
                    ['extracurricular_id' => $ekskul->id, 'day_of_week' => $s['day_of_week'], 'start_time' => $s['start_time']],
                    ['end_time' => $s['end_time']]
                );
            }

            foreach ($achievements as $a) {
                ExtracurricularAchievement::firstOrCreate(
                    ['extracurricular_id' => $ekskul->id, 'title' => $a['title']],
                    array_merge($a, ['extracurricular_id' => $ekskul->id])
                );
            }
        }
    }
}
