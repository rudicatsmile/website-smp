<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // PPDB
            [
                'category' => 'ppdb',
                'question' => 'Bagaimana cara mendaftar di SMP Al Wathoniyah 9?',
                'answer' => 'Pendaftaran dapat dilakukan secara online melalui website kami di halaman SPMB, atau datang langsung ke sekolah dengan membawa fotokopi rapor, akta kelahiran, dan KK. Informasi lebih lanjut hubungi panitia PPDB.',
                'keywords' => 'daftar, pendaftaran, cara daftar, masuk, ppdb, spmb',
                'order' => 1,
            ],
            [
                'category' => 'ppdb',
                'question' => 'Kapan pendaftaran PPDB dibuka?',
                'answer' => 'Pendaftaran PPDB biasanya dibuka pada bulan Mei - Juni setiap tahunnya. Untuk jadwal pasti, pantau terus website dan media sosial resmi sekolah.',
                'keywords' => 'jadwal, kapan, buka, pendaftaran, ppdb, tanggal',
                'order' => 2,
            ],
            [
                'category' => 'ppdb',
                'question' => 'Apa saja syarat pendaftaran siswa baru?',
                'answer' => 'Syarat pendaftaran: 1) Fotokopi rapor kelas 4-6 SD, 2) Fotokopi akta kelahiran, 3) Fotokopi KK, 4) Pas foto 3x4 (2 lembar), 5) Mengisi formulir pendaftaran.',
                'keywords' => 'syarat, dokumen, persyaratan, daftar, berkas',
                'order' => 3,
            ],

            // Biaya
            [
                'category' => 'biaya',
                'question' => 'Berapa biaya SPP per bulan?',
                'answer' => 'Biaya SPP di SMP Al Wathoniyah 9 sangat terjangkau. Untuk informasi nominal terbaru, silakan hubungi bagian administrasi sekolah atau kunjungi halaman kontak kami.',
                'keywords' => 'spp, biaya, bayar, uang sekolah, bulanan, harga',
                'order' => 1,
            ],
            [
                'category' => 'biaya',
                'question' => 'Apakah ada biaya pendaftaran?',
                'answer' => 'Biaya pendaftaran relatif terjangkau. Silakan hubungi panitia PPDB untuk informasi biaya terkini karena dapat berubah setiap tahun ajaran.',
                'keywords' => 'biaya daftar, pendaftaran, uang pangkal, formulir',
                'order' => 2,
            ],
            [
                'category' => 'biaya',
                'question' => 'Apakah tersedia beasiswa?',
                'answer' => 'Ya, sekolah menyediakan program beasiswa bagi siswa berprestasi dan siswa kurang mampu. Informasi lebih lanjut dapat ditanyakan ke bagian kesiswaan.',
                'keywords' => 'beasiswa, gratis, keringanan, bantuan, subsidi',
                'order' => 3,
            ],

            // Fasilitas
            [
                'category' => 'fasilitas',
                'question' => 'Apa saja fasilitas yang tersedia di sekolah?',
                'answer' => 'Fasilitas kami meliputi: ruang kelas nyaman, laboratorium komputer, perpustakaan, mushola, lapangan olahraga, kantin sehat, dan area parkir. Lihat halaman Fasilitas untuk detail lengkap.',
                'keywords' => 'fasilitas, gedung, lab, laboratorium, komputer, perpustakaan, lapangan, mushola',
                'order' => 1,
            ],
            [
                'category' => 'fasilitas',
                'question' => 'Apakah ada laboratorium komputer?',
                'answer' => 'Ya, kami memiliki laboratorium komputer dengan akses internet yang digunakan untuk pembelajaran TIK dan praktik siswa.',
                'keywords' => 'lab, komputer, internet, tik, teknologi',
                'order' => 2,
            ],

            // Akademik
            [
                'category' => 'akademik',
                'question' => 'Apa saja ekstrakurikuler yang tersedia?',
                'answer' => 'Ekstrakurikuler yang tersedia antara lain: Pramuka, Paskibra, Marawis, Futsal, Voli, Pencak Silat, dan English Club.',
                'keywords' => 'ekskul, ekstrakurikuler, pramuka, olahraga, seni, klub',
                'order' => 1,
            ],
            [
                'category' => 'akademik',
                'question' => 'Bagaimana kualitas guru di sekolah ini?',
                'answer' => 'Guru-guru kami adalah tenaga pendidik profesional yang kompeten di bidangnya, sebagian besar telah bersertifikasi dan berpengalaman.',
                'keywords' => 'guru, pengajar, kualitas, tenaga pendidik, profesional',
                'order' => 2,
            ],

            // Umum
            [
                'category' => 'umum',
                'question' => 'Dimana lokasi SMP Al Wathoniyah 9?',
                'answer' => 'SMP Al Wathoniyah 9 berlokasi di Jakarta Timur. Alamat lengkap dapat dilihat di halaman Kontak Kami.',
                'keywords' => 'alamat, lokasi, dimana, peta, maps, tempat',
                'order' => 1,
            ],
            [
                'category' => 'umum',
                'question' => 'Bagaimana cara menghubungi sekolah?',
                'answer' => 'Anda dapat menghubungi kami melalui telepon, WhatsApp, email, atau datang langsung ke sekolah. Informasi kontak lengkap tersedia di halaman Kontak.',
                'keywords' => 'kontak, telepon, wa, whatsapp, email, hubungi',
                'order' => 2,
            ],
            [
                'category' => 'umum',
                'question' => 'Jam operasional sekolah?',
                'answer' => 'Kegiatan belajar mengajar berlangsung Senin - Jumat pukul 07.00 - 14.00 WIB. Kantor administrasi buka hingga pukul 15.00.',
                'keywords' => 'jam, waktu, operasional, buka, jadwal, belajar',
                'order' => 3,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
