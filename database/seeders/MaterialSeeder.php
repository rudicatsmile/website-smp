<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\StaffMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'category' => 'matematika',
                'title' => 'Modul Ajar Bilangan Bulat Kelas 7',
                'excerpt' => 'Materi pengantar bilangan bulat: operasi penjumlahan, pengurangan, perkalian, dan pembagian.',
                'description' => '<p>Modul ini berisi capaian pembelajaran, alur tujuan pembelajaran, asesmen diagnostik, dan rencana kegiatan untuk topik bilangan bulat.</p>',
                'type' => 'modul_ajar', 'grade' => '7', 'phase' => 'D', 'curriculum' => 'merdeka',
                'semester' => '1', 'academic_year' => '2025/2026', 'is_public' => true, 'is_featured' => true,
                'tags' => ['bilangan', 'kelas 7', 'aljabar dasar'],
            ],
            [
                'category' => 'ipa',
                'title' => 'RPP Klasifikasi Makhluk Hidup Kelas 7',
                'excerpt' => 'Rencana pelaksanaan pembelajaran tentang klasifikasi makhluk hidup berbasis 5 kingdom.',
                'description' => '<p>RPP mencakup tujuan pembelajaran, langkah-langkah kegiatan, asesmen formatif, dan refleksi.</p>',
                'type' => 'rpp', 'grade' => '7', 'phase' => 'D', 'curriculum' => 'merdeka',
                'semester' => '1', 'academic_year' => '2025/2026', 'is_public' => true,
                'tags' => ['biologi', 'klasifikasi'],
            ],
            [
                'category' => 'bahasa-indonesia',
                'title' => 'LKPD Teks Deskripsi Kelas 7',
                'excerpt' => 'Lembar kerja peserta didik untuk latihan menyusun teks deskripsi yang efektif.',
                'description' => '<p>LKPD berisi panduan menulis teks deskripsi dengan struktur identifikasi, deskripsi bagian, dan kesimpulan.</p>',
                'type' => 'lkpd', 'grade' => '7', 'phase' => 'D', 'curriculum' => 'merdeka',
                'semester' => '1', 'is_public' => true,
                'tags' => ['teks deskripsi', 'menulis'],
            ],
            [
                'category' => 'bahasa-inggris',
                'title' => 'ATP Bahasa Inggris Fase D',
                'excerpt' => 'Alur Tujuan Pembelajaran Bahasa Inggris Fase D Kurikulum Merdeka.',
                'description' => '<p>ATP menguraikan urutan capaian pembelajaran semester ganjil dan genap.</p>',
                'type' => 'atp', 'grade' => 'umum', 'phase' => 'D', 'curriculum' => 'merdeka',
                'semester' => 'tahunan', 'academic_year' => '2025/2026', 'is_public' => true, 'is_featured' => true,
                'tags' => ['atp', 'fase d'],
            ],
            [
                'category' => 'pai',
                'title' => 'Modul Ajar PAI Akhlak Mulia Kelas 8',
                'excerpt' => 'Modul ajar untuk pembentukan akhlak mulia di kelas 8 dengan pendekatan kontekstual.',
                'description' => '<p>Berisi tujuan pembelajaran, kegiatan inti, dan asesmen sikap.</p>',
                'type' => 'modul_ajar', 'grade' => '8', 'phase' => 'D', 'curriculum' => 'merdeka',
                'semester' => '1', 'is_public' => true,
                'tags' => ['akhlak', 'kelas 8'],
            ],
            [
                'category' => 'ipa',
                'title' => 'Bahan Ajar Internal: Sistem Pernapasan Kelas 8',
                'excerpt' => 'Bahan ajar lengkap khusus internal guru — belum dipublikasikan.',
                'description' => '<p>Bahan ajar mendalam untuk persiapan KBM materi sistem pernapasan.</p>',
                'type' => 'bahan_ajar', 'grade' => '8', 'phase' => 'D', 'curriculum' => 'merdeka',
                'semester' => '2', 'is_public' => false,
                'tags' => ['biologi', 'pernapasan'],
            ],
        ];

        foreach ($samples as $i => $row) {
            $category = MaterialCategory::where('slug', $row['category'])->first();
            if (! $category) {
                continue;
            }

            $author = StaffMember::query()->inRandomOrder()->first();

            Material::updateOrCreate(
                ['slug' => Str::slug($row['title'])],
                [
                    'material_category_id' => $category->id,
                    'staff_member_id' => $author?->id,
                    'title' => $row['title'],
                    'slug' => Str::slug($row['title']),
                    'excerpt' => $row['excerpt'],
                    'description' => $row['description'],
                    'type' => $row['type'],
                    'grade' => $row['grade'],
                    'phase' => $row['phase'] ?? null,
                    'curriculum' => $row['curriculum'],
                    'semester' => $row['semester'],
                    'academic_year' => $row['academic_year'] ?? null,
                    'file_path' => 'materials/sample-' . Str::slug($row['title']) . '.pdf',
                    'file_name' => Str::slug($row['title']) . '.pdf',
                    'file_size' => random_int(150_000, 2_500_000),
                    'file_mime' => 'application/pdf',
                    'tags' => $row['tags'] ?? [],
                    'is_public' => $row['is_public'] ?? true,
                    'is_featured' => $row['is_featured'] ?? false,
                    'is_active' => true,
                    'published_at' => now()->subDays($i),
                    'order' => $i + 1,
                ],
            );
        }
    }
}
