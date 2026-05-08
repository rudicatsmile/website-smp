<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoAchievementSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Tim Debat Bahasa Inggris',
                'institution' => 'SMP Al Wahoniyah 9',
                'level' => 'Kota',
                'rank' => '10 Besar',
                'excerpt' => '10 Besar Debat Bahasa Inggris pada ajang Festival dan Lomba Kreasi tahun 2026.',
                'achieved_at' => '2026-03-15',
            ],
            [
                'title' => 'Tim Riset Olimpiade',
                'institution' => 'SMP Al Wahoniyah 9',
                'level' => 'Nasional',
                'rank' => 'Lolos Seleksi Proposal',
                'excerpt' => 'Lolos Seleksi Proposal pada ajang Olimpiade Penelitian Siswa Indonesia (OPSI) 2026.',
                'achieved_at' => '2026-04-02',
            ],
            [
                'title' => 'Khalif — Story Telling',
                'institution' => 'SMP Al Wahoniyah 9',
                'level' => 'Kota',
                'rank' => 'Juara Harapan 2',
                'excerpt' => 'Juara Harapan 2 Lomba Storytelling pada Festival dan Lomba Kreasi se-Indonesia.',
                'achieved_at' => '2026-02-18',
            ],
            [
                'title' => 'Tim Tari Kreasi Tradisional',
                'institution' => 'SMP Al Wahoniyah 9',
                'level' => 'Provinsi',
                'rank' => '10 Besar',
                'excerpt' => '10 Besar Lomba Tari Kreasi Tradisional tingkat SMP pada Festival dan Lomba Kreasi se-Indonesia 2026.',
                'achieved_at' => '2026-01-30',
            ],
        ];

        foreach ($items as $i => $data) {
            Achievement::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                array_merge($data, [
                    'description' => '<p>'.$data['excerpt'].'</p><p>Selamat dan sukses kepada para siswa berprestasi yang telah mengharumkan nama sekolah.</p>',
                    'order' => $i,
                    'is_featured' => true,
                    'is_active' => true,
                ])
            );
        }
    }
}
