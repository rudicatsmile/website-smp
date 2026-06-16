<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LessonSession;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use App\Models\StaffCategory;
use App\Models\MaterialCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LessonSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Kategori Guru (StaffCategory)
        $staffCategory = StaffCategory::firstOrCreate(
            ['name' => 'Guru Pelajaran'],
            ['slug' => 'guru-pelajaran', 'is_active' => true]
        );

        // 2. Buat 5 Guru (StaffMember + User)
        $teachers = [];
        $teacherNames = ['Bpk. Budi Santoso', 'Ibu Siti Aminah', 'Bpk. Joko Anwar', 'Ibu Rina Mariana', 'Bpk. Ahmad Dahlan'];
        foreach ($teacherNames as $i => $name) {
            $email = 'guru' . ($i + 1) . '@sekolah.com';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => bcrypt('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $teachers[] = StaffMember::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'nip' => '1000' . $i,
                    'staff_category_id' => $staffCategory->id,
                    'joined_at' => now(),
                ]
            );
        }

        // 3. Buat 5 Kelas (SchoolClass)
        $classes = [];
        $classNames = ['7A', '7B', '8A', '8B', '9A'];
        foreach ($classNames as $name) {
            $classes[] = SchoolClass::firstOrCreate(
                ['name' => $name],
                ['grade_level' => substr($name, 0, 1), 'capacity' => 30, 'is_active' => true]
            );
        }

        // 4. Buat 5 Mata Pelajaran (MaterialCategory)
        $subjects = [];
        $subjectNames = ['Matematika', 'IPA Terpadu', 'Bahasa Indonesia', 'Sejarah', 'Bahasa Inggris'];
        foreach ($subjectNames as $name) {
            $subjects[] = MaterialCategory::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
        }

        // 5. Data Sesi
        $sessionsData = [
            [
                'topic' => 'Persamaan Kuadrat',
                'status' => 'completed',
                'achievement_percent' => 100,
            ],
            [
                'topic' => 'Sistem Reproduksi Manusia',
                'status' => 'completed',
                'achievement_percent' => 90,
            ],
            [
                'topic' => 'Teks Eksposisi',
                'status' => 'ongoing',
                'achievement_percent' => 50,
            ],
            [
                'topic' => 'Sejarah Kemerdekaan Indonesia',
                'status' => 'published',
                'achievement_percent' => 0,
            ],
            [
                'topic' => 'Simple Present Tense',
                'status' => 'draft',
                'achievement_percent' => 0,
            ],
        ];

        // 6. Insert Sesi Pelajaran
        foreach ($sessionsData as $index => $data) {
            $teacher = $teachers[$index];
            $class = $classes[$index];
            $subject = $subjects[$index];

            $startTime = Carbon::today()->addHours(7 + $index);
            $endTime = (clone $startTime)->addMinutes(90);

            LessonSession::create([
                'staff_member_id' => $teacher->id,
                'school_class_id' => $class->id,
                'material_category_id' => $subject->id,
                'topic' => $data['topic'],
                'session_date' => Carbon::today(),
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'period' => $index + 1,
                'status' => $data['status'],
                'achievement_percent' => $data['achievement_percent'],
                'notes' => 'Catatan otomatis dari seeder.',
            ]);
        }

        $this->command->info('Berhasil membuat 5 data Guru, Kelas, Mapel, dan Lesson Session!');
    }
}
