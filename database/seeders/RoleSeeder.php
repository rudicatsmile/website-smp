<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['super_admin', 'admin', 'editor', 'contributor', 'teacher', 'student', 'counselor', 'parent', 'piket'] as $name) {
            Role::findOrCreate($name, 'web');
        }
    }
}
