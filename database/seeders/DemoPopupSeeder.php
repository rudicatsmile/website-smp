<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Popup;
use Illuminate\Database\Seeder;

class DemoPopupSeeder extends Seeder
{
    public function run(): void
    {
        Popup::updateOrCreate(
            ['title' => 'Selamat Datang di SMP Al Wathoniyah 9'],
            [
                'content' => '<p>Pendaftaran Murid Baru tahun ajaran 2026/2027 telah <strong>dibuka</strong>. Segera daftarkan putra/putri Anda sebelum kuota terpenuhi.</p>',
                'image' => null,
                'link_url' => '/spmb',
                'link_text' => 'Daftar SPMB',
                'size' => 'md',
                'order' => 1,
                'show_once' => true,
                'is_active' => true,
            ]
        );
    }
}
