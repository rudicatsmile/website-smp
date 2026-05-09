<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\InternalAnnouncement;
use App\Models\StaffMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InternalAnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        // Demo teacher user linked to a staff member
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@smpalwahoniyah9.sch.id'],
            [
                'name' => 'Demo Guru',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
        $teacher->syncRoles(['teacher']);

        $firstStaff = StaffMember::active()->whereNull('user_id')->first();
        if ($firstStaff) {
            $firstStaff->update(['user_id' => $teacher->id]);
        }

        $author = User::role(['super_admin', 'admin'])->first() ?? User::first();

        $samples = [
            [
                'title' => 'Rapat Bulanan Dewan Guru',
                'category' => 'rapat',
                'priority' => 'penting',
                'is_pinned' => true,
                'body' => '<p>Diberitahukan kepada seluruh Bapak/Ibu Guru bahwa akan diadakan rapat bulanan dewan guru.</p><ul><li>Hari: Senin minggu pertama</li><li>Pukul: 13.00 WIB</li><li>Tempat: Ruang Rapat</li></ul><p>Agenda: evaluasi pembelajaran bulan berjalan & rencana kegiatan bulan depan.</p>',
                'target_roles' => ['semua_guru'],
                'expires_at' => now()->addWeeks(2),
            ],
            [
                'title' => 'Surat Tugas Pelatihan Kurikulum Merdeka',
                'category' => 'surat_tugas',
                'priority' => 'urgent',
                'is_pinned' => false,
                'body' => '<p>Bapak/Ibu yang ditunjuk akan mengikuti pelatihan implementasi Kurikulum Merdeka. Mohon hadir tepat waktu dan membawa laptop masing-masing.</p>',
                'target_roles' => [],
                'target_staff_ids' => StaffMember::active()->take(2)->pluck('id')->toArray(),
                'expires_at' => now()->addWeek(),
            ],
            [
                'title' => 'Agenda Dinas Kepala Sekolah Pekan Ini',
                'category' => 'agenda_dinas',
                'priority' => 'normal',
                'body' => '<p>Berikut agenda dinas Kepala Sekolah pekan ini:</p><ol><li>Senin: Rapat MKKS</li><li>Rabu: Visitasi pengawas</li><li>Jumat: Rapat komite sekolah</li></ol>',
                'target_roles' => ['semua_guru', 'staf'],
            ],
            [
                'title' => 'Pengingat Pengisian Jurnal Mengajar',
                'category' => 'umum',
                'priority' => 'normal',
                'body' => '<p>Mohon Bapak/Ibu Guru rutin mengisi jurnal mengajar harian melalui sistem yang tersedia. Jurnal akan direkap setiap akhir bulan.</p>',
                'target_roles' => ['semua_guru'],
            ],
            [
                'title' => 'Pengumuman Kedaluwarsa (Test)',
                'category' => 'umum',
                'priority' => 'normal',
                'body' => '<p>Pengumuman ini sudah kedaluwarsa dan tidak boleh muncul untuk guru.</p>',
                'target_roles' => ['semua_guru'],
                'published_at' => now()->subMonth(),
                'expires_at' => now()->subDays(3),
            ],
        ];

        foreach ($samples as $i => $data) {
            InternalAnnouncement::updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                array_merge([
                    'user_id' => $author?->id,
                    'published_at' => now()->subDays($i),
                    'is_active' => true,
                    'attachments' => [],
                    'target_roles' => $data['target_roles'] ?? [],
                    'target_staff_ids' => $data['target_staff_ids'] ?? [],
                ], $data),
            );
        }
    }
}
