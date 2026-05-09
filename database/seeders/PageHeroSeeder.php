<?php

namespace Database\Seeders;

use App\Models\PageHero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageHeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heroes = [
            [
                'key' => 'profil',
                'title' => 'Profil Sekolah',
                'subtitle' => 'Mengenal lebih dekat visi, misi, sejarah, dan keluarga besar kami.',
                'icon' => 'building-office-2',
            ],
            [
                'key' => 'akademik',
                'title' => 'Akademik',
                'subtitle' => 'Program dan kegiatan akademik untuk mengembangkan potensi siswa secara menyeluruh.',
                'icon' => 'academic-cap',
            ],
            [
                'key' => 'fasilitas',
                'title' => 'Fasilitas',
                'subtitle' => 'Sarana dan prasarana yang mendukung kegiatan belajar mengajar yang berkualitas.',
                'icon' => 'building-library',
            ],
            [
                'key' => 'prestasi',
                'title' => 'Prestasi Murid',
                'subtitle' => 'Kami percaya setiap anak adalah bintang. Berikut catatan prestasi terbaik siswa-siswi kami.',
                'icon' => 'trophy',
            ],
            [
                'key' => 'galeri',
                'title' => 'Galeri',
                'subtitle' => 'Dokumentasi kegiatan dan momen berharga di lingkungan sekolah kami.',
                'icon' => 'photo',
            ],
            [
                'key' => 'berita',
                'title' => 'Berita & Pengumuman',
                'subtitle' => 'Informasi, pengumuman, dan kegiatan terkini dari lingkungan sekolah kami.',
                'icon' => 'newspaper',
            ],
            [
                'key' => 'download',
                'title' => 'Download',
                'subtitle' => 'Unduh berkas, formulir, dan dokumen resmi sekolah.',
                'icon' => 'arrow-down-tray',
            ],
            [
                'key' => 'kalender',
                'title' => 'Kalender Agenda',
                'subtitle' => 'Jadwal kegiatan, acara, dan libur sekolah untuk orang tua dan siswa.',
                'icon' => 'calendar-days',
            ],
            [
                'key' => 'kontak',
                'title' => 'Kontak Kami',
                'subtitle' => 'Hubungi kami untuk pertanyaan, saran, atau informasi lebih lanjut.',
                'icon' => 'chat-bubble-left-right',
            ],
        ];

        foreach ($heroes as $data) {
            PageHero::updateOrCreate(
                ['key' => $data['key']],
                array_merge($data, [
                    'overlay_from' => 'emerald-600',
                    'overlay_via' => 'emerald-700',
                    'overlay_to' => 'teal-800',
                    'overlay_opacity' => 100,
                    'show_breadcrumb' => true,
                    'is_active' => true,
                ]),
            );
        }
    }
}
