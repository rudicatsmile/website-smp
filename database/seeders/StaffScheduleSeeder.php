<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MaterialCategory;
use App\Models\StaffMember;
use App\Models\StaffSchedule;
use Illuminate\Database\Seeder;

class StaffScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $staff = StaffMember::active()->take(6)->get();
        if ($staff->isEmpty()) {
            return;
        }

        $matematika = MaterialCategory::where('slug', 'matematika')->first();
        $ipa = MaterialCategory::where('slug', 'ipa')->first();
        $ips = MaterialCategory::where('slug', 'ips')->first();
        $bIndo = MaterialCategory::where('slug', 'bahasa-indonesia')->first();
        $bIng = MaterialCategory::where('slug', 'bahasa-inggris')->first();
        $pai = MaterialCategory::where('slug', 'pai')->first();

        $entries = [
            // Senin
            ['day' => 1, 'start' => '07:00', 'end' => '08:00', 'type' => 'rapat', 'class' => null, 'sub' => null, 'loc' => 'Ruang Rapat', 'idx' => 0],
            ['day' => 1, 'start' => '08:00', 'end' => '09:30', 'type' => 'mengajar', 'class' => '7A', 'sub' => $matematika, 'loc' => 'R. 7A', 'idx' => 0],
            ['day' => 1, 'start' => '09:45', 'end' => '11:15', 'type' => 'mengajar', 'class' => '7B', 'sub' => $ipa, 'loc' => 'Lab IPA', 'idx' => 1],
            ['day' => 1, 'start' => '11:15', 'end' => '12:45', 'type' => 'mengajar', 'class' => '8A', 'sub' => $bIndo, 'loc' => 'R. 8A', 'idx' => 2],

            // Selasa
            ['day' => 2, 'start' => '07:00', 'end' => '08:30', 'type' => 'mengajar', 'class' => '8B', 'sub' => $matematika, 'loc' => 'R. 8B', 'idx' => 0],
            ['day' => 2, 'start' => '08:30', 'end' => '10:00', 'type' => 'mengajar', 'class' => '9A', 'sub' => $bIng, 'loc' => 'R. 9A', 'idx' => 3],
            ['day' => 2, 'start' => '10:15', 'end' => '11:45', 'type' => 'mengajar', 'class' => '7C', 'sub' => $ips, 'loc' => 'R. 7C', 'idx' => 4],

            // Rabu
            ['day' => 3, 'start' => '07:00', 'end' => '08:30', 'type' => 'mengajar', 'class' => '9B', 'sub' => $pai, 'loc' => 'R. 9B', 'idx' => 5 % max(1, $staff->count())],
            ['day' => 3, 'start' => '08:30', 'end' => '10:00', 'type' => 'mengajar', 'class' => '7A', 'sub' => $bIndo, 'loc' => 'R. 7A', 'idx' => 2],
            ['day' => 3, 'start' => '10:15', 'end' => '11:45', 'type' => 'mengajar', 'class' => '8C', 'sub' => $ipa, 'loc' => 'Lab IPA', 'idx' => 1],

            // Kamis
            ['day' => 4, 'start' => '07:00', 'end' => '08:30', 'type' => 'mengajar', 'class' => '7B', 'sub' => $matematika, 'loc' => 'R. 7B', 'idx' => 0],
            ['day' => 4, 'start' => '08:30', 'end' => '10:00', 'type' => 'mengajar', 'class' => '9C', 'sub' => $ips, 'loc' => 'R. 9C', 'idx' => 4],

            // Jumat
            ['day' => 5, 'start' => '07:00', 'end' => '08:30', 'type' => 'mengajar', 'class' => '8A', 'sub' => $bIng, 'loc' => 'R. 8A', 'idx' => 3],
            ['day' => 5, 'start' => '08:30', 'end' => '09:30', 'type' => 'mengajar', 'class' => '7C', 'sub' => $pai, 'loc' => 'R. 7C', 'idx' => 5 % max(1, $staff->count())],

            // Sabtu - Piket
            ['day' => 6, 'start' => '07:00', 'end' => '13:00', 'type' => 'piket', 'class' => null, 'sub' => null, 'loc' => 'Ruang Guru', 'idx' => 0],
            ['day' => 6, 'start' => '07:00', 'end' => '13:00', 'type' => 'piket', 'class' => null, 'sub' => null, 'loc' => 'Pos Piket', 'idx' => 1],
        ];

        foreach ($entries as $i => $row) {
            $teacher = $staff[$row['idx'] % $staff->count()] ?? $staff->first();

            StaffSchedule::updateOrCreate(
                [
                    'staff_member_id' => $teacher->id,
                    'day_of_week' => $row['day'],
                    'start_time' => $row['start'],
                    'type' => $row['type'],
                ],
                [
                    'material_category_id' => $row['sub']?->id,
                    'end_time' => $row['end'],
                    'class_name' => $row['class'],
                    'location' => $row['loc'],
                    'period' => null,
                    'color' => StaffSchedule::TYPE_COLORS[$row['type']] ?? 'emerald',
                    'academic_year' => '2025/2026',
                    'semester' => '1',
                    'is_active' => true,
                    'order' => $i + 1,
                ],
            );
        }
    }
}
