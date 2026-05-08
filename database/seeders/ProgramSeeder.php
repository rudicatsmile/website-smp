<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'title' => 'Program Tahfidz Al-Quran',
                'slug' => 'program-tahfidz-al-quran',
                'icon' => 'book-open',
                'excerpt' => 'Program menghafal Al-Quran dengan metode yang terstruktur dan bimbingan khusus dari guru yang berpengalaman.',
                'description' => '<p>Program Tahfidz Al-Quran dirancang untuk membantu siswa menghafal Al-Quran dengan metode yang terstruktur dan efektif. Siswa akan mendapatkan bimbingan khusus dari guru-guru yang berpengalaman dalam bidang tahfidz.</p><p>Program ini mencakup:</p><ul><li>Hafalan juz 30 terlebih dahulu</li><li>Tilawah dan tajwid</li><li>Murajaah (pengulangan hafalan)</li><li>Evaluasi berkala</li></ul>',
                'button_text' => 'Lihat Detail',
                'order' => 1,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Ekstrakurikuler Robotik',
                'slug' => 'ekstrakurikuler-robotik',
                'icon' => 'cpu-chip',
                'excerpt' => 'Mengembangkan kreativitas dan kemampuan teknologi siswa melalui pembelajaran robotik dan pemrograman.',
                'description' => '<p>Ekstrakurikuler Robotik bertujuan untuk mengembangkan kreativitas dan kemampuan teknologi siswa. Siswa akan belajar merakit dan memprogram robot sederhana hingga kompleks.</p><p>Kegiatan meliputi:</p><ul><li>Perakitan robot</li><li>Pemrograman dasar (Scratch, Arduino)</li><li>Proyek tim</li><li>Kompetisi robotik</li></ul>',
                'button_text' => 'Lihat Detail',
                'order' => 2,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Program Bahasa Asing',
                'slug' => 'program-bahasa-asing',
                'icon' => 'language',
                'excerpt' => 'Pembelajaran bahasa Arab dan Inggris untuk membekali siswa dengan kemampuan komunikasi global.',
                'description' => '<p>Program Bahasa Asing fokus pada pembelajaran bahasa Arab dan Inggris untuk membekali siswa dengan kemampuan komunikasi global. Metode pembelajaran interaktif dan menyenangkan.</p><p>Program ini mencakup:</p><ul><li>Kelas bahasa Arab</li><li>Kelas bahasa Inggris</li><li>Klub bahasa</li><li>English Camp</li></ul>',
                'button_text' => 'Lihat Detail',
                'order' => 3,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Program Olahraga & Seni',
                'slug' => 'program-olahraga-dan-seni',
                'icon' => 'musical-note',
                'excerpt' => 'Mengembangkan bakat olahraga dan seni siswa melalui berbagai kegiatan ekstrakurikuler.',
                'description' => '<p>Program Olahraga & Seni bertujuan untuk mengembangkan bakat siswa di bidang olahraga dan seni. Tersedia berbagai pilihan ekstrakurikuler sesuai minat siswa.</p><p>Pilihan kegiatan:</p><ul><li>Futsal dan basket</li><li>Seni tari dan musik</li><li>Paduan suara</li><li>Kaligrafi</li></ul>',
                'button_text' => 'Lihat Detail',
                'order' => 4,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Program Bimbingan Konseling',
                'slug' => 'program-bimbingan-konseling',
                'icon' => 'heart',
                'excerpt' => 'Layanan bimbingan dan konseling untuk mendukung perkembangan emosional dan akademik siswa.',
                'description' => '<p>Program Bimbingan Konseling menyediakan layanan dukungan untuk perkembangan emosional dan akademik siswa. Konselor profesional siap membantu siswa mengatasi berbagai tantangan.</p><p>Layanan yang tersedia:</p><ul><li>Konseling individu</li><li>Konseling kelompok</li><li>Bimbingan karir</li><li>Workshop pengembangan diri</li></ul>',
                'button_text' => 'Lihat Detail',
                'order' => 5,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => 'Program Literasi Digital',
                'slug' => 'program-literasi-digital',
                'icon' => 'device-phone-mobile',
                'excerpt' => 'Membekali siswa dengan kemampuan menggunakan teknologi secara bijak dan produktif.',
                'description' => '<p>Program Literasi Digital bertujuan untuk membekali siswa dengan kemampuan menggunakan teknologi secara bijak dan produktif. Siswa belajar tentang keamanan internet dan etika digital.</p><p>Materi pembelajaran:</p><ul><li>Keamanan internet</li><li>Etika digital</li><li>Penggunaan media sosial yang bijak</li><li>Produktivitas digital</li></ul>',
                'button_text' => 'Lihat Detail',
                'order' => 6,
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
