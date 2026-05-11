<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CounselingMessage;
use App\Models\CounselingTicket;
use App\Models\StaffCategory;
use App\Models\StaffMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CounselingSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Counselor user + StaffMember
        $counselorUser = User::firstOrCreate(
            ['email' => 'bk@smpalwathoniyah9.sch.id'],
            ['name' => 'Ibu Rina (Guru BK)', 'password' => Hash::make('password'), 'is_active' => true],
        );
        if (! $counselorUser->hasRole('counselor')) {
            $counselorUser->assignRole('counselor');
        }

        $category = StaffCategory::firstOrCreate(
            ['slug' => 'bimbingan-konseling'],
            ['name' => 'Bimbingan Konseling', 'order' => 50],
        );

        $counselorStaff = StaffMember::firstOrCreate(
            ['slug' => 'ibu-rina-bk'],
            [
                'staff_category_id' => $category->id,
                'user_id' => $counselorUser->id,
                'name' => 'Ibu Rina',
                'position' => 'Guru BK',
                'is_active' => true,
                'order' => 50,
            ],
        );

        if (! $counselorStaff->user_id) {
            $counselorStaff->update(['user_id' => $counselorUser->id]);
        }

        // 2) Demo tiket
        // (a) Publik anonim baru
        $t1 = CounselingTicket::firstOrCreate(
            ['subject' => 'Merasa cemas menghadapi ujian'],
            [
                'user_id' => null,
                'reporter_name' => null,
                'reporter_contact' => null,
                'category' => 'kesehatan',
                'priority' => 'medium',
                'status' => 'new',
                'body' => 'Saya sering tidak bisa tidur saat dekat hari ujian. Susah konsentrasi belajar.',
                'channel' => 'public',
                'is_anonymous' => true,
                'last_activity_at' => now()->subHours(6),
            ],
        );
        if ($t1->messages()->count() === 0) {
            CounselingMessage::create([
                'counseling_ticket_id' => $t1->id,
                'sender_type' => 'anonymous',
                'body' => $t1->body,
            ]);
        }

        // (b) Portal, in_progress dengan 2 balasan
        $demoStudent = User::where('email', 'siswa@smpalwathoniyah9.sch.id')->first();
        if ($demoStudent) {
            $t2 = CounselingTicket::firstOrCreate(
                ['subject' => 'Kesulitan mengikuti pelajaran Matematika'],
                [
                    'user_id' => $demoStudent->id,
                    'reporter_name' => $demoStudent->name,
                    'reporter_contact' => $demoStudent->email,
                    'category' => 'akademik',
                    'priority' => 'medium',
                    'status' => 'in_progress',
                    'body' => 'Sudah beberapa bab Matematika saya tidak paham. Tidak berani bertanya di kelas.',
                    'channel' => 'portal',
                    'is_anonymous' => false,
                    'assigned_to' => $counselorStaff->id,
                    'last_activity_at' => now()->subHours(2),
                ],
            );
            if ($t2->messages()->count() === 0) {
                CounselingMessage::create([
                    'counseling_ticket_id' => $t2->id,
                    'sender_type' => 'student',
                    'user_id' => $demoStudent->id,
                    'body' => $t2->body,
                    'created_at' => now()->subDay(),
                    'updated_at' => now()->subDay(),
                ]);
                CounselingMessage::create([
                    'counseling_ticket_id' => $t2->id,
                    'sender_type' => 'counselor',
                    'user_id' => $counselorUser->id,
                    'staff_member_id' => $counselorStaff->id,
                    'body' => 'Halo, terima kasih sudah menceritakan. Bagian mana yang paling sulit? Kita cari waktu diskusi bareng ya.',
                    'created_at' => now()->subHours(6),
                    'updated_at' => now()->subHours(6),
                ]);
                CounselingMessage::create([
                    'counseling_ticket_id' => $t2->id,
                    'sender_type' => 'student',
                    'user_id' => $demoStudent->id,
                    'body' => 'Baik Bu, terutama bab pecahan dan pembagian panjang.',
                    'created_at' => now()->subHours(2),
                    'updated_at' => now()->subHours(2),
                ]);
            }

            // (c) Resolved
            CounselingTicket::firstOrCreate(
                ['subject' => 'Masalah pertemanan selesai'],
                [
                    'user_id' => $demoStudent->id,
                    'reporter_name' => $demoStudent->name,
                    'reporter_contact' => $demoStudent->email,
                    'category' => 'pertemanan',
                    'priority' => 'low',
                    'status' => 'resolved',
                    'body' => 'Ada konflik kecil dengan teman sekelas. Sudah selesai setelah bicara.',
                    'channel' => 'portal',
                    'is_anonymous' => false,
                    'assigned_to' => $counselorStaff->id,
                    'resolved_at' => now()->subDays(3),
                    'last_activity_at' => now()->subDays(3),
                ],
            );
        }
    }
}
