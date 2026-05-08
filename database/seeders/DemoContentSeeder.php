<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Academic;
use App\Models\DownloadCategory;
use App\Models\Facility;
use App\Models\NewsCategory;
use App\Models\Slider;
use App\Models\SpmbPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        // Slider demo
        Slider::firstOrCreate(
            ['title' => 'Selamat Datang di SMP Al Wahoniyah 9'],
            [
                'subtitle' => 'Mendidik Generasi Berakhlak & Berprestasi',
                'image' => 'sliders/demo-1.jpg',
                'link_url' => '/profil',
                'link_text' => 'Pelajari Lebih Lanjut',
                'order' => 1,
                'is_active' => true,
            ],
        );

        // News categories
        foreach (['Pengumuman', 'Kegiatan', 'Prestasi', 'Akademik'] as $cat) {
            NewsCategory::firstOrCreate(['slug' => Str::slug($cat)], ['name' => $cat]);
        }

        // Download categories
        foreach (['Formulir', 'Kurikulum', 'Surat Edaran'] as $cat) {
            DownloadCategory::firstOrCreate(['slug' => Str::slug($cat)], ['name' => $cat]);
        }

        // Facilities
        $facilities = [
            ['name' => 'Perpustakaan', 'description' => 'Koleksi buku lengkap untuk menunjang pembelajaran.', 'icon' => 'heroicon-o-book-open'],
            ['name' => 'Laboratorium IPA', 'description' => 'Lab IPA modern untuk eksperimen siswa.', 'icon' => 'heroicon-o-beaker'],
            ['name' => 'Lab Komputer', 'description' => 'Pelatihan komputer & teknologi.', 'icon' => 'heroicon-o-computer-desktop'],
            ['name' => 'Masjid', 'description' => 'Tempat ibadah yang nyaman.', 'icon' => 'heroicon-o-building-library'],
            ['name' => 'Lapangan Olahraga', 'description' => 'Lapangan multifungsi.', 'icon' => 'heroicon-o-trophy'],
        ];
        foreach ($facilities as $i => $f) {
            Facility::firstOrCreate(
                ['slug' => Str::slug($f['name'])],
                array_merge($f, ['order' => $i + 1, 'is_active' => true]),
            );
        }

        // Academic
        $academics = [
            ['name' => 'Kurikulum Merdeka', 'head_name' => 'Wakasek Kurikulum', 'description' => 'Penerapan kurikulum merdeka di seluruh tingkatan.'],
            ['name' => 'Tahfidz Quran', 'head_name' => 'Koordinator Tahfidz', 'description' => 'Program hafalan Al-Quran terstruktur.'],
            ['name' => 'Bahasa Arab & Inggris', 'head_name' => 'Koordinator Bahasa', 'description' => 'Pembelajaran bahasa intensif.'],
        ];
        foreach ($academics as $i => $a) {
            Academic::firstOrCreate(
                ['slug' => Str::slug($a['name'])],
                array_merge($a, ['order' => $i + 1, 'is_active' => true]),
            );
        }

        // SPMB period
        SpmbPeriod::firstOrCreate(
            ['name' => 'SPMB ' . now()->year . '/' . (now()->year + 1)],
            [
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
                'quota' => 200,
                'fee' => 250000,
                'description' => 'Penerimaan murid baru tahun ajaran ' . now()->year . '/' . (now()->year + 1),
                'is_active' => true,
            ],
        );
    }
}
