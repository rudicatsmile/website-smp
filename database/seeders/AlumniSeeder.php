<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Alumni;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name'                  => 'Ahmad Fajar Ramadhan',
                'graduation_year'       => 2018,
                'current_status'        => 'working',
                'company_or_institution'=> 'Bank BRI',
                'position'              => 'Software Engineer',
                'city'                  => 'Jakarta',
                'quote'                 => 'Disiplin yang diajarkan di SMP Al Wathoniyah 9 menjadi fondasi karier saya.',
                'story'                 => '<p>Setelah lulus dari SMP Al Wathoniyah 9 tahun 2018, Ahmad melanjutkan ke SMA terbaik di Jakarta dan kemudian berhasil meraih beasiswa di Universitas Indonesia jurusan Teknik Informatika.</p><p>Kini Ahmad bekerja sebagai Software Engineer di Bank BRI dan aktif berkontribusi dalam inovasi layanan digital perbankan nasional.</p>',
                'is_featured'           => true,
                'is_published'          => true,
                'order'                 => 1,
            ],
            [
                'name'                  => 'Siti Nurhaliza Putri',
                'graduation_year'       => 2019,
                'current_status'        => 'studying',
                'company_or_institution'=> 'Universitas Gadjah Mada',
                'position'              => 'Kedokteran Umum',
                'city'                  => 'Yogyakarta',
                'quote'                 => 'Guru-guru di sini luar biasa sabar dan penuh inspirasi.',
                'story'                 => '<p>Siti adalah salah satu siswi terbaik angkatan 2019. Kecintaannya pada ilmu biologi tumbuh saat masih bersekolah di SMP Al Wathoniyah 9.</p><p>Berkat kerja keras dan doa orang tua, kini ia menempuh pendidikan Kedokteran di UGM dan bermimpi menjadi dokter spesialis anak.</p>',
                'is_featured'           => true,
                'is_published'          => true,
                'order'                 => 2,
            ],
            [
                'name'                  => 'Rizky Maulana Hakim',
                'graduation_year'       => 2017,
                'current_status'        => 'entrepreneur',
                'company_or_institution'=> 'CV. Maulana Kreatif',
                'position'              => 'Founder & CEO',
                'city'                  => 'Bekasi',
                'quote'                 => 'Keberanian untuk bermimpi besar saya dapatkan dari lingkungan sekolah ini.',
                'story'                 => '<p>Rizky tidak langsung kuliah setelah SMA. Ia memulai usaha kecil-kecilan di bidang desain grafis dari kamarnya. Dalam 5 tahun, usahanya berkembang menjadi perusahaan kreatif dengan 12 karyawan.</p><p>Rizky membuktikan bahwa jiwa wirausaha bisa tumbuh dari sekolah yang menanamkan nilai kerja keras dan kreativitas.</p>',
                'is_featured'           => true,
                'is_published'          => true,
                'order'                 => 3,
            ],
            [
                'name'                  => 'Dewi Rahayu Lestari',
                'graduation_year'       => 2020,
                'current_status'        => 'both',
                'company_or_institution'=> 'Universitas Trisakti',
                'position'              => 'Manajemen Bisnis',
                'city'                  => 'Jakarta',
                'quote'                 => 'SMP Al Wathoniyah 9 mengajarkan saya arti tanggung jawab.',
                'story'                 => '<p>Dewi adalah contoh nyata generasi multitasking. Sambil kuliah Manajemen Bisnis di Trisakti, ia aktif magang di perusahaan startup dan memiliki usaha reseller online sendiri.</p>',
                'is_featured'           => false,
                'is_published'          => true,
                'order'                 => 4,
            ],
        ];

        foreach ($data as $item) {
            Alumni::create(array_merge($item, [
                'slug' => Str::slug($item['name']),
            ]));
        }
    }
}
