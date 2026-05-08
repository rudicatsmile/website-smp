<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@smpalwahoniyah9.sch.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
        $admin->syncRoles(['super_admin']);

        $editor = User::updateOrCreate(
            ['email' => 'editor@smpalwahoniyah9.sch.id'],
            [
                'name' => 'Editor',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
        $editor->syncRoles(['editor']);
    }
}
