<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ClassAnnouncement;
use App\Models\ClassAssignment;
use App\Models\ClassMaterial;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PortalContentSeeder extends Seeder
{
    public function run(): void
    {
        $year = '2025/2026';
        $teacher = StaffMember::active()->first();

        // 1) Classes
        $classDefs = [
            ['grade' => 7, 'section' => 'A'],
            ['grade' => 7, 'section' => 'B'],
            ['grade' => 8, 'section' => 'A'],
            ['grade' => 8, 'section' => 'B'],
            ['grade' => 9, 'section' => 'A'],
            ['grade' => 9, 'section' => 'B'],
        ];
        $classes = [];
        foreach ($classDefs as $i => $def) {
            $classes[] = SchoolClass::firstOrCreate(
                ['grade' => $def['grade'], 'section' => $def['section'], 'academic_year' => $year],
                [
                    'name' => $def['grade'] . $def['section'],
                    'homeroom_teacher_id' => $teacher?->id,
                    'is_active' => true,
                    'order' => $i,
                ],
            );
        }

        // 2) Demo students (1 demo akun + 19 dummy)
        $kelas7A = SchoolClass::where('name', '7A')->where('academic_year', $year)->first();

        $demoUser = User::firstOrCreate(
            ['email' => 'siswa@smpalwahoniyah9.sch.id'],
            ['name' => 'Demo Siswa', 'password' => Hash::make('password'), 'is_active' => true],
        );
        if (! $demoUser->hasRole('student')) {
            $demoUser->assignRole('student');
        }

        Student::firstOrCreate(
            ['nis' => '0001'],
            [
                'user_id' => $demoUser->id,
                'school_class_id' => $kelas7A?->id,
                'name' => 'Demo Siswa',
                'slug' => 'demo-siswa',
                'gender' => 'L',
                'is_active' => true,
            ],
        );

        $names = [
            'Ahmad Fauzi', 'Budi Santoso', 'Citra Dewi', 'Dani Kurniawan', 'Eka Putri',
            'Fajar Nugroho', 'Gita Lestari', 'Hadi Pratama', 'Indah Sari', 'Joko Susilo',
            'Kartika Ayu', 'Lukman Hakim', 'Maya Andini', 'Nanda Ramadhan', 'Olivia Putri',
            'Putra Ramadhan', 'Qori Aulia', 'Ridho Maulana', 'Sari Widyastuti', 'Tegar Adi',
        ];
        foreach ($names as $i => $name) {
            $class = $classes[$i % count($classes)];
            Student::firstOrCreate(
                ['nis' => str_pad((string) ($i + 100), 4, '0', STR_PAD_LEFT)],
                [
                    'school_class_id' => $class->id,
                    'name' => $name,
                    'slug' => Str::slug($name . '-' . $i),
                    'gender' => $i % 2 === 0 ? 'L' : 'P',
                    'is_active' => true,
                ],
            );
        }

        // 3) Assignments
        $subject = MaterialCategory::first();
        $assignmentDefs = [
            ['title' => 'Tugas Matematika: Soal Latihan Bab 1', 'class' => '7A', 'due' => now()->addDays(3), 'pub' => true],
            ['title' => 'PR Bahasa Indonesia: Menulis Teks Narasi', 'class' => '7A', 'due' => now()->addDays(7), 'pub' => true],
            ['title' => 'Tugas IPA: Praktikum Sederhana', 'class' => '7A', 'due' => now()->subDays(2), 'pub' => true],
            ['title' => 'Tugas IPS: Peta Indonesia', 'class' => '7B', 'due' => now()->addDays(5), 'pub' => true],
            ['title' => 'Tugas Bahasa Inggris: Vocabulary List', 'class' => '8A', 'due' => now()->addDays(2), 'pub' => true],
            ['title' => 'Tugas PJOK: Laporan Olahraga', 'class' => '8B', 'due' => now()->addDays(10), 'pub' => true],
            ['title' => 'Tugas Seni: Karya Lukisan', 'class' => '9A', 'due' => now()->addDays(14), 'pub' => true],
            ['title' => 'Tugas Agama: Hafalan Surat Pendek', 'class' => '9B', 'due' => now()->subDays(5), 'pub' => true],
        ];
        foreach ($assignmentDefs as $i => $def) {
            $cls = collect($classes)->first(fn ($c) => $c->name === $def['class']);
            if (! $cls) continue;
            ClassAssignment::firstOrCreate(
                ['slug' => Str::slug($def['title']) . '-' . $i],
                [
                    'school_class_id' => $cls->id,
                    'material_category_id' => $subject?->id,
                    'staff_member_id' => $teacher?->id,
                    'title' => $def['title'],
                    'description' => '<p>Kerjakan dengan teliti dan kumpulkan tepat waktu.</p>',
                    'due_at' => $def['due'],
                    'max_score' => 100,
                    'is_published' => $def['pub'],
                    'published_at' => now()->subDays(1),
                ],
            );
        }

        // 4) Announcements
        $annDefs = [
            ['title' => 'Selamat Datang di Portal Siswa', 'class' => null, 'pinned' => true],
            ['title' => 'Jadwal UTS Semester Ganjil', 'class' => null, 'pinned' => true],
            ['title' => 'Pengumuman untuk Kelas 7A: Rapat Wali Kelas', 'class' => '7A', 'pinned' => false],
            ['title' => 'Pengumuman Kelas 8A: Kegiatan Studi Tour', 'class' => '8A', 'pinned' => false],
        ];
        foreach ($annDefs as $i => $def) {
            $cls = $def['class'] ? collect($classes)->first(fn ($c) => $c->name === $def['class']) : null;
            ClassAnnouncement::firstOrCreate(
                ['slug' => Str::slug($def['title']) . '-' . $i],
                [
                    'school_class_id' => $cls?->id,
                    'staff_member_id' => $teacher?->id,
                    'title' => $def['title'],
                    'body' => '<p>' . $def['title'] . '. Mohon perhatian semua siswa.</p>',
                    'pinned' => $def['pinned'],
                    'is_published' => true,
                    'published_at' => now()->subDays($i),
                ],
            );
        }

        // 5) Materials
        $matDefs = [
            ['title' => 'Modul Matematika Kelas 7 Bab 1', 'class' => '7A'],
            ['title' => 'Bahan Ajar Bahasa Indonesia', 'class' => null],
            ['title' => 'Slide IPA: Sistem Tata Surya', 'class' => '7B'],
            ['title' => 'Modul IPS Kelas 8', 'class' => '8A'],
            ['title' => 'Latihan Soal Bahasa Inggris', 'class' => null],
        ];
        foreach ($matDefs as $i => $def) {
            $cls = $def['class'] ? collect($classes)->first(fn ($c) => $c->name === $def['class']) : null;
            ClassMaterial::firstOrCreate(
                ['slug' => Str::slug($def['title']) . '-' . $i],
                [
                    'school_class_id' => $cls?->id,
                    'material_category_id' => $subject?->id,
                    'staff_member_id' => $teacher?->id,
                    'title' => $def['title'],
                    'description' => 'Materi pembelajaran untuk siswa.',
                    'is_published' => true,
                ],
            );
        }
    }
}
