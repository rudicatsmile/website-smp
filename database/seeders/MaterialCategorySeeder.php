<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\MaterialCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MaterialCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Matematika', 'icon' => 'calculator', 'color' => 'emerald'],
            ['name' => 'IPA', 'icon' => 'beaker', 'color' => 'teal'],
            ['name' => 'IPS', 'icon' => 'globe-alt', 'color' => 'amber'],
            ['name' => 'Bahasa Indonesia', 'icon' => 'book-open', 'color' => 'rose'],
            ['name' => 'Bahasa Inggris', 'icon' => 'language', 'color' => 'sky'],
            ['name' => 'PAI', 'icon' => 'sparkles', 'color' => 'green'],
            ['name' => 'PPKn', 'icon' => 'flag', 'color' => 'red'],
            ['name' => 'Seni Budaya', 'icon' => 'paint-brush', 'color' => 'purple'],
        ];

        foreach ($categories as $i => $data) {
            MaterialCategory::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                array_merge($data, [
                    'slug' => Str::slug($data['name']),
                    'order' => $i + 1,
                    'is_active' => true,
                ]),
            );
        }
    }
}
