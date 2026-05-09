<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\StaffCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StaffCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kepala Sekolah', 'order' => 1],
            ['name' => 'Wakil Kepala Sekolah', 'order' => 2],
            ['name' => 'Guru', 'order' => 3],
            ['name' => 'Tenaga Administrasi', 'order' => 4],
            ['name' => 'Pustakawan', 'order' => 5],
            ['name' => 'Keamanan', 'order' => 6],
            ['name' => 'Kebersihan', 'order' => 7],
        ];

        foreach ($categories as $i => $data) {
            StaffCategory::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                array_merge($data, [
                    'order' => $data['order'],
                    'is_active' => true,
                ])
            );
        }
    }
}
