<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'Penerimaan Siswa Baru Tahun Ajaran 2026/2027',
                'message' => 'Pendaftaran siswa baru telah dibuka. Segera daftarkan putra-putri Anda!',
                'link_url' => '/spmb',
                'link_text' => 'Daftar Sekarang',
                'color' => 'emerald',
                'is_active' => true,
                'is_dismissible' => true,
                'order' => 1,
            ],
            [
                'title' => 'Libur Hari Raya',
                'message' => 'Sekolah libur tanggal 10-12 Mei 2026 dalam rangka Hari Raya. Kegiatan belajar mengajar dilanjutkan pada tanggal 13 Mei 2026.',
                'color' => 'amber',
                'is_active' => true,
                'is_dismissible' => true,
                'order' => 2,
            ],
        ];

        foreach ($announcements as $data) {
            Announcement::create($data);
        }
    }
}
