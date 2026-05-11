<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentPayment;
use App\Models\StudentViolation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParentPortalSeeder extends Seeder
{
    public function run(): void
    {
        $year = '2025/2026';

        // 1) Parent user
        $parent = User::firstOrCreate(
            ['email' => 'ortu@smpalwathoniyah9.sch.id'],
            ['name' => 'Bapak Budi', 'password' => Hash::make('password'), 'is_active' => true],
        );
        if (! $parent->hasRole('parent')) {
            $parent->assignRole('parent');
        }

        // 2) Two demo children
        $kelas7A = SchoolClass::where('name', '7A')->where('academic_year', $year)->first();
        $kelas8B = SchoolClass::where('name', '8B')->where('academic_year', $year)->first();

        $aisyah = Student::firstOrCreate(
            ['nis' => 'BP0001'],
            [
                'school_class_id' => $kelas7A?->id,
                'name' => 'Aisyah Putri',
                'slug' => 'aisyah-putri',
                'gender' => 'P',
                'birth_date' => Carbon::parse('2012-03-15'),
                'birth_place' => 'Surabaya',
                'parent_name' => $parent->name,
                'parent_phone' => '081234567890',
                'is_active' => true,
            ],
        );

        $fadhil = Student::firstOrCreate(
            ['nis' => 'BP0002'],
            [
                'school_class_id' => $kelas8B?->id,
                'name' => 'Fadhil Ramadhan',
                'slug' => 'fadhil-ramadhan',
                'gender' => 'L',
                'birth_date' => Carbon::parse('2011-08-22'),
                'birth_place' => 'Surabaya',
                'parent_name' => $parent->name,
                'parent_phone' => '081234567890',
                'is_active' => true,
            ],
        );

        // 3) Attach to parent
        $parent->children()->syncWithoutDetaching([
            $aisyah->id => ['relation' => 'ayah', 'is_primary' => true],
            $fadhil->id => ['relation' => 'ayah', 'is_primary' => false],
        ]);

        $teacher = StaffMember::active()->first();

        // 4) Generate demo data for each child
        foreach ([$aisyah, $fadhil] as $child) {
            $this->seedGrades($child, $teacher, $year);
            $this->seedAttendance($child, $teacher);
            $this->seedViolations($child, $teacher);
            $this->seedPayments($child);
        }
    }

    protected function seedGrades(Student $student, ?StaffMember $teacher, string $year): void
    {
        if ($student->grades()->count() > 0) return;

        $subjects = ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA', 'IPS', 'PPKn', 'Agama Islam', 'Pendidikan Jasmani'];
        foreach (['ganjil', 'genap'] as $sem) {
            foreach ($subjects as $subj) {
                $tugas = rand(75, 95);
                $uts = rand(70, 92);
                $uas = rand(72, 94);
                $akhir = round(($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4), 2);
                Grade::create([
                    'student_id' => $student->id,
                    'staff_member_id' => $teacher?->id,
                    'subject' => $subj,
                    'academic_year' => $year,
                    'semester' => $sem,
                    'nilai_tugas' => $tugas,
                    'nilai_uts' => $uts,
                    'nilai_uas' => $uas,
                    'nilai_akhir' => $akhir,
                    'predikat' => Grade::calcPredikat($akhir),
                    'catatan_guru' => $akhir >= 85 ? 'Pertahankan prestasi yang baik.' : null,
                ]);
            }
        }
    }

    protected function seedAttendance(Student $student, ?StaffMember $teacher): void
    {
        if ($student->attendances()->count() > 0) return;

        $start = Carbon::now()->subDays(30);
        for ($i = 0; $i <= 30; $i++) {
            $date = $start->copy()->addDays($i);
            if (in_array($date->dayOfWeek, [0, 6], true)) continue; // skip weekend

            $roll = rand(1, 100);
            $status = match (true) {
                $roll <= 80 => 'hadir',
                $roll <= 88 => 'terlambat',
                $roll <= 93 => 'izin',
                $roll <= 97 => 'sakit',
                default => 'alpa',
            };

            StudentAttendance::create([
                'student_id' => $student->id,
                'staff_member_id' => $teacher?->id,
                'date' => $date->toDateString(),
                'status' => $status,
                'note' => $status === 'sakit' ? 'Surat dokter' : ($status === 'izin' ? 'Acara keluarga' : null),
            ]);
        }
    }

    protected function seedViolations(Student $student, ?StaffMember $teacher): void
    {
        if ($student->violations()->count() > 0) return;

        $samples = [
            ['kedisiplinan', 'Datang terlambat ke sekolah', 5, 'Teguran lisan'],
            ['atribut', 'Tidak memakai dasi', 3, 'Peringatan'],
            ['kedisiplinan', 'Tidak mengikuti upacara', 10, 'Pembinaan'],
        ];
        $count = rand(1, 3);
        for ($i = 0; $i < $count; $i++) {
            [$cat, $desc, $pts, $act] = $samples[array_rand($samples)];
            StudentViolation::create([
                'student_id' => $student->id,
                'staff_member_id' => $teacher?->id,
                'date' => Carbon::now()->subDays(rand(3, 25))->toDateString(),
                'category' => $cat,
                'description' => $desc,
                'points' => $pts,
                'action_taken' => $act,
            ]);
        }
    }

    protected function seedPayments(Student $student): void
    {
        if ($student->payments()->count() > 0) return;

        $months = [
            ['Juli 2025', 6, true],
            ['Agustus 2025', 5, true],
            ['September 2025', 4, true],
            ['Oktober 2025', 3, false], // unpaid
            ['November 2025', 2, false], // unpaid
            ['Desember 2025', 1, null], // overdue
        ];
        foreach ($months as [$label, $monthsAgo, $paid]) {
            $dueDate = Carbon::now()->subMonthsNoOverflow($monthsAgo)->endOfMonth();
            $status = $paid === true ? 'paid' : ($paid === null ? 'overdue' : 'unpaid');
            StudentPayment::create([
                'student_id' => $student->id,
                'type' => 'spp',
                'period' => $label,
                'amount' => 350000,
                'due_date' => $dueDate->toDateString(),
                'status' => $status,
                'paid_at' => $paid === true ? $dueDate->copy()->subDays(rand(1, 10)) : null,
                'paid_amount' => $paid === true ? 350000 : null,
            ]);
        }

        // Seragam lunas
        StudentPayment::create([
            'student_id' => $student->id,
            'type' => 'seragam',
            'period' => 'Seragam Tahun Ajaran Baru',
            'amount' => 650000,
            'due_date' => Carbon::now()->subMonths(6)->toDateString(),
            'status' => 'paid',
            'paid_at' => Carbon::now()->subMonths(6),
            'paid_amount' => 650000,
        ]);
    }
}
