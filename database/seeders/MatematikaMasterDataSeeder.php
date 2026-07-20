<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AssessmentType;
use App\Models\LearningMedia;
use App\Models\LearningMethod;
use App\Models\LearningModel;
use App\Models\LearningObjective;
use App\Models\MaterialCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MatematikaMasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Temukan kategori mata pelajaran Matematika
        $matematika = MaterialCategory::where('slug', 'matematika')
            ->orWhere('name', 'like', '%Matematika%')
            ->first();

        if (! $matematika) {
            $this->command?->warn('Mata Pelajaran Matematika tidak ditemukan di database. Pastikan MaterialCategorySeeder sudah dijalankan.');
            return;
        }

        $subjectId = $matematika->id;

        // 1. Tujuan Pembelajaran (Fokus SMP Kelas 7)
        $objectives = [
            'Memahami dan mengoperasikan bilangan bulat dan pecahan.',
            'Mengenal bentuk aljabar dan melakukan operasi aljabar dasar.',
            'Memahami konsep himpunan, notasi himpunan, dan diagram Venn.',
            'Menyelesaikan persamaan dan pertidaksamaan linear satu variabel.',
            'Memahami konsep perbandingan senilai dan berbalik nilai.'
        ];

        foreach ($objectives as $i => $obj) {
            LearningObjective::firstOrCreate(
                ['material_category_id' => $subjectId, 'name' => $obj],
                ['order' => $i + 1, 'is_active' => true]
            );
        }

        // 2. Model Pembelajaran
        $models = [
            'Problem Based Learning (PBL)',
            'Discovery Learning',
            'Project Based Learning (PjBL)',
            'Cooperative Learning'
        ];

        foreach ($models as $i => $model) {
            LearningModel::firstOrCreate(
                ['material_category_id' => $subjectId, 'name' => $model],
                ['order' => $i + 1, 'is_active' => true]
            );
        }

        // 3. Metode Pembelajaran
        $methods = [
            'Ceramah Interaktif',
            'Diskusi Kelompok',
            'Tanya Jawab',
            'Demonstrasi / Alat Peraga',
            'Latihan Terbimbing'
        ];

        foreach ($methods as $i => $method) {
            LearningMethod::firstOrCreate(
                ['material_category_id' => $subjectId, 'name' => $method],
                ['order' => $i + 1, 'is_active' => true]
            );
        }

        // 4. Jenis Asesmen
        $assessments = [
            'Formatif (Latihan Harian)',
            'Sumatif (Ulangan Harian)',
            'Kuis Lisan / Tulisan',
            'Penugasan / Pekerjaan Rumah (PR)',
            'Penilaian Proyek / Portofolio'
        ];

        foreach ($assessments as $i => $assessment) {
            AssessmentType::firstOrCreate(
                ['material_category_id' => $subjectId, 'name' => $assessment],
                ['order' => $i + 1, 'is_active' => true]
            );
        }

        // 5. Media Pembelajaran
        $media = [
            'Modul Cetak / Buku Paket Matematika Kelas 7',
            'Papan Tulis dan Spidol',
            'Proyektor / LCD untuk Presentasi',
            'Alat Peraga (Blok Aljabar / Bangun Datar)',
            'Video Pembelajaran Interaktif / Animasi'
        ];

        foreach ($media as $i => $med) {
            LearningMedia::firstOrCreate(
                ['material_category_id' => $subjectId, 'name' => $med],
                ['order' => $i + 1, 'is_active' => true]
            );
        }

        $this->command?->info('Master Data Kurikulum Matematika berhasil ditambahkan!');
    }
}
